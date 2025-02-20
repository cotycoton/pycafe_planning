

<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'votre_base';
$user = 'votre_utilisateur';
$password = 'votre_mot_de_passe';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données envoyées
    $cellule = $_POST['cellule'];
    $contenu = $_POST['contenu'];

    // Insertion ou mise à jour dans la base
    $stmt = $pdo->prepare("INSERT INTO tableau_etat (cellule, contenu) VALUES (:cellule, :contenu)
                           ON DUPLICATE KEY UPDATE contenu = :contenu");
    $stmt->execute(['cellule' => $cellule, 'contenu' => $contenu]);

    echo "État sauvegardé avec succès.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

