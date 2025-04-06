<?php
include("../config/connection.php"); 
include("../functions/common_functions.php");

if(!isset($_GET['order_id']))
    header('location: user_profile.php');

$order_id = $_GET['order_id'];
$get_order = "SELECT * FROM `user_orders` WHERE `order_id` = $order_id";
$order_result = mysqli_query($conn, $get_order);
$order_data = mysqli_fetch_assoc($order_result);
$invoice_number = $order_data['invoice_number'];
$amount = $order_data['amount_due'];


if(isset($_POST['confirm_payment'])){
    // $invoice_number = $_POST['invoice_number'];
    // $amount = $_POST['amount'];
    $pay_mode = $_POST['payment_mode'];
    
    //if the user didn't choose a paymode
    if($pay_mode == "#"){
        echo "<script>alert('Choose a payment method to continue')</script>";
    }
    else{
        $insert_payment = "INSERT INTO `user_payments` VALUES(DEFAULT, ?, ?, ?, ?, NOW())";
        $insert_payment_statement = mysqli_prepare($conn, $insert_payment);
        mysqli_stmt_bind_param($insert_payment_statement, "iiis", $order_id, $invoice_number, $amount, $pay_mode);
        if (mysqli_stmt_execute($insert_payment_statement)) {
            echo "<script>alert('Payment completed successfully')</script>";
            header('location: user_profile.php?orders');
            //update order
            $update_order = "UPDATE `user_orders` SET `order_status` = 'complete' WHERE `order_id` = $order_id";
            $result = mysqli_query($conn, $update_order);
        }
        else {
        echo "Error inserting data: " . mysqli_error($conn);
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
    <title>Confirm Payment</title>
    <!-- Bootsrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body class="bg-secondary">
    <div class="container my-5">
        <h1 class="text-light text-center">Confirm Payment</h1>
        <form action="" method="post">
            <div class="form-outline my-4 w-50 m-auto">
                <input type="text" class="form-control w-50 m-auto" name="invoice_number" value="<?php echo $invoice_number?>" disabled readonly>
            </div>
            <div class="form-outline text-center my-4 w-50 m-auto">
                <label for="" class="text-light mb-2 d-block">Amount:</label>
                <div class="input-group w-50 m-auto">
                    <span class="input-group-text">&dollar;</span>
                    <input type="number" class="form-control w-50 m-auto" name="amount" value="<?php echo $amount?>" disabled readonly>
                </div>
            </div>
            <div class="form-outline my-4 w-50 m-auto">
                <select name="payment_mode" class="form-select w-50 m-auto">
                    <option value="#">select payment mode</option>
                    <option value="UPI">UPI</option>
                    <option value="Netbanking">Netbanking</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                </select>
            </div>
            <div class="form-outline text-center my-4 w-50 m-auto">
                <input type="submit" value="Confirm" name="confirm_payment" class="btn btn-primary">
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
</body>
</html>