
<?php
function supprimer($pdo, $clef, $prenom) {
    $stmt = $pdo->prepare("DELETE FROM data WHERE clef = ? AND prenom = ?");
    return $stmt->execute([$clef, $prenom]);
}

// Exemple : supprimer Alice de la clé "123"
supprimer($pdo, "123", "Alice");
?>


