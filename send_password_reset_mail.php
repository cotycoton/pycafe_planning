

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendPasswordResetEmail($email, $token) {
	$mail = new PHPMailer(true);
    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
        $mail->Username = 'epicafe.besayes@gmail.com'; // Remplacez par votre email
        $mail->Password = 'epic@fe2024!'; // Remplacez par votre mot de passe SMTP
        $mail->Username = 'julien.montagnier@gmail.com'; // Remplacez par votre email
        $mail->Password = 'cD680cY3'; // Remplacez par votre mot de passe SMTP
	
	$mail->Host = 'smtp-relay.brevo.com'; // Remplacez par votre serveur SMTP
        $mail->Username = 'epicafe.besayes@gmail.com'; // Remplacez par votre email
        $mail->Password = 'epic@fe2024!'; // Remplacez par votre mot de passe SMTP
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
        $mail->Subject = 'Réinitialisation de votre mot de passe';
	$mail->Body    = "<p>Cliquez sur le lien suivant pour réinitialiser votre mot de passe :</p>
			  <p>https://telegraphe-optifluides.fr/epicafe/planning/reset_password.php?token=$token</p>
                         <p><a href='https://telegraphe-optifluides.fr/epicafe/planning/reset_password.php?token=$token'>Réinitialiser le mot de passe</a></p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}



