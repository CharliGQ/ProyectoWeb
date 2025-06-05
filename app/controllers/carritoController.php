<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../models/carritoModel.php';
require_once '../models/productoModel.php';

class CarritoController {
    private $crudCarrito;
    private $crudProducto;

    public function __construct() {
        $this->crudCarrito = new CrudCarrito();
        $this->crudProducto = new CrudProducto();
    }

    public function agregarAlCarrito($id_producto, $cantidad = 1) {
        if (!isset($_SESSION['usuario'])) {
            return ['success' => false, 'message' => 'Debes iniciar sesión para agregar productos al carrito'];
        }
        $id_usuario = $_SESSION['usuario']['id'];
        // Buscar o crear carrito activo
        $id_carrito = $this->crudCarrito->obtenerCarritoActivo($id_usuario);
        if (!$id_carrito) {
            $id_carrito = $this->crudCarrito->crearCarrito($id_usuario);
        }
        // Obtener precio actual del producto
        $producto = $this->crudProducto->obtenerProducto($id_producto);
        if (!$producto) {
            return ['success' => false, 'message' => 'Producto no encontrado'];
        }
        $precio_unitario = $producto->getPrecio();
        $this->crudCarrito->agregarProducto($id_carrito, $id_producto, $cantidad, $precio_unitario);
        return ['success' => true, 'message' => 'Producto agregado al carrito'];
    }

    public function verCarrito() {
        if (!isset($_SESSION['usuario'])) {
            return ['success' => false, 'message' => 'Debes iniciar sesión'];
        }
        $id_usuario = $_SESSION['usuario']['id'];
        $id_carrito = $this->crudCarrito->obtenerCarritoActivo($id_usuario);
        if (!$id_carrito) {
            return ['success' => true, 'productos' => [], 'total' => 0];
        }
        $db = Db::conectar();
        $select = $db->prepare('SELECT cd.id_producto, cd.cantidad, cd.precio_unitario, p.nombre, p.imagen_url FROM carrito_detalle cd JOIN productos p ON cd.id_producto = p.id_producto WHERE cd.id_carrito = :id_carrito');
        $select->bindValue('id_carrito', $id_carrito);
        $select->execute();
        $productos = $select->fetchAll();
        $total = 0;
        foreach ($productos as $prod) {
            $total += $prod['cantidad'] * $prod['precio_unitario'];
        }
        return ['success' => true, 'productos' => $productos, 'total' => $total];
    }

    public function actualizarCantidad($id_producto, $cantidad) {
        if (!isset($_SESSION['usuario'])) {
            return ['success' => false, 'message' => 'Debes iniciar sesión'];
        }
        if ($cantidad < 1) {
            return ['success' => false, 'message' => 'La cantidad debe ser mayor a 0'];
        }
        
        $id_usuario = $_SESSION['usuario']['id'];
        $id_carrito = $this->crudCarrito->obtenerCarritoActivo($id_usuario);
        if (!$id_carrito) {
            return ['success' => false, 'message' => 'No hay carrito activo'];
        }

        $db = Db::conectar();
        // Verificar si el producto existe en el carrito
        $select = $db->prepare('SELECT * FROM carrito_detalle WHERE id_carrito = :id_carrito AND id_producto = :id_producto');
        $select->bindValue('id_carrito', $id_carrito);
        $select->bindValue('id_producto', $id_producto);
        $select->execute();
        $detalle = $select->fetch();

        if (!$detalle) {
            return ['success' => false, 'message' => 'Producto no encontrado en el carrito'];
        }

        // Actualizar cantidad
        $update = $db->prepare('UPDATE carrito_detalle SET cantidad = :cantidad WHERE id_detalle = :id_detalle');
        $update->bindValue('cantidad', $cantidad);
        $update->bindValue('id_detalle', $detalle['id_detalle']);
        
        if ($update->execute()) {
            // Actualizar total del carrito
            $this->crudCarrito->actualizarTotal($id_carrito);
            return ['success' => true, 'message' => 'Cantidad actualizada'];
        }
        
        return ['success' => false, 'message' => 'Error al actualizar la cantidad'];
    }

    public function eliminarProducto($id_producto) {
        if (!isset($_SESSION['usuario'])) {
            return ['success' => false, 'message' => 'Debes iniciar sesión'];
        }
        
        $id_usuario = $_SESSION['usuario']['id'];
        $id_carrito = $this->crudCarrito->obtenerCarritoActivo($id_usuario);
        if (!$id_carrito) {
            return ['success' => false, 'message' => 'No hay carrito activo'];
        }

        $db = Db::conectar();
        // Verificar si el producto existe en el carrito
        $select = $db->prepare('SELECT * FROM carrito_detalle WHERE id_carrito = :id_carrito AND id_producto = :id_producto');
        $select->bindValue('id_carrito', $id_carrito);
        $select->bindValue('id_producto', $id_producto);
        $select->execute();
        $detalle = $select->fetch();

        if (!$detalle) {
            return ['success' => false, 'message' => 'Producto no encontrado en el carrito'];
        }

        // Eliminar producto
        $delete = $db->prepare('DELETE FROM carrito_detalle WHERE id_detalle = :id_detalle');
        $delete->bindValue('id_detalle', $detalle['id_detalle']);
        
        if ($delete->execute()) {
            // Actualizar total del carrito
            $this->crudCarrito->actualizarTotal($id_carrito);
            return ['success' => true, 'message' => 'Producto eliminado del carrito'];
        }
        
        return ['success' => false, 'message' => 'Error al eliminar el producto'];
    }

    public function procesarVenta($metodo_pago) {
        if (!isset($_SESSION['usuario'])) {
            return ['success' => false, 'message' => 'Debes iniciar sesión'];
        }

        $id_usuario = $_SESSION['usuario']['id'];
        $id_carrito = $this->crudCarrito->obtenerCarritoActivo($id_usuario);
        
        if (!$id_carrito) {
            return ['success' => false, 'message' => 'No hay carrito activo'];
        }

        $db = Db::conectar();
        try {
            $db->beginTransaction();

            // Obtener detalles del carrito
            $select = $db->prepare('SELECT cd.*, p.stock FROM carrito_detalle cd 
                                  JOIN productos p ON cd.id_producto = p.id_producto 
                                  WHERE cd.id_carrito = :id_carrito');
            $select->bindValue('id_carrito', $id_carrito);
            $select->execute();
            $detalles = $select->fetchAll();

            // Verificar stock
            foreach ($detalles as $detalle) {
                if ($detalle['cantidad'] > $detalle['stock']) {
                    throw new Exception('Stock insuficiente para el producto ID: ' . $detalle['id_producto']);
                }
            }

            // Obtener total del carrito
            $select = $db->prepare('SELECT total FROM carrito_compras WHERE id_carrito = :id_carrito');
            $select->bindValue('id_carrito', $id_carrito);
            $select->execute();
            $total = $select->fetchColumn();

            // Crear venta
            $insert = $db->prepare('INSERT INTO ventas (id_carrito, total, metodo_pago, status) 
                                  VALUES (:id_carrito, :total, :metodo_pago, "pagado")');
            $insert->bindValue('id_carrito', $id_carrito);
            $insert->bindValue('total', $total);
            $insert->bindValue('metodo_pago', $metodo_pago);
            $insert->execute();
            $id_venta = $db->lastInsertId();

            // Crear detalles de venta y actualizar stock
            foreach ($detalles as $detalle) {
                // Insertar detalle de venta
                $insert = $db->prepare('INSERT INTO ventas_detalle (id_venta, id_producto, cantidad, precio_unitario) 
                                      VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario)');
                $insert->bindValue('id_venta', $id_venta);
                $insert->bindValue('id_producto', $detalle['id_producto']);
                $insert->bindValue('cantidad', $detalle['cantidad']);
                $insert->bindValue('precio_unitario', $detalle['precio_unitario']);
                $insert->execute();

                // Actualizar stock
                $update = $db->prepare('UPDATE productos SET stock = stock - :cantidad 
                                      WHERE id_producto = :id_producto');
                $update->bindValue('cantidad', $detalle['cantidad']);
                $update->bindValue('id_producto', $detalle['id_producto']);
                $update->execute();
            }

            // Marcar carrito como completado
            $update = $db->prepare('UPDATE carrito_compras SET estado = "comprado" WHERE id_carrito = :id_carrito');
            $update->bindValue('id_carrito', $id_carrito);
            $update->execute();

            $db->commit();
            return ['success' => true, 'message' => 'Venta procesada con éxito'];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Error al procesar la venta: ' . $e->getMessage()];
        }
    }
}

// Manejo de peticiones AJAX
if (isset($_GET['action']) && $_GET['action'] === 'agregar') {
    header('Content-Type: application/json');
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
    $carritoController = new CarritoController();
    echo json_encode($carritoController->agregarAlCarrito($id_producto, $cantidad));
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'ver') {
    header('Content-Type: application/json');
    $carritoController = new CarritoController();
    echo json_encode($carritoController->verCarrito());
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'actualizar') {
    header('Content-Type: application/json');
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
    $carritoController = new CarritoController();
    echo json_encode($carritoController->actualizarCantidad($id_producto, $cantidad));
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'eliminar') {
    header('Content-Type: application/json');
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $carritoController = new CarritoController();
    echo json_encode($carritoController->eliminarProducto($id_producto));
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'procesar-venta') {
    header('Content-Type: application/json');
    $metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : '';
    $carritoController = new CarritoController();
    echo json_encode($carritoController->procesarVenta($metodo_pago));
    exit();
}
?> 