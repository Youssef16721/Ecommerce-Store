<?php 
require "config/connection.php"; 
include("functions/common_functions.php");

session_start();

$ip_add = getIPAddress();

if(isset($_GET['search_data_product'])){
  $search_data = $_GET['search_data'];
  echo "<script>window.open('index.php?search_data=$search_data&search_data_product=search', '_self')</script>";
}
// Update a single item
if(isset($_POST['update_item'])){
  // echo "<pre>";
  // print_r($_POST);
  // echo "<pre>";
  // Iterate over $_POST['update_item'] to handle each update request
  foreach($_POST['update_item'] as $product_id => $update_button){
    $item_quantity = isset($_POST['item_quantity'][$product_id]) ? intval($_POST['item_quantity'][$product_id]) : 0;
    // echo $item_quantity."<br>";
    // Validate the quantity (e.g., ensure it's a positive integer and not an empty text)
    if($item_quantity > 0){
        $update_item_query = "UPDATE `cart_details` SET `quantity` = ? 
        WHERE `cart_details`.`product_id` = ? AND ip_address = ?";
        $update_item_statement = mysqli_prepare($conn, $update_item_query);
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($update_item_statement, "iis", $item_quantity, $product_id, $ip_add);
        if (mysqli_stmt_execute($update_item_statement)) {
          echo "<script>alert('Quantity updated successfully.')</script>";
        } else {
          echo "Error updating quantity: " . mysqli_error($conn);
        }
    }
    else if($item_quantity < 0){
      echo "<script>alert('Enter a positive integer number')</script>";
    }
    else{
      echo "<script>alert('You must insert the quantity.')</script>";
    }
  }
}


// Remove a single item
if(isset($_POST['remove_item'])){

  foreach($_POST['remove_item'] as $product_id => $remove_button){

    // Remove the item from the database
    $remove_item_query = "DELETE FROM `cart_details` WHERE `product_id` = ? AND ip_address = ?";
    $remove_item_statement = mysqli_prepare($conn, $remove_item_query);
    mysqli_stmt_bind_param($remove_item_statement, "is", $product_id, $ip_add);
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($remove_item_statement, "is", $product_id, $ip_add);
        if (mysqli_stmt_execute($remove_item_statement)) {
          echo "<script>alert('Item removed successfully.')</script>";
        } else {
          echo "Error removing item: " . mysqli_error($conn);
        }
  }
}



// Update selected items
if (isset($_POST['update_selected_items'])) {
  if (isset($_POST['selected_items'])) {
      foreach ($_POST['selected_items'] as $selected_item) {
          $product_id = $selected_item;
          $item_quantity = isset($_POST['item_quantity'][$product_id]) ? intval($_POST['item_quantity'][$product_id]) : 0;
          // Validate the quantity (e.g., ensure it's a positive integer and not an empty text)
        if($item_quantity > 0){
          $update_item_query = "UPDATE `cart_details` SET `quantity` = ? 
          WHERE `cart_details`.`product_id` = ? AND ip_address = ?";
          $update_item_statement = mysqli_prepare($conn, $update_item_query);
          // Bind parameters and execute the statement
          mysqli_stmt_bind_param($update_item_statement, "iis", $item_quantity, $product_id, $ip_add);
          if (mysqli_stmt_execute($update_item_statement)) {
            echo "<script>alert('Quantity updated successfully.')</script>";
          } else {
            echo "Error updating quantity: " . mysqli_error($conn);
          }
        }
        else if($item_quantity < 0){
          echo "<script>alert('Enter a positive integer number')</script>";
        }
        else{
          echo "<script>alert('You must insert the quantity.')</script>";
        }
      }
  }
  else{
    echo "<script>alert('Select items you want to update!')</script>";
  }
}


// Remove selected items
if (isset($_POST['remove_selected_items'])) {
  if (isset($_POST['selected_items'])) {
      foreach ($_POST['selected_items'] as $selected_item) {
        $product_id = $selected_item;
        $remove_item_query = "DELETE FROM `cart_details` WHERE `product_id` = ? AND ip_address = ?";
        $remove_item_statement = mysqli_prepare($conn, $remove_item_query);
        mysqli_stmt_bind_param($remove_item_statement, "is", $product_id, $ip_add);
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($remove_item_statement, "is", $product_id, $ip_add);
        if (mysqli_stmt_execute($remove_item_statement)) {
          echo "<script>alert('Items removed successfully.')</script>";
        } else {
          echo "Error removing items: " . mysqli_error($conn);
        }
      }
  }
  else{
    echo "<script>alert('Select items you want to remove!')</script>";
  }
}



$cart_items = "SELECT * FROM `cart_details` JOIN `product` ON cart_details.product_id = product.product_id WHERE ip_address = '$ip_add'";
$result_items = mysqli_query($conn, $cart_items);
$result_num_rows = mysqli_num_rows($result_items);
// code completed below
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce</title>
    <!-- Bootsrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font awesome cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Css link -->
    <link rel="stylesheet" href="styles/style.css">
    <style>
      .cart-img{
        width: 30%;
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
    </style>
</head>
<body>
    
<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><img src="images/logo-modified.png" alt="Zoomia" class="logo-img"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?display_all">Products</a>
        </li>
        <!-- Register/Account -->
        <?php if(!isset($_SESSION['username'])): ?>
        <li class="nav-item">
          <a class="nav-link" href="user_area/user_registration.php">Register</a>
        </li>
        <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="user_area/user_profile.php">My Account</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup><?php echo num_cart_items(); ?></sup></a>
        </li>
      </ul>
      <form class="d-flex" role="search" action="" method="get">
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
      <a class="nav-link" href="user_area/user_profile.php">Welcome <?php echo $_SESSION['first_name'] ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?logout">Logout</a>
      </li>
    <?php endif;  ?>
  </ul>
</div>



<!-- Heading -->
<div class="bg-light py-5">
  <h1 class="text-center text-uppercase">Zoomia store</h1>
  <p class="text-center">Anything you need</p>
</div>



<!-- items table -->
<div class="container">
    <div class="row">
      <!--Check if cart is empty -->
      <?php if($result_num_rows > 0): ?>
      <form action="cart.php" method="post">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <th>Select Item</th>
                <th>Product Title</th>
                <th>Product Image</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th colspan="2">Operations</th>
            </thead>
            <tbody>
              <!-- fetching cart items -->
              <?php
              $total_price = 0;
              while($item_row = mysqli_fetch_assoc($result_items)):
                $product_id = $item_row['product_id'];
                $product_price = $item_row['product_price'];
                $product_title = $item_row['product_title'];
                $product_image1 = $item_row['product_image1'];
                $product_quantity = $item_row['quantity'];
                $product_total_price = $product_price * $product_quantity;
                $total_price += $product_total_price; 
                ?>

                <tr>
                    <!-- <input type="hidden" name="product_id" value="<?php //echo $product_id ?>"> -->
                    <td><input type="checkbox" name="selected_items[]" value="<?php echo $product_id; ?>"></td>
                    <td><?php echo $product_title ?></td>
                    <td><img src="admin_area/product_images/<?php echo $product_image1; ?>" alt="<?php echo $product_title; ?>" class="cart-img"></td>
                    <td>
                      <div class="form-floating w-60 m-auto">
                        <input id="prodqty" type="number" name="item_quantity[<?php echo $product_id; ?>]" class="form-control">
                        <label for="prodqty">Product quantity is :<?php echo $product_quantity?></label>
                      </div>
                    </td>    
                    <td>&dollar;<?php echo $product_total_price ?>/-</td>                    
                    <td><button type="submit" name="update_item[<?php echo $product_id; ?>]" class="btn btn-warning">Update Item</button></td>
                    <td><button type="submit" name="remove_item[<?php echo $product_id; ?>]" class="btn btn-danger">Remove Item</button></td>
                </tr>

              <?php endwhile; ?>
            </tbody>
        </table>
        <!-- subtotal -->
        <h4 class="text-info">Subtotal:<strong>&dollar;<?php echo $total_price ?>/-</strong></h4>
        <div class="d-flex justify-content-between my-3">
          <div>
            <a href="index.php" class="btn btn-secondary mx-3">Continue shopping</a>
            <a href="cart.php?checkout" class="btn btn-primary">CheckOut</a>
          </div>
          <div>
            <button type="submit" name="update_selected_items" class="btn btn-warning mx-3">Update selected items</button>
            <button type="submit" name="remove_selected_items" class="btn btn-danger">Remove selected items</button>
          </div>
        </div>
      </form>
      <?php else: ?>
        <h2 class="text-center text-danger mt-5">Cart Is Empty</h2>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary my-3">Continue Shopping</a>
        </div>
      <?php endif; ?>
    </div>
</div>

<?php
  if(isset($_GET['checkout'])){
    if(isset($_SESSION['username']))
      echo "<script>window.open('user_area/payment.php', '_self')</script>";
    else
      echo "<script>window.open('user_area/user_login.php', '_self')</script>";
  }
?>


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
</script>

<!-- footer -->
<?php include "includes/footer.php"; ?>