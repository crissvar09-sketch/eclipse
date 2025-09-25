<?php
/**
 * Archivo de inicialización
 * Incluye todas las dependencias necesarias
 */

// Verificar si ya se ha inicializado
if (defined('ECLIPSE_INITIALIZED')) {
    return;
}

define('ECLIPSE_INITIALIZED', true);

// Incluir configuración de base de datos
require_once __DIR__ . '/../config/database.php';

// Incluir clases
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Cart.php';
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/../classes/SessionManager.php';
require_once __DIR__ . '/../classes/Validator.php';

// Inicializar gestor de sesiones
$sessionManager = new SessionManager();

// Inicializar clases principales
$userManager = new User();
$productManager = new Product();
$cartManager = new Cart();
$orderManager = new Order();