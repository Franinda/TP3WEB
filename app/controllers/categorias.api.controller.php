<?php
require_once 'app/controllers/api.controller.php';
require_once 'app/models/categorias.model.php';

class CategoriasApiController extends ApiController {
    private $model;

    function __construct() {
        parent::__construct();
        $this->model = new CategoriasModel();
    }

    // Obtener todas las categorías o una específica por ID
    function get($request, $response) {
        $sort = $_GET['sort'] ?? null; 
        $order = $_GET['order'] ?? 'asc'; 
    
        if (isset($request->params->ID)) {
            $categoria = $this->model->getCatById($request->params->ID);
            if (!empty($categoria)) {
                $response->response($categoria, 200);
            } else {
                $response->response('Categoría no encontrada.', 404);
            }
        } else {
            $categorias = $this->model->getCategorias($sort, $order);
            $response->response($categorias, 200);
        }
    }
    

    

    // Crear una nueva categoría
    function create($request, $response) {
        $body = $request->body; // El body ya viene parseado
        $Nombre_Categoria = $body['Nombre_Categoria'] ?? null;
        $Imagen_Categoria = $body['Imagen_Categoria'] ?? null;

        if ($Nombre_Categoria && $Imagen_Categoria) {
            $ID_Categoria = $this->model->insertarCat($Nombre_Categoria, $Imagen_Categoria);
            $response->response(["message" => "Categoría creada con ID=$ID_Categoria"], 201);
        } else {
            $response->response(["error" => "Datos incompletos."], 400);
        }
    }

    // Actualizar una categoría por ID
    function update($request, $response) {
        $ID_Categoria = $request->params->ID;
        $categoria = $this->model->getCatById($ID_Categoria);

        if ($categoria) {
            $body = $request->body;
            $Nombre_Categoria = $body['Nombre_Categoria'] ?? null;
            $Imagen_Categoria = $body['Imagen_Categoria'] ?? null;

            if ($Nombre_Categoria && $Imagen_Categoria) {
                $this->model->updateCat($ID_Categoria, $Nombre_Categoria, $Imagen_Categoria);
                $response->response(["message" => "Categoría con ID=$ID_Categoria actualizada."], 200);
            } else {
                $response->response(["error" => "Datos incompletos para actualizar."], 400);
            }
        } else {
            $response->response(["error" => "Categoría no encontrada."], 404);
        }
    }
}
