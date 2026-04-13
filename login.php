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
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: dashboard.php');
            exit();
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }
}

include 'Includes/header.php';
?>

<h1>Connexion</h1>

<?php if (isset($_SESSION['message'])): ?>
    <p class="success"><?= htmlspecialchars($_SESSION['message']) ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (!empty($message)): ?>
    <p class="error"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>

<p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
<p><a href="changer_mot_de_passe.php">Changer mon mot de passe</a></p>

<?php include 'Includes/footer.php'; ?> 