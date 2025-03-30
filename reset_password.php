<?php
// reset_password.php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['password'];
    
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $new_password)) {
        die("Le mot de passe doit contenir au moins 6 caractères, incluant chiffres et lettres.");
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
        $stmt->execute([$hashed_password, $token]);
        echo "Mot de passe mis à jour avec succès.";
    } else {
        echo "Token invalide.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe planning EPICAFE</title>
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
	h2
	{
		margin : 5px;
		margin-bottom:10px;
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
            margin: 5px 0;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
	}
	
	.div_label
	{
		margin:5px;
		display:inline-block;
		width:90%;
	}
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            margin: 10px 15px;
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
        <h2>planning EPICAFE</h2>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $_GET['token'] ?? ''; ?>">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <button type="submit">Réinitialiser</button>
    </form>
</div>
</body>
</html>


