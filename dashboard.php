<?php
require_once 'Includes/authentification.php';
include 'Includes/header.php';
?>

<h1>Dashboard</h1>
<p><?= $_SESSION['user_email'] ?></p> 
<div class="dashboard-links"> 
    <a class="card" href="produits.php"> 
        📦 Gérer les produits 
    </a> 
    <a class="card" href="clients.php"> 
        👥 Gérer les clients 
    </a> 
    <a class="card" href="ventes.php"> 
    🧾 Gérer les ventes 
</a> 
</div> 

<?php include 'Includes/footer.php'; ?> 