<?php
/**
 * Clase para manejo del carrito de compras
 * Sigue el principio de responsabilidad única (SRP)
 */

if (!class_exists('Cart')) {
    class Cart {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Agregar producto al carrito
     */
    public function addItem(int $userId, int $productId, int $quantity = 1): bool {
        try {
            // Verificar si el producto ya existe en el carrito
            $stmt = $this->db->prepare("SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ?");
            $stmt->execute([$userId, $productId]);
            $existingItem = $stmt->fetch();

            if ($existingItem) {
                // Actualizar cantidad
                $newQuantity = $existingItem['cantidad'] + $quantity;
                $stmt = $this->db->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
                return $stmt->execute([$newQuantity, $existingItem['id']]);
            } else {
                // Agregar nuevo item
                $stmt = $this->db->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
                return $stmt->execute([$userId, $productId, $quantity]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtener carrito del usuario
     */
    public function getUserCart(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT c.*, p.nombre, p.precio, p.imagen_url, p.descripcion
            FROM carrito c
            JOIN productos p ON c.producto_id = p.id
            WHERE c.usuario_id = ? AND p.activo = 1
            ORDER BY c.fecha_agregado DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Actualizar cantidad de un item
     */
    public function updateQuantity(int $userId, int $productId, int $quantity): bool {
        try {
            if ($quantity <= 0) {
                return $this->removeItem($userId, $productId);
            }
            
            $stmt = $this->db->prepare("UPDATE carrito SET cantidad = ? WHERE usuario_id = ? AND producto_id = ?");
            return $stmt->execute([$quantity, $userId, $productId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Eliminar item del carrito
     */
    public function removeItem(int $userId, int $productId): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM carrito WHERE usuario_id = ? AND producto_id = ?");
            return $stmt->execute([$userId, $productId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Limpiar carrito del usuario
     */
    public function clearCart(int $userId): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM carrito WHERE usuario_id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Calcular total del carrito
     */
    public function getCartTotal(int $userId): float {
        $stmt = $this->db->prepare("
            SELECT SUM(c.cantidad * p.precio) as total
            FROM carrito c
            JOIN productos p ON c.producto_id = p.id
            WHERE c.usuario_id = ? AND p.activo = 1
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return (float) ($result['total'] ?? 0);
    }
    }
}
