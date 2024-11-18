<?php

class ProductosModel {
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

    public function getProductos($sort = null, $order = 'asc') {
        $pdo = $this->crearConexion();
        $validColumns = ['ID_Producto', 'Nombre_producto', 'Precio']; 
        $sort = in_array($sort, $validColumns) ? $sort : 'id'; 
        $order = strtolower($order) === 'desc' ? 'DESC' : 'ASC'; 
        $sql = "SELECT * FROM productos ORDER BY $sort $order";
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    

    //Consulta todas los producto por categoria
    public function getProductosByCategoria($categoriaID) {
        $pdo = $this->crearConexion();
        $sql = "SELECT p.* FROM productos p 
                JOIN categorias c ON p.ID_Categoria = c.ID_Categoria 
                WHERE p.ID_Categoria = $categoriaID";
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    //Consulta el producto por ID
    public function getProductoById($idProducto) {
        $pdo = $this->crearConexion();
        $sql = "SELECT * FROM productos WHERE ID_Producto = $idProducto"; 
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }


    //Update producto
    public function updateProducto($idProducto, $nombre, $precio, $categoriaID) {
        $pdo = $this->crearConexion();
        $sql = "UPDATE productos SET Nombre_producto = $nombre, Precio = $precio, ID_Categoria = $categoriaID WHERE ID_Producto = $idProducto";
        $query = $pdo->prepare($sql);
        return $query->execute();
    }

    //crar producto
    public function insertarProducto($nombre, $precio, $categoriaID) {
        $pdo = $this->crearConexion();
        $sql = "INSERT INTO productos (Nombre_producto, Precio, ID_Categoria) VALUES ($nombre, $precio, $categoriaID)";
        $query = $pdo->prepare($sql);
        return $query->execute();
    }
    
    
}
