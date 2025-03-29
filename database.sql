CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

-- Table des utilisateurs
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) UNIQUE NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    password_hash VARCHAR(128) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

-- Table des catégories
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL
);

-- Table des produits
CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description TEXT,
    price FLOAT NOT NULL,
    stock INT NOT NULL,
    image_url VARCHAR(200),
    category_id INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES category(id)
);

-- Table des commandes
CREATE TABLE `order` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date_ordered DATETIME NOT NULL,
    status VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
);

-- Table des articles de commande
CREATE TABLE order_item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price FLOAT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES `order`(id),
    FOREIGN KEY (product_id) REFERENCES product(id)
);

-- Insertion de données de test
INSERT INTO category (name) VALUES 
('Électronique'),
('Vêtements'),
('Livres');

INSERT INTO user (username, email, password_hash, is_admin) VALUES
('admin', 'admin@example.com', 'pbkdf2:sha256:600000$dummyhash$1234567890abcdef', TRUE);

INSERT INTO product (name, description, price, stock, category_id) VALUES
('Smartphone XYZ', 'Un smartphone puissant avec écran HD', 499.99, 10, 1),
('T-shirt Cool', 'T-shirt en coton 100% bio', 19.99, 50, 2),
('Python pour les Nuls', 'Apprenez Python facilement', 29.99, 20, 3);
