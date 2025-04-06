<?php 
    $get_brands = "SELECT * FROM `brand`";
    $result_brand = mysqli_query($conn, $get_brands);
    if($brands_count=mysqli_num_rows($result_brand)):
    ?>
<p class="text-success fs-3 my-4 text-center">All Brands</p>
<table class="table table-bordered text-center">
    <thead class="table-dark">
        <tr>
            <th>Brand ID</th>
            <th>Brand Title</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody class="table-info">
        <?php 
            while($brand_row = mysqli_fetch_assoc($result_brand)): 
                $brand_id = $brand_row['brand_id'];
                $brand_title = $brand_row['name'];
        ?>
        <tr>
            <td><?php echo $brand_id ?></td>
            <td><?php echo $brand_title ?></td>
            <td><a href="index.php?edit_brand=<?php echo $brand_id ?>" class="text-dark"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td><a href="#" class="text-dark delete-brand" data-bs-toggle="modal" data-bs-target="#exampleModal" data-brand-id="<?php echo $brand_id ?>"><i class="fa-solid fa-trash"></i></a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php else: ?>
<p class="text-success fs-3 my-4 text-center">There are no brands present inside the DB!</p>
<?php endif; ?>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Brand?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure? By deleting this brand, the products related to it also could be deleted.
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
        const deleteButtons = document.querySelectorAll('.delete-brand');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const brandId = event.currentTarget.getAttribute('data-brand-id');
                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                confirmDeleteBtn.setAttribute('data-brand-id', brandId);
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
            const brandId = confirmDeleteBtn.getAttribute('data-brand-id');
            // Redirect to delete_brand endpoint with the selected brandId
            window.location.href = `index.php?delete_brand=${brandId}`;
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

        /* .fa-trash:hover {
            animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        } */
    </style>
