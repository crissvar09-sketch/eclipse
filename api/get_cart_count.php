<?php
/**
 * API para obtener el contador de items en el carrito
 */
require_once '../config.php';

header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'count' => 0]);
    exit();
}

try {
    $userId = getUserId();
    
    // Obtener cantidad total de items en el carrito
    $stmt = $pdo->prepare("SELECT SUM(cantidad) as total FROM carrito WHERE usuario_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    
    $count = intval($result['total'] ?? 0);
    
    echo json_encode([
        'success' => true,
        'count' => $count
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'count' => 0,
        'message' => $e->getMessage()
    ]);
}
