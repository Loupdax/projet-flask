<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Accueil - Classic Cars";
include 'includes/header.php';
?>

<main class="container mt-4">
    <div class="jumbotron text-center">
        <h1>Bienvenue chez Classic Cars</h1>
        <p class="lead">Découvrez notre collection exclusive de voitures anciennes</p>
    </div>

    <div class="featured-cars">
        <h2 class="text-center mb-4">Nos véhicules à la une</h2>
        <div class="row">
            <?php
            $sql = "SELECT * FROM voitures ORDER BY date_ajout DESC LIMIT 6";
            $result = $conn->query($sql);

            while ($car = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="' . htmlspecialchars($car['image_principale']) . '" class="card-img-top" alt="' . htmlspecialchars($car['marque'] . ' ' . $car['modele']) . '">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($car['marque'] . ' ' . $car['modele']) . '</h5>
                            <p class="card-text">Année: ' . htmlspecialchars($car['annee']) . '</p>
                            <p class="card-text">Prix: ' . number_format($car['prix'], 2, ',', ' ') . ' €</p>
                            <a href="detail_voiture.php?id=' . $car['id'] . '" class="btn btn-primary">Voir détails</a>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
