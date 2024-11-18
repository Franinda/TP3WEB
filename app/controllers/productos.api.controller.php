<?php
require_once 'app/controllers/api.controller.php';
require_once 'app/models/productos.model.php';

class ProductosApiController extends ApiController {
    private $model;

    function __construct() {
        parent::__construct();
        $this->model = new ProductosModel();
    }

    function get($request, $response) {
        $sort = $_GET['sort'] ?? null; 
        $order = $_GET['order'] ?? 'asc'; 
    
        if (isset($request->params->ID)) {
            $producto = $this->model->getProductoById($request->params->ID);
            if (!empty($producto)) {
                $response->response($producto, 200);
            } else {
                $response->response('Producto no encontrado.', 404);
            }
        } else {
            // Pasar los parámetros al modelo
            $productos = $this->model->getProductos($sort, $order);
            $response->response($productos, 200);
        }
    }
    

    // Obtener productos por categoría
    function getByCategoria($request, $response) {
        if (isset($request->params->ID_Categoria)) {
            $categoriaID = $request->params->ID_Categoria;  
            $productos = $this->model->getProductosByCategoria($categoriaID);  
            if (!empty($productos)) {
                $response->response($productos, 200);  
            } else {
                $response->response('No se encontraron productos para esta categoría.', 404);  
            }
        } else {
            $response->response('ID de categoría no proporcionado.', 400);  
        }
    }

    // Crear un nuevo producto
    function create($request, $response) {
        $body = $request->body; 
        $Nombre_producto = $body['Nombre_producto'] ?? null;
        $Precio = $body['Precio'] ?? null;
        $ID_Categoria = $body['ID_Categoria'] ?? null;

        if ($Nombre_producto && $Precio && $ID_Categoria) {
            $ID_Producto = $this->model->insertarProducto($Nombre_producto, $Precio, $ID_Categoria);
            $response->response(["message" => "Producto creado con ID=$ID_Producto"], 201);
        } else {
            $response->response(["error" => "Datos incompletos."], 400);
        }
    }

    // Actualizar un producto por ID
    function update($request, $response) {
        $ID_Producto = $request->params->ID;
        $producto = $this->model->getProductoById($ID_Producto);

        if ($producto) {
            $body = $request->body;
            $Nombre_producto = $body['Nombre_producto'] ?? null;
            $Precio = $body['Precio'] ?? null;
            $ID_Categoria = $body['ID_Categoria'] ?? null;

            if ($Nombre_producto && $Precio && $ID_Categoria) {
                $this->model->updateProducto($ID_Producto, $Nombre_producto, $Precio, $ID_Categoria);
                $response->response(["message" => "Producto con ID=$ID_Producto actualizado."], 200);
            } else {
                $response->response(["error" => "Datos incompletos para actualizar."], 400);
            }
        } else {
            $response->response(["error" => "Producto no encontrado."], 404);
        }
    }

}
