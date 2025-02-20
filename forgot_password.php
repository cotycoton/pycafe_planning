
<?php
require 'database.php';
require 'send_password_reset_mail.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user) {
        $token = bin2hex(random_bytes(50));
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);
        
        if (sendPasswordResetEmail($email, $token)) {
            echo "Un email de réinitialisation a été envoyé.";
        } else {
            echo "Erreur lors de l'envoi de l'email.";
        }
    } else {
        echo "Aucun compte trouvé avec cet email.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('Epicafe_logo.png') no-repeat center center fixed;
            background-size: contain;
        }   
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white; /* Couleur blanche */
            opacity: 0.85; /* Ajuste la clarté (0 = invisible, 1 = opaque) */
            z-index: -1;
        }
       .login-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 8px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
	    text-align: center;
	    width: 100%;
            max-width: 350px;
        }
        a {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Mot de passe oublie</h2>
    <form action="forgot_password.php" method="POST">
        <input type="email" name="email" placeholder="Votre email" required>
        <button type="submit">Envoyer</button>
    </form>
    <a href="login2.php">Déjà un compte ? Connectez-vous</a>
    <a href="register2.php">Créer un compte</a>
    </div>
</body>
</html>

