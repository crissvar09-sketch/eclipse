<?php
require_once '../config.php';
requireLogin();

// Verificar si es administrador (puedes agregar esta lógica)
$user_id = getUserId();
$stmt = $pdo->prepare("SELECT rol FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Por ahora, permitir acceso a cualquier usuario logueado
// En producción, deberías verificar si es administrador

$error = '';
$success = '';

// Procesar formulario de nuevo usuario
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'create_user') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $rol = $_POST['rol'] ?? 'cliente';
    
    if (empty($username) || empty($email) || empty($password) || empty($nombre)) {
        $error = 'Todos los campos obligatorios deben ser completados';
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
                    INSERT INTO usuarios (username, email, password_hash, nombre, telefono, direccion, rol, activo, fecha_registro) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())
                ");
                $stmt->execute([
                    $username,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT),
                    $nombre,
                    $telefono ?: null,
                    $direccion ?: null,
                    $rol
                ]);
                
                $success = 'Usuario creado exitosamente';
            }
        } catch (Exception $e) {
            $error = 'Error al crear el usuario: ' . $e->getMessage();
        }
    }
}

// Obtener lista de usuarios
$stmt = $pdo->query("SELECT id, username, email, nombre, rol, activo, fecha_registro FROM usuarios ORDER BY fecha_registro DESC");
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Eclipse</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/validation.js"></script>
</head>
<body>
    <div class="animated-bg"></div>
    
    <header class="header">
        <div class="logo">
            <img src="https://i.imgur.com/uW6yFj1.png" alt="Logo de Eclipse" />
            ECLIPSE - Admin
        </div>
        <nav class="nav">
            <a href="../index.php">Inicio</a>
            <a href="users.php">Usuarios</a>
            <a href="../logout.php">Salir</a>
        </nav>
    </header>

    <main>
        <section class="menu-section">
            <div class="container">
                <h2 class="season-heading">Gestión de Usuarios</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <!-- Formulario para crear nuevo usuario -->
                <div class="admin-form-container">
                    <h3>Crear Nuevo Usuario</h3>
                    <form method="POST" id="createUserForm">
                        <input type="hidden" name="action" value="create_user">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="username">Nombre de Usuario *</label>
                                <input type="text" id="username" name="username" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre Completo *</label>
                                <input type="text" id="nombre" name="nombre" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" id="telefono" name="telefono">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion" name="direccion">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">Contraseña *</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="rol">Rol</label>
                                <select id="rol" name="rol">
                                    <option value="cliente">Cliente</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="confirm-btn">Crear Usuario</button>
                    </form>
                </div>
                
                <!-- Lista de usuarios existentes -->
                <div class="users-list">
                    <h3>Usuarios Registrados</h3>
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Nombre</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?= $usuario['id'] ?></td>
                                        <td><?= htmlspecialchars($usuario['username']) ?></td>
                                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                        <td>
                                            <span class="role-badge role-<?= $usuario['rol'] ?>">
                                                <?= ucfirst($usuario['rol']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?= $usuario['activo'] ? 'active' : 'inactive' ?>">
                                                <?= $usuario['activo'] ? 'Activo' : 'Inactivo' ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
