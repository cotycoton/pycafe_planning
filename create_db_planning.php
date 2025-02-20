

<?php
$pdo = new PDO("mysql:host=localhost", "root", "mot_de_passe");

// Créer la base de données
$pdo->exec("CREATE DATABASE IF NOT EXISTS ma_base");
$pdo->exec("USE ma_base");

// Créer la table
$pdo->exec("CREATE TABLE IF NOT EXISTS data (
    clef VARCHAR(255) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    commentaire TEXT DEFAULT NULL,
    PRIMARY KEY (clef, prenom)
)");

echo "Base de données et table créées avec succès !";

?>
