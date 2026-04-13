<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$stmt = $pdo->query("SELECT * FROM clients ORDER BY id DESC");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<h1>Liste des clients</h1>

<div class="top-actions">
    <a class="btn" href="ajouter_client.php">Ajouter un client</a>
</div>

<?php if (!empty($clients)): ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id']) ?></td>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= !empty($client['email']) ? htmlspecialchars($client['email']) : 'Non renseigné' ?></td>
                        <td><?= !empty($client['telephone']) ? htmlspecialchars($client['telephone']) : 'Non renseigné' ?></td>
                        <td><?= !empty($client['adresse']) ? htmlspecialchars($client['adresse']) : 'Non renseignée' ?></td>
                        <td class="actions">
                            <a class="action-link edit-link" href="modifier_client.php?id=<?= $client['id'] ?>">Modifier</a>
                            <a class="action-link delete-link" href="supprimer_client.php?id=<?= $client['id'] ?>" onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <p>Aucun client enregistré pour le moment.</p>
        <a class="btn" href="ajouter_client.php">Ajouter le premier client</a>
    </div>
<?php endif; ?>

<?php include 'Includes/footer.php'; ?> 