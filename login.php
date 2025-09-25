<?php
require_once 'config.php';

// Si ya está logueado, ir al inicio
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar la entrada para evitar XSS
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validación más estricta
    if (empty($username) || empty($password)) {
        $error = 'Completa todos los campos.';
    } else {
        try {
            // Usar consultas preparadas para prevenir inyección SQL
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ? AND activo = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                login($user);
                header('Location: index.php');
                exit();
            } else {
                $error = 'Credenciales incorrectas o usuario inactivo.';
            }
        } catch (PDOException $e) {
            // En un entorno de producción, registrar este error en un log
            $error = 'Ha ocurrido un error en la conexión a la base de datos. Inténtalo de nuevo más tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Eclipse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Variables y estilos globales */
        :root {
            --color-primary-dark: #1a0f3a;
            --color-secondary-dark: #2d1b69;
            --color-accent-gold: #d4af37;
            --color-accent-purple: #6a5acd;
            --color-text-light: #f0f0f0;
            --color-background-soft: #3a2b7e;
            --color-error: #ff4d4d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--color-text-light);
            min-height: 100vh;
            overflow: hidden; /* Evitar barras de desplazamiento durante animaciones */
            background-color: var(--color-primary-dark);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Fondo animado */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: linear-gradient(135deg, var(--color-secondary-dark) 0%, var(--color-primary-dark) 100%);
            overflow: hidden;
        }
        .animated-bg::before, .animated-bg::after,
        .animated-bg span:nth-child(1), .animated-bg span:nth-child(2), .animated-bg span:nth-child(3), .animated-bg span:nth-child(4) {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: move-bubbles 15s infinite ease-in-out;
            will-change: transform, opacity;
        }
        .animated-bg::before {
            width: 150px; height: 150px; top: 10%; left: 50%; opacity: 0.5; animation-delay: -2s;
        }
        .animated-bg::after {
            width: 100px; height: 100px; top: 60%; left: 20%; opacity: 0.3; animation-delay: -5s;
        }
        .animated-bg span:nth-child(1) { width: 80px; height: 80px; top: 30%; right: 10%; opacity: 0.4; animation-delay: -7s; }
        .animated-bg span:nth-child(2) { width: 120px; height: 120px; bottom: 5%; left: 40%; opacity: 0.6; animation-delay: -3s; }
        .animated-bg span:nth-child(3) { width: 70px; height: 70px; top: 20%; left: 15%; opacity: 0.2; animation-delay: -9s; }
        .animated-bg span:nth-child(4) { width: 90px; height: 90px; bottom: 20%; right: 30%; opacity: 0.5; animation-delay: -1s; }

        @keyframes move-bubbles {
            0%, 100% { transform: translateY(0) translateX(0) scale(1); opacity: 0.5; }
            25% { transform: translateY(-30px) translateX(20px) scale(1.05); opacity: 0.7; }
            50% { transform: translateY(-60px) translateX(-20px) scale(1.1); opacity: 0.8; }
            75% { transform: translateY(-30px) translateX(20px) scale(1.05); opacity: 0.7; }
        }

        /* Contenedor de Login */
        .login-section {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            perspective: 1000px; /* Para la animación 3D */
        }

        .login-container {
            max-width: 400px;
            padding: 40px;
            background-color: var(--color-background-soft);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            opacity: 0; /* Inicialmente oculto para la animación */
            transform: rotateY(90deg) scale(0.8); /* Posición inicial para el efecto 3D */
            animation: cardReveal 1.2s ease-out forwards;
            animation-delay: 0.5s; /* Retrasar un poco para que el fondo cargue */
        }
        
        @keyframes cardReveal {
            0% { opacity: 0; transform: rotateY(90deg) scale(0.8); }
            70% { opacity: 1; transform: rotateY(-10deg) scale(1.05); }
            100% { opacity: 1; transform: rotateY(0deg) scale(1); }
        }

        .login-container .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 32px; /* Aumentar el tamaño del texto "ECLIPSE" */
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 25px; /* Más espacio debajo del logo */
            opacity: 0;
            transform: translateY(-20px) scale(0.8);
            animation: logoEnter 1s ease-out forwards;
            animation-delay: 0.2s; /* Logo aparece antes del contenedor */
            color: var(--color-text-light); /* Asegurar color del texto del logo */
        }

        .login-container .logo img {
            height: 70px; /* ¡Aumentar el tamaño de la imagen del logo aquí! */
            filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.6)); /* Sombra para resaltar aún más */
            animation: logoPulse 2s ease-in-out infinite 2s; /* Pulse después de la entrada */
        }
        
        @keyframes logoEnter {
            0% { opacity: 0; transform: translateY(-50px) scale(0.5); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes logoPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); } /* Ligeramente más grande en el pulso */
            100% { transform: scale(1); }
        }

        .login-container h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem; /* Un poco más grande */
            margin-bottom: 30px; /* Más espacio */
            color: var(--color-text-light);
            text-shadow: 2px 2px 5px rgba(0,0,0,0.3);
            animation: textFadeIn 1s ease-out forwards;
            animation-delay: 1.5s;
        }
        
        @keyframes textFadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .login-input-group {
            position: relative;
        }

        .login-input-group i {
            position: absolute;
            left: 18px; /* Un poco más a la derecha */
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-accent-gold); /* Icono dorado */
            font-size: 1.2rem;
            pointer-events: none; /* Para que no interfiera con el input */
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 16px 18px 16px 50px; /* Ajuste de padding para el icono */
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.08); /* Fondo un poco más claro */
            color: white;
            border-radius: 10px; /* Bordes más suaves */
            font-size: 1.1rem;
            transition: border-color 0.3s ease, background 0.3s ease;
        }

        .login-container input:focus {
            outline: none;
            border-color: var(--color-accent-gold);
            background: rgba(255, 255, 255, 0.15); /* Cambiar fondo al enfocar */
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.3); /* Resplandor al enfocar */
        }

        .login-btn {
            background: linear-gradient(90deg, #d4af37, #f4e68c); /* Degradado horizontal */
            color: var(--color-primary-dark);
            padding: 18px 45px; /* Más grande */
            border: none;
            border-radius: 10px;
            font-size: 1.2rem; /* Texto más grande */
            font-weight: 700; /* Más audaz */
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            position: relative; /* Para el efecto de brillo */
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .login-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
            background: linear-gradient(90deg, #f4e68c, #d4af37); /* Invertir degradado */
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-links {
            margin-top: 25px;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .login-links a {
            color: var(--color-accent-gold);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-links a:hover {
            color: #fff; /* Blanco al pasar el ratón */
            text-decoration: underline;
        }

        .error-message {
            background: rgba(255, 77, 77, 0.2);
            border: 1px solid var(--color-error);
            color: var(--color-error);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 500px) {
            .login-container {
                margin: 20px;
                padding: 30px 20px;
            }
            .login-container .logo {
                font-size: 28px; /* Ajuste para pantallas pequeñas */
            }
            .login-container .logo img {
                height: 60px; /* Ajuste del tamaño del logo para pantallas pequeñas */
            }
            .login-container h2 {
                font-size: 2.2rem;
            }
            .login-container input[type="text"],
            .login-container input[type="password"] {
                padding: 14px 15px 14px 45px;
                font-size: 1rem;
            }
            .login-btn {
                padding: 16px 30px;
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="animated-bg">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    
    <main>
        <section class="login-section">
            <div class="login-container">
                <div class="logo">
                    <img src="assets/img/logo.png" alt="Logo de Eclipse" />
                    ECLIPSE
                </div>
                <h2>Inicio de Sesión</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="login-input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Usuario" value="<?= htmlspecialchars($username) ?>" required autocomplete="username">
                    </div>
                    <div class="login-input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
                    </div>
                    <button type="submit" class="login-btn">Iniciar Sesión</button>
                </form>
                
                <div class="login-links">
                    <p>¿No tienes cuenta? <a href="register.php">Crear Cuenta</a></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>