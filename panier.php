<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Traitement des actions du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token($_POST['csrf_token'])) {
    if (isset($_POST['action']) && isset($_POST['voiture_id'])) {
        $voiture_id = (int)$_POST['voiture_id'];
        
        if ($_POST['action'] === 'add') {
            add_to_cart($voiture_id);
        } elseif ($_POST['action'] === 'remove') {
            remove_from_cart($voiture_id);
        }
    }
    
    // Redirection pour éviter la soumission multiple du formulaire
    header('Location: panier.php');
    exit;
}

$page_title = "Panier - Classic Cars";
include 'includes/header.php';

// Récupération des voitures du panier
$voitures_panier = array();
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    $ids = implode(',', array_map('intval', $_SESSION['panier']));
    $sql = "SELECT * FROM voitures WHERE id IN ($ids)";
    $result = $conn->query($sql);
    while ($voiture = $result->fetch_assoc()) {
        $voitures_panier[] = $voiture;
    }
}
?>

<main class="container mt-4">
    <h1 class="mb-4">Votre panier</h1>

    <?php if (empty($voitures_panier)): ?>
        <div class="alert alert-info">
            Votre panier est vide. <a href="catalogue.php">Découvrez notre catalogue</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($voitures_panier as $voiture): ?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?php echo htmlspecialchars($voiture['image_principale']); ?>" 
                                     class="img-fluid rounded-start" 
                                     alt="<?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']); ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']); ?>
                                    </h5>
                                    <p class="card-text">
                                        Année: <?php echo htmlspecialchars($voiture['annee']); ?><br>
                                        Prix: <?php echo format_price($voiture['prix']); ?>
                                    </p>
                                    <form action="panier.php" method="post">
                                        <input type="hidden" name="voiture_id" value="<?php echo $voiture['id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <button type="submit" name="action" value="remove" class="btn btn-danger">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Récapitulatif</h5>
                        <p class="card-text">
                            <strong>Total: <?php echo format_price(get_cart_total()); ?></strong>
                        </p>
                        <?php if (is_logged_in()): ?>
                            <form action="commander.php" method="post">
                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    Passer la commande
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                Veuillez vous <a href="login.php">connecter</a> pour passer votre commande.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
