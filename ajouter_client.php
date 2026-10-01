<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');

    if (empty($nom)) {
        $message = "Le nom du client est obligatoire.";
        $messageType = 'error';
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'adresse email est invalide.";
        $messageType = 'error';
    } else {
        $stmt = $pdo->prepare("INSERT INTO clients (nom, email, telephone, adresse) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $telephone, $adresse]);

        $message = "Client ajouté avec succès.";
        $messageType = 'success';

        $_POST = [];
    }
}

include 'Includes/header.php';
?>

<span class="eyebrow">Fichier client</span>
<h1>Ajouter un client</h1>
<p class="page-intro">Ajoutez les coordonnées utiles d’un nouveau client.</p>

<?php if (!empty($message)): ?>
    <p class="<?= htmlspecialchars($messageType) ?>">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<form method="POST" action="">
    <label for="nom">Nom du client :</label>
    <input
        type="text"
        id="nom"
        name="nom"
        placeholder="Ex : Jean Dupont"
        value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
        required
    >

    <label for="email">Adresse email :</label>
    <input
        type="email"
        id="email"
        name="email"
        placeholder="Ex : jean@email.com"
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
    >

    <label for="telephone">Téléphone :</label>
    <input
        type="text"
        id="telephone"
        name="telephone"
        placeholder="Ex : 06 12 34 56 78"
        value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>"
    >

    <label for="adresse">Adresse :</label>
    <textarea
        id="adresse"
        name="adresse"
        placeholder="Ex : 10 rue de Paris, 75000 Paris"
    ><?= htmlspecialchars($_POST['adresse'] ?? '') ?></textarea>

    <button type="submit">Ajouter le client</button>
</form>

<a class="back-link" href="clients.php">← Retour aux clients</a>

<?php include 'Includes/footer.php'; ?> 