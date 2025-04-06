<?php

require '../config/connection.php';

if(isset($_POST['insert_brand'])){

    $brand_name = htmlspecialchars($_POST['brand_name']);
    // verification
    $select_name = "SELECT * FROM `brand` WHERE name = '$brand_name'";
    $select_result = mysqli_query($conn, $select_name);
    $row_num = mysqli_num_rows($select_result);
    if($row_num)
        echo "<script>alert('This Brand is present inside the database')</script>";
    else{
        // data insertion
        $insert_brand = "INSERT INTO `brand` VALUES(DEFAULT,'$brand_name')";
        $insert_result = mysqli_query($conn,$insert_brand);
        if($insert_result)
            echo "<script>alert('Brand has been added successfully')</script>";
    }
}

?>

<form action="" method="post">
    <div class="input-group mb-3">
        <span class="input-group-text bg-primary" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span>
        <input type="text" class="form-control" name="brand_name" placeholder="Insert the brand....." aria-label="Username" aria-describedby="basic-addon1">
    </div>
    <div class="input-group mb-2">        
        <input type="submit" class="btn btn-primary" name="insert_brand" value="Insert Brand">
    </div>
</form>