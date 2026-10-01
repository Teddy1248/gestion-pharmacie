<?php
session_start();
require_once 'Configuration/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($email === '' || $currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'adresse email est invalide.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Les nouveaux mots de passe ne correspondent pas.";
    } elseif (strlen($newPassword) < 8) {
        $message = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
    } else {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $message = "Aucun compte trouvé avec cet email.";
        } elseif (!password_verify($currentPassword, $user['password'])) {
            $message = "L'ancien mot de passe est incorrect.";
        } elseif (password_verify($newPassword, $user['password'])) {
            $message = "Le nouveau mot de passe doit être différent de l'ancien.";
        } else {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$newHash, $user['id']]);
            $_SESSION['message'] = "Mot de passe modifié avec succès. Vous pouvez maintenant vous connecter.";
            header('Location: login.php');
            exit();
        }
    }
}

include 'Includes/header.php';
?>

<span class="eyebrow">Sécurité</span>
<h1>Changer mon mot de passe</h1>
<p class="page-intro">Choisissez un nouveau mot de passe d’au moins 8 caractères.</p>

<?php if ($message !== ''): ?><p class="error"><?= htmlspecialchars($message) ?></p><?php endif; ?>

<form method="POST" action="changer_mot_de_passe.php">
    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" autocomplete="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

    <label for="current_password">Ancien mot de passe</label>
    <input type="password" id="current_password" name="current_password" autocomplete="current-password" required>

    <label for="new_password">Nouveau mot de passe</label>
    <input type="password" id="new_password" name="new_password" autocomplete="new-password" minlength="8" required>

    <label for="confirm_password">Confirmer le nouveau mot de passe</label>
    <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" minlength="8" required>

    <button type="submit">Modifier le mot de passe</button>
</form>

<a class="back-link" href="login.php">← Retour à la connexion</a>

<?php include 'Includes/footer.php'; ?>
