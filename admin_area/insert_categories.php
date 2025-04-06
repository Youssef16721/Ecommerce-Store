<?php 

require '../config/connection.php';

if(isset($_POST['insert_cat'])){

    $cat_title = htmlspecialchars($_POST['cat_title']);
    // verification
    $select_tit = "SELECT * FROM `category` WHERE title = '$cat_title'";
    $select_result = mysqli_query($conn, $select_tit);
    $row_num = mysqli_num_rows($select_result);
    if($row_num)
        echo "<script>alert('This Category is present inside the database')</script>";
    else{
        // data insertion
        $insert_cat = "INSERT INTO `category` VALUES(DEFAULT,'$cat_title')";
        $insert_result = mysqli_query($conn,$insert_cat);
        if($insert_result)
            echo "<script>alert('Category has been added successfully')</script>";
    }
}

?>

<form action="" method="post">
    <div class="input-group mb-3">
        <span class="input-group-text bg-primary" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span>
        <input type="text" class="form-control" name="cat_title" placeholder="Insert the category....." aria-label="Username" aria-describedby="basic-addon1">
    </div>
    <div class="input-group mb-2">        
        <input type="submit" class="btn btn-primary" name="insert_cat" value="Insert Category">
    </div>
</form>