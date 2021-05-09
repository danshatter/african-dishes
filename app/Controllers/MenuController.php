<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;
use App\Validation\Validator;
use Slim\Views\Twig as View;
use Slim\Flash\Messages as Flash;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Views\TwigRuntimeLoader;
use Slim\Exception\HttpNotFoundException;
use App\Models\{Category, Menu, User};

class MenuController
{
    
    private $view;
    private $validator;
    private $flash;
    private $routeParser;
    private $pathToImages = __DIR__.'/../../public_html/images/menus/';
    private $allowedImages = ['image/jpeg', 'image/jpg', 'image/png'];

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

        $menus = Menu::all()->load('category');

        $user = User::find($id);

        return $this->view->render($response, 'admin/menu-list.twig', compact('menus', 'user'));
    }

    public function create(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $categories = Category::all();

        $user = User::find($id);

        return $this->view->render($response, 'admin/add-menu.twig', compact('categories', 'user'));
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

        // Variables Initialization
        $error = false;
        $data = $request->getParsedBody();
        $image = $request->getUploadedFiles()['image'];

        // Form input Validation
        $validData = $this->validator->ready($data)->validate($data, [
            'name' => V::notEmpty()->newMenu(),
            'quantity' => V::notEmpty()->intVal(),
            'price' => V::notEmpty()->intVal(),
            'category' => V::notEmpty()->validCategory(),
            'description' => V::notEmpty()
        ]);
        
        // Image validation (any error)
        if ($image->getError() !== 0) {
            $error = 'Error occurred while uploading image. Please try again';
        }

        // Image validation (any image uploaded)
        if ($image->getError() === 4) {
            $error = 'Image is required';
        }

        // Error checking
        if ($this->validator->failed() || $error) {
            $_SESSION['old'] = $data;
            
            $_SESSION['errors'] = $this->validator->getErrors();
            
            $_SESSION['errors']['image'] = $error;

            return $response->withHeader('Location', $this->routeParser->urlFor('add-menu'));
        }

        // Validation to check if image is among allowed images
        if (!in_array($image->getClientMediaType(), $this->allowedImages)) {
            $_SESSION['errors']['image'] = 'Uploaded images format should be .jpg, .jpeg or .png';

            return $response->withHeader('Location', $this->routeParser->urlFor('add-menu'));
        }

        // File extension
        $ext = pathinfo($image->getClientFilename(), PATHINFO_EXTENSION);
        $ext = strtolower($ext);

        // Joining image to query fields
        $validData = array_merge($validData, ['image_extension' => $ext]);

        // Query database
        $menu = Category::find($data['category'])->menus()->create($validData);
        
        // Uploading image
        $image->moveTo($this->pathToImages.$menu->id.'.'.$ext);

        // Flash message
        $this->flash->addMessage('menu-create-success', 'Menu item created successfully');

        return $response->withHeader('Location', $this->routeParser->urlFor('add-menu'));
    }

    public function edit(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $menu = Menu::find($request->getAttribute('menu'));

        if (!isset($menu)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }

        $categories = Category::all();

        $user = User::find($id);

        return $this->view->render($response, 'admin/edit-menu.twig', compact('menu', 'categories', 'user'));
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

        $menu = Menu::find($request->getAttribute('menu'));

        if (!isset($menu)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }

        // Variables Initialization
        $error = false;
        $data = $request->getParsedBody();
        $image = $request->getUploadedFiles()['image'];

        // Form input Validation
        $validData = $this->validator->ready($data)->validate($data, [
            'name' => V::notEmpty()->updateMenu($menu->name),
            'quantity' => V::notEmpty()->intVal(),
            'price' => V::notEmpty()->intVal(),
            'category' => V::notEmpty()->validCategory(),
            'description' => V::notEmpty()
        ]);

        // Image validation (any image uploaded to update current image)
        if ($image->getError() !== 4) {
            // Image validation (any error)
            if ($image->getError() !== 0) {
                $error = 'Error occurred while uploading image. Please try again';
            }
        }

        // Validation for present and valid image
        if ($image->getError() === 0) {
            // Validation to check if image is among allowed images
            if (!in_array($image->getClientMediaType(), $this->allowedImages)) {
                $error = 'Uploaded images format should be .jpg, .jpeg or .png';
            }
        }

        // Error checking
        if ($this->validator->failed() || $error) {
            $_SESSION['old'] = $data;
            
            $_SESSION['errors'] = $this->validator->getErrors();
            
            $_SESSION['errors']['image'] = $error;

            return $response->withHeader('Location', $this->routeParser->urlFor('edit-menu', [
                'menu' => $menu->id
            ]));
        }

        // Getting file extension when an image is uploaded
        if ($image->getError() === 0) {
            // File extension
            $ext = pathinfo($image->getClientFilename(), PATHINFO_EXTENSION);
            $ext = strtolower($ext);

            // Joining image to query fields
            $validData = array_merge($validData, ['image_extension' => $ext]);
        }
        
        // Turn category into category_id
        $validData['category_id'] = $validData['category'];
        
        // Unset category
        unset($validData['category']);

        // Query database
        $menu->update($validData);
        
        // File
        $file = $this->pathToImages.$menu->id.'.'.$ext;

        // Making sure that an image was uploaded and no error occurred
        if ($image->getError() === 0) {
            // Uploading image
            if (file_exists($file)) {
                unlink($file);
            }
            
            $image->moveTo($file);
        }

        // Flash message
        $this->flash->addMessage('menu-update-success', 'Menu item updated successfully');

        return $response->withHeader('Location', $this->routeParser->urlFor('admin.index'));
    }

    public function destroy(Request $request, Response $response)
    {
        $id = $request->getAttribute('session_id');
        $isAdmin = $request->getAttribute('is_admin');

        if (!isset($id)) {
            return $response;
        }

        if ($isAdmin !== true) {
            return $response;
        }

        $menu = Menu::find($request->getAttribute('menu'));

        if (!isset($menu)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }

        $menu->delete();

        unlink($this->pathToImages.$menu->id.'.'.$menu->image_extension);

        $this->flash->addMessage('delete-menu-success', 'menu deleted successfully');

        return $response->withHeader('Location', $this->routeParser->urlFor('admin.index'));
    }

}