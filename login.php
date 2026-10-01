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

    if ($email === '' || $password === '') {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: dashboard.php');
            exit();
        }

        $message = "Email ou mot de passe incorrect.";
    }
}

include 'Includes/header.php';
?>

<span class="eyebrow">Espace sécurisé</span>
<h1>Connexion</h1>
<p class="page-intro">Connectez-vous pour accéder aux produits, clients, stocks et ventes.</p>

<?php if (isset($_SESSION['message'])): ?>
    <p class="success"><?= htmlspecialchars($_SESSION['message']) ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if ($message !== ''): ?>
    <p class="error"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" autocomplete="email" placeholder="vous@exemple.fr" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" autocomplete="current-password" required>

    <button type="submit">Se connecter</button>
</form>

<p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
<p><a href="changer_mot_de_passe.php">Changer mon mot de passe</a></p>

<?php include 'Includes/footer.php'; ?>
