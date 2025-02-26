<?php
session_start();
require 'database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: planning.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
            opacity: 0.7; /* Ajuste la clarté (0 = invisible, 1 = opaque) */
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
        input {
            display: block;
	    width: 90%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        a {
            display: block;
            margin-top: 10px;
        }


    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion Planning</h2>
    	<form action="login_process.php" method="POST">
        	<input type="text" name="email" placeholder="Email" required><br>
        	<input type="password" id="password" name="password" placeholder="Mot de passe" required>
        	<br>
        	<input type="checkbox" id="showPassword">
		<label for="showPassword">Afficher le mot de passe</label>
                <label>
                    <input type="checkbox" name="remember_me"> Se souvenir de moi
                 </label>
        	<br>
        	<button type="submit">Se connecter</button>
    	</form>
    <script>
        document.getElementById("showPassword").addEventListener("change", function() {
            let passwordField = document.getElementById("password");
            passwordField.type = this.checked ? "text" : "password";
        });
    </script>


        <a href="forgot_password.php">Mot de passe oublié ?</a>
        <a href="register2.php">Créer un compte</a>
    </div>
</body>
</html>


