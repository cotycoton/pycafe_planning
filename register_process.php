
<?php
require 'database.php';
require 'send_mail.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    
    // Vérification de la validité du mot de passe
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
	    if (!preg_match('/^(?=.*[\W])([A-Za-z\d\W]{6,})$/', $password)) {
		    die("Le mot de passe doit contenir au moins 6 caractères, incluant chiffres et lettres. $password");
	    }
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Vérification si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        die("Cet email est déjà utilisé.");
    }
    
    // Insertion du nouvel utilisateur avec active = 0 (non activé)
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, phone, password, active) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->execute([$firstname, $lastname, $email, $phone, $hashed_password]);
    
    // Envoi d'un email à l'administrateur
    $admin_email = "epicafe.besayes@gmail.com";
    $subject = "Nouvelle inscription en attente de validation";
    $message = "Un nouvel utilisateur s'est inscrit :\n\n" .
               "Nom: $firstname $lastname\n" .
               "Email: $email\n" .
               "Téléphone: $phone\n\n" .
               "Veuillez activer ce compte via l'administration.";
    sendMail($admin_email, $subject, $message);
    
    echo "Inscription réussie ! Votre compte doit être validé par un administrateur.";
}
?>



