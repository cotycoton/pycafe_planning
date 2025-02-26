
<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    echo json_encode(["isLoggedIn" => true]);
} else {
    echo json_encode(["isLoggedIn" => false]);
}
?>

