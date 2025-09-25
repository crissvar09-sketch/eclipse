<?php
require_once 'config.php';
requireLogin();

$user_id = getUserId();

// Obtener carrito
$stmt = $pdo->prepare("
    SELECT c.*, p.nombre, p.precio, p.imagen_url
    FROM carrito c
    JOIN productos p ON c.producto_id = p.id
    WHERE c.usuario_id = ? AND p.activo = 1
");
$stmt->execute([$user_id]);
$carrito = $stmt->fetchAll();

if (empty($carrito)) {
    header('Location: menu.php');
    exit();
}

// Calcular total
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

$error = '';

if ($_POST) {
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    
    if ($nombre && $direccion && $telefono && $metodo_pago) {
        try {
            $pdo->beginTransaction();
            
            // Crear pedido
            $numero_pedido = 'ECL' . date('YmdHis') . rand(100, 999);
            $stmt = $pdo->prepare("
                INSERT INTO pedidos (usuario_id, numero_pedido, total, metodo_pago, direccion_entrega, telefono_contacto) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$user_id, $numero_pedido, $total, $metodo_pago, $direccion, $telefono]);
            $pedido_id = $pdo->lastInsertId();
            
            // Crear detalles
            foreach ($carrito as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $stmt = $pdo->prepare("
                    INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$pedido_id, $item['producto_id'], $item['cantidad'], $item['precio'], $subtotal]);
            }
            
            // Limpiar carrito
            $stmt = $pdo->prepare("DELETE FROM carrito WHERE usuario_id = ?");
            $stmt->execute([$user_id]);
            
            $pdo->commit();
            header("Location: confirmacion.php?pedido_id=$pedido_id");
            exit();
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Error al procesar el pedido: ' . $e->getMessage();
        }
    } else {
        $error = 'Completa todos los campos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Eclipse</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/validation.js"></script>
</head>
<body>
    <div class="animated-bg"></div>
    
    <header class="header">
        <div class="logo">
            <img src="https://i.imgur.com/uW6yFj1.png" alt="Logo de Eclipse" />
            ECLIPSE
        </div>
        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="menu.php">Menú</a>
            <a href="carrito.php">Carrito</a>
            <a href="logout.php">Salir</a>
        </nav>
    </header>

    <main>
        <section class="menu-section">
            <div class="container">
                <h2 class="season-heading">Finalizar Compra</h2>
                <div class="checkout-container">
                    
                    <?php if ($error): ?>
                        <div class="error-message"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <div class="checkout-content">
                        <div class="checkout-form">
                            <form method="POST">
                                <h3>Información de Contacto</h3>
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" value="<?= htmlspecialchars(getUserName()) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" name="direccion" required>
                                </div>
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" name="telefono" required>
                                </div>
                                <h3>Método de Pago</h3>
                                <div class="payment-options">
                                    <label><input type="radio" name="metodo_pago" value="efectivo" checked> Efectivo</label>
                                    <label><input type="radio" name="metodo_pago" value="tarjeta"> Tarjeta</label>
                                    <label><input type="radio" name="metodo_pago" value="transferencia"> Transferencia</label>
                                </div>
                                <button type="submit" class="confirm-btn">Confirmar Pedido</button>
                            </form>
                        </div>
                        
                        <div class="order-summary">
                            <h3>Resumen del Pedido</h3>
                            <?php foreach ($carrito as $item): ?>
                                <div class="summary-item">
                                    <span><?= htmlspecialchars($item['nombre']) ?> (x<?= $item['cantidad'] ?>)</span>
                                    <span>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                            <div class="summary-total">
                                <span>Total</span>
                                <span>$<?= number_format($total, 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <img src="https://i.imgur.com/uW6yFj1.png" alt="Logo de Eclipse" class="footer-logo">
        <p class="copyright">© 2024 Eclipse - Sabores que cambian con el tiempo.</p>
    </footer>
</body>
</html>