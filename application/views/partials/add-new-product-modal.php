    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?=base_url('products/add_new_product')?>" method="POST" class="new_product">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
<?php   if (!empty($messages)) {
            foreach ($messages as $key => $message) {   
?>
            <div class="mb-3 alert alert-<?=($key === 'error' ? 'danger' : 'success')?>" role="alert"><?=$message?></div>
<?php       }
        }
?>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="product_name">Product Name</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" name="product_name" value="<?=$product_name?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="product_description">Product Description</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" name="product_description" value="<?=$product_description?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="product_categories">Product Categories</label>
                        </div>
                        <div class="col-8">
<?php               if (!empty($categories)) { ?>
                            <div class="categories">
<?php                   foreach ($categories as $category) {   ?>
                                <div class="category">
                                    <input type="checkbox" name="categories[]" value="<?=$category['id']?>" <?=(in_array($category['id'], $selected_categories) ? 'checked' : '')?>>
                                    <input type="text" class="update-category" category-id="category-<?=$category['id']?>" value="<?=$category['category_name']?>">
                                    <i class="bi bi-pencil"></i>
                                    <a href="<?=base_url('products/delete_category/'.$category['id'])?>" class="delete-category"><i class="bi bi-x"></i></a>
                                </div>
<?php                   } ?>
                            </div>
<?php               }   ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="category_name">or add new category</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control new-category-name" name="new_category_name">
                            <a href="#" class="add-new-category">add new category</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>


<?php
        /***
         * This section is the hidden form | For AJAX updating/adding global categories
         */
        if (!empty($categories)) { 
            foreach ($categories as $category) {    
?>
            <form action="<?=base_url('products/update_category/'.$category['id'])?>" class="hidden hidden-form-update-category">
                <input type="text" name="category_name" class="category-<?=$category['id']?>" value="<?=$category['category_name']?>" readonly>
            </form>
<?php       } 
        }   
?>
            <form action="<?=base_url('products/add_new_category')?>" class="hidden hidden-form-new-category">
                <input type="text" name="category_name" class="hidden-new-category-name">
            </form>



        </div>
    </div>