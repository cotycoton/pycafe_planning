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

#if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
#    die('Token CSRF invalide');
#}


// VÉRIFICATION CSRF OBLIGATOIRE
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
    http_response_code(403);
    die('Erreur de sécurité : Token CSRF manquant');
}

if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die('Erreur de sécurité : Token CSRF invalide');
}

// DÉTRUIRE le token après usage (important !)
unset($_SESSION['csrf_token']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $rememberMe = isset($_POST['remember_me']);
    // Vérification si l'utilisateur existe et est actif
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    // Vérification du mot de passe original
    $isPasswordValid = password_verify($password, $user['password']);
    // Vérification du mot de passe avec la première lettre en minuscule
    $passwordLowerFirst = lcfirst($password);
    $isPasswordLowerValid = password_verify($passwordLowerFirst, $user['password']);

    //if ($user && password_verify($password, $user['password'])) {
    if ($user && ($isPasswordValid || $isPasswordLowerValid)) {

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


