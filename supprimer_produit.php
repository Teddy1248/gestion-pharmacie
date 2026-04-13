<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /produits.php');
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
$stmt->execute([$id]);

header('Location: /produits.php');
exit(); 