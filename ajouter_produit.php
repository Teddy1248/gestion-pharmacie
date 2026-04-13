<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

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
        $stmt = $pdo->prepare("INSERT INTO produits (nom, description, prix, stock) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $description, $prix, $stock]);

        $message = "Produit ajouté avec succès.";
        $messageType = 'success';

        $_POST = [];
    }
}

include 'Includes/header.php';
?>

<h1>Ajouter un produit</h1>

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
        placeholder="Ex : Doliprane"
        value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
        required
    >

    <label for="description">Description :</label>
    <textarea
        id="description"
        name="description"
        placeholder="Ex : Médicament contre la douleur"
    ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

    <label for="prix">Prix (€) :</label>
    <input
        type="number"
        id="prix"
        name="prix"
        step="0.01"
        min="0"
        placeholder="Ex : 3.50"
        value="<?= htmlspecialchars($_POST['prix'] ?? '') ?>"
        required
    >

    <label for="stock">Quantité en stock :</label>
    <input
        type="number"
        id="stock"
        name="stock"
        min="0"
        placeholder="Ex : 20"
        value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>"
        required
    >

    <button type="submit">Ajouter le produit</button>
</form>

<p><a href="produits.php">← Retour à la liste des produits</a></p>

<?php include 'Includes/footer.php'; ?> 