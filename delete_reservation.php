<?php
// Paramètres de connexion
$host = "localhost";
$dbname = "EPICAFE_planning";
$username = "root"; // Modifier si nécessaire
$password = ""; // Modifier si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Erreur de connexion : " . $e->getMessage()]));
}

// Vérification des paramètres
if (!isset($_POST['date']) || !isset($_POST['plage']) || !isset($_POST['id'])) {
    die(json_encode(["success" => false, "message" => "Données manquantes"]));
}

$date = $_POST['date'];
$plage = $_POST['plage'];
$id = $_POST['id'];

// Suppression dans la base de données
$sql = "DELETE FROM reservations WHERE date_reservation = :date AND plage_horaire = :plage AND id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':plage', $plage);
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Réservation supprimée"]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la suppression"]);
}
?>

