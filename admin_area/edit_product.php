<?php 
//if the user change the url (product_id) or delete it
if(!isset($_GET['edit_product'])){
    echo "<script>window.open('index.php?view_products', '_self')</script>";
}

elseif($_GET['edit_product'] == ''){
    echo "<script>window.open('index.php?view_products', '_self')</script>";
}
else{

    $product_id = $_GET['edit_product'];
    $fetch_product_query = "SELECT * FROM `product`
    JOIN `brand` ON `product`.`brand_id` = `brand`.`brand_id`
    JOIN `category` ON `product`.`category_id` = `category`.`category_id` 
    WHERE `product_id` = $product_id";
    $fetch_product_result = mysqli_query($conn, $fetch_product_query);

    //if inserted id not present
    $product_count = mysqli_num_rows($fetch_product_result);
    if($product_count == 0){
        echo "<script>alert('product with id $product_id is not present')</script>";
        header('location: index.php?view_products');
    }
    $product_row = mysqli_fetch_assoc($fetch_product_result);
    $product_title = $product_row['product_title'];
    $product_description = $product_row['product_desc'];
    $product_keywords = $product_row['product_keywords'];
    $product_image1 = $product_row['product_image1'];
    $product_image2 = $product_row['product_image2'];
    $product_image3 = $product_row['product_image3'];
    $product_brand = $product_row['name'];
    $product_category = $product_row['title'];
    $product_price = $product_row['product_price'];


    //El 27sn t keep el code here because you can't write it down and there are info sent to the browser (fetch form data) and when you use header this will produce a warning: You display some info and redirect user to another location
    if(isset($_POST['edit_product_btn'])){
    
        $edit_title = htmlspecialchars($_POST['product_title']);
        $edit_desc = htmlspecialchars($_POST['product_desc']);
        $edit_keywords = htmlspecialchars($_POST['product_keywords']);
        $edit_price = htmlspecialchars($_POST['product_price']);
        $edit_brand = htmlspecialchars($_POST['product_brand']);
        $edit_category = htmlspecialchars($_POST['product_category']);
     
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
    
    
            $update_product = "UPDATE `product` SET `product_title` = ?, `product_desc` = ?, `product_keywords` = ?, `product_price` = ?, `product_image1` = ?, `product_image2` = ?, `product_image3` = ?, `category_id` = ?, `brand_id` = ?
            WHERE `product_id` = ?";
            $update_product_statement = mysqli_prepare($conn, $update_product);
            mysqli_stmt_bind_param($update_product_statement, "sssisssiii", $edit_title, $edit_desc, $edit_keywords, $edit_price, $product_image1, $product_image2, $product_image3, $edit_category, $edit_brand, $product_id);
            if (mysqli_stmt_execute($update_product_statement)){
                echo "<script>alert('Data Updated Successfully')</script>";
                echo "<script>window.open('index.php?edit_product=$product_id', '_self')</script>";
            } else {
                echo "Error updating quantity: " . mysqli_error($conn);
            }
        }
    }
}
?>

<p class="text-success fs-3 my-4 text-center">Edit Product</p>

<form action="" method="post" enctype="multipart/form-data">
    <!-- Title -->
    <div class="form-outline mb-4 w-50 m-auto">
        <label for="productTitle" class="form-label">Product Title:</label>
        <input type="text" id="productTitle" name="product_title" class="form-control" placeholder="Enter the product title.." autocomplete="off" required="required" value="<?php echo $product_title?>">
    </div>
    <!-- Description -->
    <div class="form-outline mb-4 w-50 m-auto">
        <label for="productDesc" class="form-label">Product Description:</label>
        <input type="text" id="productDesc" name="product_desc" class="form-control" placeholder="Enter the product Description.." autocomplete="off" required="required" value="<?php echo $product_description?>"></textarea>
    </div>
    <!-- Keywords -->
    <div class="form-outline mb-4 w-50 m-auto">
        <label for="productKeywords" class="form-label">Product Keywords</label>
        <input type="text" id="productKeywords" name="product_keywords" class="form-control" placeholder="Enter product Keywords.." autocomplete="off" required="required" value="<?php echo $product_keywords?>">
    </div>
    <!-- Brands -->
    <div class="form-outline mb-4 w-50 m-auto">
        <div class="input-group">
            <label class="input-group-text" for="select_brand">brands</label>
            <select class="form-select" id="select_brand" name="product_brand">
                <option>Choose a brand from this menu</option>
                <?php 
                    $select_brand = "SELECT * FROM `brand`";
                    $select_result = mysqli_query($conn, $select_brand);
                    while($row = mysqli_fetch_assoc($select_result)){
                    $brand_title = $row['name'];
                    $brand_id = $row['brand_id'];
                    if($product_brand == $brand_title)
                        echo "<option value=\"$brand_id\" selected>$brand_title</option>";
                    else
                        echo "<option value=\"$brand_id\">$brand_title</option>";
                    }
                    ?>
            </select>
        </div>
        <p class="form-text"><b>Notice</b> that the brands should be inserted first by the Admin</p>
    </div>
    <!-- Categories -->
    <div class="form-outline mb-4 w-50 m-auto">
        <div class="input-group">
            <label class="input-group-text" for="select_category">categories</label>
            <select class="form-select" id="select_category" name="product_category">
                <option>Choose a category from this menu</option>
                <?php 
                    $select_category = "SELECT * FROM `category`";
                    $select_result = mysqli_query($conn, $select_category);
                    while($row = mysqli_fetch_assoc($select_result)){
                    $category_title = $row['title'];
                    $category_id = $row['category_id'];
                    if($product_category == $category_title)
                        echo "<option value=\"$category_id\" selected>$category_title</option>";
                    else
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
        <div class="d-flex">
            <img src="product_images/<?php echo $product_image1 ?>" alt="" class="product-img">
            <input type="file" id="productImage1" name="product_image1" class="form-control" >
        </div>
    </div>
    <div class="form-outline mb-4 w-50 m-auto">
        <label for="productImage2" class="form-label">Product Image2</label>
        <div class="d-flex">
            <img src="product_images/<?php echo $product_image2 ?>" alt="" class="product-img">
            <input type="file" id="productImage2" name="product_image2" class="form-control" >
        </div>
    </div>
    <div class="form-outline mb-4 w-50 m-auto">
        <label for="productImage3" class="form-label">Product Image3</label>
        <div class="d-flex">
            <img src="product_images/<?php echo $product_image3 ?>" alt="" class="product-img">
            <input type="file" id="productImage3" name="product_image3" class="form-control" >
        </div>
    </div>
    <!-- Price -->
    <div class="form-outline mb-4 w-50 m-auto">
        <label for="productPrice" class="form-label">Product Price</label>
        <input type="number" id="productPrice" name="product_price" class="form-control" required="required" placeholder="Enter Product Price" autocomplete="off" value="<?php echo $product_price?>">
    </div>
    <!-- submit -->
    <div class="form-outline mb-4 w-50 m-auto d-flex justify-content-between">
        <input type="submit" name="edit_product_btn" class="btn btn-primary" value="Edit Product">
        <a href="index.php?view_products" class="btn btn-secondary">Back</a>
    </div>
    
</form>
