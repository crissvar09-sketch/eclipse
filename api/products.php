<?php
/**
 * API para manejo de productos
 * Endpoints RESTful para operaciones de productos
 */
require_once '../includes/init.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Obtener productos
            $season = $_GET['season'] ?? null;
            
            if ($season) {
                $products = $productManager->getBySeason($season);
            } else {
                $products = $productManager->getAll();
            }
            
            echo json_encode([
                'success' => true,
                'products' => $products
            ]);
            break;
            
        case 'POST':
            // Crear nuevo producto (requiere autenticación de admin)
            $sessionManager->requireAuth();
            
            $data = json_decode(file_get_contents('php://input'), true);
            $success = $productManager->create($data);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Product created successfully']);
            } else {
                throw new Exception('Failed to create product');
            }
            break;
            
        case 'PUT':
            // Actualizar producto (requiere autenticación de admin)
            $sessionManager->requireAuth();
            
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['id'] ?? null;
            
            if (!$productId) {
                throw new Exception('Product ID is required');
            }
            
            unset($data['id']);
            $success = $productManager->update($productId, $data);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
            } else {
                throw new Exception('Failed to update product');
            }
            break;
            
        case 'DELETE':
            // Eliminar producto (requiere autenticación de admin)
            $sessionManager->requireAuth();
            
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['id'] ?? null;
            
            if (!$productId) {
                throw new Exception('Product ID is required');
            }
            
            $success = $productManager->delete($productId);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
            } else {
                throw new Exception('Failed to delete product');
            }
            break;
            
        default:
            throw new Exception('Method not allowed');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
