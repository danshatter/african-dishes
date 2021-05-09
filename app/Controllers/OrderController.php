<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;
use App\Validation\Validator;
use Slim\Views\Twig as View;
use Slim\Flash\Messages as Flash;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Exception\HttpNotFoundException;
use App\Models\{Order, Menu, User, Location};
use App\Mail\Mailer;
use Throwable;

class OrderController
{
    
    private $view;
    private $validator;
    private $flash;
    private $routeParser;
    private $initializeURL = 'https://api.paystack.co/transaction/initialize';
    private $verifyURL = 'https://api.paystack.co/transaction/verify';

    public function __construct(View $view, Validator $validator, Flash $flash, RouteParser $routeParser)
    {
        $this->view = $view;
        $this->validator = $validator;
        $this->flash = $flash;
        $this->routeParser = $routeParser;
    }

    public function index(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $orders = Order::all()->sort(function($a, $b) {
            if (strtotime($a->created_at) === strtotime($b->created_at)) {
                return 0;
            }

            if (strtotime($a->created_at) < strtotime($b->created_at)) {
                return 1;
            }

            return -1;
        });

        $items = $orders->pluck('item');

        // IDs of all menu items initialization
        $idsArray = [];

        // Collect all menu items ID and make a unique array with all the IDs in it
        if (!empty($items)) {
            foreach ($items as $item) {
                $cart = $item['cart'];

                $ids = array_keys($cart);

                foreach ($ids as $oneID) {
                    $itemID = explode('id', $oneID);

                    $theID = end($itemID);

                    array_push($idsArray, $theID);
                }
            }
        }

        $idsArray = array_unique($idsArray);

        // All menu items that will be contained in the current page
        $subMenus = Menu::find($idsArray);

        // Menu to pass to the page
        $menus = [];

        foreach ($subMenus as $menu) {
            $menus['id'.$menu->id] = $menu;
        }

        $user = User::find($id);

        return $this->view->render($response, 'admin/orders.twig', compact('orders', 'user', 'menus', 'items'));
    }

    public function store(Request $request, Response $response)
    {
        // The cart items containing the ID and the quantity
        $cart = $request->getAttribute('cart');

        // Validation for the cart if it is empty or does not exist
        if (count($cart) === 0 || !isset($cart)) {
            $this->flash->addMessage('empty-cart', 'your cart is empty');

            return $response->withHeader('Location', $this->routeParser->urlFor('checkout'));
        }

        // array of item IDs requested by the user
        $ids = array_map(function($item) {
            return $item['id'];
        }, $cart);

        $menus = Menu::find($ids);

        // The user should never see this if all goes well
        if (empty($menus)) {
            $this->flash->addMessage('empty-menu', 'the food menu items request does not exist or is out of stock');

            return $response->withHeader('Location', $this->routeParser->urlFor('checkout'));
        }

        // Now here is where our magic happens. The user should always see this
        // The quantity to id cart values
        $quantity = $this->quantityToCartId($cart);

        $data = $request->getParsedBody();
        $totals = $this->calculateTotal($menus, $cart);

        // Initialize cart errors
        $cartErrors = [];

        foreach ($menus as $item) {
            // Validate menu items so that the user does not order food quantity that is not available
            if ($item->quantity < $quantity['id'.$item->id]) {
                if ($item->quantity <= 0) {
                    // Out of stock
                    $cartErrors[] =  ucwords('You ordered '.$quantity['id'.$item->id].' '.$item->name.'. '.'It is out of stock');
                } else {
                    // Error message displayed to the user when quantity is not enough
                    $cartErrors[] =  ucwords('You ordered '.$quantity['id'.$item->id].' '.$item->name.'. '.$item->quantity.' remaining');
                }
            }
        }

        if (count($cartErrors) !== 0) {
            $_SESSION['errors'] = $cartErrors;

            return $response->withHeader('Location', $this->routeParser->urlFor('cart'));
        }

        $validData = $this->validator->ready($data)->validate($data, [
            'first_name' => V::notEmpty()->noWhitespace()->alpha(),
            'last_name' => V::notEmpty()->noWhitespace()->alpha(),
            'email' => V::notEmpty()->email(),
            'phone' => V::notEmpty()->phone(),
            'address_1' => V::notEmpty(),
            'address_2' => V::optional(V::alwaysValid()),
            'payment_method' => V::notEmpty()->validPayment(),
            'delivery_location' => V::notEmpty()->validDeliveryLocation()
        ]);

        if ($this->validator->failed()) {
            $_SESSION['errors'] = $this->validator->getErrors();  
            $_SESSION['old'] = $data;

            return $response->withHeader('Location', $this->routeParser->urlFor('checkout'));
        }
        
        // The delivery location for item to be delivered to
        $deliveryLocation = Location::where('location', $validData['delivery_location'])->first();

        $newArray = [
            'customer_name' => $validData['first_name'].' '.$validData['last_name'], // Customer name as needed in DB
            'amount' => $totals['grand_total'], // Total amount paid for all menu items
            'delivery_fee' => $deliveryLocation->amount
        ];

        // Switch the merger array for different types of payment method
        if ($validData['payment_method'] === 'delivery') {
            /**Here we are going to merge the item lists and the amount to the data
            and put the customer name field. This is the case of delivery
            */
            $mergerArray = array_merge($newArray, ['item' => json_encode(['cart' => $quantity])]);
        } elseif ($validData['payment_method'] === 'card') {
            // The case when the payment method is card
            $mergerArray = array_merge($newArray, ['item' => ['cart' => $quantity]]);
        }

        // Final data to be used
        $finalData = array_merge($validData, $mergerArray);

        switch ($validData['payment_method']) {
            // And here we have the case of card. This is what sends the user to the payment gateway
            case 'card':
                $curl = curl_init();
        
                curl_setopt_array($curl, [
                    CURLOPT_URL => $this->initializeURL,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode([
                        'amount' => ($totals['grand_total'] + $deliveryLocation->amount) * 100, // The amount has to be multiplied by 100 for kobo
                        'email' => $finalData['email'],
                        'metadata' => $finalData
                    ]),
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'Authorization: Bearer '.$_ENV['PAYMENT_SECRET_KEY'],
                        'Cache-Control: no-cache'
                    ],
                    CURLOPT_RETURNTRANSFER => true
                ]);
                
                $data = curl_exec($curl);

                if (curl_error($curl)) {
                    $this->flash->addMessage('curl-error', 'an error occured while trying to initialize payment');

                    return $response->withHeader('Location', $this->routeParser->urlFor('checkout'));
                }

                curl_close($curl);

                $info = json_decode($data, true);

                return $response->withHeader('Location', $info['data']['authorization_url']);
            break;

            //Due to the above validation, a user never sees this but this is here just in case
            default:
                throw new HttpNotFoundException($request);
                
                return $response;
            break;
        }
        
        // This should never run
        throw new HttpNotFoundException($request);
                
        return $response;
    }

    // Webhook post route for online payment
    public function webhook(Request $request, Response $response)
    {
        // This is the check that paystack needs
        if ($request->hasHeader('X-Paystack-Signature')) {
            // Paystack signature
            $signature = $request->getHeader('X-Paystack-Signature')[0];

            // JSON data received from paystack
            $dataJSON = (string) $request->getBody();

            // Ultimate test for Paystack
            if ($signature === hash_hmac('sha512', $dataJSON, $_ENV['PAYMENT_SECRET_KEY'])) {
                // Response code for Paystack
                http_response_code(200);
                
                // Our code starts here
                // Our data in our desired format
                $data = json_decode($dataJSON, true);

                // Checking for a particular kind of event. Could add others if need be
                switch($data['event']) {
                    case 'charge.success':
                        // The user data to be put in the database
                        $userData = $data['data']['metadata'];

                        // address 2 is optional so if empty, set it to null
                        if (empty($userData['address_2'])) {
                            $userData['address_2'] = null;
                        }

                        // The cart data
                        $dbCart = $userData['item']['cart'];

                        $finalData = array_merge($userData, [
                            'item' => json_encode([
                                'cart' => $dbCart
                            ]),
                            'payment_reference' => $data['data']['reference']
                        ]);

                        // Create the order
                        $order = Order::create($finalData);

                        // Creating cart from the quantity to cart ID
                        $cart = $this->getCartFromQuantityId($dbCart);

                        // IDs of all the menu items required from the user
                        $ids = array_map(function($item) {
                            return $item['id'];
                        }, $cart);

                        // Get the associated menu items
                        $menus = Menu::find($ids);
                        
                        // Reduce the quantity of the menu item in the database
                        $this->quantityChange($menus, $dbCart);
                        
                        try {
                            Mailer::orderSuccessful($finalData['email']);
                        } catch (Throwable $e) {
                            $error = $e;
                        }

                        // 1. Send admin message of order requested

                        // 2. Send user message of order being processed

                        // 3. Send mail if remaining quantity is less than 0 now, (Might put it somewhere else)

                        return $response;
                    break;
                    
                    default:
                        throw new HttpNotFoundException($request);
                        
                        return $response;
                    break;
                }

                return $response;
            }

            return $response;
        }

        return $response;
    }

    public function complete(Request $request, Response $response)
    {
        $userId = $request->getAttribute('session_id');

        $user = User::find($userId);

        $reference = $request->getQueryParams()['reference'];
 
        // Case for pay on delivery
        if (isset($reference)) {
            // Case for payment with card
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $this->verifyURL.'/'.$reference,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer '.$_ENV['PAYMENT_SECRET_KEY']
                ]
            ]);

            $info = curl_exec($curl);

            if (curl_error($curl)) {
                $this->flash->addMessage('verify-failed', 'an error occured while trying to verify your payment. Please contact us if you have been deducted');

                return $response->withHeader('Location', $this->routeParser->urlFor('checkout'));
            }

            curl_close($curl);

            // Data gotten back from the Payment API
            $data = json_decode($info, true);

            $order = $data['data']['metadata'];

            // address 2 is optional so if empty, set it to null
            if (empty($order['address_2'])) {
                $order['address_2'] = null;
            }

            $orderDetails = $this->processAfterPayment($order);

            extract($orderDetails);
            
            $mainTotal = $totals['grand_total'] + $order['delivery_fee'];

            return $this->view->render($response, 'order-success.twig', compact('order', 'menus', 'quantityId', 'totals', 'data', 'user', 'mainTotal'));
        }

        throw new HttpNotFoundException($request);

        return $response;
    }

    public function disapprove(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $order = Order::find($request->getAttribute('order'));

        if (!isset($order)) {
            throw new HttpNotFoundException($request);

            return $response;
        }
        
        if ($order->status['status'] !== 'disapproved') {
            // Set status to active
            $order->status = 2;
            
            // Saving changes
            $order->save();

            /**
             * Send Disapproval mail to customer
             */

            $this->flash->addMessage('order-disapproved', 'you have declined the order successfully');
        
            return $response->withHeader('Location', $this->routeParser->urlFor('orders'));
        }

        return $response;
    }

    public function confirm(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $order = Order::find($request->getAttribute('order'));

        if (!isset($order)) {
            throw new HttpNotFoundException($request);

            return $response;
        }
        
        if ($order->status['status'] !== 'confirmed') {
            // Set status to active
            $order->status = 1;
            
            // Saving changes
            $order->save();

            /**
             * Send Confirmation mail to customer
             */

            $this->flash->addMessage('order-confirmed', 'you have succesfully confirmed the order');
        
            return $response->withHeader('Location', $this->routeParser->urlFor('orders'));
        }

        return $response;
    }

    private function processAfterPayment($order)
    {
        $cartItems = $order['item'];

        //The cart
        $quantityId = $cartItems['cart'];

        $cart = $this->getCartFromQuantityId($quantityId);

        // ID array initialization
        $ids = [];
        
        // IDs of purchased menu items
        foreach ($quantityId as $key => $value) {
            $item = explode('id', $key);

            // Insert ID into ID array
            array_push($ids, end($item));
        }

        // All Menu Items purchased
        $menus = Menu::find($ids);

        $totals = $this->calculateTotal($menus, $cart);

        return [
            'order' => $order,
            'menus' => $menus,
            'quantityId' => $quantityId,
            'totals' => $totals
        ];
    }

    private function quantityToCartId($cart)
    {
        if (count($cart) > 0) {
            $quantity = [];

            foreach($cart as $item) {
                $quantity['id'.$item['id']] = (int) $item['quantity'];
            }

            return $quantity;
        }
    }

    private function getCartFromQuantityId($quantity)
    {
        if (count($quantity) > 0) {
            // Cart initialization
            $cart = [];

            foreach($quantity as $key => $value) {
                // Menu item initialization
                $item = [];

                $exploded = explode('id', $key);

                // ID of menu item
                $id = end($exploded);

                $item['id'] = $id;

                $item['quantity'] = $value;

                array_push($cart, $item);
            }

            return $cart;
        }
    }

    private function calculateTotal($data, $cart)
    {
        if (!empty($data) && count($cart) !== 0 && $cart !== null) {
            $quantity = $this->quantityToCartId($cart);

            $total = 0;
            $itemSum = [];

            // total of all items calculation
            foreach ($data as $menu) {
                // split price into whole number side and decimal side
                $array = explode('.', $menu->price);

                // split price into digits
                $digits = explode(',', $array[0]);

                // join price numbers together
                $priceString = implode('', $digits);

                // cast number as an integer
                $price = (int) $priceString;

                // calculate the item sum of the current iteration
                $totalPrice = $quantity['id'.$menu->id] * $price;

                // assign total price to the current iteration 
                $itemSum['id'.$menu->id] = $totalPrice;

                // Add total price to the grand total
                $total += $totalPrice;
            }

            return [
                'grand_total' => $total,
                'item_total' => $itemSum
            ];
        }
    }

    private function quantityChange($menus, $quantity)
    {
        // Just making sure that the menus is not empty
        if (!empty($menus)) {
            foreach ($menus as $item) {
                // Validation to make sure that the quantity in the menu item is more than the quantity ordered
                if ($item->quantity >= (int) $quantity['id'.$item->id]) {
                    // Reduce the item quantity in the menu model
                    $item->quantity = $item->quantity - (int) $quantity['id'.$item->id];

                    $item->save();
                }
            }
        }
    }

}