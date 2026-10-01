<?php
session_start();
require_once 'Configuration/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($email === '' || $password === '' || $confirm === '') {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'adresse email est invalide.";
    } elseif ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 8) {
        $message = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $message = "Cette adresse email est déjà utilisée.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)")->execute([$email, $hash]);
            $_SESSION['message'] = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
            header('Location: login.php');
            exit();
        }
    }
}

include 'Includes/header.php';
?>

<span class="eyebrow">Nouveau compte</span>
<h1>Inscription</h1>
<p class="page-intro">Créez votre accès à PharmaGestion en quelques secondes.</p>

<?php if ($message !== ''): ?>
    <p class="error"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="register.php">
    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" autocomplete="email" placeholder="vous@exemple.fr" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required>

    <label for="confirm_password">Confirmer le mot de passe</label>
    <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" minlength="8" required>

    <button type="submit">Créer mon compte</button>
</form>

<p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>

<?php include 'Includes/footer.php'; ?>
