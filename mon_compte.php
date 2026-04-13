<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$message = '';

$stmt = $pdo->prepare("SELECT email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<h1>Mon compte</h1>

<?php if (!empty($message)): ?>
    <p class="info-box"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if ($user): ?>
    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Date de création :</strong> <?= htmlspecialchars($user['created_at']) ?></p>
<?php endif; ?>

<div class="danger-zone">
    <h2>Zone dangereuse</h2>
    <p>La suppression du compte est définitive. Vous serez immédiatement déconnecté.</p>

    <form method="POST" action="supprimer_compte.php" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
        <button type="submit" class="btn-danger">Supprimer mon compte</button>
    </form>
</div>

<?php include 'Includes/footer.php'; ?> 