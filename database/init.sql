-- Habilitar extensión para UUIDs
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ============================================
-- SECUENCIAS
-- ============================================
DROP SEQUENCE IF EXISTS productos_id_seq;
DROP SEQUENCE IF EXISTS usuarios_id_seq;

CREATE SEQUENCE productos_id_seq START 1;
CREATE SEQUENCE usuarios_id_seq START 1;

-- ============================================
-- BORRADO PREVIO DE TABLAS
-- ============================================
DROP TABLE IF EXISTS user_roles;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;

-- ============================================
-- TABLA USUARIOS
-- ============================================
CREATE TABLE usuarios (
    id BIGINT PRIMARY KEY DEFAULT nextval('usuarios_id_seq'),
    nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- ============================================
-- TABLA USER_ROLES
-- ============================================
CREATE TABLE user_roles (
    user_id BIGINT REFERENCES usuarios(id),
    roles VARCHAR(255) NOT NULL
);

-- ============================================
-- TABLA CATEGORÍAS
-- ============================================
CREATE TABLE categorias (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nombre VARCHAR(255) UNIQUE NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- ============================================
-- TABLA PRODUCTOS
-- ============================================
CREATE TABLE productos (
    id BIGINT PRIMARY KEY DEFAULT nextval('productos_id_seq'),
    uuid UUID UNIQUE NOT NULL DEFAULT gen_random_uuid(),
    marca VARCHAR(255),
    modelo VARCHAR(255),
    descripcion VARCHAR(255),
    precio DOUBLE PRECISION DEFAULT 0.0,
    stock INTEGER DEFAULT 0,
    imagen TEXT DEFAULT 'https://via.placeholder.com/150',
    categoria_id UUID REFERENCES categorias(id),
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- ============================================
-- INSERTAR USUARIOS
-- ============================================
INSERT INTO usuarios (nombre, apellidos, username, email, password) VALUES
('Admin', 'Admin', 'admin', 'admin@sesanus.com', '$2y$10$tFUnZtp9v0/iE63QxxSR2.IlQWEvb28TK/ABexItd8pYW5q7auT9W'),
('Usuario', 'Normal', 'user', 'user@sesanus.com', '$2y$10$OXhpy5MhRagIabGHzaWucOSNqCte/ZaDTjosJYuGfvbT3zAVFm/s6');

-- ============================================
-- INSERTAR ROLES
-- ============================================
INSERT INTO user_roles (user_id, roles) VALUES
(1, 'ADMIN'),
(1, 'USER'),
(2, 'USER');

-- ============================================
-- INSERTAR CATEGORÍAS
-- ============================================
INSERT INTO categorias (id, nombre) VALUES
('11111111-1111-1111-1111-111111111111', 'Salud'),
('22222222-2222-2222-2222-222222222222', 'Deporte');

-- ============================================
-- INSERTAR PRODUCTOS
-- ============================================
INSERT INTO productos (uuid, marca, modelo, descripcion, precio, stock, categoria_id, imagen) VALUES
(gen_random_uuid(), 'B-Levels', 'Triple Magnesium', 'Suplemento con tres tipos de magnesio que ayudan al equilibrio muscular, nervioso y energético.', 14.99, 30, '11111111-1111-1111-1111-111111111111', 'magnesium-thumbnail.webp'),
(gen_random_uuid(), 'B-Levels', 'Omega 3', 'Cápsulas con ácidos grasos Omega 3 (EPA y DHA) para cuidar el corazón y la concentración.', 17.90, 40, '11111111-1111-1111-1111-111111111111', 'omega3-thumbnail.webp'),
(gen_random_uuid(), 'B-Levels', 'Creatine', 'Creatina con enzimas digestivas para mejorar la fuerza, el rendimiento y la recuperación.', 19.50, 50, '22222222-2222-2222-2222-222222222222', 'PORTADA-creatine.webp'),
(gen_random_uuid(), 'B-Levels', 'Isolated Protein', 'Proteína aislada de suero sabor chocolate negro, 25 dosis de alta pureza.', 29.90, 25, '22222222-2222-2222-2222-222222222222', 'PORTADA-whey-isolated-protein-dark-choco.webp');