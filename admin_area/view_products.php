
<?php 
    $get_products = "SELECT * FROM `product` 
                        LEFT JOIN `brand` ON `product`.`brand_id` = `brand`.`brand_id`
                        LEFT JOIN `category` ON `product`.`category_id` = `category`.`category_id`
                        ORDER BY `product_id`";
    $result_product = mysqli_query($conn, $get_products);
    if($products_count=mysqli_num_rows($result_product)):
    ?>
<p class="text-success fs-3 my-4 text-center">All Products</p>
<table class="table table-bordered text-center">
    <thead class="table-dark">
        <tr>
            <th>Product ID</th>
            <th>Product Title</th>
            <th>Product Image</th>
            <th>Product Price</th>
            <th>Insertion Date</th>
            <th>Total Sold</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody class="table-info">
        <?php while($product_row = mysqli_fetch_assoc($result_product)): 
                $product_id = $product_row['product_id'];
                $product_title = $product_row['product_title'];
                $product_image = $product_row['product_image1'];
                $product_brand = $product_row['name'];
                $product_category = $product_row['title'];
                $product_date = $product_row['date'];
                $product_status = $product_row['status'];
                $product_price = $product_row['product_price'];

                //total sold
                $get_quantity = "SELECT SUM(quantity) as 'total_sold' FROM `pending_products` WHERE `product_id` = $product_id";
                $result_quantity = mysqli_query($conn, $get_quantity);
                $result = mysqli_fetch_assoc($result_quantity);
                $total_sold = $result['total_sold'];
                $total_sold = ($total_sold == '') ? 0 : $total_sold;// perche appare come testo vuoto
        ?>
        <tr>
            <td><?php echo $product_id ?></td>
            <td><?php echo $product_title ?></td>
            <td><img class="product-img" src="product_images/<?php echo $product_image ?>" alt="<?php echo $product_title ?>"></td>
            <td>&dollar;<?php echo $product_price ?></td>
            <td><?php echo $product_date ?></td>
            <td><?php echo $total_sold ?></td>
            <td><?php echo $product_brand ?></td>
            <td><?php echo $product_category ?></td>
            <td><?php echo $product_status ?></td>
            <td><a href="index.php?edit_product=<?php echo $product_id?>" class="text-dark"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td><a href="index.php?delete_product=<?php echo $product_id?>" class="text-dark"><i class="fa-solid fa-trash"></i></a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php else: ?>
<p class="text-success fs-3 my-4 text-center">There are no products present inside the DB!</p>
<?php endif; ?>

<style>
    .fa-pen-to-square{
          color: black;
          transition: color 0.5s;
        }
        .fa-pen-to-square:hover {
            animation: bounce 0.5s;
            transform: translate3d(0, 0, 0);
            backface-visibility: hidden;
            perspective: 1000px;
            color: orange;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        .fa-trash{
            color: black;
            transition: color 0.5s;
        }
        .fa-trash:hover{
            color: red;
        }

        .fa-trash:hover {
            animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        
</style>