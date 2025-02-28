
<?php
require 'database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: rgba(0, 0, 0, 0.05);
        }
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
            opacity: 0.3; /* Ajuste la clarté (0 = invisible, 1 = opaque) */
            z-index: -1;
        }
	h2
	{
		margin : 5px;
		margin-bottom:10px;
	}
        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
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
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            opacity: 0.5; /* Ajuste la clarté (0 = invisible, 1 = opaque) */
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        a {
            display: block;
            margin-top: 10px;
        }
    </style>
    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const error = document.getElementById('error');
            const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
            
            if (!regex.test(password)) {
                error.textContent = "Le mot de passe doit contenir au moins 6 caractères, incluant chiffres et lettres.";
                return false;
            }
            if (password !== confirmPassword) {
                error.textContent = "Les mots de passe ne correspondent pas.";
                return false;
            }
            error.textContent = "";
            return true;
        }
    </script>
</head>
<body>
    <div class="register-container">
        <h2>Inscription Planning</h2>
        <form action="register_process.php" method="POST" onsubmit="return validatePassword()">
            <input type="text" name="firstname" placeholder="Prénom" required>
            <input type="text" name="lastname" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="text" name="phone" placeholder="Numéro de téléphone" optional>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            <input type="password" id="confirm_password" placeholder="Confirmez le mot de passe" required>
            <p id="error" class="error"></p>
            <button type="submit">S'inscrire</button>
        </form>
        <a href="login2.php">Déjà un compte ? Connectez-vous</a>
    </div>
</body>
</html>


