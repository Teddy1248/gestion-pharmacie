<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$sql = "
    SELECT v.id, v.total, v.created_at, c.nom AS client_nom, u.email AS vendeur_email
    FROM ventes v
    LEFT JOIN clients c ON v.client_id = c.id
    INNER JOIN users u ON v.user_id = u.id
    ORDER BY v.id DESC
";
$ventes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<span class="eyebrow">Historique</span>
<h1>Ventes</h1>
<p class="page-intro">Enregistrez les transactions et retrouvez le détail de chaque vente.</p>

<?php if (isset($_SESSION['message'])): ?>
    <p class="success"><?= htmlspecialchars($_SESSION['message']) ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="top-actions">
    <a class="btn" href="ajouter_vente.php">+ Enregistrer une vente</a>
</div>

<?php if ($ventes): ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>ID</th><th>Client</th><th>Vendeur</th><th>Total</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($ventes as $vente): ?>
                <tr>
                    <td>#<?= (int) $vente['id'] ?></td>
                    <td><strong><?= htmlspecialchars($vente['client_nom'] ?? 'Non renseigné') ?></strong></td>
                    <td><?= htmlspecialchars($vente['vendeur_email']) ?></td>
                    <td><?= number_format((float) $vente['total'], 2, ',', ' ') ?> €</td>
                    <td><?= htmlspecialchars($vente['created_at']) ?></td>
                    <td><a class="action-link edit-link" href="details_vente.php?id=<?= (int) $vente['id'] ?>">Voir le détail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h2>Aucune vente</h2>
        <p>Votre historique de ventes apparaîtra ici.</p>
        <a class="btn" href="ajouter_vente.php">Enregistrer une vente</a>
    </div>
<?php endif; ?>

<?php include 'Includes/footer.php'; ?>
