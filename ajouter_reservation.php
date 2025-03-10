<?php
// Paramètres de connexion à la base de données
$host = "localhost";
$dbname = "gestion_coworking";
$username = "root"; // Adapter selon votre configuration
$password = ""; // Adapter selon votre configuration

try {
    // Connexion à la base de données avec PDO
    $pdo_planning = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_planning->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données envoyées en JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Vérification des champs requis
    if (isset($data['date'], $data['plage'], $data['id'], $data['nom'], $data['prenom'], $data['cowork'], $data['commentaire'], $data['events'])) {
        $date = $data['date'];
        $plage = $data['plage'];
        $id = $data['id'];
        $nom = $data['nom'];
	$prenom = $data['prenom'];
	$events = $data['events'];
        $cowork = filter_var($data['cowork'], FILTER_VALIDATE_BOOLEAN); // Convertit en booléen
        $commentaire = $data['commentaire'];

        // Vérification que l'ID n'existe pas déjà pour cette date et plage horaire
        $checkSql = "SELECT COUNT(*) FROM reservations WHERE date_reservation = :date AND plage_horaire = :plage AND id = :id";
        $checkStmt = $pdo_planning->prepare($checkSql);
        $checkStmt->bindParam(':date', $date);
        $checkStmt->bindParam(':plage', $plage);
        $checkStmt->bindParam(':id', $id);
        $checkStmt->execute();
        $exists = $checkStmt->fetchColumn();

        if ($exists) {
            echo json_encode(["error" => "L'ID existe déjà pour cette date et plage horaire"]);
            exit;
        }

        // Requête d'insertion SQL
        $sql = "INSERT INTO reservations (date_reservation, plage_horaire, id, nom, prenom, commentaire, cowork, evenements) 
                VALUES (:date, :plage, :id, :nom, :prenom, :commentaire, :cowork, :evenements)";
        //$sql = "INSERT INTO reservations (date_reservation, plage_horaire, id, nom, prenom, commentaire, cowork) 
        //        VALUES (:date, :plage, :id, :nom, :prenom, :commentaire, :cowork )";

        $stmt = $pdo_planning->prepare($sql);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':plage', $plage);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':cowork', $cowork, PDO::PARAM_BOOL);
        $stmt->bindValue(':evenements', $events, PDO::PARAM_STR);

        // Exécution et retour JSON
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Échec de l'insertion"]);
        }
    } else {
        echo json_encode(["error" => "Données incomplètes"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
}
?>

