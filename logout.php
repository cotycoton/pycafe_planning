<?php
// Initialize the session
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

ini_set('session.cookie_secure', 1);       // HTTPS seulement
ini_set('session.cookie_httponly', 1);     // Inaccessible en JS
ini_set('session.cookie_samesite', 'Strict'); // Protection CSRF
session_start();
 
// Unset all of the session variables
$_SESSION = array();

// Supprimer le token de la base de données
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}


// Détruire la session
session_unset();
session_destroy();


// Supprimer le cookie
setcookie('remember_me', '', time() - 3600, '/');

// Redirect to login page
header("location: login2.php");
exit;
?>
