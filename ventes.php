<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

// Récupérer les ventes avec client + utilisateur
$sql = "
    SELECT
        v.id,
        v.total,
        v.created_at,
        c.nom AS client_nom,
        u.email AS vendeur_email
    FROM ventes v
    LEFT JOIN clients c ON v.client_id = c.id
    INNER JOIN users u ON v.user_id = u.id
    ORDER BY v.id DESC
";

$stmt = $pdo->query($sql);
$ventes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<h1>Liste des ventes</h1>

<?php if (isset($_SESSION['message'])): ?>
    <p class="success"><?= htmlspecialchars($_SESSION['message']) ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="top-actions">
    <a class="btn" href="ajouter_vente.php">➕ Enregistrer une vente</a>
</div>

<?php if (!empty($ventes)): ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Vendeur</th>
                    <th>Total (€)</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventes as $vente): ?>
                    <tr>
                        <td><?= htmlspecialchars($vente['id']) ?></td>
                        <td><?= htmlspecialchars($vente['client_nom'] ?? 'Non renseigné') ?></td>
                        <td><?= htmlspecialchars($vente['vendeur_email']) ?></td>
                        <td><?= number_format((float)$vente['total'], 2, ',', ' ') ?></td>
                        <td><?= htmlspecialchars($vente['created_at']) ?></td>
                        <td>
                            <a href="details_vente.php?id=<?= $vente['id'] ?>">
                                Voir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Aucune vente enregistrée.</p>
<?php endif; ?>

<?php include 'Includes/footer.php'; ?> 