
<?php
function inserer($pdo, $clef, $prenom, $commentaire = null) {
    $stmt = $pdo->prepare("INSERT INTO data (clef, prenom, commentaire) VALUES (?, ?, ?)");
    return $stmt->execute([$clef, $prenom, $commentaire]);
}

// Exemple d'insertion
inserer($pdo, "123", "Alice", "Premier commentaire");
inserer($pdo, "123", "Bob");  // Pas de commentaire
inserer($pdo, "456", "Charlie", "Autre clé");
?>

