<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Vérification si l'utilisateur est connecté
if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = 'mon_compte.php';
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Récupération des informations de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Traitement du formulaire de mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if (verify_csrf_token($_POST['csrf_token'])) {
        $nom = clean_input($_POST['nom']);
        $prenom = clean_input($_POST['prenom']);
        $telephone = clean_input($_POST['telephone']);
        $adresse = clean_input($_POST['adresse']);

        $stmt = $conn->prepare("UPDATE users SET nom = ?, prenom = ?, telephone = ?, adresse = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nom, $prenom, $telephone, $adresse, $user_id);
        
        if ($stmt->execute()) {
            $success_message = "Votre profil a été mis à jour avec succès.";
            // Mise à jour des données de session
            $_SESSION['user_name'] = $prenom . ' ' . $nom;
        } else {
            $error_message = "Une erreur est survenue lors de la mise à jour du profil.";
        }
    }
}

// Récupération des commandes de l'utilisateur
$stmt = $conn->prepare("
    SELECT c.*, 
           COUNT(dc.id) as nombre_voitures,
           GROUP_CONCAT(v.marque, ' ', v.modele SEPARATOR ', ') as voitures
    FROM commandes c
    LEFT JOIN details_commande dc ON c.id = dc.commande_id
    LEFT JOIN voitures v ON dc.voiture_id = v.id
    WHERE c.user_id = ?
    GROUP BY c.id
    ORDER BY c.date_commande DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$commandes = $stmt->get_result();

// Récupération des favoris
$stmt = $conn->prepare("
    SELECT v.*, m.nom as marque_nom
    FROM favoris f
    JOIN voitures v ON f.voiture_id = v.id
    JOIN marques m ON v.marque_id = m.id
    WHERE f.user_id = ?
    ORDER BY f.date_ajout DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$favoris = $stmt->get_result();

$page_title = "Mon Compte - Classic Cars";
include 'includes/header.php';
?>

<main class="container mt-4">
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Menu latéral -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list">Mon Profil</a>
                <a href="#commandes" class="list-group-item list-group-item-action" data-bs-toggle="list">Mes Commandes</a>
                <a href="#favoris" class="list-group-item list-group-item-action" data-bs-toggle="list">Mes Favoris</a>
            </div>
        </div>

        <!-- Contenu -->
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Profil -->
                <div class="tab-pane fade show active" id="profile">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Mon Profil</h3>
                            <form method="post" action="">
                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                <input type="hidden" name="update_profile" value="1">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom" 
                                               value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" 
                                               value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" 
                                           value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" 
                                           value="<?php echo htmlspecialchars($user['telephone']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="adresse" name="adresse" 
                                              rows="3"><?php echo htmlspecialchars($user['adresse']); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Mettre à jour le profil</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Commandes -->
                <div class="tab-pane fade" id="commandes">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Mes Commandes</h3>
                            <?php if ($commandes->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Référence</th>
                                                <th>Date</th>
                                                <th>Voitures</th>
                                                <th>Total</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($commande = $commandes->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($commande['reference']); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($commande['date_commande'])); ?></td>
                                                    <td><?php echo htmlspecialchars($commande['voitures']); ?></td>
                                                    <td><?php echo format_price($commande['total']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php 
                                                            echo $commande['statut'] === 'payee' ? 'success' : 
                                                                ($commande['statut'] === 'en_attente' ? 'warning' : 'info'); 
                                                        ?>">
                                                            <?php echo ucfirst(str_replace('_', ' ', $commande['statut'])); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p>Vous n'avez pas encore passé de commande.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Favoris -->
                <div class="tab-pane fade" id="favoris">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Mes Favoris</h3>
                            <?php if ($favoris->num_rows > 0): ?>
                                <div class="row">
                                    <?php while ($favori = $favoris->fetch_assoc()): ?>
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <img src="<?php echo htmlspecialchars($favori['image_principale']); ?>" 
                                                     class="card-img-top" alt="<?php echo htmlspecialchars($favori['marque_nom'] . ' ' . $favori['modele']); ?>">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <?php echo htmlspecialchars($favori['marque_nom'] . ' ' . $favori['modele']); ?>
                                                    </h5>
                                                    <p class="card-text">
                                                        Année: <?php echo htmlspecialchars($favori['annee']); ?><br>
                                                        Prix: <?php echo format_price($favori['prix']); ?>
                                                    </p>
                                                    <a href="detail_voiture.php?id=<?php echo $favori['id']; ?>" 
                                                       class="btn btn-primary">Voir détails</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p>Vous n'avez pas encore de favoris.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
