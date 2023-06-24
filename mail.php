<?php

use PHPMailer\PHPMailer\PHPMailer;

// use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function send_mail($recipient, $subject, $message)
{
  $mail = new PHPMailer();
  $mail->IsSMTP();

  $mail->SMTPDebug = 0;
  $mail->SMTPAuth = TRUE;
  $mail->SMTPSecure = "tls";
  $mail->Port = 587;
  $mail->Host = "smtp.gmail.com";
  $mail->Username = "janak123g@gmail.com";
  $mail->Password = "asndicebuckfqmhc";

  $mail->IsHTML(true);
  $mail->AddAddress($recipient, "user");
  $mail->SetFrom("medicalhealth@gmail.com", "Medical & Health");
  // $mail->AddReplyTo("reply-to-email", "reply-to-name");
  // $mail->AddCC("cc-recipient-email", "cc-recipient-name");
  $mail->Subject = $subject;
  $content = $message;

  $mail->MsgHTML($content);
  if (!$mail->Send()) {
    //echo "Error while sending Email.";
    return false;
  } else {
    //echo "Email sent successfully";
    return true;
  }
}