

<?php
require 'database.php';
session_start();

// Vérification si l'administrateur est connecté
//if (!isset($_SESSION['admin_id'])) {
//    die("Accès refusé. Vous devez être administrateur pour voir cette page.");
//}

$stmt = $pdo->query("SELECT id, firstname, lastname, email, phone, active FROM users");
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
                        <a href="activate_user.php?id=<?php echo $user['id']; ?>">Activer</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>


