<?php
namespace App\Core;

class Router {
    // Stores our routes: ['GET' => ['/' => 'HomeController@index']]
    protected $routes = [];

    // Registers a GET route
    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    // Registers a POST route
    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    // Registers a PUT route
    public function put($uri, $action) {
        $this->routes['PUT'][$uri] = $action;
    }

    // Registers a DELETE route
    public function delete($uri, $action) {
        $this->routes['DELETE'][$uri] = $action;
    }

    // Matches the request to a route
    public function resolve($uri, $method) {
        // Strip query strings (e.g., /login?id=1 becomes /login)
        $path = parse_url($uri, PHP_URL_PATH);
        $action = $this->routes[$method][$path] ?? false;

        if (!$action) {
            http_response_code(404);
            echo "404 - Page Not Found";
            return;
        }

        // If action is a callback function, run it
        if (is_callable($action)) {
            return $action();
        }

        // If action is an array [Controller, Method], instantiate and run
        if (is_array($action)) {
            [$controller, $method] = $action;
            $controllerInstance = new $controller();
            return $controllerInstance->$method();
        }
    }

    // Dispatch method that calls resolve
    public function dispatch($uri) {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->resolve($uri, $method);
    }
}