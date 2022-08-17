<?php   $this->load->view('templates/header');  ?>
<?php   $this->load->view('templates/admin-navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container admin-product-list p-5">
    <div class="row">
        <div class="col">
            <input type="text" class="form-control" placeholder="Search products">
        </div>
        <div class="col text-right">
            <a href="<?=base_url('products/prepare_add_new_form')?>" class="btn btn-primary" id="add_new_product">Add New Product</a>
            <button class="add-button hidden" data-bs-toggle="modal" data-bs-target="#new_product"></button>
        </div>
        <div class="col-12 mt-5" id="dynamic-products-list-paginate">
            <table class="table products-list">
                <thead>
                    <tr>
                        <th class="table-id">ID</th>
                        <th class="table-picture">Picture</th>
                        <th class="table-name">Name</th>
                        <th class="table-price text-right">Price</th>
                        <th class="table-actions text-right">action</th>
                    </tr>
                </thead>
                <tbody id="product-row-list">
<?php   if (!empty($products)) {
                foreach ($products as $product) {
?>
                    <tr>
                        <td class="table-id"><?=$product['id']?></td>
                        <td class="table-picture"><img src="<?=($product['product_images'] != '[]' ? $product['product_images'] : base_url('placeholder.png'))?>"></td>
                        <td class="table-name"><?=$product['product_name']?></td>
                        <td class="table-price text-right"><?=$product['product_price']?></td>
                        <td class="table-actions text-right">
                            <a class="edit-product" href="<?=base_url('products/edit_product/'.$product['id'])?>">Edit</a>
                            <button class="edit-button hidden" data-bs-toggle="modal" data-bs-target="#edit_product"></button>
                            <a class="delete-product" href="<?=base_url('products/delete_product/'.$product['id'])?>">Delete</a>
                        </td>
                    </tr>
<?php
                }
        } else {
            echo 'No data available';
        }
?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php   $this->load->view('admin/admin-new-product-modal');  ?>
<?php   $this->load->view('admin/admin-edit-product-modal');  ?>
<?php   $this->load->view('templates/footer');  ?>


<!-- Hidden Forms | Categories AJAX --->
<section class="hidden hidden-form categories"></section>
<form action="<?=base_url('products/add_new_category')?>" class="hidden hidden-form-new-category"><input type="text" name="category_name" class="hidden-new-category-name"></form>
<?=form_open_multipart('products/upload_images', 'class="upload-images hidden"')?></form>