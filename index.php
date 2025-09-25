<?php
require_once 'config.php';
requireLogin();

// Obtener productos
$stmt = $pdo->query("SELECT * FROM productos WHERE activo = 1 ORDER BY temporada, nombre");
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eclipse - Heladería</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="animated-bg">
        <div class="floating-element floating-1"></div>
        <div class="floating-element floating-2"></div>
        <div class="floating-element floating-3"></div>
        <div class="floating-element floating-4"></div>
    </div>
    
    <header class="header">
        <div class="logo">
            <img src="assets/img/logo.png" alt="Logo de Eclipse" />
            ECLIPSE
        </div>
        <nav class="nav">
            <a href="index.php" class="active">Inicio</a>
            <a href="menu.php">Menú</a>
            <a href="carrito.php">Carrito</a>
            <a href="logout.php">Salir</a>
        </nav>
    </header>

    <main>
        <section class="hero-section">
            <div class="hero-left">
                <div class="hero-content">
                    <div class="hero-badge">Productos Artesanales</div>
                    <h1 class="hero-title">
                        <span class="title-line">¡Bienvenidos a</span>
                        <span class="title-line">Heladería de</span>
                        <span class="title-line">Productos <span class="highlight">Finos</span>!</span>
                    </h1>
                    <p class="hero-text-description">
                        Donde cada estación trae un nuevo sabor. Deléitate con nuestras 
                        <span class="text-highlight">creaciones artesanales</span> elaboradas 
                        con ingredientes premium y amor por el detalle.
                    </p>
                    <div class="hero-buttons">
                        <a href="menu.php" class="menu-btn-hero primary">
                            <span>Ver Menú</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="#features" class="menu-btn-hero secondary">
                            <span>Descubrir Más</span>
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-images-container">
                    <div class="hero-image-wrapper main-image">
                        <img src="assets/img/unnamed.png" alt="Helado de Fresa" class="hero-ice-cream-img">
                        <div class="image-glow"></div>
                    </div>
                    <div class="hero-image-wrapper secondary-image">
                        <img src="assets/img/images.jpeg" alt="Helado de Vainilla" class="hero-ice-cream-img">
                        <div class="image-glow"></div>
                    </div>
                    <div class="floating-icons">
                        <div class="floating-icon icon-1">🍓</div>
                        <div class="floating-icon icon-2">🍦</div>
                        <div class="floating-icon icon-3">✨</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="features-section">
            <div class="container">
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Ingredientes Naturales</h3>
                        <p>Solo utilizamos ingredientes frescos y naturales de la más alta calidad</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3>Recetas Artesanales</h3>
                        <p>Cada helado es elaborado con técnicas tradicionales y recetas únicas</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-snowflake"></i>
                        </div>
                        <h3>Sabores de Temporada</h3>
                        <p>Renovamos nuestro menú siguiendo el ritmo de las estaciones</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo-section">
                <img src="assets/img/logo.png" alt="Logo de Eclipse" class="footer-logo">
                <p class="footer-tagline">Sabores que cambian con el tiempo</p>
            </div>
            <div class="footer-social">
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
        <p class="copyright">© 2025 Eclipse - Todos los derechos reservados.</p>
    </footer>

    <script>
        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const heroContent = document.querySelector('.hero-content');
            const heroImages = document.querySelector('.hero-images-container');
            
            setTimeout(() => {
                heroContent.style.opacity = '1';
                heroContent.style.transform = 'translateY(0)';
            }, 300);
            
            setTimeout(() => {
                heroImages.style.opacity = '1';
                heroImages.style.transform = 'translateX(0)';
            }, 600);
        });

        // Scroll suave para el botón "Descubrir Más"
        document.querySelector('.secondary').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('#features').scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>