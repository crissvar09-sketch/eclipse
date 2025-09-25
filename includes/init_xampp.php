<?php
/**
 * Archivo de inicialización para XAMPP
 * Rutas absolutas para evitar problemas
 */

// Verificar si ya se ha inicializado
if (defined('ECLIPSE_INITIALIZED')) {
    return;
}

define('ECLIPSE_INITIALIZED', true);

// Obtener la ruta base del proyecto
$base_path = dirname(__DIR__);

// Verificar que estamos en el directorio correcto
if (!file_exists($base_path . '/config/database.php')) {
    // Intentar rutas alternativas para XAMPP
    $possible_paths = [
        dirname(__DIR__),
        $_SERVER['DOCUMENT_ROOT'] . '/WEB Criqui',
        'C:/xampp/htdocs/WEB Criqui',
        getcwd()
    ];
    
    foreach ($possible_paths as $path) {
        if (file_exists($path . '/config/database.php')) {
            $base_path = $path;
            break;
        }
    }
}

// Incluir archivos con rutas absolutas
require_once $base_path . '/config/database.php';
require_once $base_path . '/classes/Database.php';
require_once $base_path . '/classes/User.php';
require_once $base_path . '/classes/Product.php';
require_once $base_path . '/classes/Cart.php';
require_once $base_path . '/classes/Order.php';
require_once $base_path . '/classes/Validator.php';
require_once $base_path . '/classes/SessionManager.php';

// Inicializar variables globales
$sessionManager = new SessionManager();
$userManager = new User();
$productManager = new Product();
$cartManager = new Cart();
$orderManager = new Order();
