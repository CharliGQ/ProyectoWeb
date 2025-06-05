<?php
require_once '../config/database.php';

class Producto {
    private $id_producto;
    private $id_usuario;
    private $nombre;
    private $descripcion;
    private $precio;
    private $stock;
    private $imagen_url;
    private $fecha_creacion;

    public function __construct() {}

    public function getIdProducto() {
        return $this->id_producto;
    }

    public function setIdProducto($id_producto) {
        $this->id_producto = $id_producto;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function getImagenUrl() {
        return $this->imagen_url;
    }

    public function setImagenUrl($imagen_url) {
        $this->imagen_url = $imagen_url;
    }

    public function getFechaCreacion() {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }
}

class CrudProducto {
    public function __construct() {}

    // Create
    public function insertar($producto) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO productos (id_usuario, nombre, descripcion, precio, stock, imagen_url) VALUES (:id_usuario, :nombre, :descripcion, :precio, :stock, :imagen_url)');
        $insert->bindValue('id_usuario', $producto->getIdUsuario());
        $insert->bindValue('nombre', $producto->getNombre());
        $insert->bindValue('descripcion', $producto->getDescripcion());
        $insert->bindValue('precio', $producto->getPrecio());
        $insert->bindValue('stock', $producto->getStock());
        $insert->bindValue('imagen_url', $producto->getImagenUrl());
        return $insert->execute();
    }

    // Read
    public function mostrar() {
        $db = Db::conectar();
        $listaProductos = [];
        $select = $db->query('SELECT * FROM productos');

        foreach ($select->fetchAll() as $producto) {
            $myProducto = new Producto();
            $myProducto->setIdProducto($producto['id_producto']);
            $myProducto->setIdUsuario($producto['id_usuario']);
            $myProducto->setNombre($producto['nombre']);
            $myProducto->setDescripcion($producto['descripcion']);
            $myProducto->setPrecio($producto['precio']);
            $myProducto->setStock($producto['stock']);
            $myProducto->setImagenUrl($producto['imagen_url']);
            $myProducto->setFechaCreacion($producto['fecha_creacion']);
            $listaProductos[] = $myProducto;
        }
        return $listaProductos;
    }

    // Read by creator
    public function mostrarPorCreador($id_usuario) {
        $db = Db::conectar();
        $listaProductos = [];
        $select = $db->prepare('SELECT * FROM productos WHERE id_usuario = :id_usuario');
        $select->bindValue('id_usuario', $id_usuario);
        $select->execute();

        foreach ($select->fetchAll() as $producto) {
            $myProducto = new Producto();
            $myProducto->setIdProducto($producto['id_producto']);
            $myProducto->setIdUsuario($producto['id_usuario']);
            $myProducto->setNombre($producto['nombre']);
            $myProducto->setDescripcion($producto['descripcion']);
            $myProducto->setPrecio($producto['precio']);
            $myProducto->setStock($producto['stock']);
            $myProducto->setImagenUrl($producto['imagen_url']);
            $myProducto->setFechaCreacion($producto['fecha_creacion']);
            $listaProductos[] = $myProducto;
        }
        return $listaProductos;
    }

    // Update
    public function actualizar($producto) {
        $db = Db::conectar();
        $actualizar = $db->prepare('UPDATE productos SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock, imagen_url=:imagen_url WHERE id_producto=:id');
        $actualizar->bindValue('id', $producto->getIdProducto());
        $actualizar->bindValue('nombre', $producto->getNombre());
        $actualizar->bindValue('descripcion', $producto->getDescripcion());
        $actualizar->bindValue('precio', $producto->getPrecio());
        $actualizar->bindValue('stock', $producto->getStock());
        $actualizar->bindValue('imagen_url', $producto->getImagenUrl());
        return $actualizar->execute();
    }

    // Delete
    public function eliminar($id) {
        $db = Db::conectar();
        $eliminar = $db->prepare('DELETE FROM productos WHERE id_producto=:id');
        $eliminar->bindValue('id', $id);
        return $eliminar->execute();
    }

    // Get single product
    public function obtenerProducto($id) {
        $db = Db::conectar();
        $select = $db->prepare('SELECT * FROM productos WHERE id_producto=:id');
        $select->bindValue('id', $id);
        $select->execute();
        $producto = $select->fetch();
        
        if ($producto) {
            $myProducto = new Producto();
            $myProducto->setIdProducto($producto['id_producto']);
            $myProducto->setIdUsuario($producto['id_usuario']);
            $myProducto->setNombre($producto['nombre']);
            $myProducto->setDescripcion($producto['descripcion']);
            $myProducto->setPrecio($producto['precio']);
            $myProducto->setStock($producto['stock']);
            $myProducto->setImagenUrl($producto['imagen_url']);
            $myProducto->setFechaCreacion($producto['fecha_creacion']);
            return $myProducto;
        }
        return null;
    }
}
?>