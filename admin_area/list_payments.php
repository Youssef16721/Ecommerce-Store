<?php
    $get_payments = "SELECT * FROM `user_payments`";
    $result_payments = mysqli_query($conn, $get_payments);
    $payments_count = mysqli_num_rows($result_payments);
    if($payments_count): ?>
    <p class="text-success fs-3 my-4 text-center">All Payments</p>
    <table class="table table-hover">
        <thead class="table-dark">
        <tr>
            <th>Payment ID</th>
            <th>Order ID</th>
            <th>Invoice Number</th>
            <th>Amount Due</th>
            <th>Payment Mode</th>
            <th>Payment Date</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody class="table-info">
        <!-- fetching payments -->
        <?php
            while($pay_row = mysqli_fetch_assoc($result_payments)):
        ?>
        <tr>
            <td><?php echo $pay_row['payment_id'] ?></td>
            <td><?php echo $pay_row['order_id'] ?></td>
            <td><?php echo $pay_row['invoice_number'] ?></td>
            <td>&dollar;<?php echo $pay_row['amount']?></td>
            <td><?php echo $pay_row['payment_mode']?></td>
            <td><?php echo $pay_row['date']?></td>
            <td><a href="" class="text-dark delete-payment" data-bs-toggle="modal" data-bs-target="#paymentModal" data-pay-id="<?php echo $pay_row['payment_id'] ?>"><i class="fa-solid fa-trash"></i></a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-success fs-3 my-4 text-center">There are no payments yet</p>
    <?php endif; ?>



    <!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Payment?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure? By deleting this payment, the user could make confusion.
        The product status won't change anyway!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="confirmDeletePayBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-payment');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const payId = event.currentTarget.getAttribute('data-pay-id');
                const confirmDeleteBtn = document.getElementById('confirmDeletePayBtn');
                confirmDeleteBtn.setAttribute('data-pay-id', payId);
            });

            // Add hover effect for shaking trash icon
            button.addEventListener('mouseover', () => {
                button.querySelector('.fa-trash').classList.add('fa-shake');
            });
            button.addEventListener('mouseout', () => {
                button.querySelector('.fa-trash').classList.remove('fa-shake');
            });
        });

        const confirmDeleteBtn = document.getElementById('confirmDeletePayBtn');
        confirmDeleteBtn.addEventListener('click', () => {
            const payId = confirmDeleteBtn.getAttribute('data-pay-id');
            // Redirect to delete_payment endpoint with the selected payId
            window.location.href = `index.php?delete_payment=${payId}`;
        });
    });
</script>



<style>
  .fa-trash{
    color: black;
    transition: color 0.5s;
  }
  .fa-trash:hover{
    color: red;
  }
</style>

