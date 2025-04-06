<?php
    $get_orders = "SELECT * FROM `user_orders`";
    $result_orders = mysqli_query($conn, $get_orders);
    $orders_count = mysqli_num_rows($result_orders);
    if($orders_count): ?>
    <p class="text-success fs-3 my-4 text-center">All Orders</p>
    <table class="table table-hover">
        <thead class="table-dark">
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Amount Due</th>
            <th>Invoice Number</th>
            <th>Total Products</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Details</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody class="table-info">
        <!-- fetching orders -->
        <?php
            while($order_row = mysqli_fetch_assoc($result_orders)):
        ?>
        <tr>
            <td><?php echo $order_row['order_id'] ?></td>
            <td><?php echo $order_row['user_id'] ?></td>
            <td>&dollar;<?php echo $order_row['amount_due']?></td>
            <td><?php echo $order_row['invoice_number']?></td>
            <td><?php echo $order_row['total_products']?></td>
            <td><?php echo $order_row['order_date']?></td>
            <td><?php echo $order_row['order_status']?></td>
            <td><a href="index.php?invoice_num=<?php echo $order_row['invoice_number']?>" class="text-dark"><i class="fa-solid fa-circle-info fa-beat-fade" ></i></a></td>
            <td><a href="#" class="text-dark delete-order" data-bs-toggle="modal" data-bs-target="#exampleModal" data-order-id="<?php echo $order_row['order_id'] ?>"><i class="fa-solid fa-trash"></i></a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-success fs-3 my-4 text-center">There are no orders yet</p>
    <?php endif; ?>



    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Order?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure? By deleting this order, the user could make confusion.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-order');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const orderId = event.currentTarget.getAttribute('data-order-id');
                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                confirmDeleteBtn.setAttribute('data-order-id', orderId);
            });

            // Add hover effect for shaking trash icon
            button.addEventListener('mouseover', () => {
                button.querySelector('.fa-trash').classList.add('fa-shake');
            });
            button.addEventListener('mouseout', () => {
                button.querySelector('.fa-trash').classList.remove('fa-shake');
            });
        });

        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.addEventListener('click', () => {
            const orderId = confirmDeleteBtn.getAttribute('data-order-id');
            // Redirect to delete_order endpoint with the selected orderId
            window.location.href = `index.php?delete_order=${orderId}`;
        });
    });
</script>

<!-- info icon -->
<style>
  .fa-circle-info{
    --fa-beat-fade-opacity: 1;
    --fa-beat-fade-scale: 1;
    --fa-shake: stop;
    color:black;
    transition: color 0.5s;
  }
  .fa-circle-info:hover{
    --fa-beat-fade-opacity: 0.6;
    --fa-beat-fade-scale: 1.075;
    color: orange;
  }

  .fa-trash{
    color: black;
    transition: color 0.5s;
  }
  .fa-trash:hover{
    color: red;
  }
</style>

