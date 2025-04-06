<?php
if(isset($_GET['invoice_num'])):
if($_GET['invoice_num'] == ''){
    echo "<script>window.open('index.php?list_orders', '_self')</script>";//when i use the header func it displays a warning because the code of the page it just executed.  
}
else{
    $invoice_number = $_GET['invoice_num'];
    $get_order = "SELECT * FROM `user_orders` WHERE `invoice_number` = $invoice_number";
    $result_order = mysqli_query($conn, $get_order);
    $order_count = mysqli_num_rows($result_order);
    if($order_count == 0){
    echo "<script>alert('Order doesn't exist')</script>";
    echo "<script>window.open('index.php?list_orders', '_self')</script>";        
    }
    else{
    $pend_num = 1;
    $get_pending_products = "SELECT * FROM `pending_products` 
                                JOIN `product` ON `pending_products`.`product_id` =  `product`.`product_id`
                                WHERE `invoice_number` = $invoice_number";
    $result_pending_products = mysqli_query($conn, $get_pending_products);?>
      <p class="text-center text-success fs-3 my-4">Order Details</p>
      <table class="table table-hover">
        <thead class="table-dark">
          <tr>
            <th>Sl no</th>
            <th>Invoice Number</th>
            <th>Product Title</th>
            <th>Product Image</th>
            <th>Quantity</th>
            <th>Price <span class="fw-100">(p*1q)</span></th>

          </tr>
        </thead>
        <tbody class="table-info">
          <?php while($products_row = mysqli_fetch_assoc($result_pending_products)){ ?>
          <tr>
              <td><?php echo $pend_num ?></td>
              <td><?php echo $products_row['invoice_number']?></td>
              <td><?php echo $products_row['product_title']?></td>
              <td><img src="../admin_area/product_images/<?php echo $products_row['product_image1']?>" alt="product_image" class="edit-img"></td>
              <td><?php echo $products_row['quantity']?></td>
              <td>&dollar;<?php echo $products_row['product_price']?></td>
              
              <?php $pend_num++; ?>
          </tr>
          <?php } ?>
        </tbody>
        <?php 
            $order_row = mysqli_fetch_assoc($result_order);
        ?>
        <tfoot class="table-warning">
            <tr>
                <th colspan="4">Total</th>
                <td><?php echo $order_row['total_products']?>prods</td>
                <td>&dollar;<?php echo $order_row['amount_due']?></td>
            </tr>
        </tfoot>
      </table>
      <?php }} ?>
       
    <?php else:
        echo "<script>window.open('index.php?list_orders', '_self')</script>";
    endif;
    ?>

    <style>
      .edit-img{
        width: 100px;
        object-fit: contain;
      }
    </style>