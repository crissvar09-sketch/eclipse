<?php
/**
 * Clase para manejo de usuarios
 * Sigue el principio de responsabilidad única (SRP)
 */

if (!class_exists('User')) {
    class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Autenticar usuario
     */
    public function authenticate(string $username, string $password): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = ? AND activo = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }

    /**
     * Obtener usuario por ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Crear nuevo usuario
     */
    public function create(array $userData): bool {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO usuarios (username, email, password_hash, nombre, telefono, direccion) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $userData['username'],
                $userData['email'],
                password_hash($userData['password'], PASSWORD_DEFAULT),
                $userData['nombre'],
                $userData['telefono'] ?? null,
                $userData['direccion'] ?? null
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualizar información del usuario
     */
    public function update(int $id, array $userData): bool {
        try {
            $fields = [];
            $values = [];
            
            foreach ($userData as $key => $value) {
                if ($key !== 'id') {
                    $fields[] = "$key = ?";
                    $values[] = $value;
                }
            }
            
            $values[] = $id;
            $stmt = $this->db->prepare("UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id = ?");
            return $stmt->execute($values);
        } catch (PDOException $e) {
            return false;
        }
    }
    }
}
