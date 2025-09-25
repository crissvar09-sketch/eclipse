<?php
/**
 * Configuración de la base de datos
 * Sigue el principio de responsabilidad única (SRP)
 */
class DatabaseConfig {
    private const HOST = 'localhost';
    private const DB_NAME = 'eclipse_heladeria';
    private const USERNAME = 'root';
    private const PASSWORD = '';
    private const CHARSET = 'utf8mb4';

    public static function getDSN(): string {
        return "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::CHARSET;
    }

    public static function getUsername(): string {
        return self::USERNAME;
    }

    public static function getPassword(): string {
        return self::PASSWORD;
    }

    public static function getOptions(): array {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }
}
