<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: clients.php');
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    header('Location: clients.php');
    exit();
}

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
        $stmt = $pdo->prepare("UPDATE clients SET nom = ?, email = ?, telephone = ?, adresse = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $telephone, $adresse, $id]);

        $message = "Client modifié avec succès.";
        $messageType = 'success';

        $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

include 'Includes/header.php';
?>

<h1>Modifier un client</h1>

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
        value="<?= htmlspecialchars($client['nom']) ?>"
        required
    >

    <label for="email">Adresse email :</label>
    <input
        type="email"
        id="email"
        name="email"
        value="<?= htmlspecialchars($client['email'] ?? '') ?>"
        placeholder="Ex : client@email.com"
    >

    <label for="telephone">Téléphone :</label>
    <input
        type="text"
        id="telephone"
        name="telephone"
        value="<?= htmlspecialchars($client['telephone'] ?? '') ?>"
        placeholder="Ex : 06 12 34 56 78"
    >

    <label for="adresse">Adresse :</label>
    <textarea
        id="adresse"
        name="adresse"
        placeholder="Ex : 10 rue de Paris, 75000 Paris"
    ><?= htmlspecialchars($client['adresse'] ?? '') ?></textarea>

    <button type="submit">Enregistrer les modifications</button>
</form>

<p><a href="clients.php">← Retour à la liste des clients</a></p>

<?php include 'Includes/footer.php'; ?> 