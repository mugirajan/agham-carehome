<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php_mail/vendor/autoload.php';

header('Content-Type: application/json');

/* -----------------------------------------
   EMAIL SETTINGS
----------------------------------------- */

$dzEmailTo   = "aghamseniorcare@gmail.com"; // ADMIN EMAIL
$dzEmailFrom = "Agham Senior Care Home";

/* -----------------------------------------
   SMTP MAIL FUNCTION (CLEAN + INBOX SAFE)
----------------------------------------- */

function smtp_mail($to, $subject, $message, $replyTo = '')
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;

        $mail->Username   = 'contact@blackitechs.com';
        $mail->Password   = 'Contact@bits#737';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        /* -----------------------------------------
           IMPORTANT INBOX FIXES
        ----------------------------------------- */

        $mail->setFrom('contact@blackitechs.com', 'Agham Senior Care Home');

        $mail->addAddress($to);

        if (!empty($replyTo) && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($replyTo);
        }

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Spam reduction headers
        $mail->Priority = 1;
        $mail->XMailer   = 'Agham Mail System';

        $mail->Subject = $subject;
        $mail->Body    = $message;

        return $mail->send();

    } catch (Exception $e) {
        return false;
    }
}


/* -----------------------------------------
   FORM SUBMIT
----------------------------------------- */

try {

    if (!empty($_POST)) {

        $dzRes = array(
            'status' => 0,
            'msg' => 'Something went wrong.'
        );

        if ($_POST['dzToDo'] == 'Contact') {

            $error = false;

            $dzName        = trim($_POST['dzName'] ?? '');
            $dzPhoneNumber = trim($_POST['dzPhoneNumber'] ?? '');
            $dzEmail       = trim($_POST['dzEmail'] ?? '');
            $dzCity        = trim($_POST['dzCity'] ?? '');
            $dzService     = trim($_POST['dzService'] ?? '');
            $dzAge         = trim($_POST['dzAge'] ?? '');
            $dzMessage     = trim($_POST['dzMessage'] ?? '');

            /* -----------------------------------------
               VALIDATION
            ----------------------------------------- */

            if (empty($dzName)) {
                $error = true;
                $msg = 'Please enter full name.';
            }
            else if (!filter_var($dzEmail, FILTER_VALIDATE_EMAIL)) {
                $error = true;
                $msg = 'Invalid email format.';
            }
            else if (empty($dzPhoneNumber)) {
                $error = true;
                $msg = 'Please enter mobile number.';
            }
            else if (!preg_match('/^[0-9]{10}$/', $dzPhoneNumber)) {
                $error = true;
                $msg = 'Invalid mobile number.';
            }
            else if (empty($dzCity)) {
                $error = true;
                $msg = 'Please enter city.';
            }
            else if (empty($dzService)) {
                $error = true;
                $msg = 'Please select service.';
            }
            else if (empty($dzAge)) {
                $error = true;
                $msg = 'Please enter age.';
            }
            else if (empty($dzMessage)) {
                $error = true;
                $msg = 'Please enter message.';
            }

            if ($error) {
                $dzRes['msg'] = $msg;
                echo json_encode($dzRes);
                exit;
            }

            /* -----------------------------------------
               ADMIN MAIL (IMPORTANT FIXED FORMAT)
            ----------------------------------------- */

            $adminSubject = "New Enquiry - Agham Senior Care Home";

         $adminMessage = "
<div style='font-family: Arial, sans-serif; background:#f4f6f9; padding:20px;'>

    <div style='max-width:600px;margin:auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #eee;'>

        <div style='background:#0d6efd;color:#fff;padding:15px;text-align:center;font-size:18px;font-weight:bold;'>
            New Enquiry Received
        </div>

        <div style='padding:20px;'>

            <p style='font-size:14px;color:#333;'>
                You have received a new enquiry from your website.
            </p>

            <table style='width:100%;border-collapse:collapse;font-size:14px;'>
                
                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Name</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzName</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Phone</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzPhoneNumber</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Email</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzEmail</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>City</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzCity</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Service</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzService</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Age</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzAge</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Message</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzMessage</td>
                </tr>

            </table>

            <p style='margin-top:20px;font-size:12px;color:#777;'>
                Reply directly to this email if needed.
            </p>

        </div>

        <div style='background:#f1f1f1;padding:10px;text-align:center;font-size:12px;color:#777;'>
            Agham Senior Care Home
        </div>

    </div>

</div>";

            $adminMail = smtp_mail(
                $dzEmailTo,
                $adminSubject,
                $adminMessage,
                '' // ❌ IMPORTANT: no user email here (prevents spam trigger)
            );

            /* -----------------------------------------
               USER CONFIRMATION MAIL
            ----------------------------------------- */

            $userSubject = "Thank you for contacting Agham Senior Care Home";

         $userMessage = "
<div style='font-family: Arial, sans-serif; background:#f4f6f9; padding:20px;'>

    <div style='max-width:600px;margin:auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #eee;'>

        <div style='background:#28a745;color:#fff;padding:15px;text-align:center;font-size:18px;font-weight:bold;'>
            Thank You for Contacting Us
        </div>

        <div style='padding:20px;'>

            <h3 style='color:#333;'>Hi $dzName 👋</h3>

            <p style='font-size:14px;color:#555;'>
                We have received your enquiry successfully. Our team will contact you soon.
            </p>

            <table style='width:100%;border-collapse:collapse;font-size:14px;margin-top:10px;'>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Name</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzName</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Phone</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzPhoneNumber</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Email</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzEmail</td>
                </tr>

                <tr>
                    <td style='padding:10px;border:1px solid #eee;background:#f9f9f9;font-weight:bold;'>Service</td>
                    <td style='padding:10px;border:1px solid #eee;'>$dzService</td>
                </tr>

            </table>

            <p style='margin-top:20px;font-size:13px;color:#777;'>
                Regards,<br>
                <b>Agham Senior Care Home</b>
            </p>

        </div>

        <div style='background:#f1f1f1;padding:10px;text-align:center;font-size:12px;color:#777;'>
            We will contact you shortly
        </div>

    </div>

</div>";

            $userMail = smtp_mail(
                $dzEmail,
                $userSubject,
                $userMessage,
                'contact@blackitechs.com'
            );

            /* -----------------------------------------
               FINAL RESPONSE
            ----------------------------------------- */

            if ($adminMail && $userMail) {
                $dzRes['status'] = 1;
                $dzRes['msg'] = 'Enquiry submitted successfully.';
            } else {
                $dzRes['msg'] = 'Mail sending failed.';
            }

            echo json_encode($dzRes);
            exit;
        }
    }

} catch (Exception $e) {

    echo json_encode([
        'status' => 0,
        'msg' => $e->getMessage()
    ]);

    exit;
}

?>