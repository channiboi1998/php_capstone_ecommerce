<?php   $this->load->view('templates/header');  ?>
<?php   $this->load->view('templates/admin-navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container admin-product-list p-5">
    <div class="row">
        <div class="col">
            <input type="text" class="form-control" placeholder="Search products">
        </div>
        <div class="col text-right">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_product">Add New Product</a>
        </div>
        <div class="col-12 mt-5">
            <table class="table">
                <thead>
                    <tr>
                        <th>Picture</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Inventory Count</th>
                        <th>Quantity Sold</th>
                        <th>action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<?php   $this->load->view('admin/admin-new-product-modal');  ?>

<?php   $this->load->view('templates/footer');  ?>