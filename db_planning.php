<?php
// Paramètres de connexion à la base de données
$host = "localhost";
$dbname = "EPICAFE_planning";
$username = "root"; // À adapter selon votre configuration
$password = ""; // À adapter selon votre configuration

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des paramètres GET
    if (isset($_GET['date']) && isset($_GET['plage'])) {
        $date = $_GET['date'];
        $plage = $_GET['plage'];

	echo "date : $date\n";
	echo "plage : $plage\n";
        // Requête SQL pour récupérer les données
        $sql = "SELECT id, nom, prenom, commentaire, cowork FROM reservations WHERE date_reservation = :date AND plage_horaire = :plage";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':plage', $plage);
        $stmt->execute();

        // Récupération des résultats
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retourner les résultats en JSON
	header('Content-Type: application/json');
	echo "resulats";
        echo json_encode($resultats);
    } else {
        echo json_encode(["error" => "Veuillez spécifier une date et une plage horaire"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
}
?>


