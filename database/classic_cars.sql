-- Création de la base de données
CREATE DATABASE IF NOT EXISTS classic_cars;
USE classic_cars;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    adresse TEXT,
    telephone VARCHAR(20),
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des voitures
CREATE TABLE voitures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(100) NOT NULL,
    modele VARCHAR(100) NOT NULL,
    annee INT NOT NULL,
    kilometrage INT,
    prix DECIMAL(10, 2) NOT NULL,
    description TEXT,
    etat VARCHAR(50),
    image_principale VARCHAR(255) NOT NULL,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des images de voitures
CREATE TABLE images_voiture (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voiture_id INT,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (voiture_id) REFERENCES voitures(id) ON DELETE CASCADE
);

-- Table des commandes
CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'en_attente',
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table des détails de commande
CREATE TABLE details_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT,
    voiture_id INT,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id),
    FOREIGN KEY (voiture_id) REFERENCES voitures(id)
);

-- Insertion de quelques voitures de démonstration
INSERT INTO voitures (marque, modele, annee, kilometrage, prix, description, etat, image_principale) VALUES
('Citroën', 'Traction Avant', 1934, 89000, 45000.00, 'Magnifique Citroën Traction Avant en excellent état', 'Excellent', 'assets/images/voitures/citroen-traction.jpg'),
('Porsche', '356', 1963, 125000, 150000.00, 'Porsche 356 restaurée, matching numbers', 'Très bon', 'assets/images/voitures/porsche-356.jpg'),
('Mercedes-Benz', '300 SL', 1955, 78000, 1200000.00, 'Légendaire Mercedes 300 SL Papillon', 'Collection', 'assets/images/voitures/mercedes-300sl.jpg'),
('Jaguar', 'Type E', 1961, 92000, 180000.00, 'Jaguar Type E Série 1 3.8L', 'Restaurée', 'assets/images/voitures/jaguar-type-e.jpg'),
('Ferrari', '250 GT', 1960, 45000, 2500000.00, 'Ferrari 250 GT Berlinetta, histoire complète', 'Exception', 'assets/images/voitures/ferrari-250gt.jpg'),
('Volkswagen', 'Coccinelle', 1957, 115000, 25000.00, 'VW Coccinelle, restauration complète', 'Bon', 'assets/images/voitures/vw-coccinelle.jpg');
