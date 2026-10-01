<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$isLoggedIn = isset($_SESSION['user_id']);
$authPages = ['login.php', 'register.php', 'changer_mot_de_passe.php'];
$isAuthPage = in_array($currentPage, $authPages, true);

function navActive(array $pages, string $currentPage): string
{
    return in_array($currentPage, $pages, true) ? ' class="active" aria-current="page"' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f6b45">
    <meta name="description" content="Application légère de gestion de pharmacie : produits, clients, stocks et ventes.">
    <title>PharmaGestion</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="<?= $isAuthPage ? 'auth-page' : '' ?>">
<header class="site-header">
    <nav class="navbar" aria-label="Navigation principale">
        <a href="<?= $isLoggedIn ? 'dashboard.php' : 'index.php' ?>" class="brand" aria-label="PharmaGestion - Accueil">
            <span class="brand-mark" aria-hidden="true">+</span>
            <span>Pharma<span>Gestion</span></span>
        </a>

        <?php if ($isLoggedIn): ?>
            <ul class="nav-links">
                <li><a href="dashboard.php"<?= navActive(['dashboard.php'], $currentPage) ?>>Tableau de bord</a></li>
                <li><a href="produits.php"<?= navActive(['produits.php', 'ajouter_produit.php', 'modifier_produit.php'], $currentPage) ?>>Produits</a></li>
                <li><a href="clients.php"<?= navActive(['clients.php', 'ajouter_client.php', 'modifier_client.php'], $currentPage) ?>>Clients</a></li>
                <li><a href="ventes.php"<?= navActive(['ventes.php', 'ajouter_vente.php', 'details_vente.php'], $currentPage) ?>>Ventes</a></li>
                <li><a href="mon_compte.php"<?= navActive(['mon_compte.php'], $currentPage) ?>>Compte</a></li>
                <li><a class="logout-link" href="logout.php">Déconnexion</a></li>
            </ul>
        <?php else: ?>
            <div class="public-nav">
                <?php if ($currentPage !== 'login.php'): ?><a href="login.php">Connexion</a><?php endif; ?>
                <?php if ($currentPage !== 'register.php'): ?><a class="nav-cta" href="register.php">Créer un compte</a><?php endif; ?>
            </div>
        <?php endif; ?>
    </nav>
</header>

<main class="container<?= $isAuthPage ? ' auth-container' : '' ?>">
