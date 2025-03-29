<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: catalogue.php');
    exit;
}

$voiture = get_car_details($_GET['id']);
if (!$voiture) {
    header('Location: catalogue.php');
    exit;
}

$page_title = $voiture['marque'] . ' ' . $voiture['modele'] . ' - Classic Cars';
include 'includes/header.php';

// Récupération des images supplémentaires
$images = get_car_images($_GET['id']);
?>

<main class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <!-- Galerie d'images principale -->
            <div id="carouselVoiture" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?php echo htmlspecialchars($voiture['image_principale']); ?>" 
                             class="d-block w-100" 
                             alt="<?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']); ?>">
                    </div>
                    <?php foreach ($images as $image): ?>
                        <div class="carousel-item">
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                                 class="d-block w-100" 
                                 alt="<?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($images) > 0): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselVoiture" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselVoiture" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informations de la voiture -->
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"><?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']); ?></h1>
                    <p class="h2 text-primary mb-4"><?php echo format_price($voiture['prix']); ?></p>
                    
                    <h5 class="mb-3">Caractéristiques :</h5>
                    <ul class="list-unstyled">
                        <li><strong>Année :</strong> <?php echo htmlspecialchars($voiture['annee']); ?></li>
                        <li><strong>Kilométrage :</strong> <?php echo number_format($voiture['kilometrage'], 0, ',', ' '); ?> km</li>
                        <li><strong>État :</strong> <?php echo htmlspecialchars($voiture['etat']); ?></li>
                    </ul>

                    <form action="panier.php" method="post" class="mt-4">
                        <input type="hidden" name="voiture_id" value="<?php echo $voiture['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <button type="submit" name="action" value="add" class="btn btn-primary btn-lg w-100">
                            Ajouter au panier
                        </button>
                    </form>

                    <div class="mt-4">
                        <h5>Contact rapide :</h5>
                        <p>Pour plus d'informations sur ce véhicule :</p>
                        <a href="tel:+33123456789" class="btn btn-outline-primary mb-2 w-100">
                            <i class="bi bi-telephone"></i> 01 23 45 67 89
                        </a>
                        <a href="mailto:contact@classic-cars.fr" class="btn btn-outline-primary w-100">
                            <i class="bi bi-envelope"></i> Envoyer un email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description détaillée -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Description détaillée</h3>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($voiture['description'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
