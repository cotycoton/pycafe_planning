<?php
// Paramètres de connexion
$host = "localhost";
$dbname = "gestion_coworking";
$username = "root"; // Modifier si nécessaire
$password = ""; // Modifier si nécessaire

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification des paramètres GET
    if (isset($_GET['date']) && isset($_GET['plage'])) {
        $date = $_GET['date'];
        $plage = $_GET['plage'];

        // Requête SQL pour récupérer les réservations
        $sql = "SELECT id, nom, prenom, commentaire, cowork FROM reservations WHERE date_reservation = :date AND plage_horaire = :plage";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':plage', $plage);
        $stmt->execute();

        // Récupération des résultats
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retour JSON
        header('Content-Type: application/json');
        echo json_encode($resultats);
    } else {
        echo json_encode(["error" => "Veuillez spécifier une date et une plage horaire"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
}
?>



