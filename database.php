<?php
$host = 'localhost';
$dbname = 'EPICAFE_LOGIN';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$host = "localhost";
$dbname = "gestion_coworking";
$username = "root"; // Modifier si nécessaire
$password = ""; // Modifier si nécessaire

try {
    // Connexion à la base de données
    $pdo_planning = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_planning->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>

