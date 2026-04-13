<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$stmt = $pdo->query("SELECT * FROM produits ORDER BY id DESC");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<h1>Liste des produits</h1>

<?php if (isset($_SESSION['message'])): ?>
    <p class="success"><?= htmlspecialchars($_SESSION['message']) ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="top-actions">
    <a class="btn" href="ajouter_produit.php">Ajouter un produit</a>
</div>

<?php if (!empty($produits)): ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix (€)</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produits as $produit): ?>
                    <tr>
                        <td><?= htmlspecialchars($produit['id']) ?></td>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td>
                            <?= !empty($produit['description']) ? htmlspecialchars($produit['description']) : 'Aucune description' ?>
                        </td>
                        <td><?= number_format((float) $produit['prix'], 2, ',', ' ') ?></td>
                        <td>
                            <?php if ((int) $produit['stock'] <= 0): ?>
                                <span class="stock-zero">Rupture</span>
                            <?php elseif ((int) $produit['stock'] <= 5): ?>
                                <span class="stock-low"><?= htmlspecialchars($produit['stock']) ?> en stock</span>
                            <?php else: ?>
                                <?= htmlspecialchars($produit['stock']) ?>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a class="action-link edit-link" href="modifier_produit.php?id=<?= $produit['id'] ?>">Modifier</a>
                            <a class="action-link delete-link" href="supprimer_produit.php?id=<?= $produit['id'] ?>" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <p>Aucun produit enregistré pour le moment.</p>
        <a class="btn" href="ajouter_produit.php">Ajouter le premier produit</a>
    </div>
<?php endif; ?>

<?php include 'Includes/footer.php'; ?> 