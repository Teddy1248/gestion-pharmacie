<?php
$host = 'localhost';
$dbname = 'site6861_gestion_pharmacie';
$username = 'site6861_gestion_pharmacie_user';
$password = 'gestion_pharmacie_user';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
} 