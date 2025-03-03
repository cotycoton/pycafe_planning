

<?php
function generateMapping($data) {
    $prenomCounts = array();
    
    // Compter l'occurrence de chaque prénom (en minuscule)
    foreach ($data as $entry) {
        $prenom = strtolower($entry['prenom']);
        if (!isset($prenomCounts[$prenom])) {
            $prenomCounts[$prenom] = 0;
        }
        $prenomCounts[$prenom]++;
    }
    
    $mapping = array();
    $mapping_inv = array();
    
    // Générer le mapping
    foreach ($data as $entry) {
        $prenom = strtolower($entry['prenom']);
        $nom = strtolower($entry['nom']);
        $id = strtolower($entry['id']);
        
        if ($prenomCounts[$prenom] > 1) {
            $identifier = ucwords($prenom) . " " . ucwords(substr($nom, 0, 2)) . '.';
        } else {
            $identifier = ucwords($prenom);
        }
        
        //$mapping[$identifier] = ['nom' => $nom, 'prenom' => $prenom];
	$mapping[$id] = $identifier;
	$mapping_inv[$identifier] = $id;
    }
    
    return compact('mapping','mapping_inv');
}


function isFirstSaturdayOfMonth(string $dateStr) {
    //$date = new DateTime($dateStr);
    $date = DateTime::createFromFormat('d-m-Y', $dateStr);
    if ($date->format('N') != 6) {
        return 0; // Ce n'est pas un samedi
    }
    //echo "$dateStr";
    // Trouver le premier samedi du mois
    $firstSaturday = new DateTime($date->format('Y-m-01'));
    while ($firstSaturday->format('N') != 6) {
        $firstSaturday->modify('+1 day');
    }
    $res = ($date->format('Y-m-d') == $firstSaturday->format('Y-m-d'));
    //echo "<p>" . $date->format('Y-m-d') . "</p>";
    //echo "<p>" . $firstSaturday->format('Y-m-d') . "</p>";
    if ($res)
	    $res = 1;
    else
	    $res = 0;
    return $res;
}

// Exemples de tests
//$dates = ['2024-06-01', '2024-06-08', '2024-07-06', '2024-07-13'];
//foreach ($dates as $date) {
//    echo "$date : " . (isFirstSaturdayOfMonth($date) ? "? Premier samedi du mois" : "? Pas le premier samedi") . "\n";
//}

function getEventsByDate($date,$pdo_event) {
    //$sql = "SELECT * FROM events WHERE date_event = ?";
    $sql = "SELECT * FROM events WHERE date_event = :date_event";
    $stmt = $pdo_event->prepare($sql);
    $stmt->bindParam(':date_event', $date);
    //$stmt->bind_param("s", $date);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $events;
    $result = $stmt->get_result();
    //return print_r($stmt);
    $events = $result->fetch_all(MYSQLI_ASSOC);
    //echo json_encode($events);
}

?>
