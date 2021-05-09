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
use App\Models\{User, Location};

class LocationController
{
    
    private $view;
    private $validator;
    private $flash;
    private $routeParser;

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

        $user = User::find($id);
        
        $locations = Location::all();

        return $this->view->render($response, 'admin/delivery-location.twig', compact('user', 'locations'));
    }
    
    public function store(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $data = $request->getParsedBody();

        $validData = $this->validator->ready($data)->validate($data, [
            'location' => V::notEmpty()->newLocation(),
            'lga' => V::notEmpty(),
            'amount' => V::notEmpty()->intVal()
        ]);

        if ($this->validator->failed()) {
            $_SESSION['old'] = $data;
            $_SESSION['errors'] = $this->validator->getErrors();

            return $response->withHeader('Location', $this->routeParser->urlFor('delivery-locations'));
        }

        Location::create($validData);

        $this->flash->addMessage('add-location-success', 'Delivery location added successfully');

        return $response->withHeader('Location', $this->routeParser->urlFor('delivery-locations'));

    }

    public function edit(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');
        $locationID = $request->getAttribute('location');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }
        
        $location = Location::find($locationID);
        
        if (!isset($location)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }

        $user = User::find($id);

        return $this->view->render($response, 'admin/edit-delivery-location.twig', compact('user', 'location'));
    }
    
    public function update(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }
        
        $locationID = $request->getAttribute('location');

        $location = Location::find($locationID);

        if (!isset($location)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }

        $data = $request->getParsedBody();

        $validData = $this->validator->ready($data)->validate($data, [
            'location' => V::notEmpty()->updateLocation($location->location),
            'lga' => V::notEmpty(),
            'amount' => V::notEmpty()->intVal()
        ]);

        if ($this->validator->failed()) {
            $_SESSION['old'] = $data;
            $_SESSION['errors'] = $this->validator->getErrors();

            return $response->withHeader('Location', $this->routeParser->urlFor('edit-delivery-location', [
                'location' => $location->id
            ]));
        }

        $location->update($validData);

        $this->flash->addMessage('edit-location-success', 'Delivery location updated successfully');

        return $response->withHeader('Location', $this->routeParser->urlFor('delivery-locations'));
    }

}