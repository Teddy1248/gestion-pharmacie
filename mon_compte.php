<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$stmt = $pdo->prepare("SELECT email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

include 'Includes/header.php';
?>

<span class="eyebrow">Profil</span>
<h1>Mon compte</h1>
<p class="page-intro">Consultez les informations liées à votre accès PharmaGestion.</p>

<?php if ($user): ?>
    <div class="account-card">
        <div class="account-item"><span>Adresse email</span><strong><?= htmlspecialchars($user['email']) ?></strong></div>
        <div class="account-item"><span>Compte créé le</span><strong><?= htmlspecialchars($user['created_at']) ?></strong></div>
    </div>
<?php endif; ?>

<div class="danger-zone">
    <h2>Zone sensible</h2>
    <p>La suppression du compte est définitive et entraîne une déconnexion immédiate.</p>
    <form method="POST" action="supprimer_compte.php" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
        <button type="submit" class="btn-danger">Supprimer mon compte</button>
    </form>
</div>

<?php include 'Includes/footer.php'; ?>
