<?php
require 'database.php';
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

ini_set('session.cookie_secure', 1);       // HTTPS seulement
ini_set('session.cookie_httponly', 1);     // Inaccessible en JS
ini_set('session.cookie_samesite', 'Strict'); // Protection CSRF
session_start();

// Vérification si l'administrateur est connecté
// if (!isset($_SESSION['admin_id'])) {
//     die("Accès refusé. Vous devez être administrateur pour effectuer cette action.");
// }


// Vérification de l'ID utilisateur
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID utilisateur invalide.");
}

$user_id = (int) $_GET['id'];

// Mise à jour du statut de l'utilisateur
$stmt = $pdo->prepare("UPDATE users SET active = 0 WHERE id = ?");
if ($stmt->execute([$user_id])) {
    echo "Utilisateur désactivé avec succès.";
} else {
    echo "Erreur lors de l'activation de l'utilisateur.";
}

// Redirection vers la liste des utilisateurs
echo "<br><a href='admin_liste.php'>Retour à la liste des utilisateurs</a>";
?>
