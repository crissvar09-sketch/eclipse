    -- Script de creación de base de datos para Eclipse Heladería
    -- Sigue principios de normalización y relaciones apropiadas

    CREATE DATABASE IF NOT EXISTS eclipse_heladeria;
    USE eclipse_heladeria;

    -- Tabla de usuarios
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        nombre VARCHAR(100) NOT NULL,
        telefono VARCHAR(20),
        direccion TEXT,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        activo BOOLEAN DEFAULT TRUE
    );

    -- Tabla de categorías de productos
    CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        descripcion TEXT,
        activa BOOLEAN DEFAULT TRUE
    );

    -- Tabla de productos
    CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(200) NOT NULL,
        descripcion TEXT,
        precio DECIMAL(10,2) NOT NULL,
        imagen_url VARCHAR(500),
        categoria_id INT,
        temporada ENUM('primavera', 'verano', 'otono', 'invierno') NOT NULL,
        stock INT DEFAULT 0,
        activo BOOLEAN DEFAULT TRUE,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    );

    -- Tabla de carrito de compras
    CREATE TABLE IF NOT EXISTS carrito (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        producto_id INT NOT NULL,
        cantidad INT NOT NULL DEFAULT 1,
        fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
        FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_product (usuario_id, producto_id)
    );

    -- Tabla de pedidos
    CREATE TABLE IF NOT EXISTS pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        numero_pedido VARCHAR(20) UNIQUE NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        estado ENUM('pendiente', 'confirmado', 'preparando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
        metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
        direccion_entrega TEXT NOT NULL,
        telefono_contacto VARCHAR(20) NOT NULL,
        notas TEXT,
        fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    );

    -- Tabla de detalles de pedidos
    CREATE TABLE IF NOT EXISTS detalle_pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pedido_id INT NOT NULL,
        producto_id INT NOT NULL,
        cantidad INT NOT NULL,
        precio_unitario DECIMAL(10,2) NOT NULL,
        subtotal DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
        FOREIGN KEY (producto_id) REFERENCES productos(id)
    );

    -- Insertar datos iniciales
    INSERT INTO categorias (nombre, descripcion) VALUES
    ('Bebidas', 'Cafés, tés y bebidas especiales'),
    ('Postres', 'Helados, pasteles y dulces'),
    ('Salados', 'Sándwiches y bocadillos');

    INSERT INTO productos (nombre, descripcion, precio, imagen_url, categoria_id, temporada) VALUES
    -- Primavera
    ('Smoothie Flores Silvestres', 'Refrescante batido de frutos rojos, lavanda y un toque de miel. Frescura natural.', 6.50, 'https://i.imgur.com/QkQk1pD.png', 1, 'primavera'),
    ('Macarons de Matcha y Rosas', 'Delicados macarons con crema de té verde matcha y un sutil aroma a rosas. Elegancia floral.', 4.00, 'https://i.imgur.com/R3Z5tJm.png', 2, 'primavera'),
    ('Tarta de Frutas del Bosque', 'Base crujiente, crema pastelera suave y una explosión de bayas frescas de temporada. Dulce armonía.', 7.00, 'https://i.imgur.com/gKj3E4s.png', 2, 'primavera'),

    -- Verano
    ('Café Latte con Diseño', 'Elaborado con granos de alta calidad y arte latte único. Tu momento de energía.', 3.50, 'https://i.imgur.com/A6j5d63.jpg', 1, 'verano'),
    ('Brownie de Chocolate Intenso', 'Intenso y húmedo brownie de chocolate oscuro. El complemento perfecto para tu bebida.', 2.50, 'https://i.imgur.com/o3b0JpB.jpg', 2, 'verano'),
    ('Sándwich Tostado Clásico', 'Crujiente pan con jamón y queso fundido. Ideal para un break o desayuno rápido.', 6.00, 'https://i.imgur.com/s6Z2C2b.jpg', 3, 'verano'),

    -- Otoño
    ('Pastel de Calabaza y Especias', 'Suave pastel de calabaza con canela, nuez moscada y un toque de jengibre. Confort en cada bocado.', 5.00, 'https://i.imgur.com/v8tTq1m.png', 2, 'otono'),
    ('Chai Latte Cremoso', 'Mezcla de té negro con especias aromáticas y leche espumosa. La calidez del otoño en tu taza.', 4.00, 'https://i.imgur.com/M5Z5Z5A.png', 1, 'otono'),
    ('Manzanas Asadas con Canela', 'Manzanas horneadas con mantequilla, azúcar morena y canela, un clásico reconfortante.', 4.50, 'https://i.imgur.com/7bQ7Y5D.png', 2, 'otono'),

    -- Invierno
    ('Helado Vainilla Bourbon', 'Cremoso helado de vainilla con auténtica vainilla Bourbon y un toque de caramelo.', 4.00, 'https://i.imgur.com/0FwX3aT.jpg', 2, 'invierno'),
    ('Ensalada Frutal de Invierno', 'Fresca selección de frutas de estación como kiwi, naranja y uvas. Ligera y deliciosa.', 5.50, 'https://i.imgur.com/8Qp40pB.jpg', 2, 'invierno'),
    ('Helado de Fresa Silvestre', 'Un sabor clásico con la dulzura natural de las fresas frescas. Un deleite para el paladar.', 4.00, 'https://i.imgur.com/u6zD3wW.jpg', 2, 'invierno');

    -- Usuario de prueba
    INSERT INTO usuarios (username, email, password_hash, nombre, telefono, direccion) VALUES
    ('admin', 'admin@eclipse.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', '3001234567', 'Av. Principal 123');
