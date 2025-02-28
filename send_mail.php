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
	
	$mail->Host = 'smtp-relay.brevo.com'; // Remplacez par votre serveur SMTP
        $mail->Username = '8626ec001@smtp-brevo.com'; // Remplacez par votre email
        $mail->Password = 'nGkz8EDJIFSdZP5b'; // Remplacez par votre mot de passe SMTP
	
	$mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;


        
        // Destinataires
        $mail->setFrom('epicafe.besayes@gmail.com', 'Support');
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



