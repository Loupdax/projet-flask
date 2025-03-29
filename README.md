# Classic Cars - Site de vente de voitures anciennes

Ce projet est un site web de vente de voitures anciennes développé avec PHP et MySQL.

## Prérequis

- MAMP (Apache, MySQL, PHP)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Navigateur web moderne

## Installation

1. Clonez ce dépôt dans le dossier htdocs de MAMP :
```bash
cd /chemin/vers/mamp/htdocs
git clone [url-du-repo] site_marchant
```

2. Importez la base de données :
- Ouvrez phpMyAdmin (http://localhost/phpmyadmin)
- Créez une nouvelle base de données nommée "classic_cars"
- Importez le fichier `database/classic_cars.sql`

3. Configurez la connexion à la base de données :
- Ouvrez le fichier `includes/config.php`
- Vérifiez que les paramètres de connexion correspondent à votre configuration

4. Démarrez MAMP et accédez au site :
http://localhost/site_marchant

## Structure du projet

```
site_marchant/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── includes/
│   ├── config.php
│   ├── functions.php
│   ├── header.php
│   └── footer.php
├── database/
│   └── classic_cars.sql
├── index.php
├── catalogue.php
├── detail_voiture.php
├── panier.php
├── login.php
├── register.php
├── contact.php
└── README.md
```

## Fonctionnalités

- Catalogue de voitures avec filtres
- Système de panier
- Inscription et connexion des utilisateurs
- Détails des voitures avec galerie d'images
- Formulaire de contact
- Interface responsive
- Sécurité (CSRF, XSS, injection SQL)

## Sécurité

- Protection contre les injections SQL avec des requêtes préparées
- Protection CSRF sur tous les formulaires
- Validation et nettoyage des entrées utilisateur
- Hachage des mots de passe avec password_hash()
- Protection XSS avec htmlspecialchars()

## Auteur

[Votre nom]

## Licence

Ce projet est sous licence MIT.
