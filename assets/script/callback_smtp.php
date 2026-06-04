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

    // Mail receiver
    $mail->addAddress('aghamseniorcare@gmail.com');

    // Reply-to user
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'New Callback Request - Agham Senior Care Home';

    $mail->Body = '

    <body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:30px 0;">
    <tr>
    <td align="center">

    <table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;">

        <tr>
            <td style="background:#e1b45a;padding:30px;text-align:center;">
                <h1 style="margin:0;color:#ffffff;font-size:28px;">
                    Agham Senior Care Home
                </h1>

                <p style="margin:10px 0 0;color:#d9f3f0;font-size:15px;">
                    New Callback Request Received
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding:30px;">

                <h2 style="margin-top:0;color:#1f2937;">
                    Contact Information
                </h2>

                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">

                    <tr>
                        <td style="padding:14px;background:#f8fafc;font-weight:bold;border:1px solid #e5e7eb;width:35%;">
                            Name
                        </td>
                        <td style="padding:14px;border:1px solid #e5e7eb;">
                            '.$name.'
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px;background:#f8fafc;font-weight:bold;border:1px solid #e5e7eb;">
                            Email
                        </td>
                        <td style="padding:14px;border:1px solid #e5e7eb;">
                            '.$email.'
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px;background:#f8fafc;font-weight:bold;border:1px solid #e5e7eb;">
                            Mobile Number
                        </td>
                        <td style="padding:14px;border:1px solid #e5e7eb;">
                            '.$phone.'
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px;background:#f8fafc;font-weight:bold;border:1px solid #e5e7eb;">
                            Location
                        </td>
                        <td style="padding:14px;border:1px solid #e5e7eb;">
                            '.$location.'
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px;background:#f8fafc;font-weight:bold;border:1px solid #e5e7eb;">
                            Service Required
                        </td>
                        <td style="padding:14px;border:1px solid #e5e7eb;">
                            '.$service.'
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px;background:#f8fafc;font-weight:bold;border:1px solid #e5e7eb;">
                            Looking For
                        </td>
                        <td style="padding:14px;border:1px solid #e5e7eb;">
                            '.$person.'
                        </td>
                    </tr>

                </table>

                <div style="margin-top:25px;padding:18px;background:#ecfdf5;border-left:4px solid #10b981;border-radius:6px;color:#065f46;">

                    A new callback enquiry has been submitted through the Agham Senior Care Home website.

                </div>

            </td>
        </tr>

        <tr>
            <td style="background:#f8fafc;padding:20px;text-align:center;border-top:1px solid #e5e7eb;">

                <p style="margin:0;color:#6b7280;font-size:13px;">
                    © '.date('Y').' Agham Senior Care Home. All Rights Reserved.
                </p>

            </td>
        </tr>

    </table>

    </td>
    </tr>
    </table>

    </body>';

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