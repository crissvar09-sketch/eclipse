<?php
require_once 'config.php';
requireLogin();

$pedido_id = $_GET['pedido_id'] ?? null;

if (!$pedido_id) {
    header('Location: index.php');
    exit();
}

// Obtener pedido
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$pedido_id, getUserId()]);
$pedido = $stmt->fetch();

if (!$pedido) {
    header('Location: index.php');
    exit();
}

// Obtener detalles
$stmt = $pdo->prepare("
    SELECT dp.*, p.nombre as producto_nombre
    FROM detalle_pedidos dp
    JOIN productos p ON dp.producto_id = p.id
    WHERE dp.pedido_id = ?
");
$stmt->execute([$pedido_id]);
$detalles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación - Eclipse</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="animated-bg"></div>
    
    <header class="header">
        <div class="logo">
            <img src="assets/img/logo.png" alt="Logo de Eclipse" />
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
        <section class="confirmation-section">
            <div class="container text-center">
                <h2 class="season-heading">¡Pedido Confirmado! 🎉</h2>
                <p class="season-description">Gracias por tu compra. Tu pedido está siendo preparado.</p>
                <p style="margin-top: 30px; font-size: 1.5rem; color: var(--color-accent-gold);">
                    Número de Pedido: #<?= htmlspecialchars($pedido['numero_pedido']) ?>
                </p>
                
                <div style="margin: 40px 0; text-align: left; max-width: 600px; margin-left: auto; margin-right: auto;">
                    <h3>Detalles del Pedido:</h3>
                    <div style="background: rgba(255, 255, 255, 0.05); padding: 20px; border-radius: 10px; margin: 20px 0;">
                        <?php foreach ($detalles as $detalle): ?>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span><?= htmlspecialchars($detalle['producto_nombre']) ?> (x<?= $detalle['cantidad'] ?>)</span>
                                <span>$<?= number_format($detalle['subtotal'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr style="border: none; border-top: 1px dashed rgba(255, 255, 255, 0.2); margin: 15px 0;">
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem;">
                            <span>Total</span>
                            <span>$<?= number_format($pedido['total'], 2) ?></span>
                        </div>
                    </div>
                    
                    <div style="margin: 20px 0;">
                        <p><strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?></p>
                        <p><strong>Método de pago:</strong> <?= ucfirst($pedido['metodo_pago']) ?></p>
                        <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion_entrega']) ?></p>
                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono_contacto']) ?></p>
                    </div>
                </div>
                
                <a href="index.php" class="continue-shopping-btn">Volver al Inicio</a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <img src="assets/img/logo.png" alt="Logo de Eclipse" class="footer-logo">
        <p class="copyright">© 2024 Eclipse - Sabores que cambian con el tiempo.</p>
    </footer>
</body>
</html>