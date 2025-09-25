<?php
/**
 * Header común para todas las páginas
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eclipse - Sabores que cambian con el tiempo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="animated-bg"></div>

    <header class="header" id="main-header">
        <div class="logo">
            <img src="https://i.imgur.com/uW6yFj1.png" alt="Logo de Eclipse" />
            ECLIPSE
        </div>
        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="menu.php">Menú Estacional</a>
            <a href="carrito.php" class="cart-link">
                Carrito
                <span class="cart-counter" style="display: none;">0</span>
            </a>
            <a href="pedidos.php">Pedidos</a>
            <?php if ($sessionManager->isLoggedIn()): ?>
                <a href="logout.php">Cerrar Sesión</a>
            <?php endif; ?>
        </nav>
    </header>
