<?php
/**
 * API para agregar productos al carrito
 */
require_once '../config.php';

header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

try {
    // Obtener datos del POST
    $productId = $_POST['product_id'] ?? null;
    $quantity = intval($_POST['quantity'] ?? 1);
    $userId = getUserId();
    
    if (!$productId) {
        throw new Exception('ID del producto requerido');
    }
    
    if ($quantity < 1) {
        throw new Exception('La cantidad debe ser mayor a 0');
    }
    
    // Verificar que el producto existe y está activo
    $stmt = $pdo->prepare("SELECT id, nombre, precio FROM productos WHERE id = ? AND activo = 1");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        throw new Exception('Producto no encontrado o no disponible');
    }
    
    // Verificar si el producto ya está en el carrito
    $stmt = $pdo->prepare("SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ?");
    $stmt->execute([$userId, $productId]);
    $existingItem = $stmt->fetch();
    
    if ($existingItem) {
        // Actualizar cantidad existente
        $newQuantity = $existingItem['cantidad'] + $quantity;
        $stmt = $pdo->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Agregar nuevo item al carrito
        $stmt = $pdo->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad, fecha_agregado) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$userId, $productId, $quantity]);
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Producto agregado al carrito',
        'product_name' => $product['nombre']
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
