<?php 
//if the user change the url (product_id) or delete it
if(!isset($_GET['edit_brand']))
    echo "<script>window.open('index.php?view_brands', '_self')</script>";

elseif($_GET['edit_brand'] == '')
    echo "<script>window.open('index.php?view_brands', '_self')</script>";
else{
    $brand_id = $_GET['edit_brand'];
    $get_brand = "SELECT * FROM `brand` WHERE `brand_id` = $brand_id";
    $brand_result = mysqli_query($conn, $get_brand);
    
    //if inserted id not present
    $brand_count = mysqli_num_rows($brand_result);
    if($brand_count == 0){
        echo "<script>alert('Brand is not present')</script>";
        echo "<script>window.open('index.php?view_brands', '_self')</script>";
    }
    else{
    $brand = mysqli_fetch_assoc($brand_result);
    $brand_title = $brand['name'];
    

    //Update 
    if(isset($_POST['edit_brand_btn'])){
        $new_brand_title = $_POST['brand_title'];

        if($new_brand_title == $brand_title){
            echo "<script>alert('brand title is the same')</script>";
        }
        else{
            $update_brand = "UPDATE `brand` SET `name` = ? WHERE `brand_id` = ?";
            $update_brand_statement = mysqli_prepare($conn, $update_brand);
            mysqli_stmt_bind_param($update_brand_statement, "si", $new_brand_title, $brand_id);
            if (mysqli_stmt_execute($update_brand_statement)){
                echo "<script>alert('Brand Updated Successfully')</script>";
                echo "<script>window.open('index.php?view_brands', '_self')</script>";
            } else {
                echo "Error updating quantity: " . mysqli_error($conn);
            }
        }

    }
}
}
?>
<p class="text-success fs-3 my-4 text-center">Edit Brand</p>
<form action="" method="post" enctype="multipart/form-data">
    <!-- Title -->
    <div class="form-outline mb-4 w-50 m-auto">
        <label for="brandTitle" class="form-label">Brand Title:</label>
        <input type="text" id="brandTitle" name="brand_title" class="form-control" placeholder="Enter the brand title.." autocomplete="off" required="required" value="<?php echo $brand_title?>">
    </div>
    <div class="form-outline mb-4 w-50 m-auto d-flex justify-content-between">
        <input type="submit" name="edit_brand_btn" class="btn btn-primary" value="Edit Category">
        <a href="index.php?view_brands" class="btn btn-secondary">Back</a>
    </div>
</form>