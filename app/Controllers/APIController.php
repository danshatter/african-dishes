<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\{Menu};
use Slim\Views\Twig as View;

class APIController
{
    
    public function getCategoryMenuList(Request $request, Response $response)
    {
        $category = $request->getAttribute('category');

        echo Menu::where('category_id', $category)->get()->load('category')->toJson();

        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function menuList(Request $request, Response $response)
    {
        // cart items from the user
        $cart = $request->getParsedBody();

        // array of item IDs requested by the user
        $ids = array_map(function($item) {
            return $item['id'];
        }, $cart);
        
        $menus = Menu::find($ids)->load('category')->toArray();

        // total if the menu cart is empty. This will not be the total
        $total = null;
        
        if (!empty($menus)) {
            $total = $this->calculateTotal($menus, $cart);
        }

        echo json_encode([
            'total' => $total,
            'data' => $menus
        ]);

        return $response->withHeader('Content-Type', 'application/json');
    }

    private function calculateTotal($data, $cart)
    {
        // cart array initialization
        $quantity = [];

        // flipping id as the key and quantity as the value
        foreach ($cart as $item) {
            $quantity[$item['id']] = (int) $item['quantity'];
        } 

        $total = 0;
        $itemSum = [];

        // total of all items calculation
        foreach ($data as $menu) {
            // split price into whole number side and decimal side
            $array = explode('.', $menu['price']);

            // split price into digits
            $digits = explode(',', $array[0]);

            // join price numbers together
            $priceString = implode('', $digits);

            // cast number as an integer
            $price = (int) $priceString;

            // calculate the item sum of the current iteration
            $totalPrice = $quantity[$menu['id']] * $price;

            // assign total price to the current iteration 
            $itemSum['id'.$menu['id']] = number_format($totalPrice, 2);

            // Add total price to the grand total
            $total += $totalPrice;
        }

        return [
            'grand_total' => number_format($total, 2),
            'item_total' => $itemSum
        ];
    }

    public function getCart(Request $request, Response $response)
    {
        // User cart items
        $cart = $request->getParsedBody();

        // Set cart into session
        $_SESSION['cart'] = $cart;

        echo json_encode(['message' => 'Processing Order']);

        return $response->withHeader('Content-Type', 'application/json');
    }

    // public function test(Request $request, Response $response)
    // {
    //     $carbon = Carbon::parse('2020-10-14 22:55:54');

    //     echo $carbon->fullMonth;
    //     return $response;
    // }

}