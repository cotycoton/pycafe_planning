

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



