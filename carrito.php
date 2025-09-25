<?php
require_once 'config.php';
requireLogin();

$user_id = getUserId();

// Obtener carrito del usuario
$stmt = $pdo->prepare("
    SELECT c.*, p.nombre, p.precio, p.imagen_url, p.descripcion
    FROM carrito c
    JOIN productos p ON c.producto_id = p.id
    WHERE c.usuario_id = ? AND p.activo = 1
");
$stmt->execute([$user_id]);
$carrito = $stmt->fetchAll();

// Calcular total
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Eclipse</title>
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
        <section class="menu-section">
            <div class="container">
                <h2 class="season-heading">Mi Carrito 🛒</h2>
                <div class="cart-container">
                    <div id="cart-items-container">
                        <?php if (empty($carrito)): ?>
                            <p style="text-align: center; margin-top: 20px; color: rgba(255, 255, 255, 0.6);">Tu carrito está vacío.</p>
                        <?php else: ?>
                            <?php foreach ($carrito as $item): ?>
                                <div class="cart-item">
                                    <div class="cart-item-info">
                                        <img src="<?= htmlspecialchars($item['imagen_url']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                                        <span class="cart-item-name"><?= htmlspecialchars($item['nombre']) ?></span>
                                    </div>
                                    <div class="cart-item-controls">
                                        <button onclick="updateQuantity(<?= $item['producto_id'] ?>, <?= $item['cantidad'] - 1 ?>)">-</button>
                                        <span class="quantity"><?= $item['cantidad'] ?></span>
                                        <button onclick="updateQuantity(<?= $item['producto_id'] ?>, <?= $item['cantidad'] + 1 ?>)">+</button>
                                        <button onclick="removeItem(<?= $item['producto_id'] ?>)">🗑️</button>
                                    </div>
                                    <span class="cart-item-price">$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="cart-total">
                        <span>Total</span>
                        <span>$<?= number_format($total, 2) ?></span>
                    </div>
                    <div class="cart-actions">
                        <?php if (!empty($carrito)): ?>
                            <a href="checkout.php" class="checkout-btn">Finalizar compra</a>
                        <?php endif; ?>
                        <a href="menu.php" class="continue-shopping-btn">Seguir comprando</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <img src="assets/img/logo.png" alt="Logo de Eclipse" class="footer-logo">
        <p class="copyright">© 2025 Eclipse - Sabores que cambian con el tiempo.</p>
    </footer>

    <script>
        function updateQuantity(productId, newQuantity) {
            if (newQuantity <= 0) {
                removeItem(productId);
            } else {
                fetch('api_carrito.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'update', product_id: productId, quantity: newQuantity})
                }).then(() => location.reload());
            }
        }

        function removeItem(productId) {
            fetch('api_carrito.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({action: 'remove', product_id: productId})
            }).then(() => location.reload());
        }
    </script>
</body>
</html>