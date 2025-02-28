<?php
function getOuverture($date, $plage_horaire) {
	$dbname = "gestion_coworking";
	$username = "root"; // À adapter selon votre configuration
	$password = ""; // À adapter selon votre configuration
	$servername = "localhost";
	// Connexion à la base de données
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Vérifier la connexion
	if ($conn->connect_error) {
		die(json_encode(["error" => "Échec de la connexion : " . $conn->connect_error]));
	}

	// Préparation de la requête
	$sql = "SELECT etat FROM ouverture WHERE date_reservation = ? AND plage_horaire = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ss", $date, $plage_horaire);
	$stmt->execute();
	$result = $stmt->get_result();

	// Récupération des données
	if ($row = $result->fetch_assoc()) {
		$etat = $row['etat'];
	} else {
		$etat = null; // Pas de réservation trouvée
	}

	// Fermeture de la connexion
	$stmt->close();
	$conn->close();

	return $etat;
}

// Vérifier si des paramètres sont passés via GET
if (isset($_GET['date']) && isset($_GET['plage_horaire'])) {
	header('Content-Type: application/json');
	echo json_encode(["etat" => getOuverture($_GET['date'], $_GET['plage_horaire'])]);
}
?>

