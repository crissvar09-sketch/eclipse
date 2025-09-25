<?php
/**
 * Versión simplificada de init.php
 * Para evitar problemas de rutas
 */

// Verificar si ya se ha inicializado
if (defined('ECLIPSE_INITIALIZED')) {
    return;
}

define('ECLIPSE_INITIALIZED', true);

// Incluir archivos uno por uno con rutas absolutas
$root = dirname(__DIR__);

// Configuración
require_once $root . '/config/database.php';

// Clases
require_once $root . '/classes/Database.php';
require_once $root . '/classes/User.php';
require_once $root . '/classes/Product.php';
require_once $root . '/classes/Cart.php';
require_once $root . '/classes/Order.php';
require_once $root . '/classes/SessionManager.php';
require_once $root . '/classes/Validator.php';

// Inicializar
$sessionManager = new SessionManager();
$userManager = new User();
$productManager = new Product();
$cartManager = new Cart();
$orderManager = new Order();
