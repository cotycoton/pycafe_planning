<?php



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!file_exists('vendor/autoload.php')) {
    die("Erreur : Le fichier vendor/autoload.php est introuvable. Exécutez 'composer install'.");
}
require 'vendor/autoload.php';

function sendMail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuration du serveur SMTP Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'epicerie.besayes@gmail.com'; // Remplacez par votre email Gmail
        $mail->Password = 'epic@fe2024!'; // Remplacez par votre mot de passe Gmail ou App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Destinataires
        $mail->setFrom('epicerie.besayes@gmail.com', 'support planning');
        $mail->addAddress($to);
        
        // Contenu du mail
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($message);
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>



