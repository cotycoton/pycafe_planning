<?php
require 'database.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Vérification si l'utilisateur existe et est actif
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
        $_SESSION['user_abb'] = $user['firstname'] . ' '  . ucfirst(substr($user['lastname'], 0, 2)) . '.';
        echo "Connexion réussie. Redirection en cours...";
        header("Refresh:2; url=planning.php"); // Redirection après connexion
    } else {
        echo "Email ou mot de passe incorrect, ou compte non activé.";
    }
}
?>


