<?php
/**
 * Clase para manejo de productos
 * Sigue el principio de responsabilidad única (SRP)
 */

if (!class_exists('Product')) {
    class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los productos activos
     */
    public function getAll(): array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.activo = 1 
            ORDER BY p.temporada, p.nombre
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener productos por temporada
     */
    public function getBySeason(string $season): array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.activo = 1 AND p.temporada = ?
            ORDER BY p.nombre
        ");
        $stmt->execute([$season]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener producto por ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.id = ? AND p.activo = 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Crear nuevo producto
     */
    public function create(array $productData): bool {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO productos (nombre, descripcion, precio, imagen_url, categoria_id, temporada, stock) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $productData['nombre'],
                $productData['descripcion'],
                $productData['precio'],
                $productData['imagen_url'] ?? null,
                $productData['categoria_id'] ?? null,
                $productData['temporada'],
                $productData['stock'] ?? 0
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualizar producto
     */
    public function update(int $id, array $productData): bool {
        try {
            $fields = [];
            $values = [];
            
            foreach ($productData as $key => $value) {
                if ($key !== 'id') {
                    $fields[] = "$key = ?";
                    $values[] = $value;
                }
            }
            
            $values[] = $id;
            $stmt = $this->db->prepare("UPDATE productos SET " . implode(', ', $fields) . " WHERE id = ?");
            return $stmt->execute($values);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Eliminar producto (soft delete)
     */
    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    }
}
