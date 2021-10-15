<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Config;

class Mail
{
	public static function send($to, $subject, $html, $text)
    {
		$mail = new PHPMailer(true);                     
		$mail->isSMTP();                                            
		$mail->Host       = 'mail.karolwegrzyn.pl';                     
		$mail->SMTPAuth   = true;                                   
		$mail->Username   = 'karol@karolwegrzyn.pl';                     
		$mail->Password   = Config::MAIL_PASSWORD;                               
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
		$mail->Port       = 465;                                    

		
		$mail->setFrom('karol@karolwegrzyn.pl', 'Mailer');
		$mail->addAddress($to);              


		
		$mail->isHTML(true);                                  
		$mail->Subject = $subject;
		$mail->Body    = $html;
		$mail->AltBody = $text;

		$mail->send();
	}
}