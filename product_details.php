<?php 
require "config/connection.php"; 
include("functions/common_functions.php");

session_start();

if(isset($_GET['add_to_cart']))
  cart();
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
      body{
        overflow-x: hidden;
      }
    </style>
</head>
<body>
    
<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="images/logo-modified.png" alt="Zoomia" class="logo-img"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?display_all">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="user_area/user_registration.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"><sup><?php echo num_cart_items(); ?></sup></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Total Price:&dollar;<?php echo total_cart_price(); ?>/-</a>
        </li>
        
      </ul>
      <form class="d-flex" role="search"  action="index.php" method="get">
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
        <a class="nav-link" href="#">Welcome <?php echo $_SESSION['username'] ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?logout">Logout</a>
      </li>
    <?php endif;  ?>
  </ul>
</div>



<!-- 1 container -->

<div class="bg-light py-2">
  <h1 class="text-center text-uppercase">Zoomia store</h1>
  <p class="text-center">Anything you need</p>
</div>


<!-- products -->
<div class="row px-1">
  <!-- cards -->
  <div class="col-md-10">
    <div class="row">
        <?php view_product_details(); ?>
    </div>

  </div>
  <!-- side nav -->
  <div class="col-md-2 bg-secondary p-0">
    <!-- Brands -->
    <ul class="navbar-nav me-auto text-center">
      <li class="nav-item bg-info">
        <a class="nav-link text-light fs-4 fw-bold" href="#">Brands</a>
      </li>
      <?php get_brands(); ?>
    </ul>
    <!-- Categories -->
    <ul class="navbar-nav me-auto text-center">
      <li class="nav-item bg-info">
        <a class="nav-link text-light fs-4 fw-bold" href="#">Categories</a>
      </li>
      <?php get_categories();  ?>
    </ul>
  </div>
</div>





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
