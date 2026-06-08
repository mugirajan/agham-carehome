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

    /* =========================================================
       ADMIN MAIL
    ========================================================= */

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@blackitechs.com';
    $mail->Password   = 'Contact@bits#737';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    /* 🔥 IMPORTANT ANTI-SPAM FIX */
    $mail->setFrom('contact@blackitechs.com', 'Agham Senior Care Home');
    $mail->Sender = 'contact@blackitechs.com';
    $mail->addCustomHeader('X-Auto-Response-Suppress', 'All');
    $mail->addCustomHeader('Precedence', 'bulk');

    $mail->addAddress('aghamseniorcare@gmail.com');

    /* ❌ FIX: DO NOT USE USER EMAIL AS REPLY-TO IN ADMIN MAIL */
    $mail->addReplyTo('contact@blackitechs.com', 'Agham Support Team');

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Priority = 1;
    $mail->XMailer   = 'Agham Mail System';

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

                    <tr><td style="padding:14px;background:#f8fafc;font-weight:bold;border:1px solid #e5e7eb;">Name</td><td style="padding:14px;border:1px solid #e5e7eb;">'.$name.'</td></tr>

                    <tr><td style="padding:14px;background:#f8fafc;font-weight:bold;">Email</td><td style="padding:14px;border:1px solid #e5e7eb;">'.$email.'</td></tr>

                    <tr><td style="padding:14px;background:#f8fafc;font-weight:bold;">Mobile</td><td style="padding:14px;border:1px solid #e5e7eb;">'.$phone.'</td></tr>

                    <tr><td style="padding:14px;background:#f8fafc;font-weight:bold;">Location</td><td style="padding:14px;border:1px solid #e5e7eb;">'.$location.'</td></tr>

                    <tr><td style="padding:14px;background:#f8fafc;font-weight:bold;">Service</td><td style="padding:14px;border:1px solid #e5e7eb;">'.$service.'</td></tr>

                    <tr><td style="padding:14px;background:#f8fafc;font-weight:bold;">Person</td><td style="padding:14px;border:1px solid #e5e7eb;">'.$person.'</td></tr>

                </table>

            </td>
        </tr>

    </table>

    </td>
    </tr>
    </table>

    </body>';

    $adminStatus = $mail->send();

    /* =========================================================
       USER CONFIRMATION MAIL
    ========================================================= */

    $userMail = new PHPMailer(true);

    $userMail->isSMTP();
    $userMail->Host       = 'smtp.hostinger.com';
    $userMail->SMTPAuth   = true;
    $userMail->Username   = 'contact@blackitechs.com';
    $userMail->Password   = 'Contact@bits#737';
    $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $userMail->Port       = 587;

    $userMail->setFrom('contact@blackitechs.com', 'Agham Senior Care Home');
    $userMail->addAddress($email);

    $userMail->isHTML(true);
    $userMail->CharSet = 'UTF-8';

    $userMail->Subject = 'Thank you for contacting Agham Senior Care Home';

   $userMail->Body = '

<div style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;background:#f4f6f9;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 5px 20px rgba(0,0,0,0.08);">

                    <!-- HEADER -->
                    <tr>
                        <td style="background:#10b981;padding:25px;text-align:center;color:#ffffff;">
                            <h1 style="margin:0;font-size:24px;">Agham Senior Care Home</h1>
                            <p style="margin:5px 0 0;font-size:14px;">Thank you for contacting us</p>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding:30px;color:#1f2937;">

                            <h2 style="margin-top:0;color:#111827;">Hi '.$name.' 👋</h2>

                            <p style="font-size:15px;line-height:1.6;">
                                We have successfully received your request.
                                Our team will contact you shortly.
                            </p>

                            <div style="margin-top:20px;padding:15px;background:#ecfdf5;border-left:4px solid #10b981;border-radius:6px;">
                                <b>Status:</b> Your enquiry is successfully submitted.
                            </div>

                            <!-- DETAILS BOX -->
                            <h3 style="margin-top:25px;">Your Details</h3>

                            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;border:1px solid #e5e7eb;">

                                <tr>
                                    <td style="background:#f9fafb;width:35%;font-weight:bold;">Name</td>
                                    <td>'.$name.'</td>
                                </tr>

                                <tr>
                                    <td style="background:#f9fafb;font-weight:bold;">Phone</td>
                                    <td>'.$phone.'</td>
                                </tr>

                                <tr>
                                    <td style="background:#f9fafb;font-weight:bold;">Location</td>
                                    <td>'.$location.'</td>
                                </tr>

                                <tr>
                                    <td style="background:#f9fafb;font-weight:bold;">Service</td>
                                    <td>'.$service.'</td>
                                </tr>

                                <tr>
                                    <td style="background:#f9fafb;font-weight:bold;">For</td>
                                    <td>'.$person.'</td>
                                </tr>

                            </table>

                            <p style="margin-top:25px;font-size:14px;color:#6b7280;">
                                We will contact you within 24 hours.<br>
                                If urgent, please call our support team.
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background:#f9fafb;text-align:center;padding:15px;font-size:12px;color:#6b7280;border-top:1px solid #e5e7eb;">
                            © '.date("Y").' Agham Senior Care Home. All Rights Reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</div>

';

    $userStatus = $userMail->send();

    /* =========================================================
       FINAL RESPONSE
    ========================================================= */

    if ($adminStatus && $userStatus) {
        echo json_encode([
            'status' => 1,
            'msg' => 'Success - Mail sent'
        ]);
    } else {
        echo json_encode([
            'status' => 0,
            'msg' => 'Mail failed'
        ]);
    }

} catch (Exception $e) {

    echo json_encode([
        'status' => 0,
        'msg' => $e->getMessage()
    ]);
}