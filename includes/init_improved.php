<?php
/**
 * Archivo de inicialización mejorado
 * Maneja mejor las rutas y evita inclusiones múltiples
 */

// Verificar si ya se ha inicializado
if (defined('ECLIPSE_INITIALIZED')) {
    return;
}

// Definir la constante para evitar múltiples inicializaciones
define('ECLIPSE_INITIALIZED', true);

// Obtener la ruta base del proyecto
$base_path = dirname(__DIR__);

// Función helper para incluir archivos de forma segura
function safe_require_once($file_path) {
    if (file_exists($file_path)) {
        require_once $file_path;
        return true;
    } else {
        error_log("Archivo no encontrado: $file_path");
        return false;
    }
}

// Incluir configuración de base de datos
safe_require_once($base_path . '/config/database.php');

// Incluir clases con verificación
$classes = [
    'Database',
    'User', 
    'Product',
    'Cart',
    'Order',
    'SessionManager',
    'Validator'
];

foreach ($classes as $class) {
    $file_path = $base_path . '/classes/' . $class . '.php';
    if (!class_exists($class)) {
        safe_require_once($file_path);
    }
}

// Inicializar gestor de sesiones solo si no existe
if (!isset($sessionManager)) {
    $sessionManager = new SessionManager();
}

// Inicializar clases principales solo si no existen
if (!isset($userManager)) {
    $userManager = new User();
}

if (!isset($productManager)) {
    $productManager = new Product();
}

if (!isset($cartManager)) {
    $cartManager = new Cart();
}

if (!isset($orderManager)) {
    $orderManager = new Order();
}
