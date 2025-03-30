

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendPasswordResetEmail($email, $token) {
	$mail = new PHPMailer(true);
	$mail->CharSet = 'UTF-8';
	try {

        // Configuration du serveur SMTP
        $mail->isSMTP();
	
	$mail->Host = 'smtp-relay.brevo.com'; // Remplacez par votre serveur SMTP
        $mail->Username = '8626ec001@smtp-brevo.com'; // Remplacez par votre email
        $mail->Password = 'nGkz8EDJIFSdZP5b'; // Remplacez par votre mot de passe SMTP
	
        $mail->SMTPAuth = true;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinataire
        $mail->setFrom('epicafe.besayes@gmail.com', 'Support');
        $mail->addAddress($email);

        // Contenu de l'email
        $mail->isHTML(true);
	$subject = 'Réinitialisation mot de passe planning EPICAFE';
	$mail->Subject = "=?UTF-8?B?" . base64_encode($subject) . "?=\r\n";

	$mail->Body    = "<p>Cliquez sur le lien suivant pour r&eacute;initialiser votre mot de passe :</p>
                         <p><a href='https://www.telegraphe-optifluides.fr/planning-epicafe/reset_password.php?token=$token'>R&eacute;initialiser le mot de passe</a></p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}



