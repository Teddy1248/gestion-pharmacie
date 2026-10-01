<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$stmt = $pdo->query("SELECT id, nom, description, prix, stock FROM produits ORDER BY id DESC");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<span class="eyebrow">Catalogue & stock</span>
<h1>Produits</h1>
<p class="page-intro">Suivez vos médicaments, leurs prix et la disponibilité du stock.</p>

<?php if (isset($_SESSION['message'])): ?>
    <p class="success"><?= htmlspecialchars($_SESSION['message']) ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="top-actions">
    <a class="btn" href="ajouter_produit.php">+ Ajouter un produit</a>
</div>

<?php if ($produits): ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>ID</th><th>Nom</th><th>Description</th><th>Prix</th><th>Stock</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td>#<?= (int) $produit['id'] ?></td>
                    <td><strong><?= htmlspecialchars($produit['nom']) ?></strong></td>
                    <td><?= $produit['description'] !== '' ? htmlspecialchars($produit['description']) : 'Aucune description' ?></td>
                    <td><?= number_format((float) $produit['prix'], 2, ',', ' ') ?> €</td>
                    <td>
                        <?php if ((int) $produit['stock'] <= 0): ?>
                            <span class="stock-zero">Rupture</span>
                        <?php elseif ((int) $produit['stock'] <= 5): ?>
                            <span class="stock-low"><?= (int) $produit['stock'] ?> en stock</span>
                        <?php else: ?>
                            <span class="badge"><?= (int) $produit['stock'] ?> en stock</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a class="action-link edit-link" href="modifier_produit.php?id=<?= (int) $produit['id'] ?>">Modifier</a>
                        <a class="action-link delete-link" href="supprimer_produit.php?id=<?= (int) $produit['id'] ?>" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h2>Aucun produit</h2>
        <p>Commencez par ajouter votre premier produit au catalogue.</p>
        <a class="btn" href="ajouter_produit.php">Ajouter un produit</a>
    </div>
<?php endif; ?>

<?php include 'Includes/footer.php'; ?>
