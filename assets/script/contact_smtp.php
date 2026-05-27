<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php_mail/vendor/autoload.php';

header('Content-Type: application/json');

/* -----------------------------------------
   EMAIL SETTINGS
----------------------------------------- */

$dzEmailTo   = "contact@blackitechs.com";
$dzEmailFrom = "Agham Senior Care Home";

/* -----------------------------------------
   SMTP MAIL FUNCTION
----------------------------------------- */

function smtp_mail($dzEmailTo, $dzEmailFrom, $dzEmail, $dzMailSubject, $dzMailMessage)
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

        // FROM
        $mail->setFrom('contact@blackitechs.com', $dzEmailFrom);

        // TO
        $mail->addAddress($dzEmailTo);

        // REPLY TO USER
        $mail->addReplyTo($dzEmail);

        // HTML FORMAT
        $mail->isHTML(true);

        $mail->Subject = $dzMailSubject;

        $mail->Body = $dzMailMessage;

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

            $dzName        = !empty($_POST['dzName']) ? trim(strip_tags($_POST['dzName'])) : '';

            $dzPhoneNumber = !empty($_POST['dzPhoneNumber']) ? trim(strip_tags($_POST['dzPhoneNumber'])) : '';

            $dzEmail       = !empty($_POST['dzEmail']) ? trim(strip_tags($_POST['dzEmail'])) : '';

            $dzCity        = !empty($_POST['dzCity']) ? trim(strip_tags($_POST['dzCity'])) : '';

            $dzService     = !empty($_POST['dzService']) ? trim(strip_tags($_POST['dzService'])) : '';

            $dzAge         = !empty($_POST['dzAge']) ? trim(strip_tags($_POST['dzAge'])) : '';

            $dzMessage     = !empty($_POST['dzMessage']) ? trim(strip_tags($_POST['dzMessage'])) : '';


            /* -----------------------------------------
               VALIDATION
            ----------------------------------------- */

            if (empty($dzName)) {

                $error = true;
                $msg = 'Please enter full name.';
            }

            else if (!isAlphabetic($dzName)) {

                $error = true;
                $msg = 'Name should contain only alphabets.';
            }

            else if (empty($dzPhoneNumber)) {

                $error = true;
                $msg = 'Please enter mobile number.';
            }

            else if (!isValidPhonenumber($dzPhoneNumber)) {

                $error = true;
                $msg = 'Please enter valid mobile number.';
            }

            else if (empty($dzEmail)) {

                $error = true;
                $msg = 'Please enter email address.';
            }

            else if (!filter_var($dzEmail, FILTER_VALIDATE_EMAIL)) {

                $error = true;
                $msg = 'Invalid email format.';
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

                $dzRes['status'] = 0;
                $dzRes['msg'] = $msg;

                echo json_encode($dzRes);
                exit;
            }


            /* -----------------------------------------
               MAIL SUBJECT
            ----------------------------------------- */

            $dzMailSubject = "New Enquiry From Agham Senior Care Home Website";


            /* -----------------------------------------
               MAIL BODY
            ----------------------------------------- */

            $dzMailMessage = "

            <h2>New Enquiry Details</h2>

            <table border='1' cellpadding='10' cellspacing='0' width='100%'>

                <tr>
                    <td><strong>Full Name</strong></td>
                    <td>$dzName</td>
                </tr>

                <tr>
                    <td><strong>Mobile Number</strong></td>
                    <td>$dzPhoneNumber</td>
                </tr>

                <tr>
                    <td><strong>Email Address</strong></td>
                    <td>$dzEmail</td>
                </tr>

                <tr>
                    <td><strong>City</strong></td>
                    <td>$dzCity</td>
                </tr>

                <tr>
                    <td><strong>Service</strong></td>
                    <td>$dzService</td>
                </tr>

                <tr>
                    <td><strong>Age</strong></td>
                    <td>$dzAge</td>
                </tr>

                <tr>
                    <td><strong>Message</strong></td>
                    <td>$dzMessage</td>
                </tr>

            </table>
            ";


            /* -----------------------------------------
               SEND MAIL
            ----------------------------------------- */

            $res = smtp_mail(
                $dzEmailTo,
                $dzEmailFrom,
                $dzEmail,
                $dzMailSubject,
                $dzMailMessage
            );


            if ($res) {

                $dzRes['status'] = 1;

                $dzRes['msg'] = 'Enquiry submitted successfully.';
            }

            else {

                $dzRes['status'] = 0;

                $dzRes['msg'] = 'Unable to send enquiry.';
            }

            echo json_encode($dzRes);

            exit;
        }
    }

} catch (\Exception $e) {

    $dzRes['status'] = 0;

    $dzRes['msg'] = $e->getMessage();

    echo json_encode($dzRes);

    exit;
}


/* -----------------------------------------
   NAME VALIDATION
----------------------------------------- */

function isAlphabetic($data)
{
    if (!preg_match('/[^A-Za-z ]/', $data)) {

        return true;

    } else {

        return false;
    }
}


/* -----------------------------------------
   PHONE VALIDATION
----------------------------------------- */

function isValidPhonenumber($phone_number)
{
    if (preg_match('/^[0-9]{10}+$/', $phone_number)) {

        return true;

    } else {

        return false;
    }
}

?>
