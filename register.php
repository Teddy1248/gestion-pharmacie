<?php
session_start();
require_once 'Configuration/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    if (!$email || !$password || !$confirm) {
        $message = "Champs obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    } elseif ($password !== $confirm) {
        $message = "Mots de passe différents.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $message = "Email déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (email,password) VALUES (?,?)")->execute([$email,$hash]);
            header("Location: login.php");
            exit();
        }
    }
}
?>

<?php include 'Includes/header.php'; ?>

<h1>Inscription</h1>
<p><?= htmlspecialchars($message) ?></p>

<form method="POST">
<input type="email" name="email" placeholder="Email">
<input type="password" name="password" placeholder="Mot de passe">
<input type="password" name="confirm_password" placeholder="Confirmer">
<button>S'inscrire</button>
</form>

<?php include 'Includes/footer.php'; ?> 