<?php   $this->load->view('templates/admin-header');  ?>
<?php   $this->load->view('templates/admin-navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container admin-product-list p-5">
    <div class="row">
        <div class="col">
            <input type="text" class="form-control" placeholder="Search products" id="search_product_name">
        </div>
        <div class="col text-right">
            <a href="<?=base_url('products/prepare_add_new_form')?>" class="btn btn-primary" id="add_new_product">Add New Product</a>
            <button class="add-button hidden" data-bs-toggle="modal" data-bs-target="#new_product"></button>
        </div>
        <div class="col-12 mt-5" id="dynamic-products-list-paginate"></div>
    </div>
</section>

<?php   $this->load->view('admin/admin-new-product-modal');  ?>
<?php   $this->load->view('admin/admin-edit-product-modal');  ?>
<?php   $this->load->view('templates/admin-footer');  ?>


<!-- Hidden Forms | Categories AJAX --->
<section class="hidden hidden-form categories"></section>
<form action="<?=base_url('products/add_new_category')?>" class="hidden hidden-form-new-category"><input type="text" name="category_name" class="hidden-new-category-name"></form>
<input class="hidden" type="text" value="<?=base_url('products/product_list_paginate')?>" id="hidden-list-paginate-page-url">
<?=form_open_multipart('products/upload_images', 'class="upload-images hidden"')?></form>