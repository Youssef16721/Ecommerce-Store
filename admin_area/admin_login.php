<?php 

include ("../config/connection.php");
include ("../functions/common_functions.php");

@session_start();

if(isset($_POST['admin_login'])){
    $adminname = $_POST['admin_name'];
    $admin_pass = $_POST['admin_password'];

    //select user data
    $select_admin_query="SELECT * FROM `admin` WHERE `admin_name` = '$adminname'";
    $admin_result = mysqli_query($conn, $select_admin_query);
    $admins_count = mysqli_num_rows($admin_result);
    $admin_row_data = mysqli_fetch_assoc($admin_result);

    // there is an admin
    if($admins_count){
        if(password_verify($admin_pass, $admin_row_data['admin_password'])){
            echo "<script>alert('Admin Loggedin successfully')</script>";
            $_SESSION['admin_name'] = $adminname;
            $admin_id = $admin_row_data['admin_id'];
            $_SESSION['admin_id'] = $admin_id;
            $admin_image = $admin_row_data['admin_image'];
            $_SESSION['admin_image'] = $admin_image;
            header('location: admin_profile.php');
        }
        else{
            echo "<script>alert('Invalid Password')</script>";
        }
    }
    else{
        echo "<script>alert('Invalid Name')</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            overflow: hidden;
            background-color: #f5f5f5;
        }

        .card {
            border: none;
            width: 100%; /* Ensure card takes full width */
            max-width: 800px; /* Set maximum width for larger screens */
            margin: auto; 
        }

        .form-label {
            font-weight: bold;
        }

        .input-group-text {
            background-color: #fff;
        }

        .btn-primary {
            background-color: #512da8;
            border: none;
        }

        .btn-primary:hover {
            background-color: #452a7a;
        }

        /* loader */
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
        top: 35px;
        right: 20px;
        font-size: 20px;
    }
    /* Toggle password visibility icon animation */
    #togglePasswordIcon {
        transition: all 0.3s ease;
    }
    </style>
</head>
<body>
<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-12 col-lg-10 col-xl-9">
                <div class="card shadow-lg rounded-3">
                    <div class="card-body p-md-5">
                       <div class="text-center">
                            <h2 class="">Admin Login</h2>
                            <p class="my-4 text-success">Enter your personal details to use all site features</p>
                       </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-6 text-center bg-violet">
                                <div class="col-12 col-lg-6 d-flex align-items-center">
                                    <div class="d-flex gap-3 flex-column w-100 ">
                                        <a href="#!" class="btn bsb-btn-2xl btn-outline-dark rounded-0 d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google text-danger" viewBox="0 0 16 16">
                                                <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                                            </svg>
                                            <span class="ms-2 fs-6 flex-grow-1">Continue with Google</span>
                                        </a>
                                        <a href="#!" class="btn bsb-btn-2xl btn-outline-dark rounded-0 d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-apple text-dark" viewBox="0 0 16 16">
                                                <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516.024.034 1.52.087 2.475-1.258.955-1.345.762-2.391.728-2.43Zm3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422.212-2.189 1.675-2.789 1.698-2.854.023-.065-.597-.79-1.254-1.157a3.692 3.692 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56.244.729.625 1.924 1.273 2.796.576.984 1.34 1.667 1.659 1.899.319.232 1.219.386 1.843.067.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758.347-.79.505-1.217.473-1.282Z" />
                                                <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516.024.034 1.52.087 2.475-1.258.955-1.345.762-2.391.728-2.43Zm3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422.212-2.189 1.675-2.789 1.698-2.854.023-.065-.597-.79-1.254-1.157a3.692 3.692 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56.244.729.625 1.924 1.273 2.796.576.984 1.34 1.667 1.659 1.899.319.232 1.219.386 1.843.067.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758.347-.79.505-1.217.473-1.282Z" />
                                            </svg>
                                            <span class="ms-2 fs-6 flex-grow-1">Continue with Apple</span>
                                        </a>
                                        <a href="#!" class="btn bsb-btn-2xl btn-outline-dark rounded-0 d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook text-primary" viewBox="0 0 16 16">
                                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                            </svg>
                                            <span class="ms-2 fs-6 flex-grow-1">Continue with Facebook</span>
                                        </a>
                                        <a href="verify.php" class="btn btn-warning">Forget Password</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <!-- username -->
                                    <div class="form-outline mb-4">
                                        <label for="name" class="form-label">Your name</label>
                                        <input type="text" class="form-control" id="name" name="admin_name" placeholder="Enter your name" autocomplete="off" required>
                                    </div>

                                    <!-- password -->
                                    <div class="form-outline mb-4 position-relative">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="admin_password" placeholder="Enter your password" autocomplete="off" required>
                                        <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                        </span>
                                    </div>

                                    <!-- submit -->
                                    <div class="mt-4 pt-2 d-flex">
                                        <div>
                                            <input type="submit" value="Login" name="admin_login" class="btn btn-primary mb-3 px-3">
                                            <p class="small fw-bold mt-2 mb-0">Don't have an account? <a href="admin_registration.php" class="text-danger">Register</a></p>
                                        </div>
                                        <div><a href="../index.php" class="btn btn-secondary">Back</a></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- loader -->
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

</body>
</html>
