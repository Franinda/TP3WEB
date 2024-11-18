<?php

require_once './libs/request.php';
require_once './libs/response.php';

class Route {
    private $url;
    private $verb;
    private $controller;
    private $method;
    private $params;

    public function __construct($url, $verb, $controller, $method){
        $this->url = $url;
        $this->verb = $verb;
        $this->controller = $controller;
        $this->method = $method;
        $this->params = [];
    }

    // Método para comparar la URL y el verbo
    public function match($url, $verb) {
        if ($this->verb != $verb) {
            return false;
        }
        $partsURL = explode("/", trim($url, '/'));
        $partsRoute = explode("/", trim($this->url, '/'));

        if (count($partsRoute) != count($partsURL)) {
            return false;
        }

        foreach ($partsRoute as $key => $part) {
            if ($part[0] != ":") {
                if ($part != $partsURL[$key]) {
                    return false;
                }
            } else {
                // Es un parámetro dinámico
                $this->params[substr($part, 1)] = $partsURL[$key];
            }
        }
        return true;
    }

    // Método para ejecutar el controlador y pasar los parámetros
    public function run($request, $response) {
        $controller = $this->controller;
        $method = $this->method;
        // Asignar los parámetros extraídos a la solicitud
        $request->params = (object) $this->params;
        // Llamar al método correspondiente del controlador
        (new $controller())->$method($request, $response);
    }
}

class Router {
    private $routeTable = [];
    private $middlewares = [];
    private $defaultRoute;
    private $request;
    private $response;

    public function __construct() {
        $this->defaultRoute = null;
        $this->request = new Request();
        $this->response = new Response();
    }

    // Método para gestionar las rutas
    public function route($url, $verb) {
        foreach ($this->middlewares as $middleware) {
            $middleware->run($this->request, $this->response);
        }

        // Comprobar si alguna ruta coincide
        foreach ($this->routeTable as $route) {
            if ($route->match($url, $verb)) {
                $route->run($this->request, $this->response);
                return;
            }
        }

        // Si no se encuentra la ruta y se configuró una ruta por defecto
        if ($this->defaultRoute != null) {
            $this->defaultRoute->run($this->request, $this->response);
        } else {
            // En caso de no haber ruta encontrada, retornar un error 404
            $this->response->response(["error" => "Recurso no encontrado"], 404);
        }
    }

    // Método para agregar middleware
    public function addMiddleware($middleware) {
        $this->middlewares[] = $middleware;
    }

    // Método para agregar una nueva ruta
    public function addRoute($url, $verb, $controller, $method) {
        $this->routeTable[] = new Route($url, $verb, $controller, $method);
    }

    // Método para establecer la ruta por defecto
    public function setDefaultRoute($controller, $method) {
        $this->defaultRoute = new Route("", "", $controller, $method);
    }
}
