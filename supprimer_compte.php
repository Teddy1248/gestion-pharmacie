<?php
require_once 'Includes/authentification.php';
require_once 'Configuration/database.php';

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$userId]);

session_unset();
session_destroy();

session_start();
$_SESSION['message'] = "Votre compte a été supprimé.";

header('Location: login.php');
exit();
?> 