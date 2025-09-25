<?php
/**
 * API para manejo del carrito
 * Endpoints RESTful para operaciones del carrito
 */
require_once '../includes/init.php';

header('Content-Type: application/json');

// Verificar autenticación
$sessionManager->requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$userId = $sessionManager->getUserId();

try {
    switch ($method) {
        case 'POST':
            // Agregar producto al carrito
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['product_id'] ?? null;
            $quantity = $data['quantity'] ?? 1;
            
            if (!$productId) {
                throw new Exception('Product ID is required');
            }
            
            $success = $cartManager->addItem($userId, $productId, $quantity);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Product added to cart']);
            } else {
                throw new Exception('Failed to add product to cart');
            }
            break;
            
        case 'PUT':
            // Actualizar cantidad
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['product_id'] ?? null;
            $quantity = $data['quantity'] ?? 1;
            
            if (!$productId) {
                throw new Exception('Product ID is required');
            }
            
            $success = $cartManager->updateQuantity($userId, $productId, $quantity);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Cart updated']);
            } else {
                throw new Exception('Failed to update cart');
            }
            break;
            
        case 'DELETE':
            // Eliminar producto del carrito
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['product_id'] ?? null;
            
            if (!$productId) {
                throw new Exception('Product ID is required');
            }
            
            $success = $cartManager->removeItem($userId, $productId);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
            } else {
                throw new Exception('Failed to remove product from cart');
            }
            break;
            
        case 'GET':
        default:
            // Obtener carrito del usuario
            $cartItems = $cartManager->getUserCart($userId);
            $cartTotal = $cartManager->getCartTotal($userId);
            
            echo json_encode([
                'success' => true,
                'cart' => $cartItems,
                'total' => $cartTotal
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
