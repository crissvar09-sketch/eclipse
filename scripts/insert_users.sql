-- Script para insertar usuarios de ejemplo en la base de datos
-- Ejecutar este script en phpMyAdmin o desde la línea de comandos

-- Insertar usuarios de ejemplo
INSERT INTO usuarios (username, email, password_hash, nombre, telefono, direccion, rol, activo, fecha_registro) VALUES
('admin', 'admin@eclipse.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', '1234567890', 'Dirección Admin', 'admin', 1, NOW()),
('cliente1', 'cliente1@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Pérez', '0987654321', 'Av. Principal 123', 'cliente', 1, NOW()),
('cliente2', 'cliente2@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María García', '0555444333', 'Calle Secundaria 456', 'cliente', 1, NOW()),
('cliente3', 'cliente3@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos López', '0777888999', 'Plaza Central 789', 'cliente', 1, NOW());

-- Nota: Las contraseñas hash corresponden a la contraseña "password"
-- Para generar nuevos hashes, puedes usar: password_hash('tu_contraseña', PASSWORD_DEFAULT)
