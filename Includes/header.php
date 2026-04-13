<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacie</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav>
    <h2>
    <a href="index.php" class="logo">Pharmacie</a>
</h2> 

    <?php if (isset($_SESSION['user_id'])): ?>
        <ul>
            <li><a href="dashboard.php">Tableau de bord</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="clients.php">Clients</a></li> 
            <li><a href="ventes.php">Ventes</a></li> 
            <li><a href="mon_compte.php">Mon compte</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    <?php endif; ?>
</nav>

<div class="container"> 