<?php
session_start();
include 'Includes/header.php';
?>

<h1>Bienvenue sur le site de gestion de pharmacie</h1>
<p>Ce site permet de gérer les produits, les clients et les ventes.</p>

<?php if (isset($_SESSION['user_id'])): ?>
    <a class="btn" href="dashboard.php">Aller au tableau de bord</a>
<?php else: ?>
    <a class="btn" href="login.php">Se connecter</a>
    <a class="btn" href="register.php">S'inscrire</a>
<?php endif; ?>

<?php include 'Includes/footer.php'; ?> 