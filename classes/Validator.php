<?php
/**
 * Clase para validaciones
 * Sigue el principio de responsabilidad única (SRP)
 */

if (!class_exists('Validator')) {
    class Validator {
    
    /**
     * Validar datos de login
     */
    public static function validateLogin(array $data): array {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'El nombre de usuario es obligatorio';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'La contraseña es obligatoria';
        }

        return $errors;
    }

    /**
     * Validar datos de pedido
     */
    public static function validateOrder(array $data): array {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio';
        }

        if (empty($data['direccion'])) {
            $errors['direccion'] = 'La dirección es obligatoria';
        }

        if (empty($data['telefono'])) {
            $errors['telefono'] = 'El teléfono es obligatorio';
        } elseif (!preg_match('/^[0-9+\-\s()]+$/', $data['telefono'])) {
            $errors['telefono'] = 'El teléfono contiene caracteres inválidos';
        }

        if (empty($data['metodo_pago'])) {
            $errors['metodo_pago'] = 'Debe seleccionar un método de pago';
        }

        return $errors;
    }

    /**
     * Validar email
     */
    public static function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validar teléfono
     */
    public static function validatePhone(string $phone): bool {
        return preg_match('/^[0-9+\-\s()]+$/', $phone);
    }

    /**
     * Sanitizar string
     */
    public static function sanitizeString(string $input): string {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validar cantidad
     */
    public static function validateQuantity(int $quantity): bool {
        return $quantity > 0 && $quantity <= 100;
    }
    }
}
