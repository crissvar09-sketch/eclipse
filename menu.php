<?php
require_once 'config.php';
requireLogin();

// Obtener productos por temporada
$temporadas = ['primavera', 'verano', 'otono', 'invierno'];
$productos_por_temporada = [];

foreach ($temporadas as $temporada) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE temporada = ? AND activo = 1 ORDER BY nombre");
    $stmt->execute([$temporada]);
    $productos_por_temporada[$temporada] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - Eclipse</title>
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
                <h2>Nuestro Menú Estacional</h2>
                
                <?php foreach ($temporadas as $temporada): ?>
                    <div class="season-section">
                        <h3 class="season-heading">
                            <?php
                            $iconos = [
                                'primavera' => '🌷',
                                'verano' => '☀️',
                                'otono' => '🍂',
                                'invierno' => '❄️'
                            ];
                            echo ucfirst($temporada) . ' ' . $iconos[$temporada];
                            ?>
                        </h3>
                        <p class="season-description">
                            <?php
                            $descripciones = [
                                'primavera' => 'Celebra el renacer con sabores frescos, florales y vibrantes.',
                                'verano' => '¡El sol nos llama! Disfruta de nuestros cafés especiales.',
                                'otono' => 'Colores cálidos y sabores especiados.',
                                'invierno' => 'Refresca tus sentidos con nuestros helados artesanales.'
                            ];
                            echo $descripciones[$temporada];
                            ?>
                        </p>
                        <div class="products-grid">
                            <?php foreach ($productos_por_temporada[$temporada] as $producto): ?>
                                <div class="product-card" data-product-id="<?= $producto['id'] ?>">
                                    <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="product-image">
                                    <h4 class="product-name"><?= htmlspecialchars($producto['nombre']) ?></h4>
                                    <p class="product-description"><?= htmlspecialchars($producto['descripcion']) ?></p>
                                    <p class="product-price">$<?= number_format($producto['precio'], 2) ?></p>
                                    <div class="quantity-control">
                                        <button class="decrease-btn">-</button>
                                        <span class="quantity">1</span>
                                        <button class="increase-btn">+</button>
                                    </div>
                                    <button class="add-btn" onclick="addToCart(<?= $producto['id'] ?>, <?= $producto['precio'] ?>, '<?= htmlspecialchars($producto['nombre']) ?>', '<?= htmlspecialchars($producto['imagen_url']) ?>')">Agregar al carrito</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <?php if ($temporada !== 'invierno'): ?>
                        <hr style="border: none; border-top: 1px dashed rgba(255, 255, 255, 0.1); margin: 60px 0;">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <img src="assets/img/logo.png" alt="Logo de Eclipse" class="footer-logo">
        <p class="copyright">© 2025 Eclipse - Sabores que cambian con el tiempo.</p>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>