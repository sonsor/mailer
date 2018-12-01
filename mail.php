<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

$email = $name = $subject = $body = '';
$errors = [];

$to = 'admin@mail.com';
$email = isset($_POST['email']) ? $_POST['email']: '';
$name = isset($_POST['name']) ? $_POST['name']: '';
$subject = isset($_POST['subject']) ? $_POST['subject']: '';
$body = isset($_POST['body']) ? $_POST['body']: '';

empty($email) ? $errors[] = 'email': '';
!filter_var($email, FILTER_VALIDATE_EMAIL) ? $errors[] = 'email': '';
empty($name) ? $errors[] = 'name': '';
empty($subject) ? $errors[] = 'subject': '';
empty($body) ? $errors[] = 'body': '';

if (count($errors) > 0) {
    echo json_encode([
        'status' => 'error',
        'data' => $errors
    ]);
    exit;
}

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP(false);                                      // Set mailer to use SMTP

    //Recipients
    $mail->setFrom($email, $name);
    $mail->addAddress($to);     // Add a recipient
    $mail->addReplyTo($email, $name);

    //Content
    //$mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}