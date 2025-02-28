<?php
$host = "localhost";
$dbname = "gestion_coworking";
$username = "root"; // À adapter selon votre configuration
$password = ""; // À adapter selon votre configuration

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Échec de la connexion : " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$date = $data['date'];
$plage_horaire = $data['plage_horaire'];
$etat = $data['etat'];
$sql = "INSERT INTO ouverture (date_reservation, plage_horaire, etat)
	VALUES (?, ?, ?)
	ON DUPLICATE KEY UPDATE etat = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $date, $plage_horaire, $etat, $etat);

if ($stmt->execute()) {
	echo json_encode(["message" => "Enregistrement réussi"]);
} else {
	echo json_encode(["message" => "Erreur: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

