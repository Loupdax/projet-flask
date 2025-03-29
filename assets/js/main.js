// Activation des tooltips Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Validation du formulaire d'inscription
function validateRegistrationForm() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirm');
    
    if (password.value !== confirmPassword.value) {
        alert('Les mots de passe ne correspondent pas.');
        return false;
    }
    return true;
}

// Animation smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Gestion du panier
function updateCartCount() {
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        fetch('get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                cartBadge.textContent = data.count;
                if (data.count > 0) {
                    cartBadge.style.display = 'inline';
                } else {
                    cartBadge.style.display = 'none';
                }
            });
    }
}

// Filtres dynamiques du catalogue
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('.filter-form');
    if (filterForm) {
        const inputs = filterForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
});

// Galerie d'images pour les détails de voiture
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.querySelector('.car-main-image');
    const thumbnails = document.querySelectorAll('.car-thumbnail');
    
    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                mainImage.src = this.dataset.fullsize;
                mainImage.alt = this.alt;
                
                // Mise à jour de la classe active
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
});

// Message de confirmation avant suppression
function confirmDelete(event) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
        event.preventDefault();
    }
}

// Gestion des alertes
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('main.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Raccourcis clavier
document.addEventListener('keydown', function(event) {
    // Ne pas activer les raccourcis si l'utilisateur est en train de taper dans un champ
    if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
        return;
    }

    // Alt + touches pour la navigation
    if (event.altKey) {
        switch(event.key) {
            case 'a': // Alt + A : Accueil
                window.location.href = 'index.php';
                break;
            case 'c': // Alt + C : Catalogue
                window.location.href = 'catalogue.php';
                break;
            case 'p': // Alt + P : Panier
                window.location.href = 'panier.php';
                break;
            case 'm': // Alt + M : Mon compte
                window.location.href = 'mon_compte.php';
                break;
            case 't': // Alt + T : Contact
                window.location.href = 'contact.php';
                break;
            case 's': // Alt + S : Focus sur la barre de recherche
                event.preventDefault();
                document.querySelector('input[type="search"]')?.focus();
                break;
        }
    }
});

// Afficher une info-bulle avec les raccourcis disponibles
const shortcutInfo = document.createElement('div');
shortcutInfo.innerHTML = `
    <div class="position-fixed bottom-0 end-0 m-3 p-3 bg-dark text-light rounded shadow-lg" style="z-index: 1050; max-width: 300px;">
        <h6 class="mb-2"><i class="bi bi-keyboard"></i> Raccourcis clavier :</h6>
        <ul class="list-unstyled small mb-0">
            <li><kbd>Alt</kbd> + <kbd>A</kbd> : Accueil</li>
            <li><kbd>Alt</kbd> + <kbd>C</kbd> : Catalogue</li>
            <li><kbd>Alt</kbd> + <kbd>P</kbd> : Panier</li>
            <li><kbd>Alt</kbd> + <kbd>M</kbd> : Mon compte</li>
            <li><kbd>Alt</kbd> + <kbd>T</kbd> : Contact</li>
            <li><kbd>Alt</kbd> + <kbd>S</kbd> : Recherche</li>
        </ul>
        <button class="btn btn-sm btn-outline-light mt-2" onclick="this.parentElement.style.display='none'">Fermer</button>
    </div>
`;
document.body.appendChild(shortcutInfo);
