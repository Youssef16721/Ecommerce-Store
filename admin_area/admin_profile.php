<?php
include ("../config/connection.php");
include ("../functions/common_functions.php");

session_start();

if(isset($_SESSION['admin_name'])) {
    $admin_name = $_SESSION['admin_name'];
    $get_admin = "SELECT * FROM `admin` WHERE `admin_name` = '$admin_name'";
    $admin_result = mysqli_query($conn, $get_admin);
    $admin_row = mysqli_fetch_assoc($admin_result);
    $admin_email = $admin_row['admin_email'];
    $admin_image = $admin_row['admin_image'];
    $admin_address = $admin_row['admin_address'];
    $admin_phone = $admin_row['admin_phone'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['update_admin'])){
            // Sanitize and validate input
            $new_name = mysqli_real_escape_string($conn, $_POST['admin_name']);
            $new_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
            $new_address = mysqli_real_escape_string($conn, $_POST['admin_address']);
            $new_phone = mysqli_real_escape_string($conn, $_POST['admin_phone']);
            $current_password = mysqli_real_escape_string($conn, $_POST['admin_current_pass']);
            $new_password = mysqli_real_escape_string($conn, $_POST['admin_new_pass']);
            $confirm_password = mysqli_real_escape_string($conn, $_POST['admin_conf_pass']);
            $new_image = $_FILES['profile_image']['name'];
            $new_tmp_image = $_FILES['profile_image']['tmp_name'];

            

            // Check if all fields are filled
            if (!empty($new_name) && !empty($new_email) && !empty($new_address) && !empty($new_phone) && !empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
                // Verify current password
                if (password_verify($current_password, $admin_row['admin_password'])) {
                    // Check if new password and confirm password match
                    if ($new_password === $confirm_password) {
                        // Hash the new password
                        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                        if (!empty($new_image)) {
                            $image_path = "admin_images/$admin_image";
                            if (file_exists($image_path)) {
                                unlink($image_path);
                            }

                            move_uploaded_file($new_tmp_image, "admin_images/$new_image");
                        } else {
                            $new_image = $admin_image; // Keep the current image if no new image is uploaded
                        }

                        // Update admin profile
                        $update_admin = "UPDATE `admin` SET `admin_name` = ?, `admin_email` = ?, `admin_address` = ?, `admin_phone` = ?, `admin_password` = ?, `admin_image` = ? WHERE `admin_name` = ?";

                        $update_admin_statement = mysqli_prepare($conn, $update_admin);

                        // Bind parameters and execute the statement
                        mysqli_stmt_bind_param($update_admin_statement, "sssssss", $new_name, $new_email, $new_address, $new_phone, $hashed_new_password, $new_image, $admin_name);

                        if (mysqli_stmt_execute($update_admin_statement)) {
                            echo "<script>alert('Profile updated successfully');</script>";
                            // Update session variables
                            $_SESSION['admin_name'] = $new_name;
                            $_SESSION['admin_image'] = $new_image;
                            header('Location: admin_profile.php');
                        } else {
                            echo "<script>alert('Error updating profile');</script>";
                        }
                    } else {
                        echo "<script>alert('New password and confirm password do not match');</script>";
                    }
                } else {
                    echo "<script>alert('Current password is incorrect');</script>";
                }
            } else {
                echo "<script>alert('Please fill in all fields');</script>";
            }
        }
        elseif (isset($_POST['delete_admin'])) {

            $delete_admin = "DELETE FROM `admin` WHERE `admin_name` = '$admin_name'";
            if (mysqli_query($conn, $delete_admin)) {
                echo "<script>alert('Profile deleted successfully');</script>";
                session_destroy();
                header('Location: ../index.php');
                exit(); 
            } else {
                echo "<script>alert('Error deleting profile');</script>";
            }
        }
    }

} else {
    header('location: admin_login.php');
    exit(); // It's good practice to call exit() after a header redirection
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px; /* Add padding to accommodate navbar */
        }
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 4px solid #512da8;
        }
        .profile-info {
            margin-bottom: 30px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 20px;
        }
        .profile-info h3 {
            color: #512da8;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .profile-info p {
            color: #6c757d;
        }
        .edit-profile-form {
            margin-bottom: 30px;
        }
        .edit-profile-form .form-label {
            color: #512da8;
            font-weight: bold;
        }
        .edit-profile-form .btn-primary {
            background-color: #512da8;
            border: none;
        }
        .edit-profile-form .btn-primary:hover {
            background-color: #452a7a;
        }
        .delete-profile-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .delete-profile-section .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .delete-profile-section .btn-danger:hover {
            background-color: #c82333;
        }
        .back-home-btn {
            margin-top: 20px;
        }
        .dashboard-btn {
            margin-top: 20px;
        }
        .btn {
            width: 150px;
        }
        .image-container {
            position: relative;
            display: inline-block;
        }

        .edit-image-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(255, 255, 255, 0.7); /* Semi-transparent white */
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-image-btn i {
            color: #512da8;
        }

        .edit-image-btn:hover {
            background-color: #512da8; 
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
    #toggleConfirmPasswordIcon{
        transition: all 0.3s ease;
    }
    #toggleConfirmPasswordIcon{
        transition: all 0.3s ease;
    }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-container">
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="image-container position-relative">
                    <img src="admin_images/<?php echo $admin_image?>" alt="Profile Image" class="profile-image">
                    <label for="profile_image" class="edit-image-btn" title="Edit Image">
                        <i class="fas fa-edit"></i>
                    </label>
                    <input type="file" id="profile_image" name="profile_image" style="display: none;">
                </div>
                <h2><?php echo $admin_name; ?></h2>
                <p>Email: <?php echo $admin_email; ?></p>
            </div>

            <!-- Profile Information -->
            <div class="profile-info">
                <h3>Profile Information</h3>
                <p><strong>Username:</strong> <?php echo $admin_name; ?></p>
                <p><strong>Address:</strong> <?php echo $admin_address; ?></p>
                <p><strong>Contact:</strong> <?php echo $admin_phone; ?></p>
                <!-- Back to Home and Dashboard Buttons -->
                <a href="../index.php" class="btn btn-secondary back-home-btn"><i class="fas fa-home"></i> Back to Home</a>
                <a href="index.php" class="btn btn-primary dashboard-btn"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </div>

            <!-- Edit Profile Form with Password Inputs -->
            <div class="edit-profile-form">
                <h3>Edit Profile</h3>
                
                    <div class="row">
                        <!-- Left Column - Profile Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Name</label>
                                <input type="text" class="form-control" id="username" name="admin_name" value="<?php echo $admin_name; ?>" autocomplete="off" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="admin_email" value="<?php echo $admin_email; ?>" autocomplete="off" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="admin_address" value="<?php echo $admin_address; ?>" autocomplete="off" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="contact" name="admin_phone" value="<?php echo $admin_phone; ?>" autocomplete="off" required>
                            </div>
                            <button type="submit" name="update_admin" class="btn btn-primary">Save Changes</button>
                        </div>
                        <!-- Right Column - Password Inputs -->
                        <div class="col-md-6">
                            <div class="mb-3 position-relative">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" name="admin_current_pass"  placeholder="Enter your current password" autocomplete="off" required>
                                <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="toggleCurrentPasswordVisibility()">
                                    <i class="fa fa-eye" id="toggleCurrentPasswordIcon"></i>
                                </span>
                            </div>
                            <div class="mb-3  position-relative">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="admin_new_pass" placeholder="Enter your new password" autocomplete="off" required>
                                <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                </span>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="admin_conf_pass" placeholder="Confirm your new password" autocomplete="off" required>
                                <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="toggleConfirmPasswordVisibility()">
                                    <i class="fa fa-eye" id="toggleConfirmPasswordIcon"></i>
                                </span>
                            </div>
                        </div>
                    </div>
            </div>
        </form>
        <!-- Delete Profile Section -->
        <div class="delete-profile-section">
            <h3>Delete Profile</h3>
            <p>Are you sure you want to delete your profile? This action cannot be undone.</p>
            <form action="" method="post">
                <button type="submit" name="delete_admin" class="btn btn-danger"><i class="fas fa-trash"></i> Delete Profile</button>
            </form>
        </div>
        
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>


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

    
    // Toggle current password visibility
    function toggleCurrentPasswordVisibility() {
        const currentPasswordInput = document.getElementById('currentPassword');
        const toggleCurrentPasswordIcon = document.getElementById('toggleCurrentPasswordIcon');
        if (currentPasswordInput.type === 'password') {
            currentPasswordInput.type = 'text';
            toggleCurrentPasswordIcon.classList.remove('fa-eye');
            toggleCurrentPasswordIcon.classList.add('fa-eye-slash');
        } else {
            currentPasswordInput.type = 'password';
            toggleCurrentPasswordIcon.classList.remove('fa-eye-slash');
            toggleCurrentPasswordIcon.classList.add('fa-eye');
        }
    }

    // Toggle password visibility
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('newPassword');
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
        const confirmPasswordInput = document.getElementById('confirmPassword');
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
