<?php
// Paramètres de connexion
$host = "localhost";
$dbname = "EPICAFE_planning";
$username = "root"; // Modifier si nécessaire
$password = ""; // Modifier si nécessaire

try {
    // Connexion à la base de données
    $pdo_planning = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo_planning->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
/**
 * Fonction pour récupérer les réservations en fonction d'une date et d'une plage horaire.
 *
 * @param string $date  La date des réservations
 * @param string $plage La plage horaire
 * @param PDO $pdo_planning      L'instance de connexion PDO
 * @return array        Tableau contenant les réservations
 */
function getReservations($date, $plage, $pdo_planning) {
    $sql = "SELECT id, nom, prenom, commentaire, cowork, evenements 
            FROM reservations 
            WHERE date_reservation = :date AND plage_horaire = :plage";
    $stmt = $pdo_planning->prepare($sql);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':plage', $plage);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


