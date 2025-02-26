<?php
require 'database.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $rememberMe = isset($_POST['remember_me']);
    // Vérification si l'utilisateur existe et est actif
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
	$_SESSION['user_abb'] = $user['firstname'] . ' '  . ucfirst(substr($user['lastname'], 0, 2)) . '.';


        if ($rememberMe) {
            // Générer un token unique
            $token = bin2hex(random_bytes(16));
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $user['id']]);

            // Créer un cookie sécurisé avec le token
            setcookie('remember_me', $token, [
                'expires' => time() + (86400 * 30), // 30 jours
                'path' => '/',
                'domain' => '', // Spécifiez votre domaine si nécessaire
                'secure' => true, // true si vous utilisez HTTPS
                'httponly' => false,
                'samesite' => 'Strict',
            ]);
	    echo "Sauvegarde cookie ...";
        }

        echo "Connexion réussie. Redirection en cours...";
        header("Refresh:2; url=planning.php"); // Redirection après connexion
    } else {
        echo "Email ou mot de passe incorrect, ou compte non activé.";
    }
}
?>


