<?php

//Mailer files
require "../PHPMailer/src/Exception.php";
require "../PHPMailer/src/PHPMailer.php";
require "../PHPMailer/src/SMTP.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


require "../config/mail_config.php"; 


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


include ("../config/connection.php");
include ("../functions/common_functions.php");//after the Mailer files


function generateUsername($first_name, $last_name) {
    $username = strtolower($first_name[0] . $last_name);
    return $username;
}

function validate_input($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}


if(isset($_POST['user_register'])){
    $first_name = validate_input($_POST['first_name']);
    $last_name = validate_input($_POST['last_name']);
    $user_password = validate_input($_POST['user_password']);
    $user_password_hashed = password_hash($user_password, PASSWORD_DEFAULT);
    $user_conf_password = validate_input($_POST['confirm_password']);
    $user_email = validate_input($_POST['user_email']);
    $user_country_code = validate_input($_POST['user_country']);
    $user_phone = $user_country_code . $_POST['user_phone'];
    $user_ip = getIPAddress();
    $user_image = $_FILES['user_image']['name'];
    $user_tmp_image = $_FILES['user_image']['tmp_name'];
    $user_address = validate_input($_POST['user_address']);

    // Validation
    $errors = [];

    // Validate email domain
    $email_domain = substr(strrchr($user_email, "@"), 1);
    if (!checkdnsrr($email_domain, "MX")) {
        $errors[] = "Email domain does not exist.";
    }

    // Generate unique username
    $base_username = generateUsername($first_name, $last_name);
    $username = $base_username;
    $max_attempts = 100;
    $i = 1;
    while ($i <= $max_attempts) {
        $select_user_data = "SELECT * FROM `user` WHERE `username` = ?";
        $stmt = mysqli_prepare($conn, $select_user_data);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $result_rows_count = mysqli_stmt_num_rows($stmt);

        if($result_rows_count == 0){
            break;
        } else {
            $username = $base_username . $i;
            $i++;
        }
    }

    if ($i > $max_attempts) {
        $errors[] = "Error: Could not generate a unique username. Please try again.";
    }


    // 2-passwords match
    if($user_password != $user_conf_password){
        $errors[] = "Confirm Password must be the same as Password";
    }

    // // 3- if the email address already exist
    // $select_user_data = "SELECT * FROM `user` WHERE `user_email` = ?";
    // $stmt = mysqli_prepare($conn, $select_user_data);
    // mysqli_stmt_bind_param($stmt, 's',$user_email);
    // mysqli_stmt_execute($stmt);
    // mysqli_stmt_store_result($stmt);
    // $result_rows_count = mysqli_stmt_num_rows($stmt);
    // if($result_rows_count){
    //     $errors[] = "Use another email address";
    // }

    //user data insertion
    if(empty($errors)){
        move_uploaded_file($user_tmp_image, "user_images/$user_image");
        $insert_query = "INSERT INTO `user` VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_query_statement = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_query_statement, "sssssssss", $first_name, $last_name, $username, $user_password_hashed, $user_email, $user_ip, $user_phone, $user_address, $user_image);
        if (mysqli_stmt_execute($insert_query_statement)) {
            
            $subject = "Your new username";
            $message = nl2br("Hello $first_name $last_name,\n\nYour new username is: $username

            Please save this username because you will need it to login. And DO NOT SHARE IT with anyone.

            Thank you for registering.");

            if(sendUserEmail($user_email, $subject, $message)){
            echo "<script>alert('User registered successfully. Your username has been sent to your email.')</script>";
            header('location: user_login.php?registered');
            setcookie("username", $username);
            setcookie("email", $user_email);
            setcookie("first_name", $first_name);
            setcookie("last_name", $last_name);
            }
            else{
                $errors[] = "Error! technical problem. The message wasn't sent";
            }
        } else {
            $errors[] = "Error inserting data: " . mysqli_error($conn);
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
    <title>User Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-fluid {
            padding: 2rem;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: none;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary, .btn-secondary {
            width: 100%;
        }
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #333;
            z-index: 100000;
            transition: opacity 0.75s, visibility 0.75s;
        }
        .loader--hidden {
            opacity: 0;
            visibility: hidden;
            display: none;
        }
        .loader::after {
            content: "";
            width: 75px;
            height: 75px;
            border: 15px solid #ddd;
            border-top-color: #009578;
            border-radius: 50%;
            animation: loading 0.75s ease infinite;
        }
        @keyframes loading {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }
        .form-error {
            color: red;
            font-size: 0.875rem;
        }
        .input-group-text img {
            margin-right: 5px;
        }
        .alert svg{
            width: 50px;
        }
        .eye-icon{
        top: 35px;
        right: 20px;
        font-size: 20px;
        }
        /* Toggle password visibility icon animation */
        #togglePasswordIcon {
            transition: all 0.3s ease;
        }
        #toggleConfirmPasswordIcon{
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container my-3">
        <h1 class="text-center mb-4">User Registration Form</h1>
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-6">
                <div class="card p-4">
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading text-center">ERROR!</h4>
                        <?php foreach ($errors as $error): ?>
                            <p>*<?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading">Read Carefully</h4>
                        <p>*First and Last name must be at least 3 characters long.</p>
                        <p>*Password should contain at least 8 characters, including at least one capital letter, one number, and one symbol.</p>
                        <p>*Email must be in a valid format (e.g., user@example.com).</p>
                        <p>*Phone number must be valid based on the selected country code.</p>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" id="registrationForm">
                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" required autocomplete="off">
                                <div class="form-error" id="firstNameError"></div>
                            </div>
                            <!-- Last Name -->
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" required autocomplete="off">
                                <div class="form-error" id="lastNameError"></div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="user_email" placeholder="Enter your email" required autocomplete="off">
                                <div class="form-error" id="emailError"></div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="user_password" placeholder="Enter your password" required autocomplete="off">
                                <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                </span>
                                <div class="form-error" id="passwordError"></div>
                            </div>
                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3 position-relative">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required autocomplete="off">
                                <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="toggleConfirmPasswordVisibility()">
                                    <i class="fa fa-eye" id="toggleConfirmPasswordIcon"></i>
                                </span>
                                <div class="form-error" id="confirmPasswordError"></div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Image -->
                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="user_image">
                                <div class="form-error" id="imageError"></div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Address -->
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Physical Address</label>
                                <input type="text" class="form-control" id="address" name="user_address" placeholder="Enter your address" required autocomplete="off">
                                <div class="form-error" id="addressError"></div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Country -->
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-control" id="country" name="user_country" required autocomplete="off">
                                    <!-- Countries will be populated by JavaScript -->
                                </select>
                                <div class="form-error" id="countryError"></div>
                            </div>
                            <!-- Contact -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Contact</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="countryCode"><img src="" id="countryFlag" alt="Flag" width="20"> +1</span>
                                    <input type="tel" class="form-control" id="phone" name="user_phone" placeholder="Enter your phone number" required autocomplete="off">
                                </div>
                                <div class="form-error" id="phoneError"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>
                                <input type="submit" value="Register" name="user_register" class="btn btn-primary mb-3 px-3">
                                <p class="small fw-bold mt-2 mb-0">Already have an account? <a href="user_login.php" class="text-danger">Login</a></p>
                            </div>
                            <div><a href="../index.php" class="btn btn-secondary">Back</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const countries = [
            { "name": "United States", "code": "+1", "flag": "https://flagcdn.com/us.svg", "phonePattern": /^[2-9]{1}[0-9]{9}$/ },
            { "name": "United Kingdom", "code": "+44", "flag": "https://flagcdn.com/gb.svg", "phonePattern": /^[1-9]{1}[0-9]{9}$/ },
            { "name": "Canada", "code": "+1", "flag": "https://flagcdn.com/ca.svg", "phonePattern": /^[2-9]{1}[0-9]{9}$/ },
            { "name": "Australia", "code": "+61", "flag": "https://flagcdn.com/au.svg", "phonePattern": /^[0-9]{9}$/ },
            { "name": "Germany", "code": "+49", "flag": "https://flagcdn.com/de.svg", "phonePattern": /^[0-9]{10}$/ },
            { "name": "Egypt", "code": "+20", "flag": "https://flagcdn.com/eg.svg", "phonePattern": /^[0-9]{10}$/ },
            // Add more countries and codes as needed
        ];

        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById('country');
            const countryCodeSpan = document.getElementById('countryCode');
            const countryFlagImg = document.getElementById('countryFlag');
            const phoneInput = document.getElementById('phone');

            // Populate country dropdown
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.code;
                option.dataset.flag = country.flag;
                option.dataset.pattern = country.phonePattern;
                option.textContent = country.name;
                countrySelect.appendChild(option);
            });

            // Set initial country code and flag
            const initialCountry = countries[0];
            countryCodeSpan.innerHTML = `<img src="${initialCountry.flag}" id="countryFlag" alt="Flag" width="20"> ${initialCountry.code}`;

            // Update country code and flag when country is selected
            countrySelect.addEventListener('change', function() {
                const selectedCountry = countries.find(country => country.code === this.value);
                countryCodeSpan.innerHTML = `<img src="${selectedCountry.flag}" alt="Flag" width="20"> ${selectedCountry.code}`;
            });

            // Loader functionality
            const loader = document.querySelector('.loader');
            if (loader) {
                window.addEventListener('load', function() {
                    loader.classList.add('loader--hidden');
                });
            }

            // Form validation
            const form = document.getElementById('registrationForm');
            form.addEventListener('submit', function(event) {
                let valid = true;

                // Clear previous errors
                document.querySelectorAll('.form-error').forEach(error => error.textContent = '');

                // Validate first name
                const firstName = document.getElementById('first_name').value;
                if (firstName.length < 3) {
                    valid = false;
                    document.getElementById('firstNameError').textContent = 'First name is required and must be at least 3 characters long.';
                }

                // Validate last name
                const lastName = document.getElementById('last_name').value;
                if (lastName.length < 3) {
                    valid = false;
                    document.getElementById('lastNameError').textContent = 'Last name is required and must be at least 3 characters long.';
                }

                // Validate email
                const email = document.getElementById('email').value;
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (!emailPattern.test(email)) {
                    valid = false;
                    document.getElementById('emailError').textContent = 'Please enter a valid email address.';
                }

                // Validate password
                const password = document.getElementById('password').value;
                const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%#*?&]{8,}$/;
                if (!passwordPattern.test(password)) {
                    valid = false;
                    document.getElementById('passwordError').textContent = 'Password must be at least 8 characters long and contain at least one capital letter, one number, and one symbol.';
                }

                // Validate confirm password
                const confirmPassword = document.getElementById('confirm_password').value;
                if (password !== confirmPassword) {
                    valid = false;
                    document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
                }

                // Validate phone number based on country code
                const phone = phoneInput.value;
                const selectedCountry = countries.find(country => country.code === countrySelect.value);
                const phonePattern = new RegExp(selectedCountry.phonePattern);
                if (!phonePattern.test(phone)) {
                    valid = false;
                    document.getElementById('phoneError').textContent = 'Please enter a valid phone number based on the selected country code.';
                }

                if (!valid) {
                    event.preventDefault();
                }

            });

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
        
        // Toggle confirm password visibility
        function toggleConfirmPasswordVisibility() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const toggleConfirmPasswordIcon = document.getElementById('toggleConfirmPasswordIcon');
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                toggleConfirmPasswordIcon.classList.remove('fa-eye');
                toggleConfirmPasswordIcon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                toggleConfirmPasswordIcon.classList.remove('fa-eye-slash');
                toggleConfirmPasswordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
