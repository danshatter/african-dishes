<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\{User, Category, Menu, Order, Location};
use Slim\Views\Twig as View;
use Carbon\Carbon;
use Slim\Exception\HttpNotFoundException;

class ViewController
{

    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        $user = User::find($id);

        $categories = Category::all();

        $menuCount = Menu::all()->count();

        // We want a maximum of six menu items in the home page
        if ($menuCount < 6) {
            $menus = Menu::all()->load('category')->random($menuCount);
        } else {
            $menus = Menu::all()->load('category')->random(6);
        }
        
        return $this->view->render($response, 'index.twig', compact('menus', 'user', 'categories')); 
    }
    
    public function viewOrder(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');
        $order = $request->getAttribute('order');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $order = Order::find($order);

        if (!isset($order)) {
            throw new HttpNotFoundException($request);

            return $response;
        }
        
        $item = $order->item; 

        // IDs of all menu items initialization
        $idsArray = [];

        // Collect all menu items ID and make a unique array with all the IDs in it
        $cart = $item['cart'];

        $ids = array_keys($cart);

        foreach ($ids as $oneID) {
            $itemID = explode('id', $oneID);

            $theID = end($itemID);

            array_push($idsArray, $theID);
        }

        // All menu items that will be contained in the current page
        $subMenus = Menu::find($idsArray);

        // Menu to pass to the page
        $menus = [];

        foreach ($subMenus as $menu) {
            $menus['id'.$menu->id] = $menu;
        }

        $user = User::find($id);
        
        $carbon = Carbon::parse($order->created_at);
        
        $orderTime = $carbon->isoFormat('MMMM Do YYYY, h:mm:ss a');

        return $this->view->render($response, 'admin/view-order.twig', compact('user', 'order', 'menus', 'cart', 'orderTime'));
    }

    public function displayCategoryitems(Request $request, Response $response)
    {
        $categoryId = $request->getAttribute('category');

        $id = $request->getAttribute('session_id');

        $menus = Menu::where('category_id', $categoryId)->get();
        
        $categories = Category::all();

        $category = Category::find($categoryId);

        if (empty($menus) || !isset($category)) {
            throw new HttpNotFoundException($request);

            return $response;
        }

        $user = User::find($id);

        return $this->view->render($response, 'category.twig', compact('menus', 'user', 'category', 'categories'));
    }

    public function cart(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        $user = User::find($id);

        $categories = Category::all();

        return $this->view->render($response, 'cart.twig', compact('user', 'categories'));
    }

    public function checkout(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        $user = User::find($id);

        $locations = Location::all();

        return $this->view->render($response, 'checkout.twig', compact('user', 'locations'));
    }

    public function foodList(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        $monthName = Carbon::now()->monthName;

        $user = User::find($id);
        
        $menus = Menu::all();
        
        $categories = Category::all();
        
        return $this->view->render($response, 'food-list.twig', compact('user', 'menus', 'categories', 'monthName'));
    }

    public function forgottenPassword(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        return $this->view->render($response, 'forgotten-password.twig');
    }

    public function report(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $user = User::find($id);

        return $this->view->render($response, 'admin/report.twig', compact('user'));
    }

    public function resetPassword(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        $recovery = $request->getAttribute('recovery');
        $userData = $request->getAttribute('user_data');

        // Check for recovery variable and user data
        if (!isset($recovery) || !isset($userData)) {
            return $response;
        }

        // Making sure the recovery variable is true and user data is available
        if ($recovery === true && isset($userData)) {
            return $this->view->render($response, 'reset-password.twig');
        }

        return $response;
    }

    public function profile(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (!isset($id)) {
            return $response;
        }

        $user = User::find($id);

        $categories = Category::all();

        return $this->view->render($response, 'profile.twig', compact('user', 'categories'));
    }

    public function users(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $user = User::find($id);

        $users = User::where('role', 1)->get();

        return $this->view->render($response, 'admin/users.twig', compact('user', 'users'));
    }

    public function signUp(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        return $this->view->render($response, 'sign-up.twig');
    }

    public function signIn(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        return $this->view->render($response, 'sign-in.twig');
    }

    public function adminIndex(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        // Current time instance
        $carbon = Carbon::now();

        // Current month name
        $monthName = $carbon->monthName;

        $startOfMonth = $carbon->startOfMonth();

        // Orders
        $orders = Order::all();

        // Total Orders current month
        $monthOrdersCollection = Order::where('created_at', '>', $startOfMonth)->get();

        $monthOrders = $monthOrdersCollection->count();

        // Total amount of orders
        $ordersTotal = $orders->count();

        $deliveredQuery = Order::where('status', 1);

        // Total orders confirmed by the user
        $ordersDelivered = $deliveredQuery->get()->count();

        // Total orders delivered current month
        $monthDelivered = $deliveredQuery->where('created_at', '>', $startOfMonth)->get()->count();

        // Total earnings
        $earningsTotal = $this->calculateTotalEarnings($orders->pluck('amount'));

        // Total earnings for current month
        $monthEarnings = $this->calculateTotalEarnings($monthOrdersCollection->pluck('amount')); 

        // Total categories created
        $categoriesTotal = Category::all()->count();

        // Total categories created for current month
        $monthCategories = Category::where('created_at', '>', $startOfMonth)->get()->count();

        // Total menus created
        $menusTotal = Menu::all()->count();

        // Total menus created for current month
        $monthMenus = Menu::where('created_at', '>', $startOfMonth)->get()->count();

        $usersQuery = User::where('role', 1);

        // Total registered users
        $usersTotal = $usersQuery->get()->count();

        // Total registered users for current month
        $monthUsers = $usersQuery->where('created_at', '>', $startOfMonth)->get()->count();

        // Current logged in user
        $user = User::find($id);

        return $this->view->render($response, 'admin/index.twig', compact('user', 'ordersTotal', 'earningsTotal', 'ordersDelivered', 'categoriesTotal', 'menusTotal', 'usersTotal', 'monthName', 'monthOrders', 'monthDelivered', 'monthEarnings', 'monthCategories', 'monthMenus', 'monthUsers'));
    }

    public function resendEmail(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');

        if (isset($id)) {
            return $response;
        }

        return $this->view->render($response, 'resend-email.twig');
    }

    private function calculateTotalEarnings($amounts)
    {
        $total = 0;

        if (!empty($amounts)) {
            foreach ($amounts as $amount) {
                $parts = explode('.', $amount);

                $wholePart = reset($parts);

                $nums = explode(',', $wholePart);

                $final = implode('', $nums);

                $intAmount = (int) $final;

                $total += $intAmount;
            } 
        }

        return $total;
    }

}