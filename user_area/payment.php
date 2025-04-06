<?php

session_start();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <!-- Bootstrap JS and jQuery (required for modal functionality) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Bootsrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font awesome cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center text-info mt-4">Payment options</h2>
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-6">
                <a href="https://www.paypal.com" target="_blank"><img src="../images/paypal.png" alt="Paypal.com"></a>
            </div>
            <div class="col-md-6 text-center" >
                <a href="order.php?user_id=<?php echo $_SESSION['user_id'] ?>">Pay offline</a>
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


<?php if(isset($_GET['from_log'])):?>
<!-- Modal -->
<!-- The data-backdrop="static" and data-keyboard="false" attributes prevent the modal from being closed by clicking outside or pressing Esc. -->
<div class="modal" tabindex="-1" role="dialog" id="welcomeModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Welcome <?php echo $_SESSION['username']; ?></h5>
            </div>
            <div class="modal-body">
                <p>Welcome Sir, you have some products in your cart. What would you like to do?</p>
                <button type="button" class="btn btn-primary" id="redirectButton">Go Home</button>
                <button type="button" class="btn btn-secondary" id="continueButton">Continue Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle modal interactions -->
<script>
    $(document).ready(function(){
        // Show the modal when the page loads
        $('#welcomeModal').modal('show');

        // Handle redirect button click
        $('#redirectButton').click(function(){
            // Redirect the user to another page
            window.location.href = '../index.php';
        });

        // Handle continue button click (do something on the same page)
        $('#continueButton').click(function(){
            $('#welcomeModal').modal('hide'); // Close the modal
        });
    });
</script>

<?php endif;?>