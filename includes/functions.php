<?php

// Fonction pour nettoyer les entrées utilisateur
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fonction pour vérifier si l'utilisateur est connecté
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Fonction pour obtenir les détails d'une voiture
function get_car_details($car_id) {
    global $conn;
    $car_id = (int)$car_id;
    
    $sql = "SELECT * FROM voitures WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

// Fonction pour obtenir les images d'une voiture
function get_car_images($car_id) {
    global $conn;
    $car_id = (int)$car_id;
    
    $sql = "SELECT * FROM images_voiture WHERE voiture_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fonction pour formater le prix
function format_price($price) {
    return number_format($price, 2, ',', ' ') . ' €';
}

// Fonction pour ajouter au panier
function add_to_cart($car_id) {
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }
    
    if (!in_array($car_id, $_SESSION['panier'])) {
        $_SESSION['panier'][] = $car_id;
        return true;
    }
    
    return false;
}

// Fonction pour supprimer du panier
function remove_from_cart($car_id) {
    if (isset($_SESSION['panier'])) {
        $key = array_search($car_id, $_SESSION['panier']);
        if ($key !== false) {
            unset($_SESSION['panier'][$key]);
            $_SESSION['panier'] = array_values($_SESSION['panier']);
            return true;
        }
    }
    return false;
}

// Fonction pour calculer le total du panier
function get_cart_total() {
    global $conn;
    $total = 0;
    
    if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
        $ids = implode(',', array_map('intval', $_SESSION['panier']));
        $sql = "SELECT SUM(prix) as total FROM voitures WHERE id IN ($ids)";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total = $row['total'];
    }
    
    return $total;
}

// Fonction pour générer un token CSRF
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Fonction pour vérifier le token CSRF
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
