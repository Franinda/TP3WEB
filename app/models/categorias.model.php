<?php

class CategoriasModel {
    private function crearConexion() {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'velas';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
        } catch (\Throwable $th) {
            die($th);
        }

        return $pdo;
    }

    // Obtener todas las categorías
    public function getCategorias($sort = null, $order = 'asc') {
        $pdo = $this->crearConexion();
    
        $validColumns = ['ID_Categoria', 'Nombre_Categoria']; 
        $sort = in_array($sort, $validColumns) ? $sort : 'ID_Categoria'; 
        $order = strtolower($order) === 'desc' ? 'DESC' : 'ASC'; 
    
        $sql = "SELECT * FROM categorias ORDER BY $sort $order";
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    

    // Obtener categoría por ID
    public function getCatById($idCategoria) {
        $pdo = $this->crearConexion();
        $sql = "SELECT * FROM categorias WHERE ID_Categoria = $idCategoria"; 
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }
    

    // Editar categoría
    public function updateCat($idCategoria, $nombre, $imagen) {
        $pdo = $this->crearConexion();
        $sql = "UPDATE categorias SET Nombre_Categoria = '$nombre', Imagen_Categoria = '$imagen' WHERE ID_Categoria = $idCategoria"; 
        $query = $pdo->prepare($sql);
        return $query->execute();
    }
    

    // Crear nueva categoría
    public function insertarCat($nombre, $imagen) {
        $pdo = $this->crearConexion();
        $sql = "INSERT INTO categorias (Nombre_Categoria, Imagen_Categoria) VALUES ('$nombre', '$imagen')";
        $query = $pdo->prepare($sql);
        return $query->execute();
    }


}
