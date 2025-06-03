<?php
require_once '../config/database.php';

function crearProducto($nombre, $descripcion, $precio, $stock, $imagen_url, $id_usuario) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen_url, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $precio, $stock, $imagen_url, $id_usuario]);
    } catch (PDOException $e) {
        error_log("Error al crear producto: " . $e->getMessage());
        return false;
    }
}

function listarProductosPorCreador($id_usuario) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al listar productos: " . $e->getMessage());
        return [];
    }
}

function eliminarProducto($id_producto) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
        return $stmt->execute([$id_producto]);
    } catch (PDOException $e) {
        error_log("Error al eliminar producto: " . $e->getMessage());
        return false;
    }
}
?>