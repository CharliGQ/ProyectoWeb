<?php
require_once '../config/database.php';

class Carrito {
    private $id_carrito;
    private $id_usuario;
    private $estado;
    private $total;
    private $fecha_creacion;

    public function __construct() {}

    public function getIdCarrito() { return $this->id_carrito; }
    public function setIdCarrito($id_carrito) { $this->id_carrito = $id_carrito; }

    public function getIdUsuario() { return $this->id_usuario; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    public function getFechaCreacion() { return $this->fecha_creacion; }
    public function setFechaCreacion($fecha_creacion) { $this->fecha_creacion = $fecha_creacion; }
}

class CrudCarrito {
    public function __construct() {}

    // Buscar carrito activo del usuario
    public function obtenerCarritoActivo($id_usuario) {
        $db = Db::conectar();
        $select = $db->prepare('SELECT * FROM carrito_compras WHERE id_usuario = :id_usuario AND estado = "activo" LIMIT 1');
        $select->bindValue('id_usuario', $id_usuario);
        $select->execute();
        $carrito = $select->fetch();
        return $carrito ? $carrito['id_carrito'] : null;
    }

    // Crear un nuevo carrito
    public function crearCarrito($id_usuario) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO carrito_compras (id_usuario, estado, total) VALUES (:id_usuario, "activo", 0.00)');
        $insert->bindValue('id_usuario', $id_usuario);
        $insert->execute();
        return $db->lastInsertId();
    }

    // Agregar producto al carrito
    public function agregarProducto($id_carrito, $id_producto, $cantidad, $precio_unitario) {
        $db = Db::conectar();
        // Verificar si ya existe el producto en el carrito
        $select = $db->prepare('SELECT * FROM carrito_detalle WHERE id_carrito = :id_carrito AND id_producto = :id_producto');
        $select->bindValue('id_carrito', $id_carrito);
        $select->bindValue('id_producto', $id_producto);
        $select->execute();
        $detalle = $select->fetch();
        if ($detalle) {
            // Si existe, actualizar cantidad
            $nuevaCantidad = $detalle['cantidad'] + $cantidad;
            $update = $db->prepare('UPDATE carrito_detalle SET cantidad = :cantidad WHERE id_detalle = :id_detalle');
            $update->bindValue('cantidad', $nuevaCantidad);
            $update->bindValue('id_detalle', $detalle['id_detalle']);
            $update->execute();
        } else {
            // Si no existe, insertar nuevo
            $insert = $db->prepare('INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad, precio_unitario) VALUES (:id_carrito, :id_producto, :cantidad, :precio_unitario)');
            $insert->bindValue('id_carrito', $id_carrito);
            $insert->bindValue('id_producto', $id_producto);
            $insert->bindValue('cantidad', $cantidad);
            $insert->bindValue('precio_unitario', $precio_unitario);
            $insert->execute();
        }
        // Actualizar total del carrito
        $this->actualizarTotal($id_carrito);
    }

    // Actualizar total del carrito
    public function actualizarTotal($id_carrito) {
        $db = Db::conectar();
        $select = $db->prepare('SELECT SUM(cantidad * precio_unitario) as total FROM carrito_detalle WHERE id_carrito = :id_carrito');
        $select->bindValue('id_carrito', $id_carrito);
        $select->execute();
        $total = $select->fetchColumn();
        $update = $db->prepare('UPDATE carrito_compras SET total = :total WHERE id_carrito = :id_carrito');
        $update->bindValue('total', $total);
        $update->bindValue('id_carrito', $id_carrito);
        $update->execute();
    }
}
?> 