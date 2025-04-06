<?php
include("../config/connection.php"); 
include("../functions/common_functions.php");

function validate_input($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

if(isset($_POST['admin_registeration'])){
    $admin_name = validate_input($_POST['admin_name']);
    $admin_password = validate_input($_POST['admin_password']);
    $admin_password_hashed = password_hash($admin_password, PASSWORD_DEFAULT);
    $admin_conf_password = validate_input($_POST['confirm_password']);
    $admin_email = validate_input($_POST['admin_email']);
    $admin_image = $_FILES['admin_image']['name'];
    $admin_tmp_image = $_FILES['admin_image']['tmp_name'];
    $admin_address = validate_input($_POST['admin_address']);
    $admin_phone = validate_input($_POST['admin_phone']);


    // Validation
    $errors = [];

    // Validate email domain
    $email_domain = substr(strrchr($admin_email, "@"), 1);
    if (!checkdnsrr($email_domain, "MX")) {
        $errors[] = "Email domain does not exist.";
    }

    // 1-If the admin email or name already exist
    $select_admin_data = "SELECT * FROM `admin` WHERE `admin_name` = '$admin_name' or `admin_email` = '$admin_email'";
    $select_admin_data_result = mysqli_query($conn, $select_admin_data);
    $result_rows_count = mysqli_num_rows($select_admin_data_result);
    if($result_rows_count){
        echo "<script>alert('Choose another Name or Email')</script>";
        $errors[] = "Choose another Name or Email";
    }
    
    // 2-passwords match
    if($admin_password != $admin_conf_password){
        echo "<script>alert('Confirm Password must be the same as Password')</script>";
        $errors[] = "Confirm Password must be the same as Password";
    }
    //admin data insertion
    if(empty($errors)){
        move_uploaded_file($admin_tmp_image, "admin_images/$admin_image");
        
        $insert_admin_data_query = "INSERT INTO `admin` VALUES(DEFAULT, ?, ?, ?, ?, ?, ?)";
        $insert_query_statement = mysqli_prepare($conn, $insert_admin_data_query);
        mysqli_stmt_bind_param($insert_query_statement, "ssssss", $admin_name, $admin_email, $admin_password_hashed, $admin_image, $admin_address, $admin_phone);
        if (mysqli_stmt_execute($insert_query_statement)) {
            echo "<script>alert('Admin registered successfully')</script>";
            header('location: admin_login.php');
            exit();
        }
        else {
        echo "Error inserting data: " . mysqli_error($conn);
        $errors[] = "error inserting data!!!!!!!!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <title>Registration</title>
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
            position: relative;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
          /* loader */
        .loader {
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
        .loader--hidden {
            opacity: 0;
            visibility: hidden;
            display: none;
        }
        .loader::after {
            content: "";
            width: 75px;
            height: 75px;
            border: 15px solid #dddddd;
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
        .form-error{
            color: red;
        }
        .eye-icon {
            top: 36px;
            right: 20px;
            font-size: 20px;
        }
        /* Toggle password visibility icon animation */
        #togglePasswordIcon {
            transition: all 0.3s ease;
        }
        #toggleConfirmPasswordIcon {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>

    <?php if(!empty($errors)): ?>
    <div class="toast-container bottom-0 end-0 p-3 position-fixed">

        <?php foreach ($errors as $error): ?>
        <!-- Then put toasts within -->
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="../images/logo-modified.png" class="rounded me-2" alt="..." width="20px">
            <strong class="me-auto">Zoomia</strong>
            <small class="text-body-secondary">just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ERROR! <?php echo $error?>.
        </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
<div class="container">
    <h1 class="text-center">Registration Form</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <!-- alert info -->
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">Read Carefully</h4>
                    <p>*Name must be at least 3 characters long.</p>
                    <p>*Password should contain at least 8 characters, including at least one capital letter, one number, and one symbol.</p>
                    <p>*Email must be in a valid format (e.g., user@example.com).</p>
                    <p>*Phone number must be valid based on the selected country code.</p>
                </div>
                <!-- General Profile Information -->
                <h4>General Information</h4>
                <form action="" method="post" enctype="multipart/form-data" id="editProfileForm">
                    <!-- name -->
                    <div class="form-outline mb-4">
                        <label for="name" class="form-label">Your name</label>
                        <input type="text" class="form-control" id="name" name="admin_name" placeholder="Enter your name" autocomplete="off" required>
                        <div class="form-error" id="nameError"></div>
                    </div>
                    <!-- email -->
                    <div class="form-outline mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="admin_email" placeholder="Enter your email" autocomplete="off" required>
                        <div class="form-error" id="emailError"></div>
                    </div>
                    <!-- Image -->
                    <div class="form-outline mb-4">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="admin_image">
                    </div>

                    <hr>

                    <h4>Password</h4>
                    <!-- password -->
                    <div class="form-outline mb-4 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="admin_password" placeholder="Enter your password" autocomplete="off" required>
                        <span class="position-absolute eye-icon" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                        </span>
                        <div class="form-error" id="passwordError"></div>
                    </div>
                    <!-- confirm password -->
                    <div class="form-outline mb-4 position-relative">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" autocomplete="off" required>
                        <span class="position-absolute eye-icon" style="cursor: pointer;" onclick="toggleConfirmPasswordVisibility()">
                            <i class="fa fa-eye" id="toggleConfirmPasswordIcon"></i>
                        </span>
                        <div class="form-error" id="confirmPasswordError"></div>
                    </div>
                    
                    <hr>

                    <h4>Contact</h4>
                    <!-- Address -->
                    <div class="form-outline mb-4">
                        <label for="add" class="form-label">Your Address</label>
                        <input type="text" class="form-control" id="add" name="admin_address" placeholder="Enter your address" autocomplete="off" required>
                    </div>
                    <div class="row">
                            <!-- Country -->
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-control" id="country" name="admin_country" required autocomplete="off">
                                    <!-- Countries will be populated by JavaScript -->
                                </select>
                                <div class="form-error" id="countryError"></div>
                            </div>
                            <!-- phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="countryCode"><img src="" id="countryFlag" alt="Flag" width="20"> +1</span>
                                    <input type="tel" class="form-control" id="phone" name="admin_phone" placeholder="Enter your phone number" required autocomplete="off">
                                </div>
                                <div class="form-error" id="phoneError"></div>
                            </div>
                        </div>
                    <!-- submit -->
                    <div class="mt-4 pt-2 d-flex justify-content-around align-items-start">
                        <div>
                            <input type="submit" value="Register" name="admin_registeration" class="btn btn-primary mb-3 px-3">
                            <p class="small fw-bold mt-2 mb-0">Already have an account? <a href="admin_login.php" class="link-danger">Login</a></p>
                        </div>
                        <div>
                            <input type="reset" value="Reset" class="btn btn-secondary mb-3 px-3">
                        </div>
                        <a href="admin_login.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
                <!-- loader -->
                <div class="loader"></div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP07eDgmgB7SkLt39oX3Z8lLoC4eRk5f7Zp6H9jcI4g=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-tnXfMQV0HZpsbOEKdfKjtJp4QQPfkNiFLp2o9LJf7l6sI7fwE3rdYYhH9uJKpcnq" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-QF0HGwbnZST7B80YQK4lZ1vRJl9xTos1rZZLox1YlZy3IS6hg8xIh37Uu3U7HX9A" crossorigin="anonymous"></script>

<!-- Your JavaScript code -->
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

    // Loader functionality
    const loader = document.querySelector('.loader');
    if (loader) {
        window.addEventListener('load', function() {
            loader.classList.add('loader--hidden');
        });
    }

    const countries = [
        { "name": "United States", "code": "+1", "flag": "https://flagcdn.com/us.svg", "phonePattern": /^[2-9]{1}[0-9]{9}$/ },
        { "name": "United Kingdom", "code": "+44", "flag": "https://flagcdn.com/gb.svg", "phonePattern": /^[1-9]{1}[0-9]{9}$/ },
        { "name": "Canada", "code": "+1", "flag": "https://flagcdn.com/ca.svg", "phonePattern": /^[2-9]{1}[0-9]{9}$/ },
        { "name": "Australia", "code": "+61", "flag": "https://flagcdn.com/au.svg", "phonePattern": /^[0-9]{9}$/ },
        { "name": "Germany", "code": "+49", "flag": "https://flagcdn.com/de.svg", "phonePattern": /^[0-9]{10}$/ },
        { "name": "Egypt", "code": "+20", "flag": "https://flagcdn.com/eg.svg", "phonePattern": /^[0-9]{10}$/ },
    ];

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

    // Form validation
    form.addEventListener('submit', function(event) {
        let valid = true;

        // Clear previous errors
        document.querySelectorAll('.form-error').forEach(error => error.textContent = '');

        // Validate name
        const name = document.getElementById('name').value;
        if (name.length < 3) {
            valid = false;
            document.getElementById('nameError').textContent = 'Name is required and must be at least 3 characters long.';
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

