<?php
/**
 * Script para agregar usuarios desde línea de comandos
 * Uso: php scripts/add_user.php
 */

require_once '../config.php';

echo "=== Agregar Usuario a Eclipse ===\n\n";

// Obtener datos del usuario
$username = readline("Nombre de usuario: ");
$email = readline("Email: ");
$password = readline("Contraseña: ");
$nombre = readline("Nombre completo: ");
$telefono = readline("Teléfono (opcional): ");
$direccion = readline("Dirección (opcional): ");
$rol = readline("Rol (cliente/admin) [cliente]: ") ?: 'cliente';

// Validaciones básicas
if (empty($username) || empty($email) || empty($password) || empty($nombre)) {
    echo "Error: Todos los campos obligatorios deben ser completados\n";
    exit(1);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: El email no es válido\n";
    exit(1);
}

try {
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        echo "Error: El nombre de usuario o email ya existe\n";
        exit(1);
    }
    
    // Crear nuevo usuario
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (username, email, password_hash, nombre, telefono, direccion, rol, activo, fecha_registro) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())
    ");
    
    $result = $stmt->execute([
        $username,
        $email,
        password_hash($password, PASSWORD_DEFAULT),
        $nombre,
        $telefono ?: null,
        $direccion ?: null,
        $rol
    ]);
    
    if ($result) {
        echo "✅ Usuario creado exitosamente!\n";
        echo "Usuario: $username\n";
        echo "Email: $email\n";
        echo "Rol: $rol\n";
    } else {
        echo "❌ Error al crear el usuario\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
