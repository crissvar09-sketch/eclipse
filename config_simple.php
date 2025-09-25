<?php
/**
 * Archivo de configuración simple
 */

// Verificar si ya se ha inicializado
if (defined('ECLIPSE_INITIALIZED')) {
    return;
}

define('ECLIPSE_INITIALIZED', true);

// Incluir configuración de base de datos
require_once __DIR__ . '/config/database.php';

// Inicializar conexión a la base de datos
try {
    $pdo = new PDO(
        DatabaseConfig::getDSN(),
        DatabaseConfig::getUsername(),
        DatabaseConfig::getPassword(),
        DatabaseConfig::getOptions()
    );
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Función para requerir login
 */
function requireLogin() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Función para verificar si el usuario está logueado
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Función para obtener el usuario actual
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'nombre' => $_SESSION['user_name'] ?? null
    ];
}

/**
 * Función para obtener el ID del usuario actual
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Función para cerrar sesión
 */
function logout() {
    session_unset();
    session_destroy();
}
