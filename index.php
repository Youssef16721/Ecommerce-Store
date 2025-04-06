<?php 
require "config/connection.php"; 
include("functions/common_functions.php");

session_start();
if(isset($_GET['logout'])){
  session_unset();
  session_destroy();
  header('location: index.php');
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce store</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS link -->
    <link rel="stylesheet" href="styles/style.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo-modified.png" type="image/x-icon">

</head>
<?php 
	if(isset($_GET['page_nr']))
		$link_id = $_GET['page_nr'];
	else{
		$link_id = 1;
		$_GET['page_nr'] = 1;
	}
?>
<body id="<?php echo $link_id?>">
    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-sticky">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><img src="images/favicon.gif" alt="Zoomia" class="logo-img"></a>
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
		<?php if(isset($_SESSION['admin_name'])): ?>
			<a class="nav-link" href="admin_area/index.php">Admin Area</a>
		<?php else: ?>
          <a class="nav-link" href="admin_area/admin_login.php">Admin Area</a>
		<?php endif; ?>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup><?php echo num_cart_items(); ?></sup></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Total Price:&dollar;<?php echo total_cart_price();?>/-</a>
        </li>
      </ul>
      <form class="d-flex" role="search" action="" method="get">
        <input class="form-control me-2" type="search" name="search_data" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-light" name="search_data_product" value="search" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<!-- Second Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
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
    <?php endif; ?>
  </ul>
</nav>

<style>
	@keyframes appear {
		form{
			opacity: 0;
			clip-path: inset(100% 100% 0 0);
		}
		to{
			opacity: 1;
			clip-path: inset(0 0 0 0);
		}
	}
	.view-block{
		animation: appear linear;
		animation-timeline: view();
		animation-range: entry 0% cover 40%;
	}
</style>
<div class="view">

<!-- Heading -->
<div class="bg-light py-4 text-center view-block">
  <h1 class="text-uppercase">Zoomia Store</h1>
  <p>Anything you need</p>
</div>


<!-- Products Section -->
<div class="container mb-4 view-block">
  <div class="row">
    <!-- Main Content -->
    <div class="col-md-10">
      <div class="row">
        <?php

	// pagination common part
	$start = 0;
	// number of prod per page
	$rows_per_page = 9;
	
	$prod_num_rows = 0;


	//displaying all category products
	if(isset($_GET['category'])){
		$category_id = $_GET['category'];
		$select_products = "SELECT * FROM product WHERE category_id = '$category_id' ORDER BY rand()";
		$select_products_result = mysqli_query($conn, $select_products);

		//check if there are products present in this category
		if(!mysqli_num_rows($select_products_result))
			echo "<h2 class=\"text-center text-danger\">There are no products present in this category</h2>";

		while($product_row = mysqli_fetch_assoc($select_products_result)){
			$product_id = $product_row['product_id'];
			$product_title = $product_row['product_title'];
			$product_desc = $product_row['product_desc'];
			$product_keywords = $product_row['product_keywords'];
			$product_image1 = $product_row['product_image1'];
			$product_image2 = $product_row['product_image2'];
			$product_image3 = $product_row['product_image3'];
			$product_price = $product_row['product_price'];
			$product_category = $product_row['category_id'];
			$product_brand = $product_row['brand_id'];
			echo ("<div class='card-wrapper'>
			<div class='card-container'>
				<div class='small-card'>
					<i class='fas fa-heart'></i>
				</div>
				<div class='card-top'>
					<img src='admin_area/product_images/$product_image1' alt=''>
				</div>
				<div class='card-bottom'>
					<div class='card-left'>
						<div class='card-details-1'>
							<h1>$product_title</h1>
							<p>&dollar;$product_price/-</p>
						</div>
						<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
					</div>
					<div class='card-right'>
						<div class='card-btn-done'><i class='fas fa-check'></i></div>
						<div class='card-details-2'>
							<h1>$product_title</h1>
							<p>Added to your cart</p>
						</div>
						<div class='card-btn-remove'><i class='fas fa-times'></i></div>
					</div>
				</div>
			</div>
			<div class='card-inside'>
				<div class='card-icon'><i class='fas fa-info-circle'></i></div>
				<div class='card-contents'>
					<h2>Product Details</h2>
					<div class='desc text-center my-4'>
						<p>$product_desc</p>
					</div>
					<div class='review-icon text-center'>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star-half-alt'></i>
					</div>
					<div class='button-container text-center'>
						<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
					</div>
				</div>
			</div>
		</div>");
		}
	}

	//displaying all brand products
	else if(isset($_GET['brand'])){
		$brand_id = $_GET['brand'];
		$select_products = "SELECT * FROM product WHERE brand_id = '$brand_id' ORDER BY rand()";
		$select_products_result = mysqli_query($conn, $select_products);

		//check if there are products present in this brand
		if(!mysqli_num_rows($select_products_result))
			echo "<h2 class=\"text-center text-danger\">There are no products present in this brand</h2>";

		while($product_row = mysqli_fetch_assoc($select_products_result)){
			$product_id = $product_row['product_id'];
			$product_title = $product_row['product_title'];
			$product_desc = $product_row['product_desc'];
			$product_keywords = $product_row['product_keywords'];
			$product_image1 = $product_row['product_image1'];
			$product_image2 = $product_row['product_image2'];
			$product_image3 = $product_row['product_image3'];
			$product_price = $product_row['product_price'];
			$product_category = $product_row['category_id'];
			$product_brand = $product_row['brand_id'];
			echo ("<div class='card-wrapper'>
			<div class='card-container'>
				<div class='small-card'>
					<i class='fas fa-heart'></i>
				</div>
				<div class='card-top'>
					<img src='admin_area/product_images/$product_image1' alt=''>
				</div>
				<div class='card-bottom'>
					<div class='card-left'>
						<div class='card-details-1'>
							<h1>$product_title</h1>
							<p>&dollar;$product_price/-</p>
						</div>
						<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
					</div>
					<div class='card-right'>
						<div class='card-btn-done'><i class='fas fa-check'></i></div>
						<div class='card-details-2'>
							<h1>$product_title</h1>
							<p>Added to your cart</p>
						</div>
						<div class='card-btn-remove'><i class='fas fa-times'></i></div>
					</div>
				</div>
			</div>
			<div class='card-inside'>
				<div class='card-icon'><i class='fas fa-info-circle'></i></div>
				<div class='card-contents'>
					<h2>Product Details</h2>
					<div class='desc text-center my-4'>
						<p>$product_desc</p>
					</div>
					<div class='review-icon text-center'>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star-half-alt'></i>
					</div>
					<div class='button-container text-center'>
						<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
					</div>
				</div>
			</div>
		</div>");
		}
	}

	//searching products
	else if(isset($_GET['search_data_product'])){
		$search_keywords = htmlspecialchars($_GET['search_data']);
		$search_products = "SELECT * FROM `product` WHERE product_keywords LIKE '%$search_keywords%'";
		$search_query_result = mysqli_query($conn, $search_products);

		//check if there are products match the search query
		if(!mysqli_num_rows($search_query_result))
			echo "<h2 class=\"text-center text-danger\">There are no products that match the search!</h2>";

		while($product_row = mysqli_fetch_assoc($search_query_result)){
			$product_id = $product_row['product_id'];
			$product_title = $product_row['product_title'];
			$product_desc = $product_row['product_desc'];
			$product_keywords = $product_row['product_keywords'];
			$product_image1 = $product_row['product_image1'];
			$product_image2 = $product_row['product_image2'];
			$product_image3 = $product_row['product_image3'];
			$product_price = $product_row['product_price'];
			$product_category = $product_row['category_id'];
			$product_brand = $product_row['brand_id'];
			echo ("<div class='card-wrapper'>
			<div class='card-container'>
				<div class='small-card'>
					<i class='fas fa-heart'></i>
				</div>
				<div class='card-top'>
					<img src='admin_area/product_images/$product_image1' alt=''>
				</div>
				<div class='card-bottom'>
					<div class='card-left'>
						<div class='card-details-1'>
							<h1>$product_title</h1>
							<p>&dollar;$product_price/-</p>
						</div>
						<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
					</div>
					<div class='card-right'>
						<div class='card-btn-done'><i class='fas fa-check'></i></div>
						<div class='card-details-2'>
							<h1>$product_title</h1>
							<p>Added to your cart</p>
						</div>
						<div class='card-btn-remove'><i class='fas fa-times'></i></div>
					</div>
				</div>
			</div>
			<div class='card-inside'>
				<div class='card-icon'><i class='fas fa-info-circle'></i></div>
				<div class='card-contents'>
					<h2>Product Details</h2>
					<div class='desc text-center my-4'>
						<p>$product_desc</p>
					</div>
					<div class='review-icon text-center'>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star-half-alt'></i>
					</div>
					<div class='button-container text-center'>
						<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
					</div>
				</div>
			</div>
		</div>");
		}
	}

	//displaying all products
	else{
		$records = "SELECT * FROM `product`";
		$records_result = mysqli_query($conn, $records);

    //pagination part
		$prod_num_rows = mysqli_num_rows($records_result);
		$pages = ceil($prod_num_rows / $rows_per_page);

    if(isset($_GET['page_nr'])){
      $page = $_GET['page_nr'] - 1;
      $start = $page * $rows_per_page;
    }
    $select_products = "SELECT * FROM `product` LIMIT $start, $rows_per_page";
		$select_products_result = mysqli_query($conn, $select_products);

		while($product_row = mysqli_fetch_assoc($select_products_result)){
			$product_id = $product_row['product_id'];
			$product_title = $product_row['product_title'];
			$product_desc = $product_row['product_desc'];
			$product_keywords = $product_row['product_keywords'];
			$product_image1 = $product_row['product_image1'];
			$product_image2 = $product_row['product_image2'];
			$product_image3 = $product_row['product_image3'];
			$product_price = $product_row['product_price'];
			$product_category = $product_row['category_id'];
			$product_brand = $product_row['brand_id'];
			echo ("<div class='card-wrapper'>
			<div class='card-container'>
				<div class='small-card'>
					<i class='fas fa-heart'></i>
				</div>
				<div class='card-top'>
					<img src='admin_area/product_images/$product_image1' alt=''>
				</div>
				<div class='card-bottom'>
					<div class='card-left'>
						<div class='card-details-1'>
							<h1>$product_title</h1>
							<p>&dollar;$product_price/-</p>
						</div>
						<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
					</div>
					<div class='card-right'>
						<div class='card-btn-done'><i class='fas fa-check'></i></div>
						<div class='card-details-2'>
							<h1>$product_title</h1>
							<p>Added to your cart</p>
						</div>
						<div class='card-btn-remove'><i class='fas fa-times'></i></div>
					</div>
				</div>
			</div>
			<div class='card-inside'>
				<div class='card-icon'><i class='fas fa-info-circle'></i></div>
				<div class='card-contents'>
					<h2>Product Details</h2>
					<div class='desc text-center my-4'>
						<p>$product_desc</p>
					</div>
					<div class='review-icon text-center'>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star'></i>
						<i class='fas fa-star-half-alt'></i>
					</div>
					<div class='button-container text-center'>
						<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
					</div>
				</div>
			</div>
		</div>");
		}
	}






if(isset($_GET['add_to_cart']))
            cart();
        ?>
      </div>
    </div>
    <!-- Sidebar -->
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
        <?php get_categories(); ?>
      </ul>
    </div>
  </div>
</div>


<?php if($prod_num_rows > $rows_per_page && !isset($_GET['search_data_product']) && !isset($_GET['brand']) && !isset($_GET['category'])){ ?>
<div class="page-info text-center text-primary my-5 view-block">
  <h2>Showing <?php echo $_GET['page_nr'] ?> of <?php echo $pages?> Pages</h2>
  <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center mt-3">
  <li class="page-item"><a class="page-link" href="?page_nr=1">First</a></li>

    <!-- previous btn -->
    <?php if(isset($_GET['page_nr']) && $_GET['page_nr'] > 1){ ?>

    <li class="page-item">
      <a class="page-link" href="?page_nr=<?php echo $_GET['page_nr']-1?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <?php }
    else{ ?>
    <li class="page-item">
    <a class="page-link" href="" aria-label="Previous">
      <span aria-hidden="true">&laquo;</span>
      <span class="sr-only">Previous</span>
    </a>
    </li>
    <?php }?>

    <!-- pages numbers -->
    <?php for($counter = 1; $counter <= $pages; $counter++){ ?>
    <li class="page-item">
		<a class="page-link" href="?page_nr=<?php echo $counter?>">
		<?php echo $counter?></a></li>
    <?php }?>

    <!-- next btn -->
    <?php if(!isset($_GET['page_nr'])){ ?>
    <li class="page-item">
    <a class="page-link" href="?page_nr=2" aria-label="Next">
      <span aria-hidden="true">&raquo;</span>
      <span class="sr-only">Next</span>
    </a>
    </li>
    <?php } else{
    if($_GET['page_nr'] >= $pages){ ?>
    <li class="page-item">
      <a class="page-link" href="" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
    <?php } else{?>
    <li class="page-item">
    <a class="page-link" href="?page_nr=<?php echo $_GET['page_nr'] + 1?>" aria-label="Next">
      <span aria-hidden="true">&raquo;</span>
      <span class="sr-only">Next</span>
    </a>
    </li>
    <?php }}?>
    <li class="page-item"><a class="page-link" href="?page_nr=<?php echo $pages?>">Last</a></li>
  </ul>
</nav>
</div>
<?php }?>

</div>

<!-- loader -->
<div class="loader"></div>

<!-- Card specific JavaScript -->
<script>
   document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.card-btn-buy').forEach(function(buyButton) {
        buyButton.addEventListener('click', function() {
            const cardBottom = this.closest('.card-bottom');
            if (cardBottom) {
                cardBottom.classList.add('clicked');
            }
        });
    });

    document.querySelectorAll('.card-btn-remove').forEach(function(removeButton) {
        removeButton.addEventListener('click', function() {
            const cardBottom = this.closest('.card-bottom');
            if (cardBottom) {
                cardBottom.classList.remove('clicked');
            }
        });
    });

    document.querySelectorAll('.fa-heart').forEach(function(heartIcon) {
        heartIcon.addEventListener('mouseover', function() {
            if (heartIcon) {
                heartIcon.classList.add('fa-shake');
            }
        });

        heartIcon.addEventListener('mouseout', function() {
            if (heartIcon) {
                heartIcon.classList.remove('fa-shake');
            }
        });
    });

    document.querySelectorAll('.card-inside').forEach(function(cardInside) {
        cardInside.addEventListener('mouseover', function() {
            const cardContainer = this.closest('.card-container');
            if (cardContainer) {
                const smallCard = cardContainer.querySelector('.small-card');
                if (smallCard) {
                    smallCard.style.opacity = '0';
                }
            }
        });

        cardInside.addEventListener('mouseout', function() {
            const cardContainer = this.closest('.card-container');
            if (cardContainer) {
                const smallCard = cardContainer.querySelector('.small-card');
                if (smallCard) {
                    smallCard.style.opacity = '1';
                }
            }
        });
    });

    // Loader functionality
    const loader = document.querySelector('.loader');
    if (loader) {
        window.addEventListener('load', function() {
            loader.classList.add('loader--hidden');
        });
    }


	let links = document.querySelectorAll('.page-item > a');
	let bodyId = parseInt(document.body.id) + 1;
	links[bodyId].classList.add("active");
});

</script>

<!-- footer -->
<?php include "includes/footer.php"; ?>