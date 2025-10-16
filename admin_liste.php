

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

// Vérification si l'administrateur est connecté
//if (!isset($_SESSION['admin_id'])) {
//    die("Accès refusé. Vous devez être administrateur pour voir cette page.");
//}

$stmt = $pdo->query("SELECT id, firstname, lastname, email, phone, active , role FROM users");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
</head>
<body>
    <h2>Liste des utilisateurs</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Statut</th>
            <th>Actions</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                <td><?php echo $user['active'] ? 'Actif' : 'Inactif'; ?></td>
                <td>
                    <?php if (!$user['active']): ?>
                        <a href="activate_user.php?id=<?php echo $user['id']; ?>">activer</a>
                    <?php endif; ?>
                    <?php if ($user['active']): ?>
                        <a href="deactivate_user.php?id=<?php echo $user['id']; ?>">désactiver</a>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <?php if ($user['role']=='user'): ?>
                        <a href="set_admin.php?id=<?php echo $user['id']; ?>">set admin</a>
                    <?php endif; ?>
                    <?php if ($user['role']=='admin'): ?>
                        <a href="set_user.php?id=<?php echo $user['id']; ?>">set user</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>


