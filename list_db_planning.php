

<?php
function lister_tout($pdo) {
    $stmt = $pdo->query("SELECT * FROM data");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Affichage des données
$data = lister_tout($pdo);
echo "<pre>"; print_r($data); echo "</pre>";
?>


