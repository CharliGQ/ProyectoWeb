CREATE TABLE usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
  correo VARCHAR(100) NOT NULL UNIQUE,
  contrasenia VARCHAR(255) NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  rol ENUM('registrado','creador','main_owner','moderador') DEFAULT 'registrado'
);

CREATE TABLE categorias_videos (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion TEXT
);

CREATE TABLE etiquetas (
  id_etiqueta INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE productos (
  id_producto INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL REFERENCES usuarios(id_usuario),
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL CHECK (precio > 0),
  stock INT DEFAULT 0 CHECK (stock >= 0),
  imagen_url VARCHAR(255),
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE videos (
  id_video INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL REFERENCES usuarios(id_usuario),
  titulo VARCHAR(100) NOT NULL,
  descripcion TEXT,
  url_video VARCHAR(255) NOT NULL,
  plataforma ENUM('YouTube','TikTok','Instagram','Facebook','Twitch','Otro'),
  miniatura_url VARCHAR(255),
  visibilidad ENUM('publico','privado') DEFAULT 'publico',
  estado ENUM('activo','oculto','eliminado') DEFAULT 'activo',
  fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE direcciones_envio (
  id_direccion INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL REFERENCES usuarios(id_usuario),
  receptor_nombre VARCHAR(100),
  direccion TEXT,
  ciudad VARCHAR(100),
  estado VARCHAR(100),
  codigo_postal VARCHAR(10),
  pais VARCHAR(100) DEFAULT 'México',
  telefono VARCHAR(20)
);

CREATE TABLE carrito_compras (
  id_carrito INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL REFERENCES usuarios(id_usuario),
  estado ENUM('activo','comprado','cancelado') DEFAULT 'activo',
  total DECIMAL(10,2) DEFAULT 0.00,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE patrocinios (
  id_patrocinio INT AUTO_INCREMENT PRIMARY KEY,
  id_creador INT NOT NULL REFERENCES usuarios(id_usuario),
  nombre_empresa VARCHAR(100) NOT NULL,
  descripcion TEXT,
  logo_url VARCHAR(255),
  enlace VARCHAR(255),
  fecha_inicio DATE,
  fecha_fin DATE,
  estado ENUM('activo','finalizado') DEFAULT 'activo'
);

CREATE TABLE donaciones (
  id_donacion INT AUTO_INCREMENT PRIMARY KEY,
  id_donador INT NOT NULL REFERENCES usuarios(id_usuario),
  id_creador INT NOT NULL REFERENCES usuarios(id_usuario),
  monto DECIMAL(10,2) NOT NULL,
  metodo_pago ENUM('paypal','tarjeta','transferencia','otro') NOT NULL,
  mensaje VARCHAR(255),
  fecha_donacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comentarios_productos (
  id_comentario INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL REFERENCES usuarios(id_usuario),
  id_producto INT NOT NULL REFERENCES productos(id_producto),
  comentario TEXT NOT NULL,
  calificacion TINYINT CHECK (calificacion BETWEEN 1 AND 5),
  fecha_comentario DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comentarios_video (
  id_comentario INT AUTO_INCREMENT PRIMARY KEY,
  id_video INT NOT NULL REFERENCES videos(id_video),
  id_usuario INT NOT NULL REFERENCES usuarios(id_usuario),
  comentario TEXT NOT NULL,
  fecha_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('pendiente','aprobado','eliminado') DEFAULT 'pendiente',
  moderado_por INT REFERENCES usuarios(id_usuario)
);

CREATE TABLE carrito_detalle (
  id_detalle INT AUTO_INCREMENT PRIMARY KEY,
  id_carrito INT NOT NULL REFERENCES carrito_compras(id_carrito),
  id_producto INT NOT NULL REFERENCES productos(id_producto),
  cantidad INT NOT NULL CHECK (cantidad > 0),
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED
);

CREATE TABLE ventas (
  id_venta INT AUTO_INCREMENT PRIMARY KEY,
  id_carrito INT NOT NULL REFERENCES carrito_compras(id_carrito),
  total DECIMAL(10,2) NOT NULL,
  metodo_pago ENUM('tarjeta','paypal','oxxo','transferencia') NOT NULL,
  fecha_venta DATETIME DEFAULT CURRENT_TIMESTAMP,
  status ENUM('pagado','enviado','recibido','cancelado')
);

CREATE TABLE ventas_detalle (
  id_detalle INT AUTO_INCREMENT PRIMARY KEY,
  id_venta INT NOT NULL REFERENCES ventas(id_venta),
  id_producto INT NOT NULL REFERENCES productos(id_producto),
  cantidad INT NOT NULL CHECK (cantidad > 0),
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED
);

CREATE TABLE envios (
  id_envio INT AUTO_INCREMENT PRIMARY KEY,
  id_venta INT NOT NULL REFERENCES ventas(id_venta),
  id_direccion INT NOT NULL REFERENCES direcciones_envio(id_direccion),
  metodo_envio VARCHAR(50),
  fecha_envio DATETIME,
  fecha_entrega_estimada DATETIME,
  fecha_entrega_real DATETIME,
  estado ENUM('pendiente','enviado','en tránsito','entregado','cancelado'),
  guia_rastreo VARCHAR(100)
);

CREATE TABLE reportes_comentarios (
  id_reporte INT AUTO_INCREMENT PRIMARY KEY,
  id_comentario INT NOT NULL REFERENCES comentarios_video(id_comentario),
  id_reportante INT NOT NULL REFERENCES usuarios(id_usuario),
  motivo TEXT,
  fecha_reporte DATETIME DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('pendiente','revisado','descartado') DEFAULT 'pendiente'
);

CREATE TABLE video_etiquetas (
  id_video INT NOT NULL REFERENCES videos(id_video),
  id_etiqueta INT NOT NULL REFERENCES etiquetas(id_etiqueta),
  PRIMARY KEY (id_video, id_etiqueta)
);