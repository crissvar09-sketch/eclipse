<?php
/**
 * Manejador de errores personalizado
 */

// Configurar el manejador de errores
set_error_handler(function($severity, $message, $file, $line) {
    // Log del error
    error_log("Error: $message in $file on line $line");
    
    // Redirigir a página de error 500
    if (!headers_sent()) {
        header('Location: /error_500.php');
        exit();
    }
});

// Configurar el manejador de excepciones
set_exception_handler(function($exception) {
    // Log de la excepción
    error_log("Uncaught exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    
    // Redirigir a página de error 500
    if (!headers_sent()) {
        header('Location: /error_500.php');
        exit();
    }
});

// Configurar para mostrar errores solo en desarrollo
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}