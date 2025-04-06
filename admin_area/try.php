<?php
include("../config/connection.php"); 
include("../functions/common_functions.php");


if(isset($_POST['admin_registeration'])){
    $admin_name = $_POST['admin_name'];
    $admin_password = $_POST['admin_password'];
    $admin_password_hashed = password_hash($admin_password, PASSWORD_DEFAULT);
    $admin_conf_password = $_POST['confirm_password'];
    $admin_email = $_POST['admin_email'];
    $admin_image = $_FILES['admin_image']['name'];
    $admin_tmp_image = $_FILES['admin_image']['tmp_name'];
    $admin_address = $_POST['admin_address'];
    $admin_phone = $_POST['admin_phone'];


    // Validation

    // 1-If the admin email or name already exist
    $select_admin_data = "SELECT * FROM `admin` WHERE `admin_name` = '$admin_name' or `admin_email` = '$admin_email'";
    $select_admin_data_result = mysqli_query($conn, $select_admin_data);
    $result_rows_count = mysqli_num_rows($select_admin_data_result);
    if($result_rows_count){
        echo "<script>alert('Choose another Name or Email')</script>";
    }
    elseif(strlen($admin_phone) <> 11)
        echo "<script>alert('Phone number should be 11 numbers')</script>";
    
    // 2-passwords match
    else if($admin_password != $admin_conf_password){
        echo "<script>alert('Confirm Password must be the same as Password')</script>";
    }
    //admin data insertion
    else{
        move_uploaded_file($admin_tmp_image, "admin_images/$admin_image");
        $insert_admin_data_query = "INSERT INTO `admin` VALUES(DEFAULT, ?, ?, ?, ?, ?, ?)";
        $insert_query_statement = mysqli_prepare($conn, $insert_admin_data_query);
        mysqli_stmt_bind_param($insert_query_statement, "ssssss", $admin_name, $admin_email, $admin_password_hashed, $admin_image, $admin_address, $admin_phone);
        if (mysqli_stmt_execute($insert_query_statement)) {
            echo "<script>alert('Admin registered successfully')</script>";
            header('location: admin_login.php');
        }
        else {
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
    <title>Registration</title>
    <!-- Bootsrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font awesome cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<style>
    body{
        overflow-x: hidden;
        background-color: #f8f9fa;
        padding: 20px;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
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
</style>
</head>
<body>
    <div class="container_fluid m-3">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-8">
                <div class="card p-4">
                    <form action="" method="post" enctype="multipart/form-data" id="editProfileForm">
                        <!-- name -->
                        <div class="form-outline mb-4">
                            <label for="name" class="form-label">Your name</label>
                            <input type="text" class="form-control" id="name" name="admin_name" placeholder="Enter your name" autocomplete="off" required>
                        </div>
                        <!-- email -->
                        <div class="form-outline mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="admin_email" placeholder="Enter your email" autocomplete="off" required>
                        </div>
                        <!-- password -->
                        <div class="form-outline mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="admin_password" placeholder="Enter your password" autocomplete="off" required>
                        </div>
                        <!-- confirm password -->
                        <div class="form-outline mb-4">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" autocomplete="off" required>
                        </div>
                        <!-- Image -->
                        <div class="form-outline mb-4">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="admin_image">
                        </div>
                        <!-- Address -->
                        <div class="form-outline mb-4">
                            <label for="add" class="form-label">Your Address</label>
                            <input type="text" class="form-control" id="add" name="admin_address" placeholder="Enter your address" autocomplete="off" required>
                        </div>
                        <!-- Phone -->
                        <div class="form-outline mb-4">
                            <label for="mob" class="form-label">Contact</label>
                            <input type="tel" class="form-control" id="mob" name="admin_phone" placeholder="Enter your Phone number" autocomplete="off" required>
                        </div>
                        <!-- submit -->
                        <div class="mt-4 pt-2 d-flex">
                            <div>
                                <input type="submit" value="Register" name="admin_registeration" class="btn btn-primary mb-3 px-3">
                                <p class="small fw-bold mt-2 mb-0">Already have an account? <a href="admin_login.php" class="text-danger">Login</a></p>
                            </div>
                            <div><a href="index.php" class="btn btn-secondary">Back</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- loader -->
<div class="loader"></div>

    
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Load form data from local storage
        const formData = JSON.parse(localStorage.getItem('formData'));
        if (formData) {
            for (const key in formData) {
                if (formData.hasOwnProperty(key)) {
                    const element = document.getElementById(key);
                    if (element) {
                        element.value = formData[key];
                    }
                }
            }
        }

        // Save form data to local storage on input change
        const form = document.getElementById('editProfileForm');
        form.addEventListener('input', () => {
            const formData = {};
            new FormData(form).forEach((value, key) => {
                formData[key] = value;
            });
            localStorage.setItem('formData', JSON.stringify(formData));
        });

        // Clear local storage on form submission
        form.addEventListener('submit', () => {
            localStorage.removeItem('formData');
        });
    });

    
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

</body>
</html>