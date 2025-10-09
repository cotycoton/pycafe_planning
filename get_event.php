<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "EPICAFE_planning";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connexion échouée"]));
}

header("Content-Type: application/json");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($event = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "event" => $event], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["success" => false, "error" => "Aucun événement trouvé"]);
    }
}
else if (isset($_GET['date']) ) {
    $date = $_GET['date'];
    $sql = "SELECT * FROM events WHERE date_event = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = $result->fetch_all(MYSQLI_ASSOC);
    if ($events) {
        echo json_encode(["success" => true, "events" => $events], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["success" => false, "error" => "Aucun événement trouvé"]);
    }
}
else {
    echo json_encode(["success" => false, "error" => "ID invalide"]);
}
?>

