<?php

@session_start();

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


// Check if the user is redirected from registration
$show_welcome_message = isset($_GET['registered']);

if(isset($_POST['username_resend'])){


$username = $_COOKIE['username'];
$email = $_COOKIE['email'];
$first_name = $_COOKIE['first_name'];
$last_name = $_COOKIE['last_name'];

$subject = "Your new username";
$message = nl2br("Hello $first_name $last_name,\n\nYour new username is: $username

Please save this username because you will need it to login. And DO NOT SHARE IT with anyone.

Thank you for registering.");
$sent = sendUserEmail($email, $subject, $message);

}


function validate_input($input) {
    return htmlspecialchars(trim($input));
}

if(isset($_POST['user_login'])){
    $username = validate_input($_POST['user_name']);
    $password = validate_input($_POST['user_password']);

    // Select user data
    $select_query = "SELECT * FROM `user` WHERE `username` = ?";
    $stmt = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows_count = mysqli_num_rows($result);
    $row_data = mysqli_fetch_assoc($result);

    if($rows_count){
        if(password_verify($password, $row_data['password'])){
            // Select cart items
            $ip_add = getIPAddress();
            $select_cart_items = "SELECT * FROM `cart_details` WHERE `ip_address` = '$ip_add'";
            $select_cart_result = mysqli_query($conn, $select_cart_items);
            $cart_items_row = mysqli_num_rows($select_cart_result);
            $_SESSION['username'] = $username;
            $user_id = $row_data['user_id'];
            $_SESSION['user_id'] = $user_id;
            $user_email = $row_data['user_email'];
            $_SESSION['user_email'] = $user_email;
            $first_name = $row_data['first_name'];
            $_SESSION['first_name'] = $first_name;
            $last_name = $row_data['last_name'];
            $_SESSION['last_name'] = $last_name;

            echo "<script>alert('Logged in successfully')</script>";
            if($cart_items_row == 0){
                header('location: user_profile.php');
                exit();
            }
            else{
                header('location: payment.php?from_log');
                exit();
            }
        }
        else{
            echo "<script>alert('Invalid password')</script>";
            $errors[] = "Invalid password";
        }
    }
    else{
        echo "<script>alert('Invalid username')</script>";
        $errors[] = "Invalid username";
    }
}

if(isset($_POST['forget_username'])){
    header('location: verification.php?action=forget_username');
    exit();
}

if(isset($_POST['forget_password'])){
    header('location: verification.php?action=forget_password');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="container-fluid my-3">
        <h1 class="text-center">User Login</h1>
        <div class="row d-flex align-items-center justify-content-center mt-4">
            <div class="col-lg-12 col-xl-6">
                <?php if ($show_welcome_message): ?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Well done!</h4>
                        <p>Aww yeah, you successfully registered to Zoomia. The username has been sent to you via Gmail,check your Gmail inbox for the mail.</p>
                        <hr>
                        <p class="mb-2">If you lost the mail or there is a tecnical problem click on the button below!</p>
                        <form action="" method="post">
                            <button role="submit" name="username_resend" class="btn btn-outline-light my-3">Resend the username</button>
                        </form>
                        <?php if(isset($sent)){?>
                        <?php if($sent):?>
                        <p class="text-success">Mail sent successfully, go and check it. Remember to save the username.</p>
                        <?php else:?>
                        <p class="text-danger">Error happened, please contact the support office.</p>
                        <?php endif; }?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading text-center">ERROR!</h4>
                        <?php foreach ($errors as $error): ?>
                            <p>*<?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                <form action="" method="post">
                    <!-- Username -->
                    <div class="form-outline mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="user_name" placeholder="Enter your username" autocomplete="off" required>
                    </div>
                    
                     <!-- Password -->
                     <div class="form-outline mb-4 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="user_password" placeholder="Enter your password" autocomplete="off" required>
                        <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                    
                    <!-- Submit -->
                    <div class="mt-4 pt-2 d-flex justify-content-between">
                        <div>
                            <input type="submit" value="Login" name="user_login" class="btn btn-primary mb-3 px-3">
                            <p class="small fw-bold mt-2 mb-0">Don't have an account? <a href="user_registration.php" class="text-danger">Register</a></p>
                        </div>
                        <div><a href="../index.php" class="btn btn-secondary">Back</a></div>
                    </div>
                </form>  
                <form action="" method="post">       
                    <!-- Forget buttons -->
                    <div class="d-flex justify-content-between my-3">
                        <input type="submit" value="Forget Username" name="forget_username" class="btn btn-warning mb-3 px-3">
                        <input type="submit" value="Forget Password" name="forget_password" class="btn btn-danger mb-3 px-3">
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

    // Toggle password visibility
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordIcon.classList.remove('fa-eye');
            togglePasswordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            togglePasswordIcon.classList.remove('fa-eye-slash');
            togglePasswordIcon.classList.add('fa-eye');
        }
    }
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
    .eye-icon{
      top: 40px;
      right: 20px;
      font-size: 20px;
    }
     /* Toggle password visibility icon animation */
     #togglePasswordIcon {
        transition: all 0.3s ease;
    }
</style>
</body>
</html>
