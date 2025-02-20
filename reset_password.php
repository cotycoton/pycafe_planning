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
    <title>Réinitialisation du mot de passe</title>
</head>
<body>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $_GET['token'] ?? ''; ?>">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <button type="submit">Réinitialiser</button>
    </form>
</body>
</html>


