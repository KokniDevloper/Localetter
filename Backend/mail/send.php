<?php
require_once '../auth/guard.php';
require_once '../../secret.php';

//Loading PHPMailer Files
require_once 'Exception.php';
require_once 'PHPMailer.php';
require_once 'SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents('php://input'), true);

$subject = trim($data['subject']);
$body = trim($data['body']);
$recipients = $data['recipients'];

$sent   = 0;
$failed = 0;
$errors = [];


foreach ($recipients as $email) {

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $MAIL_FROM;
        $mail->Password   = $MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($MAIL_FROM, $MAIL_NAME);
        $mail->addAddress($email);

        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        $sent++;
    } catch (Exception $e) {
        $failed++;
        $errors[] = $email . ': ' . $mail->ErrorInfo;
    }

    usleep(500000);
}

echo json_encode([
    'success' => true,
    'sent'    => $sent,
    'failed'  => $failed,
    'errors'  => $errors
]);
