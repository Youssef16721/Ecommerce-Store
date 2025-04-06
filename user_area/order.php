<?php
include("../config/connection.php");
include("../functions/common_functions.php");

session_start();
$user_id = $_SESSION['user_id'];

$invoice_number = mt_rand();
$order_status = "pending";
$ip_add = getIPAddress();
$total_price = 0;

$cart_items = "SELECT * FROM `cart_details` JOIN `product` ON `cart_details`.`product_id` = `product`.`product_id` WHERE `ip_address` = '$ip_add'";
$result_items = mysqli_query($conn, $cart_items);
$count_products = mysqli_num_rows($result_items);
while($item_row = mysqli_fetch_assoc($result_items)){
    // echo "<pre>";
    // print_r($item_row);
    // echo "<pre>";
    $product_id = $item_row['product_id'];
    $product_price = $item_row['product_price'];
    $product_quantity = $item_row['quantity'];
    $product_total_price = $product_price * $product_quantity;
    $total_price += $product_total_price;
    //inserting pending products
    
    $insert_product = "INSERT INTO `pending_products` VALUES(DEFAULT, ?, ?, ?, ?)";
    $insert_product_statement = mysqli_prepare($conn, $insert_product);
    mysqli_stmt_bind_param($insert_product_statement, "iiii", $invoice_number, $user_id, $product_id, $product_quantity);
    mysqli_stmt_execute($insert_product_statement);
}

//Inserting user orders

$insert_order = "INSERT INTO `user_orders` VALUES(DEFAULT, ?, ?, ?, ?, NOW(), ?)";
$insert_order_statement = mysqli_prepare($conn, $insert_order);
mysqli_stmt_bind_param($insert_order_statement, "iiiis", $user_id, $total_price, $invoice_number, $count_products, $order_status);
if (mysqli_stmt_execute($insert_order_statement)) {
    echo "<script>alert('Order Submitted Successfully')</script>";
    sleep(3);
    header('location: user_profile.php');
    
    //Deleting cart items
    $delete_cart = "DELETE FROM `cart_details` WHERE `ip_address` = '$ip_add'";
    $delete_cart_result = mysqli_query($conn, $delete_cart);
}
else {
    echo "Error inserting data: " . mysqli_error($conn);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order page</title>
</head>
<body>
    
</body>
</html>