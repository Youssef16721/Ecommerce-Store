<?php
include ("../config/connection.php");
include ("../functions/common_functions.php");



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
    $admin_name = validate_input($_POST['admin_name']);
    $admin_email = validate_input($_POST['admin_email']);

    if (empty($admin_name) || empty($admin_email)) {
        $errors[] = "All fields are required.";
    }

    if (!validate_email($admin_email)) {
        $errors[] = "Invalid email format.";
    }

    // Validate email domain
    $email_domain = substr(strrchr($admin_email, "@"), 1);
    if (!checkdnsrr($email_domain, "MX")) {
        $errors[] = "Email domain does not exist.";
    }

    if (empty($errors)) {

        // Verify user data for forget password
        $select_query = "SELECT * FROM `admin` WHERE `admin_name` = ? AND `admin_email` = ?";
        $stmt = mysqli_prepare($conn, $select_query);
        mysqli_stmt_bind_param($stmt, 'ss', $admin_name, $admin_email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row_data = mysqli_fetch_assoc($result);

        if ($row_data) {
            $phone = $row_data['admin_phone'];
            setcookie("admin_name", $admin_name);
            setcookie("admin_email", $admin_email);
            setcookie("phone", $phone);
            header('location: option.php');
            exit();
        } else {
            $errors[] = "Verification failed. Please check your inputs.";
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

                    <!-- Name -->
                    <div class="form-outline mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="admin_name" placeholder="Enter your name" autocomplete="off" required>
                    </div>
                    <!-- Email -->
                    <div class="form-outline mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="admin_email" placeholder="Enter your email" autocomplete="off" required>
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
