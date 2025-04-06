<?php 
include("../config/connection.php"); 
include("../functions/common_functions.php");

session_start();
if(!isset($_SESSION['admin_name']))
    header('location: admin_login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin's Dashboard</title>
    <!-- Bootstrap JS and jQuery (required for modal functionality) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Bootsrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- favicon -->
    <link rel="shortcut icon" href="../images/logo-modified.png" type="image/x-icon">

    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>


<style>
body {
    background-color: #f8f9fa; 
    overflow-x: hidden;
}

.btn {
    color: #fff;
}

.btn-info {
    background-color: #17a2b8; 
    border-color: #17a2b8;
    margin-bottom: 10px;
}

.btn-info:hover {
    background-color: #138496; 
    border-color: #117a8b;
}

.product-img{
    width: 100px;
    height: 100px;
    object-fit: contain;
}
.admin-img{
    width: 200px;
    height: 200px;
}
.user-img{
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
</style> 
    
<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php"><img src="../images/logo-modified.png" alt="Zoomia" class="logo-img"></a>
            <nav class="navbar navbar-expand-lg">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_profile.php">Welcome <?php echo $_SESSION['admin_name']?></a>
                    </li>
                </ul>
            </nav>
        </div>
    </nav> 


    <!-- Options -->
    <div class="bg-light">
        <h3 class="text-center py-2">Manage Details</h3>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-10 bg-secondary p-2 d-flex align-items-center">
            <div>
                <a href="index.php"><img src="admin_images/<?php echo $_SESSION['admin_image']?>" alt="" class="admin-img rounded-circle mb-3"></a>
                <p class="text-light text-center mb-0"><?php echo $_SESSION['admin_name']?></p>
            </div>
            <div class="m-2 text-center">
                <a href="index.php?insert_product" class="btn btn-info btn-block">Insert Product</a>
                <a href="index.php?view_products" class="btn btn-info btn-block">View Products</a>
                <a href="index.php?insert_category" class="btn btn-info btn-block">Insert Category</a>
                <a href="index.php?view_categories" class="btn btn-info btn-block">View Categories</a>
                <a href="index.php?insert_brand" class="btn btn-info btn-block">Insert Brand</a>
                <a href="index.php?view_brands" class="btn btn-info btn-block">View Brands</a>
                <a href="index.php?list_orders" class="btn btn-info btn-block">All Orders</a>
                <a href="index.php?list_payments" class="btn btn-info btn-block">All Payments</a>
                <a href="index.php?list_users" class="btn btn-info btn-block">List Users</a>
                <a href="../index.php?logout" class="btn btn-danger btn-block mb-2">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
<?php 
    if(isset($_GET["insert_category"]))
        include ("insert_categories.php");

    elseif(isset($_GET["insert_brand"]))
        include ("insert_brands.php");

    elseif(isset($_GET["insert_product"])){
        echo "<script>window.open('insert_products.php', '_self')</script>";
    }

    elseif(isset($_GET['view_products']))
        include("view_products.php");

    elseif(isset($_GET['edit_product']))
        include("edit_product.php");

    elseif(isset($_GET['delete_product'])){
        $product_id = $_GET['delete_product'];
        $delete_prod = "DELETE FROM `product` WHERE `product_id` = $product_id";
        $delete_prod_result = mysqli_query($conn, $delete_prod);
        if($delete_prod_result){
            echo "<script>alert('Product Has Been Deleted Successfully')</script>";
            echo "<script>window.open('index.php?view_products', '_self')</script>";
        }
    }
    elseif(isset($_GET['view_categories']))
        include("view_categories.php");

    elseif(isset($_GET['view_brands']))
        include("view_brands.php");
    
    elseif(isset($_GET['edit_category']))
        include("edit_category.php");
    
    elseif(isset($_GET['delete_category'])){
        $category_id = $_GET['delete_category'];
        $delete_category = "DELETE FROM `category` WHERE `category_id` = $category_id";
        $delete_category_result = mysqli_query($conn, $delete_category);
        if($delete_category_result){
            echo "<script>alert('Category Has Been Deleted Successfully')</script>";
            echo "<script>window.open('index.php?view_categories', '_self')</script>";
        }
    }
    
    elseif(isset($_GET['edit_brand']))
        include("edit_brand.php");

    elseif(isset($_GET['delete_brand'])) {
        $brand_id = $_GET['delete_brand'];
        $delete_brand = "DELETE FROM `brand` WHERE `brand_id` = $brand_id";
        $delete_brand_result = mysqli_query($conn, $delete_brand);
        if($delete_brand_result){
            echo "<script>alert('Brand Has Been Deleted Successfully')</script>";
            echo "<script>window.open('index.php?view_brands', '_self')</script>";
        }
    }
    elseif(isset($_GET['list_orders']))
        include("list_orders.php");

    elseif(isset($_GET['delete_order'])){
        $order_id = $_GET['delete_order'];
        $delete_order = "DELETE FROM `user_orders` WHERE `order_id` = $order_id";
        $delete_order_result = mysqli_query($conn, $delete_order);
        if($delete_order_result){
            echo "<script>alert('Order Has Been Deleted Successfully')</script>";
            echo "<script>window.open('index.php?list_orders', '_self')</script>";   
        }
    }
    elseif(isset($_GET['invoice_num']))
        include("order_details.php");
    
    elseif(isset($_GET['list_payments']))
        include("list_payments.php");

    elseif(isset($_GET['delete_payment'])){
        $pay_id = $_GET['delete_payment'];
        $delete_payment = "DELETE FROM `user_payments` WHERE `payment_id` = $pay_id";
        $delete_payment_result = mysqli_query($conn, $delete_payment);
        if($delete_payment_result){
            echo "<script>alert('Payment Has Been Deleted Successfully')</script>";
            echo "<script>window.open('index.php?list_payments', '_self')</script>";   
        }
    }
    elseif(isset($_GET['list_users']))
        include("list_users.php");

    elseif(isset($_GET['delete-user'])){
        $user_id = $_GET['delete-user'];
        $delete_user = "DELETE FROM `user` WHERE `user_id` = $user_id";
        $delete_user_result = mysqli_query($conn, $delete_user);
        if($delete_user_result){
            echo "<script>alert('User Has Been Deleted Successfully')</script>";
            echo "<script>window.open('index.php?list_users', '_self')</script>";   
        }
    }
        
?>
        </div>
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

<?php include "../includes/footer.php"; ?>