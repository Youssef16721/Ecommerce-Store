<?php 
include("../config/connection.php"); 
include("../functions/common_functions.php");

session_start();
if(!isset($_SESSION['username']))
    header('location: user_login.php');

else{
$username = $_SESSION['username'];


//fetching user data
$select_user = "SELECT * FROM `user` WHERE `username` = '$username'";//get the user by his username to evate that the user enters another id
$select_user_result = mysqli_query($conn, $select_user);
$user_row = mysqli_fetch_assoc($select_user_result);
$first_name = $user_row['first_name'];
$last_name = $user_row['last_name'];
$user_image = $user_row['user_image'];
$user_id = $user_row['user_id'];
$username = $user_row['username'];
$user_email = $user_row['user_email'];
$user_phone = $user_row['user_phone'];
$user_address = $user_row['user_address'];

//fetching pending orders
$get_pending_orders = "SELECT * FROM `user_orders` WHERE `user_id` = $user_id AND `order_status` = 'pending'";
$result_pending_orders = mysqli_query($conn, $get_pending_orders);
$pending_orders_count = mysqli_num_rows($result_pending_orders);


//update user data (Edit Account):
if(isset($_POST['user_update'])){
  $update_first_name = $_POST['first_name'];
  $update_last_name = $_POST['last_name'];
  $update_email = $_POST['user_email'];
  $update_password = $_POST['user_password'];
  $update_password_hashed = password_hash($update_password, PASSWORD_DEFAULT);
  $update_image = $_FILES['user_image']['name'];
  $update_tmp_image = $_FILES['user_image']['tmp_name'];
  $update_phone = $_POST['user_phone'];
  $update_address = $_POST['user_address'];

  // Validation
  $errors = [];

  // Validate email domain
  $email_domain = substr(strrchr($user_email, "@"), 1);
  if (!checkdnsrr($email_domain, "MX")) {
      $errors[] = "Email domain does not exist.";
  }

  //Check if the email or the username inserted already exist
  $select_user_data = "SELECT * FROM `user` WHERE `user_id` != $user_id AND `user_email` = '$update_email'";
  $select_user_data_result = mysqli_query($conn, $select_user_data);
  $result_rows_count = mysqli_num_rows($select_user_data_result);
  if($result_rows_count){
      echo "<script>alert('Choose another Email')</script>";
      header('location: user_profile.php?edit_acc');
      $errors[] = "Choose another Email.";
  }
  else{

  //remove old image
  unlink("user_images/$user_image");

  //update user data
  $update_data_query = "UPDATE `user` SET `first_name` = ?, `last_name` = ?, `password` = ?, `user_email` = ?, `user_image` = ?, `user_phone` = ?, `user_address` = ?
  WHERE `user_id` = ?";
  move_uploaded_file($update_tmp_image, "user_images/$update_image");
  $update_data_statement = mysqli_prepare($conn, $update_data_query);
  // Bind parameters and execute the statement
  mysqli_stmt_bind_param($update_data_statement, "sssssssi", $update_first_name, $update_last_name, $update_password_hashed, $update_email, $update_image, $update_phone, $update_address, $user_id);
  if (mysqli_stmt_execute($update_data_statement)) {
    echo "<script>alert('Data Updated Successfully.')</script>";
    header('location: ../index.php?logout');
  } else {
    echo "Error updating data: " . mysqli_error($conn);
    $errors[] = "Error updating data.";
  }
}
}


// Delete User Acc
if(isset($_POST['delete_account'])){
  $user_username = $_POST['username'];
  $user_pass = $_POST['password'];
  if($user_username == $username && password_verify($user_pass, $user_row['password'])){
    $delete_user = "DELETE FROM `user` WHERE `username` = '$username'";
    $delete_result = mysqli_query($conn, $delete_user);
    if($delete_result){
      echo "<script>alert('Account Deleted Successfully')</script>";
      header('location: ../index.php?logout');
    }
  }
  else{
    echo "<script>alert('Fill the username & password fields to continue')</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?php echo $first_name . " " . $last_name ?></title>
    <!-- Bootsrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Css link -->
    <link rel="stylesheet" href="../styles/style.css">
    <style>
      body{
        overflow-x: hidden;
      }
      .profile-img{
        width: 90%;
        margin: auto;
        display: block;
        object-fit: contain;
      }
      .edit-img{
        width: 100px;
        height: 100px;
        object-fit: contain;
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
      top: 5px;
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
    
<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php"><img src="../images/logo-modified.png" alt="Zoomia" class="logo-img"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../index.php?display_all">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="user_profile.php">My Account</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup><?php echo num_cart_items(); ?></sup></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Total Price:&dollar;<?php echo total_cart_price();?>/-</a>
        </li>
        
      </ul>
      <form class="d-flex" role="search"  action="" method="get">
        <input class="form-control me-2" type="search" name="search_data" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-light" name="search_data_product" value="search" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<!-- second nav -->

<div class="navbar navbar-expand-lg navbar-dark bg-secondary">
  <ul class="navbar-nav me-auto">

    <!-- Login/Logout -->
    <?php if(!isset($_SESSION['username'])): ?>
      <li class="nav-item">
        <a class="nav-link" href="#">Welcome Guest</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="user_area/user_login.php">Login</a>
      </li>
    <?php else: ?>
      <li class="nav-item">
        <a class="nav-link" href="#">Welcome <?php echo $first_name; ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../index.php?logout">Logout</a>
      </li>
    <?php endif;  ?>
  </ul>
</div>




<!-- Heading -->

<div class="bg-light py-2">
  <h1 class="text-center text-uppercase">Zoomia store</h1>
  <p class="text-center">Anything you need</p>
</div>





<!-- User Profile -->

<div class="row">
    <div class="col-md-2">
        <ul class="navbar-nav bg-secondary text-center">
            <li class="nav-item bg-info">
                <a class="nav-link text-light fs-4" href="user_profile.php">Your Profile</a>
            </li>
            <li class="nav-item">
                <img src="user_images/<?php echo $user_image; ?>" class="profile-img my-4" alt="user_image">
            </li>
            <li class="nav-item">
                <a class="nav-link text-light fs-5" href="user_profile.php?orders">My Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light fs-5" href="user_profile.php?pending_orders">Pending Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light fs-5" href="user_profile.php?payments">My Payments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light fs-5" href="user_profile.php?edit_acc">Edit Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light fs-5" href="user_profile.php?del_acc">Delete Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light fs-5" href="../index.php?logout">Logout</a>
            </li>
        </ul>
    </div>
    <div class="col-md-10 text-center mb-4">
      
      <!-- User Orders table -->
      <?php if(isset($_GET['orders'])):
          $order_number = 1;
          $get_orders = "SELECT * FROM `user_orders` WHERE `user_id` = $user_id";
          $result_orders = mysqli_query($conn, $get_orders);
          $orders_count = mysqli_num_rows($result_orders);
          if($orders_count): ?>
        <p class="text-success fs-3 my-4">All My Orders</p>
        <table class="table table-hover">
          <thead class="table-dark">
            <tr>
              <th>Sl no</th>
              <th>Amount Due</th>
              <th>Total Products</th>
              <th>Invoice Number</th>
              <th>Date</th>
              <th>Complete/Incomplete</th>
              <th>Status</th>
              <th>Order Details</th>
            </tr>
          </thead>
          <tbody class="table-info">
            <!-- fetching orders -->
            <?php
              while($order_row = mysqli_fetch_assoc($result_orders)):
            ?>
            <tr>
                <td><?php echo $order_number ?></td>
                <td>&dollar;<?php echo $order_row['amount_due']?></td>
                <td><?php echo $order_row['total_products']?></td>
                <td><?php echo $order_row['invoice_number']?></td>
                <td><?php echo $order_row['order_date']?></td>
                <?php 
                $order_status = ($order_row['order_status'] == 'pending') ? 'InComplete' : 'Completed';
                $order_number++;
                ?>
                <td><?php echo $order_status?></td>
                <?php if($order_status == "InComplete"): ?>
                <td><a href="confirm_payment.php?order_id=<?php echo $order_row['order_id']?>" class="text-dark">Confirm</a></td>
                <?php else: ?>
                <td class="text-success">Paid</td>
                <?php endif; ?>
                <td><a href="user_profile.php?invoice_num=<?php echo $order_row['invoice_number']?>" class="text-decoration-none">See Details</a></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p class="text-success fs-3 mt-4 mb-2">You haven't order yet</p>
          <a href="../index.php?display_all" class="text-dark">Explore Products</a>
        <?php endif; ?>

        
      <!-- Order Details -->
      <?php elseif(isset($_GET['invoice_num'])):
        if($_GET['invoice_num'] == ''){
          echo "<script>window.open('user_profile.php?orders', '_self')</script>";//when i use the header func it displays a warning because the code of the page it just executed.  
        }
        else{
          $invoice_number = $_GET['invoice_num'];
          $get_order = "SELECT * FROM `user_orders` WHERE `invoice_number` = $invoice_number";
          $result_order = mysqli_query($conn, $get_order);
          $order_count = mysqli_num_rows($result_order);
          if($order_count == 0){
            echo "<script>alert('Order doesn't exist')</script>";
            echo "<script>window.open('user_profile.php?orders', '_self')</script>";        
          }
          else{
            $pend_num = 1;
            $get_pending_products = "SELECT * FROM `pending_products` 
                                     JOIN `product` ON `pending_products`.`product_id` =  `product`.`product_id`
                                     WHERE `invoice_number` = $invoice_number";
            $result_pending_products = mysqli_query($conn, $get_pending_products);?>
          <p class="text-success fs-3 my-4">Order Details</p>
          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th>Sl no</th>
                <th>Invoice Number</th>
                <th>Product Title</th>
                <th>Product Image</th>
                <th>Quantity</th>
                <th>Price <span class="fw-100">(p*1q)</span></th>

              </tr>
            </thead>
            <tbody class="table-info">
              <?php while($products_row = mysqli_fetch_assoc($result_pending_products)){ ?>
              <tr>
                  <td><?php echo $pend_num ?></td>
                  <td><?php echo $products_row['invoice_number']?></td>
                  <td><?php echo $products_row['product_title']?></td>
                  <td><img src="../admin_area/product_images/<?php echo $products_row['product_image1']?>" alt="product_image" class="edit-img"></td>
                  <td><?php echo $products_row['quantity']?></td>
                  <td>&dollar;<?php echo $products_row['product_price']?></td>
                  
                  <?php $pend_num++; ?>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php }} ?>
          

        
      
      <!-- User Payments -->
      <?php elseif(isset($_GET['payments'])): ?>
        <!-- fetching payments -->
        <?php
              $get_payments = "SELECT * FROM `user_payments` 
              JOIN `user_orders` ON `user_payments`.`order_id` = `user_orders`.`order_id`
              JOIN `user` ON `user_orders`.`user_id` = `user`.`user_id`
              WHERE `user`.`username` = '$username'";
              $result_payments = mysqli_query($conn, $get_payments);
              $payments_count = mysqli_num_rows($result_payments);
              if($payments_count):
                $pay_number = 1;
            ?>
        <p class="text-success fs-3 my-4">All My Payments</p>
        <table class="table table-hover">
          <thead class="table-dark">
            <tr>
              <th>Sl no</th>
              <th>Amount Due</th>
              <th>Invoice Number</th>
              <th>Payment Mode</th>
              <th>Payment Date</th>
            </tr>
          </thead>
          <tbody class="table-info">
            <?php while($payment_row = mysqli_fetch_assoc($result_payments)): ?>
            <tr>
                <td><?php echo $pay_number ?></td>
                <td>&dollar;<?php echo $payment_row['amount']?></td>
                <td><?php echo $payment_row['invoice_number']?></td>
                <td><?php echo $payment_row['payment_mode']?></td>
                <td><?php echo $payment_row['date']?></td>
                <?php $pay_number++; ?>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
        <p class="text-success fs-3 mt-4 mb-2">You haven't completed any payment till now!</p>
        <a href="user_profile.php?orders" class="text-dark">Show me the orders!</a>
        <?php endif; ?>


      <!-- Edit profile -->
      <?php elseif(isset($_GET['edit_acc'])): ?>
        <p class="text-success fs-3 mt-4 mb-3">Edit Account</p>
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading text-center">ERROR!</h4>
            <?php foreach ($errors as $error): ?>
                <p>*<?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="alert alert-info w-50 m-auto mb-3" role="alert">
            <h4 class="alert-heading">Read Carefully</h4>
            <p>*First and Last name must be at least 3 characters long.</p>
            <p>*Password should contain at least 8 characters, including at least one capital letter, one number, and one symbol.</p>
            <p>*Email must be in a valid format (e.g., user@example.com).</p>
            <p>*Phone number must be valid based on the selected country code.</p>
        </div>
        <form action="user_profile.php" method="post" enctype="multipart/form-data" class="form mt-4" id="updateForm">
          <!-- first -->
          <div class="form-outline mb-4 w-50 m-auto d-flex align-items-center justify-content-evenly gap-2">
            <div class="form-outline w-50 m-auto">
              <input type="text" class="form-control" value="<?php echo $first_name ?>" name="first_name" placeholder="Your first name...." autocomplete="off" required id="first_name">
              <div class="form-error text-danger" id="firstNameError"></div>
            </div>
          <!-- last -->
            <div class="form-outline w-50 m-auto">
              <input type="text" class="form-control" value="<?php echo $last_name ?>" name="last_name" placeholder="Your last name....." autocomplete="off" required id="last_name">
              <div class="form-error text-danger" id="lastNameError"></div>
            </div>
          </div>
          <!-- username -->
          <div class="input-group mb-4 w-50 m-auto">
            <span class="input-group-text"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
            <input type="text" class="form-control" value="<?php echo $username ?>" name="user_name" placeholder="Your username" autocomplete="off" required disabled>
          </div>
          <!-- email -->
          <div class="form-outline w-50 m-auto mb-4">
            <div class="input-group">
              <span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
              <input type="email" class="form-control" value="<?php echo $user_email ?>" name="user_email" placeholder="Your email" autocomplete="off" required id="email">
            </div>
            <div class="form-error text-danger" id="emailError"></div>
          </div>
          <!-- password -->
          <div class="input-group mb-4 w-50 m-auto position-relative">
            <span class="input-group-text"><i class="fa fa-key" aria-hidden="true"></i></span>
            <input type="password" class="form-control" value="XXXXXXXXXX" name="user_password" placeholder="Your password" autocomplete="off" required id="password" id="password">
            <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                <i class="fa fa-eye" id="togglePasswordIcon"></i>
            </span>
            <div class="form-error text-danger" id="passwordError"></div>
          </div>
          <!-- Image -->
          <div class="form-outline mb-4 w-50 m-auto d-flex">
            <input type="file" class="form-control" id="image" name="user_image">
            <img src="user_images/<?php echo $user_image?>" alt="Your Image" class="edit-img">
            <div class="form-error" id="imageError"></div>
          </div>
          <!-- Address -->
          <div class="input-group mb-4 w-50 m-auto">
            <span class="input-group-text"><i class="fa fa-address-book" aria-hidden="true"></i></span>
            <input type="text" class="form-control" value="<?php echo $user_address ?>" name="user_address" placeholder="Your address" autocomplete="off" required>
            <div class="form-error" id="addressError"></div>
          </div>
          <!-- Contact : mobile -->
          <div class="input-group mb-4 w-50 m-auto">
            <span class="input-group-text"><i class="fa fa-phone" aria-hidden="true"></i></span>
            <input type="tel" class="form-control" value="<?php echo $user_phone ?>" name="user_phone" placeholder="Your phone number" autocomplete="off" required id="phone">
            <div class="form-error" id="phoneError"></div>
          </div>
          <!-- submit -->
          <div class="mt-4 d-flex align-items-center justify-content-evenly">
            <input type="submit" value="Update" name="user_update" class="btn btn-primary">
            <a href="user_profile.php" class="btn btn-secondary">Cancel</a>
          </div>
      </form>

      <!-- Deleting User Account -->
      <?php elseif(isset($_GET['del_acc'])): ?>
        <p class="text-success fs-2 mt-4 mb-2">Delete Account</p>
        <p class="text-danger fs-5 w-50 m-auto">Are you sure? By deleting your account, your will be losing your account data and orders. Once you delete your account, these information cannot be recovered.</p>
        <form action="" method="post" class="mt-5">
          <!-- username -->
          <div class="input-group mb-4 w-50 m-auto">
            <span class="input-group-text"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
            <input type="text" class="form-control" name="username" placeholder="Your username" autocomplete="off" required>
          </div>
          <!-- password -->
          <div class="input-group mb-4 w-50 m-auto position-relative">
            <span class="input-group-text"><i class="fa fa-key" aria-hidden="true"></i></span>
            <input type="password" class="form-control" name="password" placeholder="Your password" autocomplete="off" required id="password">
            <span class="position-absolute  eye-icon" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                <i class="fa fa-eye" id="togglePasswordIcon"></i>
            </span>
          </div>
          <!-- submit -->
          <div class="mt-4 d-flex align-items-center justify-content-evenly">
            <input type="submit" value="Delete My Account" name="delete_account" class="btn btn-danger">
            <a href="user_profile.php" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      <!-- if user have pending orders -->
      <?php else: ?>
      <?php if($pending_orders_count): ?>
        <p class="text-success fs-3 mt-4 mb-2">You have <span class="text-danger"><?php echo $pending_orders_count ?></span> Pending orders</p>
        <a href="user_profile.php?orders" class="text-dark">Order_Details</a>
      <?php else: ?>
        <p class="text-success fs-3 mt-4 mb-2">You have <span class="text-danger text-uppercase">zero</span> Pending orders</p>
        <a href="../index.php?display_all" class="text-dark">Explore Products</a>
      <?php endif; ?>
      <?php endif; ?>
    </div>
</div>


<?php } ?>

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


      // Form validation
      const form = document.getElementById('updateForm');
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
</script>

<!-- footer -->
<?php include "../includes/footer.php"; ?>