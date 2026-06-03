<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php_mail/vendor/autoload.php';

header('Content-Type: application/json');

try {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $service  = trim($_POST['service'] ?? '');
    $person   = trim($_POST['person'] ?? '');

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($location) ||
        empty($service) ||
        empty($person)
    ) {
        echo json_encode([
            'status' => 0,
            'msg' => 'Please fill all fields.'
        ]);
        exit;
    }

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@blackitechs.com';
    $mail->Password   = 'Contact@bits#737';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('contact@blackitechs.com', 'Agham Senior Care Home');
    $mail->addAddress('contact@blackitechs.com');
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'New Callback Request';

    $mail->Body = "
    <h2>New Callback Request</h2>

    <table border='1' cellpadding='10' cellspacing='0' width='100%'>
        <tr><td><strong>Name</strong></td><td>{$name}</td></tr>
        <tr><td><strong>Email</strong></td><td>{$email}</td></tr>
        <tr><td><strong>Phone</strong></td><td>{$phone}</td></tr>
        <tr><td><strong>Location</strong></td><td>{$location}</td></tr>
        <tr><td><strong>Service</strong></td><td>{$service}</td></tr>
        <tr><td><strong>Looking For</strong></td><td>{$person}</td></tr>
    </table>
    ";

    if ($mail->send()) {

        echo json_encode([
            'status' => 1,
            'msg' => 'Success'
        ]);

    } else {

        echo json_encode([
            'status' => 0,
            'msg' => 'Mail sending failed'
        ]);
    }

} catch (Exception $e) {

    echo json_encode([
        'status' => 0,
        'msg' => $e->getMessage()
    ]);
}
?>
