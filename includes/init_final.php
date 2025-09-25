<?php
/**
 * Archivo de inicialización final
 * Sin lógica compleja que cause problemas
 */

// Verificar si ya se inicializó
if (defined('ECLIPSE_INITIALIZED')) {
    return;
}

define('ECLIPSE_INITIALIZED', true);

// Rutas
$root = dirname(__DIR__);

// Incluir archivos en orden específico
require_once $root . '/config/database.php';
require_once $root . '/classes/Database.php';
require_once $root . '/classes/User.php';
require_once $root . '/classes/Product.php';
require_once $root . '/classes/Cart.php';
require_once $root . '/classes/Order.php';
require_once $root . '/classes/Validator.php';
require_once $root . '/classes/SessionManager.php';

// Inicializar
$sessionManager = new SessionManager();
$userManager = new User();
$productManager = new Product();
$cartManager = new Cart();
$orderManager = new Order();
