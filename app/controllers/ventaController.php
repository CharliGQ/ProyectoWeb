<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/ventaModel.php');

class VentaController {
    public function __construct() {}

    public function obtenerVentas() {
        $db = Db::conectar();
        $listaVentas = [];
        
        $query = "SELECT 
            v.id_venta,
            v.fecha_venta,
            v.total,
            v.metodo_pago,
            v.status,
            u.nombre_usuario,
            u.correo,
            p.nombre AS nombre_producto,
            vd.cantidad,
            vd.precio_unitario,
            vd.subtotal
        FROM ventas v
        JOIN carrito_compras c ON v.id_carrito = c.id_carrito
        JOIN usuarios u ON c.id_usuario = u.id_usuario
        JOIN ventas_detalle vd ON v.id_venta = vd.id_venta
        JOIN productos p ON vd.id_producto = p.id_producto
        ORDER BY v.fecha_venta DESC";

        $select = $db->query($query);

        foreach ($select->fetchAll() as $venta) {
            $myVenta = new Venta();
            $myVenta->setIdVenta($venta['id_venta']);
            $myVenta->setFechaVenta($venta['fecha_venta']);
            $myVenta->setTotal($venta['total']);
            $myVenta->setMetodoPago($venta['metodo_pago']);
            $myVenta->setStatus($venta['status']);
            $myVenta->setNombreUsuario($venta['nombre_usuario']);
            $myVenta->setCorreo($venta['correo']);
            $myVenta->setNombreProducto($venta['nombre_producto']);
            $myVenta->setCantidad($venta['cantidad']);
            $myVenta->setPrecioUnitario($venta['precio_unitario']);
            $myVenta->setSubtotal($venta['subtotal']);
            
            $listaVentas[] = $myVenta;
        }
        return $listaVentas;
    }
}

// Manejo de peticiones AJAX
if (isset($_GET['action'])) {
    $controller = new VentaController();
    
    switch ($_GET['action']) {
        case 'obtenerVentas':
            $ventas = $controller->obtenerVentas();
            $ventasArray = array_map(function($venta) {
                return [
                    'id_venta' => $venta->getIdVenta(),
                    'fecha_venta' => $venta->getFechaVenta(),
                    'total' => $venta->getTotal(),
                    'metodo_pago' => $venta->getMetodoPago(),
                    'status' => $venta->getStatus(),
                    'nombre_usuario' => $venta->getNombreUsuario(),
                    'correo' => $venta->getCorreo(),
                    'nombre_producto' => $venta->getNombreProducto(),
                    'cantidad' => $venta->getCantidad(),
                    'precio_unitario' => $venta->getPrecioUnitario(),
                    'subtotal' => $venta->getSubtotal()
                ];
            }, $ventas);
            
            header('Content-Type: application/json');
            echo json_encode($ventasArray);
            break;
    }
}
?> 