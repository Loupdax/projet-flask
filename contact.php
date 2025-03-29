<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token($_POST['csrf_token'])) {
    $nom = clean_input($_POST['nom']);
    $email = clean_input($_POST['email']);
    $sujet = clean_input($_POST['sujet']);
    $message = clean_input($_POST['message']);

    if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Veuillez entrer une adresse email valide.";
    } else {
        // Ici, vous pouvez ajouter le code pour envoyer l'email
        // Pour l'exemple, nous affichons simplement un message de succès
        $success = "Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.";
    }
}

$page_title = "Contact - Classic Cars";
include 'includes/header.php';
?>

<main class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title mb-4">Contactez-nous</h1>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="post" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet *</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="sujet" class="form-label">Sujet *</label>
                            <input type="text" class="form-control" id="sujet" name="sujet" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Envoyer le message</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nos coordonnées</h5>
                    <p class="card-text">
                        <strong>Classic Cars</strong><br>
                        123 Avenue des Collectionneurs<br>
                        75000 Paris<br><br>
                        <strong>Téléphone :</strong><br>
                        01 23 45 67 89<br><br>
                        <strong>Email :</strong><br>
                        contact@classic-cars.fr<br><br>
                        <strong>Horaires d'ouverture :</strong><br>
                        Lundi - Vendredi : 9h00 - 18h00<br>
                        Samedi : 10h00 - 17h00<br>
                        Dimanche : Fermé
                    </p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Notre emplacement</h5>
                    <div class="ratio ratio-4x3">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9914406081493!2d2.2922926156744547!3d48.858370079287466!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2sTour%20Eiffel!5e0!3m2!1sfr!2sfr!4v1647874587201!5m2!1sfr!2sfr" 
                                style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
