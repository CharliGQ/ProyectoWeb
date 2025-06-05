<?php
session_start();
require_once '../controllers/carritoController.php';
$carritoController = new CarritoController();
$carrito = $carritoController->verCarrito();
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Realizar Pago</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include 'components/theme-toggle.php'; ?>

    <a href="productos.php" class="btn-back">
        <img src="../assets/img/arrow-left.svg" alt="Regresar">
    </a>

    <div class="container">
        <h1 class="section-title">Finalizar Compra</h1>
        
        <div class="checkout-container">
            <div class="checkout-summary">
                <h2>Resumen del Carrito</h2>
                <div id="cart-items">
                    <?php if ($carrito['success'] && !empty($carrito['productos'])): ?>
                        <?php foreach ($carrito['productos'] as $producto): ?>
                            <div class="checkout-item">
                                <img src="<?php echo $producto['imagen_url'] ? (strpos($producto['imagen_url'], '/') === 0 ? $producto['imagen_url'] : '/ProyectoWeb/uploads/productos/' . basename($producto['imagen_url'])) : ''; ?>" 
                                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                <div class="checkout-item-details">
                                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                                    <p>Cantidad: <?php echo $producto['cantidad']; ?></p>
                                    <p>Precio unitario: $<?php echo number_format($producto['precio_unitario'], 2); ?></p>
                                </div>
                                <div class="checkout-item-total">
                                    $<?php echo number_format($producto['cantidad'] * $producto['precio_unitario'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="checkout-total">
                            <strong>Total a pagar: $<?php echo number_format($carrito['total'], 2); ?></strong>
                        </div>
                    <?php else: ?>
                        <p>No hay productos en el carrito</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="payment-methods">
                <h2>Método de Pago</h2>
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" id="credit-card" name="payment-method" value="credit-card" checked>
                        <label for="credit-card">
                            <img src="../assets/img/credit-card.svg" alt="Tarjeta de Crédito">
                            Tarjeta de Crédito/Débito
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="paypal" name="payment-method" value="paypal">
                        <label for="paypal">
                            <img src="../assets/img/paypal.svg" alt="PayPal">
                            PayPal
                        </label>
                    </div>
                </div>

                <div id="credit-card-form" class="payment-form">
                    <div class="form-group">
                        <label for="card-number">Número de Tarjeta</label>
                        <input type="text" id="card-number" placeholder="1234 5678 9012 3456" maxlength="19">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry">Fecha de Expiración</label>
                            <input type="text" id="expiry" placeholder="MM/AA" maxlength="5">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" placeholder="123" maxlength="3">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="card-name">Nombre en la Tarjeta</label>
                        <input type="text" id="card-name" placeholder="Juan Pérez">
                    </div>
                </div>

                <div id="paypal-form" class="payment-form" style="display: none;">
                    <p>Serás redirigido a PayPal para completar tu pago de forma segura.</p>
                </div>

                <button id="pay-button" class="btn btn-primary btn-pay">
                    Realizar Compra
                </button>
            </div>
        </div>
    </div>

    <style>
    .btn-back {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: var(--card-bg);
        padding: 10px;
        border-radius: 50%;
        box-shadow: var(--shadow);
        transition: transform 0.3s ease;
    }

    .btn-back:hover {
        transform: scale(1.1);
    }

    .btn-back img {
        width: 24px;
        height: 24px;
        filter: var(--icon-filter);
    }

    .checkout-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .checkout-summary, .payment-methods {
        background: var(--card-bg);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: var(--shadow);
    }

    .checkout-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .checkout-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }

    .checkout-item-details {
        flex: 1;
    }

    .checkout-item-details h3 {
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .checkout-item-details p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin: 0.2rem 0;
    }

    .checkout-item-total {
        font-weight: 600;
        color: var(--text-color);
    }

    .checkout-total {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid var(--border-color);
        text-align: right;
        font-size: 1.2rem;
    }

    .payment-options {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .payment-option {
        flex: 1;
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .payment-option label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-option input[type="radio"]:checked + label {
        border-color: var(--primary-color);
        background: var(--hover-bg);
    }

    .payment-option img {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    .payment-form {
        margin: 1.5rem 0;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .btn-pay {
        width: 100%;
        padding: 1rem;
        font-size: 1.1rem;
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <script>
    document.querySelectorAll('input[name="payment-method"]').forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('credit-card-form').style.display = 
                this.value === 'credit-card' ? 'block' : 'none';
            document.getElementById('paypal-form').style.display = 
                this.value === 'paypal' ? 'block' : 'none';
        });
    });

    // Formatear número de tarjeta
    document.getElementById('card-number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        let formattedValue = '';
        for(let i = 0; i < value.length; i++) {
            if(i > 0 && i % 4 === 0) formattedValue += ' ';
            formattedValue += value[i];
        }
        e.target.value = formattedValue;
    });

    // Formatear fecha de expiración
    document.getElementById('expiry').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if(value.length > 2) {
            value = value.slice(0,2) + '/' + value.slice(2);
        }
        e.target.value = value;
    });

    // Solo números en CVV
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Simulación de pago
    document.getElementById('pay-button').addEventListener('click', function() {
        const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;
        
        if(paymentMethod === 'credit-card') {
            const cardNumber = document.getElementById('card-number').value;
            const expiry = document.getElementById('expiry').value;
            const cvv = document.getElementById('cvv').value;
            const cardName = document.getElementById('card-name').value;

            if(!cardNumber || !expiry || !cvv || !cardName) {
                alert('Por favor complete todos los campos de la tarjeta');
                return;
            }
        }

        // Simular procesamiento de pago
        this.disabled = true;
        this.textContent = 'Procesando...';

        // Procesar la venta
        fetch('../controllers/carritoController.php?action=procesar-venta', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams({ 
                metodo_pago: paymentMethod === 'credit-card' ? 'tarjeta' : 'paypal'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('¡Pago procesado con éxito!');
                window.location.href = 'productos.php';
            } else {
                alert(data.message || 'Error al procesar el pago');
                this.disabled = false;
                this.textContent = 'Realizar Compra';
            }
        })
        .catch(error => {
            alert('Error al procesar el pago');
            this.disabled = false;
            this.textContent = 'Realizar Compra';
        });
    });
    </script>
</body>
</html> 