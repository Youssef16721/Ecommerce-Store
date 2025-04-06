<?php 
//if the user change the url (product_id) or delete it
if(!isset($_GET['edit_category']))
    echo "<script>window.open('index.php?view_categories', '_self')</script>";


elseif($_GET['edit_category'] == '')
    echo "<script>window.open('index.php?view_categories', '_self')</script>";
else{
    $category_id = $_GET['edit_category'];
    $get_category = "SELECT * FROM `category` WHERE `category_id` = $category_id";
    $cat_result = mysqli_query($conn, $get_category);
    
    //if inserted id not present
    $row = mysqli_num_rows($cat_result);
    if($row == 0){
        echo "<script>alert('Category is not present')</script>";
        echo "<script>window.open('index.php?view_categories', '_self')</script>";
    }
    else{
    $category = mysqli_fetch_assoc($cat_result);
    $category_title = $category['title'];
    

    //Update 
    if(isset($_POST['edit_category_btn'])){
        $cat_title = $_POST['category_title'];

        if($cat_title == $category_title){
            echo "<script>alert('category title is the same')</script>";
        }
        else{
            $update_category = "UPDATE `category` SET `title` = ? WHERE `category_id` = ?";
            $update_category_statement = mysqli_prepare($conn, $update_category);
            mysqli_stmt_bind_param($update_category_statement, "si", $cat_title, $category_id);
            if (mysqli_stmt_execute($update_category_statement)){
                echo "<script>alert('Category Updated Successfully')</script>";
                echo "<script>window.open('index.php?view_categories', '_self')</script>";
            } else {
                echo "Error updating quantity: " . mysqli_error($conn);
            }
        }

    }
}
}
?>
<p class="text-success fs-3 my-4 text-center">Edit Category</p>
<form action="" method="post" enctype="multipart/form-data">
    <!-- Title -->
    <div class="form-outline mb-4 w-50 m-auto">
        <label for="catTitle" class="form-label">Category Title:</label>
        <input type="text" id="catTitle" name="category_title" class="form-control" placeholder="Enter the category title.." autocomplete="off" required="required" value="<?php echo $category_title?>">
    </div>
    <div class="form-outline mb-4 w-50 m-auto d-flex justify-content-between">
        <input type="submit" name="edit_category_btn" class="btn btn-primary" value="Edit Category">
        <a href="index.php?view_categories" class="btn btn-secondary">Back</a>
    </div>
</form>