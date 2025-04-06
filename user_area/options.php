<?php
//Mailer files
require "../PHPMailer/src/Exception.php";
require "../PHPMailer/src/PHPMailer.php";
require "../PHPMailer/src/SMTP.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


require "../config/mail_config.php"; 

include ("../config/connection.php");
include ("../functions/common_functions.php");//after the Mailer files

function sendUserEmail($user_email, $subject, $message) {

    // $subject = "Your new username";
    // $message = nl2br("Hello,\n\nYour username is: $username

    // Please save this username because you will need it to login. And DO NOT SHARE IT with anyone.

    // Thank you for registering.");


    //create a php mailer object
    $mail = new PHPMailer(TRUE);

    //use the smtp to send the msg
    $mail->isSMTP();

    // use gmail login details to send the email
    $mail->SMTPAuth = true;

    $mail->Host = MAIL_HOST;

    $mail->Username = USERNAME;

    $mail->Password = PASSWORD;

    // Using STARTTLS encryption when sending a msg
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    // setting the TCP port to 587 to connect with the Gmail SMTP server
    $mail->Port = 587;

    // Who is sending the email
    $mail->setFrom(SEND_FROM, SEND_FROM_NAME);

    // Where the mail goes
    $mail->addAddress($user_email);


    //The 'addReplyTo' property specifies where the recipient can reply to.
    $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);

    $mail->isHTML = true;

    //incoming subject
    $mail->Subject = $subject;

    //incoming msg
    $mail->Body = $message;

    //if the html content not supported, so it displays the msg as plain text
    $mail->AltBody = $message;


    return $mail->send();
}



if(!isset($_GET['action'])){
    if(!isset($_GET['action'])){
        echo "<script>alert('Error occured! Try again.')</script>";
        header("location: user_login.php");
        exit();
    }
}



$action = isset($_GET['action']) ? $_GET['action'] : '';

$first_name = $_COOKIE['first_name'];
$email = $_COOKIE['verified_email'];
$username = $_COOKIE['verified_username'];
$phone = $_COOKIE['phone'];


if ($action == 'forget_username') {
    if (isset($_POST['send_email'])) {
        // Send username via email
    
        $subject = "Your username";
        $message = nl2br("Hello $first_name,\n\nYour username is: $username

        Please save this username because you will need it to login. And DO NOT SHARE IT with anyone.");

        $mail_sent = sendUserEmail($email, $subject, $message);
 
    } elseif (isset($_POST['send_sms'])) {
        // Send username via SMS

        $message = nl2br("Hello {$first_name}, your username is {$username}.
                        Save it in a safe local.");

        $sms_sent = sendUserSMS($phone, $message);

        echo "<script>alert('Your username has been sent to your phone.')</script>";
    }
} elseif ($action == 'forget_password') {
    if (isset($_POST['send_email'])) {
        // Send password via email

        $user_password = randomPassword(); // in common_functions file
        $user_password_hashed = password_hash($user_password, PASSWORD_DEFAULT);
        $update_pass = "UPDATE `user` SET `password` = '$user_password_hashed' WHERE `username` = '$username' AND `user_email` = '$email'";
        $update_pass_result = mysqli_query($conn, $update_pass);
     if ($update_pass_result){

        $subject = "Your new password";
        $message = nl2br("Hello $first_name,\n\nYour password is: $user_password

        Please save this username because you will need it to login. And DO NOT SHARE IT with anyone.");

        $mail_sent = sendUserEmail($email, $subject, $message);

        echo "<script>alert('Your password has been sent to your email.')</script>";
        }
        else{
            echo "Error inserting data: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['send_sms'])) {
        // Send password via SMS

        $user_password = randomPassword();
        $user_password_hashed = password_hash($user_password, PASSWORD_DEFAULT);
        $update_pass = "UPDATE `user` SET `password` = ? WHERE `username` = ? AND `user_email` = ?";
        $update_pass_statement = mysqli_prepare($conn, $update_pass);
        mysqli_stmt_bind_param($update_pass_statement, "sss", $user_password_hashed, $username, $email);
        if (mysqli_stmt_execute($update_pass_statement)){
        
        $message = nl2br("Hello {$first_name}, your password is {$user_password}.
                        Save it in a safe local.");

        $sms_sent = sendUserSMS($phone, $message);

        echo "<script>alert('Your password has been sent to your phone.')</script>";
        }
        else{
            echo "Error inserting data: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Options Form</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="container-fluid my-3">
        <h1 class="text-center">Choose Option</h1>
        <div class="row d-flex align-items-center justify-content-center mt-4">
            <div class="col-lg-12 col-xl-6">
                <form action="" method="post">
                    <div class="form-outline mb-4">
                        <h5 class="text-center">How would you like to receive your <?php echo $action == 'forget_username' ? 'username' : 'New Password'; ?>?</h5>
                    </div>
                    
                    <div class="mt-4 pt-2 d-flex justify-content-between">
                        <input type="submit" value="Send via Email" name="send_email" class="btn btn-primary mb-3 px-3">
                        <input type="submit" value="Send via SMS" name="send_sms" class="btn btn-warning mb-3 px-3">
                    </div>
                    <!-- alert msg after sending -->
                    <?php if($action == 'forget_username' && isset($_POST['send_email'])):?>
                        <!-- if the email has been sent successfully-->
                        <?php if($mail_sent):?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Done!</h4>
                        <p>The username has been sent to you via Gmail,check your Gmail inbox for the mail.</p>
                        <hr>
                        <p>*Check the spam messages.</p>
                        <a href="user_login.php" class="btn btn-light">Login</a>
                    </div>
                        <?php else:?>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Error!</h4>
                        <p>An Error Occured, check your Gmail inbox if there are messages sent to you. Contact the support office for help!</p>
                    </div>
                        <?php endif;?>


                    <?php elseif($action == 'forget_username' && isset($_POST['send_sms'])):?>
                        <!-- if the sms has been sent successfully-->
                        <?php if($sms_sent):?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Done!</h4>
                        <p>The username has been sent to you via SMS,check your messages.</p>
                        <a href="user_login.php" class="btn btn-light">Login</a>
                    </div>
                        <?php else:?>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Error!</h4>
                        <p>An Error Occured,check sms messages if there are messages sent to you. Contact the support office for help!</p>
                    </div>
                        <?php endif;?>



                    <?php elseif($action == 'forget_password' && isset($_POST['send_email'])):?>
                        <?php if($mail_sent):?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Done!</h4>
                        <p>The password has been sent to you via Gmail,check your Gmail inbox for the mail.</p>
                        <p>**We generated a new password for you, save it for now. Then you can change it in your account profile**</p>
                        <hr>
                        <p>*Check the spam messages.</p>
                        <a href="user_login.php" class="btn btn-light">Login</a>
                    </div>
                        <?php else:?>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Error!</h4>
                        <p>An Error Occured, check your Gmail inbox if there are messages sent to you. Contact the support office for help!</p>
                    </div>
                        <?php endif;?>


                    <?php elseif($action == 'forget_password' && isset($_POST['send_sms'])):?>
                        <?php if($sms_sent):?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Done!</h4>
                        <p>The password has been sent to you via SMS,check your messages.</p>
                        <p>**We generated a new password for you, save it for now. Then you can change it in your account profile**</p>
                        <a href="user_login.php" class="btn btn-light">Login</a>
                    </div>
                        <?php else:?>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Error!</h4>
                        <p>An Error Occured,check sms messages if there are messages sent to you. Contact the support office for help!</p>
                    </div>
                        <?php endif;?>

                    <?php endif;?>

                    <div class="mt-4 pt-2 d-flex justify-content-center">
                        <a href="verification.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Loader -->
<div class="loader"></div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Loader functionality
    const loader = document.querySelector('.loader');
      if (loader) {
          window.addEventListener('load', function() {
              loader.classList.add('loader--hidden');
          });
      }
    });
</script>
<style>
     /* Loader */
  .loader{
		position: fixed;
		top: 0;
		left: 0;
		width: 100vw;
		height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: #333333;
		z-index: 100000;
		transition: opacity 0.75s, visibility 0.75s;
	}
	.loader--hidden{
		opacity: 0;
		visibility: hidden;
		display: none;
	}
	.loader::after{
		content: "";
		width: 75px;
		height: 75px;
		border: 15px solid #dddddd;
		border-top-color: #009578;
		border-radius: 50%;
		animation: loading 0.75s ease infinite;
	}
	@keyframes loading {
		from{
			transform: rotate(0turn);
		}
		to{
			transform: rotate(1turn);
		}
	}
</style>
</body>
</html>
