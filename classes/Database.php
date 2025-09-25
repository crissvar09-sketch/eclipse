<?php
/**
 * Clase para manejo de conexión a base de datos
 * Sigue el principio de responsabilidad única (SRP)
 */

if (!class_exists('Database')) {
    class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new PDO(
                DatabaseConfig::getDSN(),
                DatabaseConfig::getUsername(),
                DatabaseConfig::getPassword(),
                DatabaseConfig::getOptions()
            );
        } catch (PDOException $e) {
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Patrón Singleton para asegurar una sola conexión
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    /**
     * Prevenir clonación
     */
    private function __clone() {}

    /**
     * Prevenir deserialización
     */
    public function __wakeup() {
        throw new Exception("No se puede deserializar una instancia singleton");
    }
    }
}
