

<?php
function chercher_par_clef($pdo, $clef) {
    $stmt = $pdo->prepare("SELECT prenom, commentaire FROM data WHERE clef = ?");
    $stmt->execute([$clef]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Exemple : récupérer toutes les entrées pour la clé "123"
$result = chercher_par_clef($pdo, "123");

// Affichage sous forme de liste
echo "<ul>";
foreach ($result as $row) {
    echo "<li>Prénom: {$row['prenom']}, Commentaire: " . ($row['commentaire'] ?: "Aucun") . "</li>";
}
echo "</ul>";
?>


