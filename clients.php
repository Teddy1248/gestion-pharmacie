<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$stmt = $pdo->query("SELECT id, nom, email, telephone, adresse FROM clients ORDER BY id DESC");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<span class="eyebrow">Fichier client</span>
<h1>Clients</h1>
<p class="page-intro">Retrouvez rapidement les coordonnées utiles de vos clients.</p>

<div class="top-actions">
    <a class="btn" href="ajouter_client.php">+ Ajouter un client</a>
</div>

<?php if ($clients): ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Adresse</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td>#<?= (int) $client['id'] ?></td>
                    <td><strong><?= htmlspecialchars($client['nom']) ?></strong></td>
                    <td><?= $client['email'] ? htmlspecialchars($client['email']) : 'Non renseigné' ?></td>
                    <td><?= $client['telephone'] ? htmlspecialchars($client['telephone']) : 'Non renseigné' ?></td>
                    <td><?= $client['adresse'] ? htmlspecialchars($client['adresse']) : 'Non renseignée' ?></td>
                    <td class="actions">
                        <a class="action-link edit-link" href="modifier_client.php?id=<?= (int) $client['id'] ?>">Modifier</a>
                        <a class="action-link delete-link" href="supprimer_client.php?id=<?= (int) $client['id'] ?>" onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h2>Aucun client</h2>
        <p>Ajoutez un client pour commencer à constituer votre fichier.</p>
        <a class="btn" href="ajouter_client.php">Ajouter un client</a>
    </div>
<?php endif; ?>

<?php include 'Includes/footer.php'; ?>
