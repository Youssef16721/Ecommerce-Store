<?php

// include ("config/connection.php"); do not/ when you want to use this file stable a connection first

// function get_products(){
// 	global $conn;

// 	if(isset($_GET['display_all'])){
// 		$select_products = "SELECT * FROM product ORDER BY rand()";
// 		$select_products_result = mysqli_query($conn, $select_products);
// 		while($product_row = mysqli_fetch_assoc($select_products_result)){
// 			$product_id = $product_row['product_id'];
// 			$product_title = $product_row['product_title'];
// 			$product_desc = $product_row['product_desc'];
// 			$product_image1 = $product_row['product_image1'];
// 			$product_price = $product_row['product_price'];
// 			echo ("
// 						<div class=\"wrapper\">
// 							<div class=\"container\">
// 								<div class=\"top\" style=\"background: url(admin_area/product_images/$product_image1) no-repeat center center; background-size: cover;\"></div>
// 								<div class=\"bottom\">
// 									<div class=\"left\">
// 										<div class=\"details\">
// 											<h1>$product_title</h1>
// 											<p>$$product_price</p>
// 										</div>
// 										<div class=\"buy\"><i class=\"fa-solid fa-cart-shopping\"></i></div>
// 									</div>
// 									<div class=\"right\">
// 										<div class=\"done\"><i class=\"fa-solid fa-cart-shopping\">done</i></div>
// 										<div class=\"details\">
// 											<h1>$product_title</h1>
// 											<p>Added to your cart</p>
// 										</div>
// 										<div class=\"remove\"><i class=\"fa-solid fa-cart-shopping\">clear</i></div>
// 									</div>
// 								</div>
// 							</div>
// 							<div class=\"inside\">
// 								<div class=\"icon\"><i class=\"fa-solid fa-cart-shopping\">info_outline</i></div>
// 								<div class=\"contents\">
// 									<table>
// 										<tr>
// 											<th>$product_title</th>
// 										</tr>
// 										<tr>
// 											<td>$product_desc</td>
// 										</tr>
// 									</table>
// 								</div>
// 							</div>
// 						</div>");
// 		}
// 	} else if(isset($_GET['category'])) {
// 		// Similar code structure for category filter
// 	} else if(isset($_GET['brand'])) {
// 		// Similar code structure for brand filter
// 	} else if(isset($_GET['search_data_product'])) {
// 		// Similar code structure for search functionality
// 	} else {
// 		$select_products = "SELECT * FROM `product` ORDER BY rand() LIMIT 2";
// $select_products_result = mysqli_query($conn, $select_products);
// while ($product_row = mysqli_fetch_assoc($select_products_result)) {
//     $product_id = $product_row['product_id'];
//     $product_title = $product_row['product_title'];
//     $product_desc = $product_row['product_desc'];
//     $product_image1 = $product_row['product_image1'];
//     $product_price = $product_row['product_price'];
//     echo ("
//     <div class='card-wrapper'>
//         <div class='card-container'>
//             <div class='small-card'>
//                 <i class='fas fa-heart'></i>
//             </div>
//             <div class='card-top'>
//                 <img src='admin_area/product_images/$product_image1' alt=''>
//             </div>
//             <div class='card-bottom'>
//                 <div class='card-left'>
//                     <div class='card-details-1'>
//                         <h1>$product_title</h1>
//                         <p>&dollar;$product_price/-</p>
//                     </div>
//                     <div class='card-btn-buy'><i class='fas fa-cart-plus'></i></div>
//                 </div>
//                 <div class='card-right'>
//                     <div class='card-btn-done'><i class='fas fa-check'></i></div>
//                     <div class='card-details-2'>
//                         <h1>$product_title</h1>
//                         <p>Added to your cart</p>
//                     </div>
//                     <div class='card-btn-remove'><i class='fas fa-times'></i></div>
//                 </div>
//             </div>
//         </div>
//         <div class='card-inside'>
//             <div class='card-icon'><i class='fas fa-info-circle'></i></div>
//             <div class='card-contents'>
//                 <h2>Product Details</h2>
//                 <table>
//                     <tr>
//                         <th>Width</th>
//                         <th>Height</th>
//                     </tr>
//                     <tr>
//                         <td>3000mm</td>
//                         <td>4000mm</td>
//                     </tr>
//                 </table>
// 				<div class='desc text-center my-4'>
// 					<h5>Product Description</h5>
// 					<p>$product_desc</p>
// 				</div>
//                 <div class='review-icon text-center'>
//                     <i class='fas fa-star'></i>
//                     <i class='fas fa-star'></i>
//                     <i class='fas fa-star'></i>
//                     <i class='fas fa-star'></i>
//                     <i class='fas fa-star-half-alt'></i>
//                 </div>
//                 <div class='button-container text-center'>
//                     <a href='#' class='btn btn-primary'>View Item</a>
//                 </div>
//             </div>
//         </div>
//     </div>");}
// 	}
// }

// ff

// fetching products
function get_products(){
	global $conn;

	// // pagination common part
	// $start = 0;
	// // number of prod per page
	// $rows_per_pages = 3;


	// //displaying all products
	// if(isset($_GET['display_all'])){
	// 	$select_products = "SELECT * FROM product ORDER BY rand()";
	// 	$select_products_result = mysqli_query($conn, $select_products);

	// 	//pagination part
	// 	$prod_num_rows = mysqli_num_rows($select_products_result);
	// 	$pages = ceil($prod_num_rows / $rows_per_pages);

	// 	while($product_row = mysqli_fetch_assoc($select_products_result)){
	// 		$product_id = $product_row['product_id'];
	// 		$product_title = $product_row['product_title'];
	// 		$product_desc = $product_row['product_desc'];
	// 		$product_keywords = $product_row['product_keywords'];
	// 		$product_image1 = $product_row['product_image1'];
	// 		$product_image2 = $product_row['product_image2'];
	// 		$product_image3 = $product_row['product_image3'];
	// 		$product_price = $product_row['product_price'];
	// 		$product_category = $product_row['category_id'];
	// 		$product_brand = $product_row['brand_id'];
	// 		echo ("<div class='card-wrapper'>
	// 		<div class='card-container'>
	// 			<div class='small-card'>
	// 				<i class='fas fa-heart'></i>
	// 			</div>
	// 			<div class='card-top'>
	// 				<img src='admin_area/product_images/$product_image1' alt=''>
	// 			</div>
	// 			<div class='card-bottom'>
	// 				<div class='card-left'>
	// 					<div class='card-details-1'>
	// 						<h1>$product_title</h1>
	// 						<p>&dollar;$product_price/-</p>
	// 					</div>
	// 					<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
	// 				</div>
	// 				<div class='card-right'>
	// 					<div class='card-btn-done'><i class='fas fa-check'></i></div>
	// 					<div class='card-details-2'>
	// 						<h1>$product_title</h1>
	// 						<p>Added to your cart</p>
	// 					</div>
	// 					<div class='card-btn-remove'><i class='fas fa-times'></i></div>
	// 				</div>
	// 			</div>
	// 		</div>
	// 		<div class='card-inside'>
	// 			<div class='card-icon'><i class='fas fa-info-circle'></i></div>
	// 			<div class='card-contents'>
	// 				<h2>Product Details</h2>
	// 				<div class='desc text-center my-4'>
	// 					<p>$product_desc</p>
	// 				</div>
	// 				<div class='review-icon text-center'>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star-half-alt'></i>
	// 				</div>
	// 				<div class='button-container text-center'>
	// 					<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
	// 				</div>
	// 			</div>
	// 		</div>
	// 	</div>");
	// 	}
	// }

	// //displaying all category products
	// else if(isset($_GET['category'])){
	// 	$category_id = $_GET['category'];
	// 	$select_products = "SELECT * FROM product WHERE category_id = '$category_id' ORDER BY rand()";
	// 	$select_products_result = mysqli_query($conn, $select_products);

	// 	//check if there are products present in this category
	// 	if(!mysqli_num_rows($select_products_result))
	// 		echo "<h2 class=\"text-center text-danger\">There are no products present in this category</h2>";

	// 	while($product_row = mysqli_fetch_assoc($select_products_result)){
	// 		$product_id = $product_row['product_id'];
	// 		$product_title = $product_row['product_title'];
	// 		$product_desc = $product_row['product_desc'];
	// 		$product_keywords = $product_row['product_keywords'];
	// 		$product_image1 = $product_row['product_image1'];
	// 		$product_image2 = $product_row['product_image2'];
	// 		$product_image3 = $product_row['product_image3'];
	// 		$product_price = $product_row['product_price'];
	// 		$product_category = $product_row['category_id'];
	// 		$product_brand = $product_row['brand_id'];
	// 		echo ("<div class='card-wrapper'>
	// 		<div class='card-container'>
	// 			<div class='small-card'>
	// 				<i class='fas fa-heart'></i>
	// 			</div>
	// 			<div class='card-top'>
	// 				<img src='admin_area/product_images/$product_image1' alt=''>
	// 			</div>
	// 			<div class='card-bottom'>
	// 				<div class='card-left'>
	// 					<div class='card-details-1'>
	// 						<h1>$product_title</h1>
	// 						<p>&dollar;$product_price/-</p>
	// 					</div>
	// 					<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
	// 				</div>
	// 				<div class='card-right'>
	// 					<div class='card-btn-done'><i class='fas fa-check'></i></div>
	// 					<div class='card-details-2'>
	// 						<h1>$product_title</h1>
	// 						<p>Added to your cart</p>
	// 					</div>
	// 					<div class='card-btn-remove'><i class='fas fa-times'></i></div>
	// 				</div>
	// 			</div>
	// 		</div>
	// 		<div class='card-inside'>
	// 			<div class='card-icon'><i class='fas fa-info-circle'></i></div>
	// 			<div class='card-contents'>
	// 				<h2>Product Details</h2>
	// 				<div class='desc text-center my-4'>
	// 					<p>$product_desc</p>
	// 				</div>
	// 				<div class='review-icon text-center'>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star-half-alt'></i>
	// 				</div>
	// 				<div class='button-container text-center'>
	// 					<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
	// 				</div>
	// 			</div>
	// 		</div>
	// 	</div>");
	// 	}
	// }

	// //displaying all brand products
	// else if(isset($_GET['brand'])){
	// 	$brand_id = $_GET['brand'];
	// 	$select_products = "SELECT * FROM product WHERE brand_id = '$brand_id' ORDER BY rand()";
	// 	$select_products_result = mysqli_query($conn, $select_products);

	// 	//check if there are products present in this brand
	// 	if(!mysqli_num_rows($select_products_result))
	// 		echo "<h2 class=\"text-center text-danger\">There are no products present in this brand</h2>";

	// 	while($product_row = mysqli_fetch_assoc($select_products_result)){
	// 		$product_id = $product_row['product_id'];
	// 		$product_title = $product_row['product_title'];
	// 		$product_desc = $product_row['product_desc'];
	// 		$product_keywords = $product_row['product_keywords'];
	// 		$product_image1 = $product_row['product_image1'];
	// 		$product_image2 = $product_row['product_image2'];
	// 		$product_image3 = $product_row['product_image3'];
	// 		$product_price = $product_row['product_price'];
	// 		$product_category = $product_row['category_id'];
	// 		$product_brand = $product_row['brand_id'];
	// 		echo ("<div class='card-wrapper'>
	// 		<div class='card-container'>
	// 			<div class='small-card'>
	// 				<i class='fas fa-heart'></i>
	// 			</div>
	// 			<div class='card-top'>
	// 				<img src='admin_area/product_images/$product_image1' alt=''>
	// 			</div>
	// 			<div class='card-bottom'>
	// 				<div class='card-left'>
	// 					<div class='card-details-1'>
	// 						<h1>$product_title</h1>
	// 						<p>&dollar;$product_price/-</p>
	// 					</div>
	// 					<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
	// 				</div>
	// 				<div class='card-right'>
	// 					<div class='card-btn-done'><i class='fas fa-check'></i></div>
	// 					<div class='card-details-2'>
	// 						<h1>$product_title</h1>
	// 						<p>Added to your cart</p>
	// 					</div>
	// 					<div class='card-btn-remove'><i class='fas fa-times'></i></div>
	// 				</div>
	// 			</div>
	// 		</div>
	// 		<div class='card-inside'>
	// 			<div class='card-icon'><i class='fas fa-info-circle'></i></div>
	// 			<div class='card-contents'>
	// 				<h2>Product Details</h2>
	// 				<div class='desc text-center my-4'>
	// 					<p>$product_desc</p>
	// 				</div>
	// 				<div class='review-icon text-center'>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star-half-alt'></i>
	// 				</div>
	// 				<div class='button-container text-center'>
	// 					<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
	// 				</div>
	// 			</div>
	// 		</div>
	// 	</div>");
	// 	}
	// }

	// //searching products
	// else if(isset($_GET['search_data_product'])){
	// 	$search_keywords = htmlspecialchars($_GET['search_data']);
	// 	$search_products = "SELECT * FROM `product` WHERE product_keywords LIKE '%$search_keywords%' ORDER BY rand()";
	// 	$search_query_result = mysqli_query($conn, $search_products);

	// 	//check if there are products match the search query
	// 	if(!mysqli_num_rows($search_query_result))
	// 		echo "<h2 class=\"text-center text-danger\">There are no products that match the search!</h2>";

	// 	while($product_row = mysqli_fetch_assoc($search_query_result)){
	// 		$product_id = $product_row['product_id'];
	// 		$product_title = $product_row['product_title'];
	// 		$product_desc = $product_row['product_desc'];
	// 		$product_keywords = $product_row['product_keywords'];
	// 		$product_image1 = $product_row['product_image1'];
	// 		$product_image2 = $product_row['product_image2'];
	// 		$product_image3 = $product_row['product_image3'];
	// 		$product_price = $product_row['product_price'];
	// 		$product_category = $product_row['category_id'];
	// 		$product_brand = $product_row['brand_id'];
	// 		echo ("<div class='card-wrapper'>
	// 		<div class='card-container'>
	// 			<div class='small-card'>
	// 				<i class='fas fa-heart'></i>
	// 			</div>
	// 			<div class='card-top'>
	// 				<img src='admin_area/product_images/$product_image1' alt=''>
	// 			</div>
	// 			<div class='card-bottom'>
	// 				<div class='card-left'>
	// 					<div class='card-details-1'>
	// 						<h1>$product_title</h1>
	// 						<p>&dollar;$product_price/-</p>
	// 					</div>
	// 					<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
	// 				</div>
	// 				<div class='card-right'>
	// 					<div class='card-btn-done'><i class='fas fa-check'></i></div>
	// 					<div class='card-details-2'>
	// 						<h1>$product_title</h1>
	// 						<p>Added to your cart</p>
	// 					</div>
	// 					<div class='card-btn-remove'><i class='fas fa-times'></i></div>
	// 				</div>
	// 			</div>
	// 		</div>
	// 		<div class='card-inside'>
	// 			<div class='card-icon'><i class='fas fa-info-circle'></i></div>
	// 			<div class='card-contents'>
	// 				<h2>Product Details</h2>
	// 				<div class='desc text-center my-4'>
	// 					<p>$product_desc</p>
	// 				</div>
	// 				<div class='review-icon text-center'>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star-half-alt'></i>
	// 				</div>
	// 				<div class='button-container text-center'>
	// 					<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
	// 				</div>
	// 			</div>
	// 		</div>
	// 	</div>");
	// 	}
	// 	return $pages;
	// }

	// //displaying products
	// else{
	// 	$select_products = "SELECT * FROM `product` ORDER BY rand() LIMIT 3";
	// 	$select_products_result = mysqli_query($conn, $select_products);
	// 	while($product_row = mysqli_fetch_assoc($select_products_result)){
	// 		$product_id = $product_row['product_id'];
	// 		$product_title = $product_row['product_title'];
	// 		$product_desc = $product_row['product_desc'];
	// 		$product_keywords = $product_row['product_keywords'];
	// 		$product_image1 = $product_row['product_image1'];
	// 		$product_image2 = $product_row['product_image2'];
	// 		$product_image3 = $product_row['product_image3'];
	// 		$product_price = $product_row['product_price'];
	// 		$product_category = $product_row['category_id'];
	// 		$product_brand = $product_row['brand_id'];
	// 		echo ("<div class='card-wrapper'>
	// 		<div class='card-container'>
	// 			<div class='small-card'>
	// 				<i class='fas fa-heart'></i>
	// 			</div>
	// 			<div class='card-top'>
	// 				<img src='admin_area/product_images/$product_image1' alt=''>
	// 			</div>
	// 			<div class='card-bottom'>
	// 				<div class='card-left'>
	// 					<div class='card-details-1'>
	// 						<h1>$product_title</h1>
	// 						<p>&dollar;$product_price/-</p>
	// 					</div>
	// 					<a href=\"index.php?add_to_cart=$product_id\" class='card-btn-buy'><i class='fas fa-cart-plus'></i></a>
	// 				</div>
	// 				<div class='card-right'>
	// 					<div class='card-btn-done'><i class='fas fa-check'></i></div>
	// 					<div class='card-details-2'>
	// 						<h1>$product_title</h1>
	// 						<p>Added to your cart</p>
	// 					</div>
	// 					<div class='card-btn-remove'><i class='fas fa-times'></i></div>
	// 				</div>
	// 			</div>
	// 		</div>
	// 		<div class='card-inside'>
	// 			<div class='card-icon'><i class='fas fa-info-circle'></i></div>
	// 			<div class='card-contents'>
	// 				<h2>Product Details</h2>
	// 				<div class='desc text-center my-4'>
	// 					<p>$product_desc</p>
	// 				</div>
	// 				<div class='review-icon text-center'>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star'></i>
	// 					<i class='fas fa-star-half-alt'></i>
	// 				</div>
	// 				<div class='button-container text-center'>
	// 					<a href=\"product_details.php?product_id=$product_id\" class='btn btn-primary'>View Item</a>
	// 				</div>
	// 			</div>
	// 		</div>
	// 	</div>");
	// 	}
	// 	return $pages;
	// }
	
}



//view product details
function view_product_details(){
	global $conn;
	$product_id = $_GET['product_id'];
	$select_products = "SELECT * FROM `product` WHERE product_id = $product_id";
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
			echo ("<div class=\"col-md-4 mb-2\">
					<div class=\"card\">
					<img src=\"admin_area/product_images/$product_image1\" class=\"card-img-top\" alt=\"$product_title\">
					<div class=\"card-body\">
						<h5 class=\"card-title\">$product_title</h5>
						<p class=\"card-text\">$product_desc</p>
						<p class=\"card-text\">price: $$product_price/-</p>
						<a href=\"product_details.php?add_to_cart=$product_id\" class=\"btn btn-primary\">Add To Cart</a>
						<a href=\"index.php\" class=\"btn btn-secondary\">Go home</a>
					</div>
					</div>
				</div>
				<div class=\"col-md-8\">
					<div class=\"col-md-12\">
						<h4 class=\"text-center text-info mt-2\">Related Products</h4>
					</div>
					<div class=\"col-md-6\">
						<img src=\"admin_area/product_images/$product_image2\" class=\"card-img-top\" alt=\"$product_title\">
					</div>
					<div class=\"col-md-6\">
					<img src=\"admin_area/product_images/$product_image3\" class=\"card-img-top\" alt=\"$product_title\">
					</div>

				</div>");
		}
}




//fetching brands
function get_brands(){
	global $conn;
	$select_brands = "SELECT * FROM `brand`";
		$select_result = mysqli_query($conn, $select_brands);
		while($row_data = mysqli_fetch_assoc($select_result)){
			$brand_name = $row_data['name'];
			$brand_id = $row_data['brand_id'];
			
		echo "<li class='nav-item'>
				<a class=\"nav-link text-light fs-5\" href='index.php?brand=$brand_id'>{$brand_name}</a>
				</li>";
		}
}


//fetching categories
function get_categories(){
	global $conn;
	$select_categories = "SELECT * FROM `category`";
	$select_result = mysqli_query($conn, $select_categories);
	while($row_data = mysqli_fetch_assoc($select_result)){
	  $category_name = $row_data['title'];
	  $category_id = $row_data['category_id'];
	  
	echo "<li class='nav-item'>
			<a class=\"nav-link text-light fs-5\" href='index.php?category=$category_id'>{$category_name}</a>
		  </li>";
	}
}


// Get ip customer address
function getIPAddress() {  
    //whether ip is from the share internet  
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
//whether ip is from the remote address  
    else{  
             $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
}  


// cart
function cart(){
	sleep(1);
	global $conn;
	$ip_add = getIPAddress();
	$product_id = $_GET['add_to_cart'];
	//check if the item is present
	$return_products = "SELECT * FROM `cart_details` WHERE product_id = $product_id AND ip_address = '$ip_add'";
	$return_query_result = mysqli_query($conn, $return_products);
	if($num_rows = mysqli_num_rows($return_query_result)){
		echo "<script>alert('This item is already present inside the cart')</script>";
		echo "<script>window.open('index.php', '_self')</script>";
	}
	//add to cart
	else{
		$insert_item = "INSERT INTO `cart_details` VALUES($product_id, '$ip_add', 1, NOW())";
		$insert_query_result = mysqli_query($conn, $insert_item);
		if($insert_query_result){
			echo "<script>alert('Item added to the cart')</script>";
			echo "<script>window.open('index.php', '_self')</script>";
		}
	}
}

//number of items present inside the cart
function num_cart_items(){
	global $conn;
	$ip_add = getIPAddress();
	$select_items = "SELECT * FROM `cart_details` WHERE ip_address = '$ip_add'";
	$select_query = mysqli_query($conn, $select_items);
	$select_num_rows = mysqli_num_rows($select_query);
	return $select_num_rows;
}

function total_cart_price(){
	global $conn;
	$ip_add = getIPAddress();
	$total_price = 0;
	// $cart_items = "SELECT * FROM `cart_details` WHERE ip_address = '$ip_add'";
	// $result = mysqli_query($conn, $cart_items);
	// while($item_row = mysqli_fetch_array($result)){
	// 	$product_id = $item_row['product_id'];
	// 	$get_product = "SELECT * FROM `product` WHERE product_id = $product_id";
	// 	$product_result = mysqli_query($conn, $get_product);
	// 	$product_row = mysqli_fetch_array($product_result);
	// 		$product_price = array($product_row['product_price']);
	// 		$products_value = array_sum($product_price);//[sums]
	// 		$total_price += $products_value;
		
	// }
	$cart_items = "SELECT * FROM `cart_details` JOIN `product` ON cart_details.product_id = product.product_id WHERE ip_address = '$ip_add'";
	$result = mysqli_query($conn, $cart_items);
	while($item_row = mysqli_fetch_assoc($result)){
		$product_price = $item_row['product_price'];
		$product_quantity = $item_row['quantity'];
		$product_total_price = $product_price * $product_quantity;
		$total_price += $product_total_price;
		
	}

	return $total_price;
}









// function sendUserEmail($user_email, $subject, $message) {

//     // $subject = "Your new username";
//     // $message = nl2br("Hello,\n\nYour username is: $username

//     // Please save this username because you will need it to login. And DO NOT SHARE IT with anyone.

//     // Thank you for registering.");


//     //create a php mailer object
//     $mail = new PHPMailer(TRUE);

//     //use the smtp to send the msg
//     $mail->isSMTP();

//     // use gmail login details to send the email
//     $mail->SMTPAuth = true;

//     $mail->Host = MAIL_HOST;

//     $mail->Username = USERNAME;

//     $mail->Password = PASSWORD;

//     // Using STARTTLS encryption when sending a msg
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

//     // setting the TCP port to 587 to connect with the Gmail SMTP server
//     $mail->Port = 587;

//     // Who is sending the email
//     $mail->setFrom(SEND_FROM, SEND_FROM_NAME);

//     // Where the mail goes
//     $mail->addAddress($user_email);


//     //The 'addReplyTo' property specifies where the recipient can reply to.
//     $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);

//     $mail->isHTML = true;

//     //incoming subject
//     $mail->Subject = $subject;

//     //incoming msg
//     $mail->Body = $message;

//     //if the html content not supported, so it displays the msg as plain text
//     $mail->AltBody = $message;


//     return $mail->send();
// }


function sendUserSMS($phoneNumber, $message){
	// Authorisation details.
	$username = "Zoomia";
	$hash = "7ff2b369745aa76052d5b1ab0b0390393ba57fe1a781708993bae073c737616b";

	// Config variables. Consult http://api.txtlocal.com/docs for more info.
	$test = "0";

	// Data for text message. This is the text message data.
	$sender = "API Test"; // This is who the message appears to be from.
	$numbers = $phoneNumber; // A single number or a comma-seperated list of numbers
	$message = "This is a test message from the PHP API script.";
	// 612 chars or less
	// A single number or a comma-seperated list of numbers
	$message = urlencode($message);
	$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
	$ch = curl_init('https://api.txtlocal.com/send/?');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
	curl_close($ch);
}




function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}



?>