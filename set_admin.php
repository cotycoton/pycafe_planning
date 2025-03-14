
<?php
require 'database.php';
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
$stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
if ($stmt->execute([$user_id])) {
    echo "Utilisateur avec maintenant un role admin.";
} else {
    echo "Erreur lors de l'activation de l'utilisateur comme administrateur.";
}

// Redirection vers la liste des utilisateurs
echo "<br><a href='admin_liste.php'>Retour à la liste des utilisateurs</a>";
?>
