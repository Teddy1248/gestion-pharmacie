<?php 
session_start();
require_once 'Configuration/database.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if (empty($email) || empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    $message = "Tous les champs sont obligatoires.";
    $messageType = 'error';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "L'email est invalide.";
    $messageType = 'error';
} elseif ($newPassword !== $confirmPassword) {
    $message = "Les nouveaux mots de passe ne correspondent pas.";
    $messageType = 'error'; 
} else {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $message = "Aucun compte trouvé avec cet email.";
            $messageType = 'error';
        } elseif (!password_verify($currentPassword, $user['password'])) {
            $message = "L'ancien mot de passe est incorrect.";
            $messageType = 'error';
        } elseif (password_verify($newPassword, $user['password'])) {
            $message = "Le nouveau mot de passe doit être différent de l'ancien.";
            $messageType = 'error';
        } else {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$newHash, $user['id']]);

            $_SESSION['message'] = "Mot de passe modifié avec succès. Vous pouvez maintenant vous connecter.";
            header('Location: login.php');
            exit();
        }
    }
}

include 'Includes/header.php';
?>

<h1>Changer mon mot de passe</h1>

<?php if (!empty($message)): ?>
    <p class="<?= htmlspecialchars($messageType) ?>">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<form method="POST" action="changer_mot_de_passe.php">
    <label for="email">Email :</label>
    <input
        type="email"
        id="email"
        name="email"
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
        required
    >

    <label for="current_password">Ancien mot de passe :</label>
    <input
        type="password"
        id="current_password"
        name="current_password"
        required
    >

    <label for="new_password">Nouveau mot de passe :</label>
    <input
        type="password"
        id="new_password"
        name="new_password"
        required
    >

    <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
    <input
        type="password"
        id="confirm_password"
        name="confirm_password"
        required
    >

    <button type="submit">Modifier le mot de passe</button>
</form>

<p><a href="login.php">← Retour à la connexion</a></p>

<?php include 'Includes/footer.php'; ?> 