<?php
class Venta {
    private $id_venta;
    private $fecha_venta;
    private $total;
    private $metodo_pago;
    private $status;
    private $nombre_usuario;
    private $correo;
    private $nombre_producto;
    private $cantidad;
    private $precio_unitario;
    private $subtotal;

    public function __construct() {}

    // Getters y Setters
    public function getIdVenta() {
        return $this->id_venta;
    }

    public function setIdVenta($id_venta) {
        $this->id_venta = $id_venta;
    }

    public function getFechaVenta() {
        return $this->fecha_venta;
    }

    public function setFechaVenta($fecha_venta) {
        $this->fecha_venta = $fecha_venta;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function getMetodoPago() {
        return $this->metodo_pago;
    }

    public function setMetodoPago($metodo_pago) {
        $this->metodo_pago = $metodo_pago;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getNombreUsuario() {
        return $this->nombre_usuario;
    }

    public function setNombreUsuario($nombre_usuario) {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getNombreProducto() {
        return $this->nombre_producto;
    }

    public function setNombreProducto($nombre_producto) {
        $this->nombre_producto = $nombre_producto;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function getPrecioUnitario() {
        return $this->precio_unitario;
    }

    public function setPrecioUnitario($precio_unitario) {
        $this->precio_unitario = $precio_unitario;
    }

    public function getSubtotal() {
        return $this->subtotal;
    }

    public function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;
    }
}
?> 