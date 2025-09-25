<?php
/**
 * Clase para manejo de pedidos
 * Sigue el principio de responsabilidad única (SRP)
 */

if (!class_exists('Order')) {
    class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crear nuevo pedido
     */
    public function create(array $orderData, array $cartItems): ?int {
        try {
            $this->db->beginTransaction();

            // Generar número de pedido único
            $orderNumber = $this->generateOrderNumber();
            
            // Crear el pedido
            $stmt = $this->db->prepare("
                INSERT INTO pedidos (usuario_id, numero_pedido, total, metodo_pago, direccion_entrega, telefono_contacto, notas) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $orderData['usuario_id'],
                $orderNumber,
                $orderData['total'],
                $orderData['metodo_pago'],
                $orderData['direccion_entrega'],
                $orderData['telefono_contacto'],
                $orderData['notas'] ?? null
            ]);

            $orderId = $this->db->lastInsertId();

            // Crear detalles del pedido
            foreach ($cartItems as $item) {
                $stmt = $this->db->prepare("
                    INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $subtotal = $item['cantidad'] * $item['precio'];
                $stmt->execute([
                    $orderId,
                    $item['producto_id'],
                    $item['cantidad'],
                    $item['precio'],
                    $subtotal
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return null;
        }
    }

    /**
     * Obtener pedido por ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Obtener pedidos del usuario
     */
    public function getUserOrders(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM pedidos 
            WHERE usuario_id = ? 
            ORDER BY fecha_pedido DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener detalles de un pedido
     */
    public function getOrderDetails(int $orderId): array {
        $stmt = $this->db->prepare("
            SELECT dp.*, pr.nombre as producto_nombre, pr.imagen_url
            FROM detalle_pedidos dp
            JOIN productos pr ON dp.producto_id = pr.id
            WHERE dp.pedido_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    /**
     * Actualizar estado del pedido
     */
    public function updateStatus(int $orderId, string $status): bool {
        try {
            $stmt = $this->db->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
            return $stmt->execute([$status, $orderId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Generar número de pedido único
     */
    private function generateOrderNumber(): string {
        $prefix = 'ECL';
        $timestamp = date('YmdHis');
        $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        return $prefix . $timestamp . $random;
    }
    }
}
