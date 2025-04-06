<?php 
    $get_categories = "SELECT * FROM `category`";
    $result_category = mysqli_query($conn, $get_categories);
    if($categories_count=mysqli_num_rows($result_category)):
    ?>
<p class="text-success fs-3 my-4 text-center">All Categories</p>
<table class="table table-bordered text-center">
    <thead class="table-dark">
        <tr>
            <th>Category ID</th>
            <th>Category Title</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody class="table-info">
        <?php while($category_row = mysqli_fetch_assoc($result_category)): 
                $category_id = $category_row['category_id'];
                $category_title = $category_row['title'];
        ?>
        <tr>
            <td><?php echo $category_id ?></td>
            <td><?php echo $category_title ?></td>
            <td><a href="index.php?edit_category=<?php echo $category_id?>" class="text-dark"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td><a href="#" class="text-dark delete-brand" data-bs-toggle="modal" data-bs-target="#exampleModal_<?php echo $category_id ?>"><i class="fa-solid fa-trash"></i></a></td>

            <!-- Modal for this category -->
            <div class="modal fade" id="exampleModal_<?php echo $category_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Category?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure? By deleting this category, the products related to it will also be deleted.
                        </div>
                        <div class="modal-footer">
                            <a href="index.php?view_categories" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                            <a href="index.php?delete_category=<?php echo $category_id?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php else: ?>
<p class="text-success fs-3 my-4 text-center">There are no categories present inside the DB!</p>
<?php endif; ?>


<!-- Include JavaScript to handle modal interactions -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-brand');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                // Show the corresponding modal for the clicked delete button
                const targetModalId = event.currentTarget.getAttribute('data-bs-target');
                const modalElement = document.querySelector(targetModalId);
                const modalInstance = new bootstrap.Modal(modalElement);
                modalInstance.show();
            });
        });
    });
</script>


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