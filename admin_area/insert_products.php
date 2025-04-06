
<?php require "../config/connection.php"; 
if(isset($_POST['insert_products'])){
   $product_title = htmlspecialchars($_POST['product_title']);
   $product_desc = htmlspecialchars($_POST['product_desc']);
   $product_keywords = htmlspecialchars($_POST['product_keywords']);
   $product_price = htmlspecialchars($_POST['product_price']);
   $product_brand = htmlspecialchars($_POST['product_brand']);
   $product_category = htmlspecialchars($_POST['product_category']);
   $product_status = 'true';

   ///Accessing images
   $product_image1 = $_FILES['product_image1']['name'];
   $product_image2 = $_FILES['product_image2']['name'];
   $product_image3 = $_FILES['product_image3']['name'];

   //Images tmp name
   $temp_image1 = $_FILES['product_image1']['tmp_name'];
   $temp_image2 = $_FILES['product_image2']['tmp_name'];
   $temp_image3 = $_FILES['product_image3']['tmp_name'];

   if($product_brand === "Choose a brand from this menu"){
    echo "<script>alert(\"You have to choose a brand\")</script>";
   }
   else if($product_category === "Choose a category from this menu"){
    echo "<script>alert(\"You have to choose a category\")</script>";
    }
    else{
        //Moving images
        move_uploaded_file($temp_image1,"product_images/$product_image1");
        move_uploaded_file($temp_image2,"product_images/$product_image2");
        move_uploaded_file($temp_image3,"product_images/$product_image3");
        
        //inserting products into DB
        $insert_products = "INSERT INTO product VALUES(DEFAULT, '$product_title', '$product_desc', '$product_keywords', '$product_price', '$product_image1', '$product_image2', '$product_image3', '$product_category', '$product_brand', NOW(),'$product_status')";

        $insert_products_result = mysqli_query($conn, $insert_products);
        //check
        if($insert_products_result){
            echo "<script>alert(\"Successfully inserted the product\")</script>";
            header('location: index.php');
            exit();
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
    <title>Insert Products-Admin Dashboard</title>
    <!-- Bootsrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font awesome cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Css link -->
    <link rel="stylesheet" href="../styles/style.css">
    <!-- favicon -->
    <link rel="shortcut icon" href="../images/logo-modified.png" type="image/x-icon">

    <style>
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
<body class="bg-light">

    <div class="container mt-3">
        <h1 class="text-center">Insert Products</h1>
        <!-- Insertion Form -->
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Title -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="productTitle" class="form-label">Product Title:</label>
                <input type="text" id="productTitle" name="product_title" class="form-control" placeholder="Enter the product title.." autocomplete="off" required="required">
            </div>
            <!-- Description -->
            <div class="form-floating mb-4 w-50 m-auto">
                <textarea id="productDesc" name="product_desc" class="form-control" placeholder="Enter the product Description.." autocomplete="off" required="required"></textarea>
                <label for="productDesc">Product Description:</label>
            </div>
            <!-- Keywords -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="productKeywords" class="form-label">Product Keywords</label>
                <input type="text" id="productKeywords" name="product_keywords" class="form-control" placeholder="Enter product Keywords.." autocomplete="off" required="required">
            </div>
            <!-- Brands -->
            <div class="form-outline mb-4 w-50 m-auto">
                <div class="input-group">
                    <label class="input-group-text" for="select_brand">brands</label>
                    <select class="form-select" id="select_brand" name="product_brand">
                        <option selected>Choose a brand from this menu</option>
                        <?php 
                         $select_brand = "SELECT * FROM `brand`";
                         $select_result = mysqli_query($conn, $select_brand);
                         while($row = mysqli_fetch_assoc($select_result)){
                            $brand_title = $row['name'];
                            $brand_id = $row['brand_id'];
                            echo "<option value=\"$brand_id\">$brand_title</option>";
                         }
                         ?>
                    <!-- <option value="1">One</option>
                         <option value="2">Two</option>
                         <option value="3">Three</option> -->
                    </select>
                </div>
                <p class="form-text"><b>Notice</b> that the brands should be inserted first by the Admin</p>
            </div>
            <!-- Categories -->
            <div class="form-outline mb-4 w-50 m-auto">
                <div class="input-group">
                    <label class="input-group-text" for="select_category">categories</label>
                    <select class="form-select" id="select_category" name="product_category">
                        <option selected>Choose a category from this menu</option>
                        <?php 
                         $select_category = "SELECT * FROM `category`";
                         $select_result = mysqli_query($conn, $select_category);
                         while($row = mysqli_fetch_assoc($select_result)){
                            $category_title = $row['title'];
                            $category_id = $row['category_id'];
                            echo "<option value=\"$category_id\">$category_title</option>";
                         }
                         ?>
                    </select>
                </div>
                <p class="form-text"><b>Notice</b> that the categories should be inserted first by the Admin</p>
            </div>
            <!-- Images -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="productImage1" class="form-label">Product Image1</label>
                <input type="file" id="productImage1" name="product_image1" class="form-control" required="required">
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="productImage2" class="form-label">Product Image2</label>
                <input type="file" id="productImage2" name="product_image2" class="form-control" required="required">
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="productImage3" class="form-label">Product Image3</label>
                <input type="file" id="productImage3" name="product_image3" class="form-control" required="required">
            </div>
            <!-- Price -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="productPrice" class="form-label">Product Price</label>
                <input type="text" id="productPrice" name="product_price" class="form-control" required="required" placeholder="Enter Product Price" autocomplete="off">
            </div>
            <!-- submit -->
            <div class="form-outline mb-4 w-50 m-auto d-flex justify-content-between">
                <input type="submit" name="insert_products" class="btn btn-primary" value="Insert Products">
                <a href="index.php" class="btn btn-secondary">Back</a>
            </div>
            
        </form>
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

</body>
</html>