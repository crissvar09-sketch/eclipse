<?php
require_once 'config.php';

// Si ya está logueado, ir al inicio
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    
    // Validaciones
    if (empty($username) || empty($email) || empty($password) || empty($nombre)) {
        $error = 'Todos los campos obligatorios deben ser completados';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido';
    } else {
        try {
            // Verificar si el usuario ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = 'El nombre de usuario o email ya existe';
            } else {
                // Crear nuevo usuario
                $stmt = $pdo->prepare("
                    INSERT INTO usuarios (username, email, password_hash, nombre, telefono, direccion, activo, fecha_registro) 
                    VALUES (?, ?, ?, ?, ?, ?, 1, NOW())
                ");
                $stmt->execute([
                    $username,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT),
                    $nombre,
                    $telefono ?: null,
                    $direccion ?: null
                ]);
                
                $success = 'Usuario registrado exitosamente. Ya puedes iniciar sesión.';
            }
        } catch (Exception $e) {
            $error = 'Error al crear el usuario: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Eclipse</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/validation.js"></script>
</head>
<body>
    <div class="animated-bg"></div>
    
    <main>
        <section class="login-section">
            <div class="login-container">
                <div class="logo">
                    <img src="https://i.imgur.com/uW6yFj1.png" alt="Logo de Eclipse" />
                </div>
                <h2>Crear Cuenta</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <form method="POST" id="registerForm">
                    <div class="form-group">
                        <label for="username">Nombre de Usuario *</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre Completo *</label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Contraseña *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="login-btn">Crear Cuenta</button>
                </form>
                
                <div class="login-links">
                    <p>¿Ya tienes cuenta? <a href="login.php">Iniciar Sesión</a></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
