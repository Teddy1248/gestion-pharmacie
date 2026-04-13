<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: produits.php');
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    header('Location: produits.php');
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = trim($_POST['prix'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    if (empty($nom) || $prix === '' || $stock === '') {
        $message = "Les champs nom, prix et stock sont obligatoires.";
        $messageType = 'error';
    } elseif (!is_numeric($prix) || !is_numeric($stock)) {
        $message = "Le prix et le stock doivent être numériques.";
        $messageType = 'error';
    } else {
        $stmt = $pdo->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, stock = ? WHERE id = ?");
        $stmt->execute([$nom, $description, $prix, $stock, $id]);

        $message = "Produit modifié avec succès.";
        $messageType = 'success';

        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

include 'Includes/header.php';
?>

<h1>Modifier un produit</h1>

<?php if (!empty($message)): ?>
    <p class="<?= htmlspecialchars($messageType) ?>">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<form method="POST" action="">
    <label for="nom">Nom du produit :</label>
    <input
        type="text"
        id="nom"
        name="nom"
        value="<?= htmlspecialchars($produit['nom']) ?>"
        required
    >

    <label for="description">Description :</label>
    <textarea
        id="description"
        name="description"
        placeholder="Ex : Médicament contre la douleur"
    ><?= htmlspecialchars($produit['description'] ?? '') ?></textarea>

    <label for="prix">Prix (€) :</label>
    <input
        type="number"
        id="prix"
        name="prix"
        step="0.01"
        min="0"
        value="<?= htmlspecialchars($produit['prix']) ?>"
        required
    >

    <label for="stock">Quantité en stock :</label>
    <input
        type="number"
        id="stock"
        name="stock"
        min="0"
        value="<?= htmlspecialchars($produit['stock']) ?>"
        required
    >

    <button type="submit">Enregistrer les modifications</button>
</form>

<p><a href="produits.php">← Retour à la liste des produits</a></p>

<?php include 'Includes/footer.php'; ?> 