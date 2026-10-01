<?php
session_start();
include 'Includes/header.php';
?>

<section class="hero">
    <span class="eyebrow">Gestion de pharmacie</span>
    <h1>Gérez votre pharmacie avec simplicité.</h1>
    <p>Produits, stocks, clients et ventes réunis dans une interface claire, rapide et pensée pour le quotidien.</p>
    <div class="hero-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a class="btn btn-secondary" href="dashboard.php">Ouvrir le tableau de bord</a>
        <?php else: ?>
            <a class="btn btn-secondary" href="login.php">Se connecter</a>
            <a class="btn" href="register.php">Créer un compte</a>
        <?php endif; ?>
    </div>
</section>

<?php include 'Includes/footer.php'; ?>
