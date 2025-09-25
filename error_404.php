<?php
/**
 * Página de error 404
 */
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | Eclipse</title>
    <link rel="stylesheet" href="assets/css/styles.css">
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
            <?php if (isLoggedIn()): ?>
                <a href="logout.php">Salir</a>
            <?php else: ?>
                <a href="login.php">Iniciar Sesión</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="error-page">
            <div class="container text-center">
                <h1 style="font-size: 8rem; color: #ff6b6b; margin: 2rem 0;">404</h1>
                <h2 class="season-heading">¡Oops! Página no encontrada</h2>
                <p class="season-description">Lo sentimos, la página que buscas no existe o ha sido movida.</p>
                <a href="index.php" class="continue-shopping-btn">Volver al Inicio</a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <img src="https://i.imgur.com/uW6yFj1.png" alt="Logo de Eclipse" class="footer-logo">
        <p class="copyright">© 2024 Eclipse - Sabores que cambian con el tiempo.</p>
    </footer>
</body>
</html>
