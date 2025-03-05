<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "EPICAFE_events";

	try
	{
		$pdo_event = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
		$pdo_event->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
       	catch (PDOException $e) 
	{
		echo json_encode(["success" => false, "error" => $e->getMessage()]);
		exit;
	}

	$data = json_decode(file_get_contents("php://input"), true);
	//$data['heure_debut'] = $data['heure_debut'] . ":00";
	//$data['heure_fin'] = $data['heure_fin'] . ":00";
	//$data['date_event'] = DateTime::createFromFormat('d-m-Y', $data['date_event'])->format('Y-m-d');
	if (isset($data['id']))
	{
		$data['id'] = intval($data['id']);
		if ($data['id'] > 0) 
		{
			$sql = "UPDATE events SET nom = ?, date_event = ?, heure_debut = ?, heure_fin = ?, details = ?, ressources = ? , color = ? WHERE id = ?";
			$stmt = $pdo_event->prepare($sql);
			//$stmt->bind_param("ssssssi", $data['nom'], $data['date_event'], $data['heure_debut'], $data['heure_fin'], $data['details'], $data['ressources'], $data['id']);
			$stmt->execute([$data['nom'], $data['date_event'], $data['heure_debut'], $data['heure_fin'], $data['details'], $data['ressources'],$data['color'], $data['id']]);


			$rowCount = $stmt->rowCount();
			if ($rowCount > 0) {
				echo json_encode(["success" => true, "message" => "Événement mis à jour", "id" => $data['id']]);
			} else {
				echo json_encode(["success" => false, "error" => "Aucune modification", "rowCount" => $rowCount]);
			}
		}
		else
			echo json_encode(["success" => false, "error" => "Aucune modification, pb2"]);
	} else 
	{
		//$sql = "INSERT INTO events (nom, date_event, heure_debut, heure_fin, details, ressources) VALUES (?, ?, ?, ?, ?, ?)";
		//$stmt = $pdo_event->prepare($sql);
		//$stmt->bind_param("ssssss", $data['nom'], $data['date_event'], $data['heure_debut'], $data['heure_fin'], $data['details'], $data['ressources']);
		
		$sql = "INSERT INTO events (nom, date_event, heure_debut, heure_fin, details, color, ressources) VALUES (:nom, :date, :debut, :fin, :details, :color, :ressources)";
		$stmt = $pdo_event->prepare($sql);
		$stmt->bindParam(':nom', $data['nom']);
		$stmt->bindParam(':date', $data['date_event']);
		$stmt->bindParam(':debut', $data['heure_debut']);
		$stmt->bindParam(':fin', $data['heure_fin']);
		$stmt->bindParam(':details', $data['details']);
		$stmt->bindParam(':color', $data['color']);
		$stmt->bindParam(':ressources', $data['ressources']);

		$stmt->execute();

		$rowCount = $stmt->rowCount();
		if ($rowCount > 0) {
			$id = $pdo_event->lastInsertId(); // Récupère l'ID du nouvel enregistrement
			echo json_encode(["success" => true, "message" => "Événement créé", "id" => $id]);
			//echo json_encode(["success" => true, "message" => "Événement créé"]);
		} else {
			echo json_encode(["success" => false, "error" => "Insertion échouée", "affected_rows" => $rowCount]);
		}
	}
    
}
else {
    echo json_encode(["success" => false, "error" => "Données incomplètes"]);
}
?>

