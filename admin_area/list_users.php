<?php
    $get_users = "SELECT * FROM `user`";
    $result_users = mysqli_query($conn, $get_users);
    $users_count = mysqli_num_rows($result_users);
    if($users_count): ?>
    <p class="text-success fs-3 my-4 text-center">All Users</p>
    <table class="table table-hover">
        <thead class="table-dark">
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>User Image</th>
            <th>User Email</th>
            <th>User Phone</th>
            <th>User Address</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody class="table-info">
        <!-- fetching users -->
        <?php
            while($user_row = mysqli_fetch_assoc($result_users)):
        ?>
        <tr>
            <td><?php echo $user_row['user_id'] ?></td>
            <td><?php echo $user_row['first_name'] ?></td>
            <td><?php echo $user_row['last_name'] ?></td>
            <td><img src="../user_area/user_images/<?php echo $user_row['user_image'] ?>" class="user-img" alt="User Image"></td>
            <td><?php echo $user_row['user_email']?></td>
            <td><?php echo $user_row['user_phone']?></td>
            <td><?php echo $user_row['user_address']?></td>
            <td><a href="" class="text-dark delete-user" data-bs-toggle="modal" data-bs-target="#userModal" data-user-id="<?php echo $user_row['user_id'] ?>"><i class="fa-solid fa-trash"></i></a></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-success fs-3 my-4 text-center">There are no User registered yet.</p>
    <?php endif; ?>



    <!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete User?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure? By deleting this User, the User could make confusion.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="confirmDeleteUserBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-user');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const userId = event.currentTarget.getAttribute('data-user-id');
                const confirmDeleteBtn = document.getElementById('confirmDeleteUserBtn');
                confirmDeleteBtn.setAttribute('data-user-id', userId);
            });

            // Add hover effect for shaking trash icon
            button.addEventListener('mouseover', () => {
                button.querySelector('.fa-trash').classList.add('fa-shake');
            });
            button.addEventListener('mouseout', () => {
                button.querySelector('.fa-trash').classList.remove('fa-shake');
            });
        });

        const confirmDeleteBtn = document.getElementById('confirmDeleteUserBtn');
        confirmDeleteBtn.addEventListener('click', () => {
            const userId = confirmDeleteBtn.getAttribute('data-user-id');
            // Redirect to delete_user endpoint with the selected userId
            window.location.href = `index.php?delete-user=${userId}`;
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

