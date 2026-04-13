<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ventes.php');
    exit();
}

$venteId = (int) $_GET['id'];

$stmtVente = $pdo->prepare("
    SELECT
        v.id,
        v.total,
        v.created_at,
        c.nom AS client_nom,
        c.email AS client_email,
        c.telephone AS client_telephone,
        c.adresse AS client_adresse,
        u.email AS vendeur_email
    FROM ventes v
    LEFT JOIN clients c ON v.client_id = c.id
    INNER JOIN users u ON v.user_id = u.id
    WHERE v.id = ?
");
$stmtVente->execute([$venteId]);
$vente = $stmtVente->fetch(PDO::FETCH_ASSOC);

if (!$vente) {
    header('Location: ventes.php');
    exit();
}

$stmtLignes = $pdo->prepare("
    SELECT
        nom_produit,
        prix_unitaire,
        quantite,
        sous_total
    FROM vente_produits
    WHERE vente_id = ?
    ORDER BY id ASC
");
$stmtLignes->execute([$venteId]);
$lignes = $stmtLignes->fetchAll(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<h1>Détail de la vente #<?= htmlspecialchars($vente['id']) ?></h1>

<?php if (isset($_SESSION['message'])): ?>
    <p class="success"><?= htmlspecialchars($_SESSION['message']) ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="info-box">
    <p><strong>Client :</strong> <?= htmlspecialchars($vente['client_nom'] ?? 'Client non renseigné') ?></p>
    <p><strong>Email client :</strong> <?= htmlspecialchars($vente['client_email'] ?? 'Non renseigné') ?></p>
    <p><strong>Téléphone client :</strong> <?= htmlspecialchars($vente['client_telephone'] ?? 'Non renseigné') ?></p>
    <p><strong>Adresse client :</strong> <?= htmlspecialchars($vente['client_adresse'] ?? 'Non renseignée') ?></p>
    <p><strong>Vendeur :</strong> <?= htmlspecialchars($vente['vendeur_email']) ?></p>
    <p><strong>Date :</strong> <?= htmlspecialchars($vente['created_at']) ?></p>
    <p><strong>Total :</strong> <?= number_format((float) $vente['total'], 2, ',', ' ') ?> €</p>
</div>

<h2>Produits vendus</h2>

<?php if (!empty($lignes)): ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire (€)</th>
                    <th>Quantité</th>
                    <th>Sous-total (€)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lignes as $ligne): ?>
                    <tr>
                        <td><?= htmlspecialchars($ligne['nom_produit']) ?></td>
                        <td><?= number_format((float) $ligne['prix_unitaire'], 2, ',', ' ') ?></td>
                        <td><?= (int) $ligne['quantite'] ?></td>
                        <td><?= number_format((float) $ligne['sous_total'], 2, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Aucun produit trouvé pour cette vente.</p>
<?php endif; ?>

<p><a href="ventes.php">← Retour à la liste des ventes</a></p>

<?php include 'Includes/footer.php'; ?> 