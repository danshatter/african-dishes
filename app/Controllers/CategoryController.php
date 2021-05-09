<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Interfaces\RouteParserInterface as RouteParser;
use Slim\Views\Twig as View;
use Respect\Validation\Validator as V;
use App\Validation\Validator;
use Slim\Flash\Messages as Flash;
use App\Models\{User, Category};
use Slim\Exception\HttpNotFoundException;

class CategoryController
{

    private $view;
    private $routeParser;
    private $validator;
    private $flash;

    public function __construct(View $view, RouteParser $routeParser, Validator $validator, Flash $flash)
    {
        $this->view = $view;
        $this->routeParser = $routeParser;
        $this->validator = $validator;
        $this->flash = $flash;
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

        //End of Add Middleware Checkers
        $categories = Category::all()->load('menus');

        $user = User::find($id);

        return $this->view->render($response, 'admin/categories.twig', compact('categories', 'user'));    
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
            'name' => V::notEmpty()->newCategory(),
            'description' => V::notEmpty()
        ]);

        if ($this->validator->failed()) {
            $_SESSION['old'] = $data;
            $_SESSION['errors'] = $this->validator->getErrors();

            return $response->withHeader('Location', $this->routeParser->urlFor('add-category'));
        }

        Category::create($validData);

        $this->flash->addMessage('add-category-success', 'Category created successfully');

        return $response->withHeader('Location', $this->routeParser->urlFor('admin.index'));

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

        //End of Add Middleware Checkers
        $category = Category::find($request->getAttribute('category'));

        if (!isset($category)) {
            throw new HttpNotFoundException($request);
            
            return $response;
        }

        $category = $category->load('menus');

        $user = User::find($id);

        return $this->view->render($response, 'admin/edit-category.twig', compact('category', 'user'));
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

        $category = Category::find($request->getAttribute('category'));

        if ($category === null) {
            throw new HttpNotFoundException($request);
            return $response;
        }

        $data = $request->getParsedBody();

        $validData = $this->validator->ready($data)->validate($data, [
            'name' => V::notEmpty()->updateCategory($category->name),
            'description' => V::notEmpty()
        ]);

        if ($this->validator->failed()) {
            $_SESSION['old'] = $data;
            $_SESSION['errors'] = $this->validator->getErrors();

            return $response->withHeader('Location', $this->routeParser->urlFor('edit-category', [
                'category' => $category->id
            ]));
        }

        $category->update($validData);

        $this->flash->addMessage('update-category-success', 'Category updated successfully');

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

        $category = Category::find($request->getAttribute('category'));

        if ($category === null) {
            throw new HttpNotFoundException($request);
            return $response;
        }

        $category->delete();

        $this->flash->addMessage('delete-category-success', 'Category deleted successfully');

        return $response->withHeader('Location', $this->routeParser->urlFor('admin.index'));
    }

}