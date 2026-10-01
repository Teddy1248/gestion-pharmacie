<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$stats = $pdo->query("
    SELECT
        (SELECT COUNT(*) FROM produits) AS produits,
        (SELECT COUNT(*) FROM clients) AS clients,
        (SELECT COUNT(*) FROM ventes) AS ventes
")->fetch(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<div class="dashboard-head">
    <div>
        <span class="eyebrow">Vue d’ensemble</span>
        <h1>Tableau de bord</h1>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_email']) ?>.</p>
    </div>
</div>

<div class="stats-grid" aria-label="Statistiques principales">
    <div class="stat-card"><span class="stat-label">Produits</span><strong class="stat-value"><?= $stats['produits'] ?></strong></div>
    <div class="stat-card"><span class="stat-label">Clients</span><strong class="stat-value"><?= $stats['clients'] ?></strong></div>
    <div class="stat-card"><span class="stat-label">Ventes</span><strong class="stat-value"><?= $stats['ventes'] ?></strong></div>
</div>

<div class="dashboard-links">
    <a class="card" href="produits.php">
        <span class="card-icon" aria-hidden="true">Rx</span>
        <h3>Produits</h3>
        <p>Consultez les médicaments, les prix et les niveaux de stock.</p>
    </a>
    <a class="card" href="clients.php">
        <span class="card-icon" aria-hidden="true">◎</span>
        <h3>Clients</h3>
        <p>Centralisez les coordonnées et informations de vos clients.</p>
    </a>
    <a class="card" href="ventes.php">
        <span class="card-icon" aria-hidden="true">€</span>
        <h3>Ventes</h3>
        <p>Enregistrez une vente et consultez rapidement son détail.</p>
    </a>
</div>

<?php include 'Includes/footer.php'; ?>
