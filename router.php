<?php
require_once 'libs/router.php';
require_once 'app/controllers/categorias.api.controller.php';
require_once 'app/controllers/productos.api.controller.php';

$router = new Router();

// Rutas de la API
$router->addRoute('categorias', 'GET', 'CategoriasApiController', 'get');       
$router->addRoute('categorias', 'POST', 'CategoriasApiController', 'create');  
$router->addRoute('categorias/:ID', 'GET', 'CategoriasApiController', 'get');  
$router->addRoute('categorias/:ID', 'PUT', 'CategoriasApiController', 'update'); 

$router->addRoute('productos', 'GET', 'ProductosApiController', 'get');       
$router->addRoute('productosByCategoria/:ID_Categoria', 'GET', 'ProductosApiController', 'getByCategoria'); 
$router->addRoute('productos', 'POST', 'ProductosApiController', 'create');   
$router->addRoute('productos/:ID', 'GET', 'ProductosApiController', 'get');    
$router->addRoute('productos/:ID', 'PUT', 'ProductosApiController', 'update'); 

$resource = $_GET['resource'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

if (!$resource) {
    http_response_code(400);
    echo json_encode(["error" => "No se especificó el recurso."]);
    exit;
}

// Procesar la solicitud usando el router
$router->route($resource, $method);
