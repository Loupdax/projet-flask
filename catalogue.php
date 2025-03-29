<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Catalogue - Classic Cars";
include 'includes/header.php';

// Pagination
$items_per_page = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Filtres
$where_clause = "1=1";
$params = array();
$types = "";

if (isset($_GET['marque']) && !empty($_GET['marque'])) {
    $where_clause .= " AND marque = ?";
    $params[] = $_GET['marque'];
    $types .= "s";
}

if (isset($_GET['annee_min']) && !empty($_GET['annee_min'])) {
    $where_clause .= " AND annee >= ?";
    $params[] = (int)$_GET['annee_min'];
    $types .= "i";
}

if (isset($_GET['annee_max']) && !empty($_GET['annee_max'])) {
    $where_clause .= " AND annee <= ?";
    $params[] = (int)$_GET['annee_max'];
    $types .= "i";
}

if (isset($_GET['prix_min']) && !empty($_GET['prix_min'])) {
    $where_clause .= " AND prix >= ?";
    $params[] = (float)$_GET['prix_min'];
    $types .= "d";
}

if (isset($_GET['prix_max']) && !empty($_GET['prix_max'])) {
    $where_clause .= " AND prix <= ?";
    $params[] = (float)$_GET['prix_max'];
    $types .= "d";
}

// Requête pour obtenir le nombre total de voitures
$count_sql = "SELECT COUNT(*) as total FROM voitures WHERE $where_clause";
$stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_items = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// Requête pour obtenir les voitures de la page courante
$sql = "SELECT * FROM voitures WHERE $where_clause ORDER BY date_ajout DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$types .= "ii";
$params[] = $items_per_page;
$params[] = $offset;
$stmt->bind_param($types, ...$params);
$stmt->execute();
$voitures = $stmt->get_result();
?>

<main class="container mt-4">
    <h1 class="text-center mb-4">Catalogue des voitures anciennes</h1>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-3">
                    <label for="marque" class="form-label">Marque</label>
                    <select name="marque" id="marque" class="form-select">
                        <option value="">Toutes les marques</option>
                        <?php
                        $marques = $conn->query("SELECT DISTINCT marque FROM voitures ORDER BY marque");
                        while ($marque = $marques->fetch_assoc()) {
                            $selected = (isset($_GET['marque']) && $_GET['marque'] == $marque['marque']) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($marque['marque']) . "\" $selected>" . 
                                 htmlspecialchars($marque['marque']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="annee_min" class="form-label">Année min</label>
                    <input type="number" class="form-control" id="annee_min" name="annee_min" 
                           value="<?php echo isset($_GET['annee_min']) ? htmlspecialchars($_GET['annee_min']) : ''; ?>">
                </div>
                <div class="col-md-2">
                    <label for="annee_max" class="form-label">Année max</label>
                    <input type="number" class="form-control" id="annee_max" name="annee_max"
                           value="<?php echo isset($_GET['annee_max']) ? htmlspecialchars($_GET['annee_max']) : ''; ?>">
                </div>
                <div class="col-md-2">
                    <label for="prix_min" class="form-label">Prix min</label>
                    <input type="number" class="form-control" id="prix_min" name="prix_min"
                           value="<?php echo isset($_GET['prix_min']) ? htmlspecialchars($_GET['prix_min']) : ''; ?>">
                </div>
                <div class="col-md-2">
                    <label for="prix_max" class="form-label">Prix max</label>
                    <input type="number" class="form-control" id="prix_max" name="prix_max"
                           value="<?php echo isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : ''; ?>">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des voitures -->
    <div class="row">
        <?php while ($voiture = $voitures->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($voiture['image_principale']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']); ?>">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']); ?>
                        </h5>
                        <p class="card-text">
                            Année: <?php echo htmlspecialchars($voiture['annee']); ?><br>
                            Kilométrage: <?php echo number_format($voiture['kilometrage'], 0, ',', ' '); ?> km<br>
                            Prix: <?php echo format_price($voiture['prix']); ?>
                        </p>
                        <a href="detail_voiture.php?id=<?php echo $voiture['id']; ?>" 
                           class="btn btn-primary">Voir détails</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Navigation catalogue" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php
                            echo isset($_GET['marque']) ? '&marque=' . urlencode($_GET['marque']) : '';
                            echo isset($_GET['annee_min']) ? '&annee_min=' . urlencode($_GET['annee_min']) : '';
                            echo isset($_GET['annee_max']) ? '&annee_max=' . urlencode($_GET['annee_max']) : '';
                            echo isset($_GET['prix_min']) ? '&prix_min=' . urlencode($_GET['prix_min']) : '';
                            echo isset($_GET['prix_max']) ? '&prix_max=' . urlencode($_GET['prix_max']) : '';
                        ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
