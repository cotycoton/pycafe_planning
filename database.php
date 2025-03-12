<?php
$host = 'localhost';
$dbname = 'EPICAFE_planning';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$host = "localhost";
$dbname = "EPICAFE_planning";
$username = "root"; // Modifier si nécessaire
$password = ""; // Modifier si nécessaire

try {
    // Connexion à la base de données
    $pdo_planning = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_planning->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "EPICAFE_planning";

try
{
	$pdo_event = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
	$pdo_event->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

//
//$conn = new mysqli($servername, $username, $password, $dbname);
//if ($conn->connect_error) {
//    die("Connexion échouée: " . $conn->connect_error);
//}
//

?>

