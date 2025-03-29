-- Création de la base de données
CREATE DATABASE IF NOT EXISTS classic_cars DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
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
    role ENUM('user', 'admin') DEFAULT 'user',
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    derniere_connexion DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des marques
CREATE TABLE marques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    pays_origine VARCHAR(100),
    description TEXT,
    logo_url VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des voitures
CREATE TABLE voitures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque_id INT,
    modele VARCHAR(100) NOT NULL,
    annee INT NOT NULL,
    kilometrage INT,
    prix DECIMAL(10, 2) NOT NULL,
    description TEXT,
    etat ENUM('Excellent', 'Très bon', 'Bon', 'Moyen', 'À restaurer') NOT NULL,
    carburant ENUM('Essence', 'Diesel', 'Électrique', 'Hybride') DEFAULT 'Essence',
    transmission ENUM('Manuelle', 'Automatique') DEFAULT 'Manuelle',
    puissance INT,
    cylindree INT,
    nombre_places INT,
    couleur VARCHAR(50),
    disponible BOOLEAN DEFAULT TRUE,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    derniere_modification DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (marque_id) REFERENCES marques(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des images de voitures
CREATE TABLE images_voiture (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voiture_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_principale BOOLEAN DEFAULT FALSE,
    ordre INT DEFAULT 0,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voiture_id) REFERENCES voitures(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des commandes
CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reference VARCHAR(50) UNIQUE NOT NULL,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'confirmee', 'payee', 'expediee', 'livree', 'annulee') DEFAULT 'en_attente',
    total DECIMAL(10, 2) NOT NULL,
    mode_paiement VARCHAR(50),
    date_paiement DATETIME,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des détails de commande
CREATE TABLE details_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    voiture_id INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (voiture_id) REFERENCES voitures(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des favoris
CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    voiture_id INT NOT NULL,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (voiture_id) REFERENCES voitures(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favori (user_id, voiture_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des messages de contact
CREATE TABLE messages_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des données de démonstration

-- Insertion d'un administrateur
INSERT INTO users (email, password, nom, prenom, role) VALUES
('admin@classic-cars.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'System', 'admin');

-- Insertion des marques
INSERT INTO marques (nom, pays_origine, description) VALUES
('Citroën', 'France', 'Constructeur automobile français fondé en 1919'),
('Porsche', 'Allemagne', 'Constructeur automobile de sport allemand fondé en 1931'),
('Mercedes-Benz', 'Allemagne', 'Constructeur automobile allemand fondé en 1926'),
('Ferrari', 'Italie', 'Constructeur automobile italien de voitures de sport de luxe'),
('Jaguar', 'Royaume-Uni', 'Constructeur automobile britannique de voitures de luxe'),
('Volkswagen', 'Allemagne', 'Constructeur automobile allemand fondé en 1937');

-- Insertion des voitures
INSERT INTO voitures (marque_id, modele, annee, kilometrage, prix, description, etat, puissance, cylindree) VALUES
(1, 'Traction Avant', 1934, 89000, 45000.00, 'Magnifique Citroën Traction Avant en excellent état. Restauration complète effectuée en 2019. Peinture et intérieur d''origine.', 'Excellent', 75, 1911),
(2, '356', 1963, 125000, 150000.00, 'Porsche 356 restaurée, matching numbers. Certificat d''authenticité Porsche inclus. Parfait état de fonctionnement.', 'Très bon', 90, 1600),
(3, '300 SL', 1955, 78000, 1200000.00, 'Légendaire Mercedes 300 SL Papillon. Histoire complète documentée. Un des plus beaux exemplaires existants.', 'Excellent', 215, 2996),
(4, '250 GT', 1960, 45000, 2500000.00, 'Ferrari 250 GT Berlinetta, histoire complète. Restauration par les meilleurs spécialistes italiens.', 'Excellent', 240, 2953),
(5, 'Type E', 1961, 92000, 180000.00, 'Jaguar Type E Série 1 3.8L. Matching numbers, restauration complète documentée.', 'Très bon', 265, 3781),
(6, 'Coccinelle', 1957, 115000, 25000.00, 'VW Coccinelle, restauration complète. Excellent état général, prête à rouler.', 'Bon', 30, 1192);

-- Insertion des images (chemins à adapter selon votre structure)
INSERT INTO images_voiture (voiture_id, image_url, is_principale) VALUES
(1, 'assets/images/voitures/citroen-traction-1.jpg', TRUE),
(1, 'assets/images/voitures/citroen-traction-2.jpg', FALSE),
(2, 'assets/images/voitures/porsche-356-1.jpg', TRUE),
(2, 'assets/images/voitures/porsche-356-2.jpg', FALSE),
(3, 'assets/images/voitures/mercedes-300sl-1.jpg', TRUE),
(3, 'assets/images/voitures/mercedes-300sl-2.jpg', FALSE),
(4, 'assets/images/voitures/ferrari-250gt-1.jpg', TRUE),
(4, 'assets/images/voitures/ferrari-250gt-2.jpg', FALSE),
(5, 'assets/images/voitures/jaguar-type-e-1.jpg', TRUE),
(5, 'assets/images/voitures/jaguar-type-e-2.jpg', FALSE),
(6, 'assets/images/voitures/vw-coccinelle-1.jpg', TRUE),
(6, 'assets/images/voitures/vw-coccinelle-2.jpg', FALSE);

-- Création des index pour optimiser les performances
CREATE INDEX idx_voitures_marque ON voitures(marque_id);
CREATE INDEX idx_voitures_annee ON voitures(annee);
CREATE INDEX idx_voitures_prix ON voitures(prix);
CREATE INDEX idx_images_voiture ON images_voiture(voiture_id);
CREATE INDEX idx_commandes_user ON commandes(user_id);
CREATE INDEX idx_favoris_user ON favoris(user_id);
CREATE INDEX idx_favoris_voiture ON favoris(voiture_id);
