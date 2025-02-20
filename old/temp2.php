


<?php
require 'database.php';
require 'send_mail.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
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
        echo "Aucun compte actif trouvé avec cet email.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
</head>
<body>
    <form action="forgot_password.php" method="POST">
        <input type="email" name="email" placeholder="Votre email" required>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>

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

<?php
// admin_panel.php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $stmt = $pdo->prepare("UPDATE users SET active = 1 WHERE id = ?");
    $stmt->execute([$user_id]);
    echo "Compte activé avec succès.";
}

$users = $pdo->query("SELECT id, firstname, lastname, email, active FROM users WHERE active = 0")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Activation des Comptes</title>
</head>
<body>
    <h2>Utilisateurs en attente d'activation</h2>
    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <form method="POST" action="admin_panel.php">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit">Activer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>


