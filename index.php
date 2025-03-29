<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Accueil - Classic Cars";
include 'includes/header.php';
?>

<main>
    <section class="jumbotron text-center">
        <div class="container">
            <h1 class="display-4">Bienvenue chez Classic Cars</h1>
            <p class="lead mb-4">Découvrez notre collection exclusive de voitures anciennes</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="catalogue.php" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-search me-2"></i>Explorer le catalogue
                </a>
                <a href="contact.php" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-envelope me-2"></i>Nous contacter
                </a>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Nos véhicules à la une</h2>
            <div class="row g-4">
                <?php
                $sql = "SELECT v.*, COUNT(f.voiture_id) as favoris 
                        FROM voitures v 
                        LEFT JOIN favoris f ON v.id = f.voiture_id 
                        GROUP BY v.id 
                        ORDER BY favoris DESC, date_ajout DESC 
                        LIMIT 6";
                $result = $conn->query($sql);

                while ($car = $result->fetch_assoc()) {
                    $prix_formatte = number_format($car['prix'], 2, ',', ' ');
                    echo <<<HTML
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-img-wrapper">
                                <img src="{$car['image_principale']}" class="card-img-top" 
                                     alt="{$car['marque']} {$car['modele']}"
                                     loading="lazy">
                                <div class="card-img-overlay d-flex align-items-end">
                                    <span class="badge bg-dark text-white mb-2">
                                        <i class="bi bi-calendar-event me-1"></i>{$car['annee']}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{$car['marque']} {$car['modele']}</h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-speedometer2 me-2"></i>{$car['kilometrage']} km
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 text-primary mb-0">{$prix_formatte} €</span>
                                    <a href="detail_voiture.php?id={$car['id']}" 
                                       class="btn btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    HTML;
                }
                ?>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-4">Pourquoi nous choisir ?</h2>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="bi bi-check-circle-fill text-primary h1"></i>
                        </div>
                        <div>
                            <h4>Expertise reconnue</h4>
                            <p>Plus de 20 ans d'expérience dans les voitures de collection</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="bi bi-shield-check text-primary h1"></i>
                        </div>
                        <div>
                            <h4>Garantie qualité</h4>
                            <p>Tous nos véhicules sont minutieusement inspectés</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="bi bi-gear-fill text-primary h1"></i>
                        </div>
                        <div>
                            <h4>Service après-vente</h4>
                            <p>Une équipe dédiée à votre satisfaction</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="assets/images/workshop.jpg" alt="Notre atelier" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
