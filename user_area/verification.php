<?php
include ("../config/connection.php");
include ("../functions/common_functions.php");

@session_start();

if(!isset($_GET['action'])){
    echo "<script>alert('Error occurred!')</script>";
    header("location: user_login.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate input
function validate_input($input) {
    return htmlspecialchars(trim($input));
}

$errors = [];

if(isset($_POST['verify'])){
    $first_name = validate_input($_POST['first_name']);
    $last_name = validate_input($_POST['last_name']);
    $username = isset($_POST['username']) ? validate_input($_POST['username']) : '';
    $password = isset($_POST['password']) ? validate_input($_POST['password']) : '';
    $email = validate_input($_POST['email']);

    if (empty($first_name) || empty($last_name) || empty($email) || ($action == 'forget_password' && empty($username)) || ($action == 'forget_username' && empty($password))) {
        $errors[] = "All fields are required.";
    }

    if (!validate_email($email)) {
        $errors[] = "Invalid email format.";
    }

    // Validate email domain
    $email_domain = substr(strrchr($email, "@"), 1);
    if (!checkdnsrr($email_domain, "MX")) {
        $errors[] = "Email domain does not exist.";
    }

    if (empty($errors)) {
        if ($action == 'forget_username') {
            // Verify user data for forget username
            $select_query = "SELECT * FROM `user` WHERE `first_name` = ? AND `last_name` = ? AND `user_email` = ?";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, 'sss', $first_name, $last_name, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row_data = mysqli_fetch_assoc($result);

            if ($row_data && password_verify($password, $row_data['password'])) {
                $username = $row_data['username'];
                $phone = $row_data['user_phone'];
                setcookie("first_name", $first_name);
                setcookie("verified_username", $username);
                setcookie("verified_email", $email);
                setcookie("phone", $phone);
                header('location: options.php?action=forget_username');
                exit();
            } else {
                $errors[] = "Verification failed. Please check your inputs.";
            }
        } elseif ($action == 'forget_password') {
            // Verify user data for forget password
            $select_query = "SELECT * FROM `user` WHERE `first_name` = ? AND `last_name` = ? AND `username` = ? AND `user_email` = ?";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, 'ssss', $first_name, $last_name, $username, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row_data = mysqli_fetch_assoc($result);

            if ($row_data) {
                $phone = $row_data['user_phone'];
                setcookie("first_name", $first_name);
                setcookie("verified_username", $username);
                setcookie("verified_email", $email);
                setcookie("phone", $phone);
                header('location: options.php?action=forget_password');
                exit();
            } else {
                $errors[] = "Verification failed. Please check your inputs.";
            }
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
    <title>Verification Form</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font awesome CDN link for eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="container-fluid my-3">
        <h1 class="text-center">Verification Form</h1>
        <div class="row d-flex align-items-center justify-content-center mt-4">
            <div class="col-lg-12 col-xl-6">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post">
                    <!-- First Name -->
                    <div class="form-outline mb-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" autocomplete="off" required>
                    </div>

                    <!-- Last Name -->
                    <div class="form-outline mb-4">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" autocomplete="off" required>
                    </div>

                    <?php if ($action == 'forget_password'): ?>
                    <!-- Username -->
                    <div class="form-outline mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" autocomplete="off" required>
                    </div>
                    <?php endif; ?>

                    <?php if ($action == 'forget_username'): ?>
                    <!-- Password -->
                    <div class="form-outline mb-4 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" autocomplete="off" required>
                        <span class="position-absolute eye-icon" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                    <?php endif; ?>

                    <!-- Email -->
                    <div class="form-outline mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" autocomplete="off" required>
                    </div>

                    <!-- Submit -->
                    <div class="mt-4 pt-2 d-flex justify-content-between align-items-center">
                        <input type="submit" value="Verify" name="verify" class="btn btn-primary mb-3 px-3">
                        <a href="user_login.php" class="btn btn-secondary">Back</a>
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
      top: 36px;
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
