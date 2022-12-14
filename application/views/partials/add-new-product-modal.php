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
                            <label for="product_price">Product Price</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" name="product_price" value="<?=$product_price?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="product_categories">Product Categories</label>
                        </div>
                        <div class="col-8 category-list">
<?php               if (!empty($categories)) { ?>
                            <div class="categories">
<?php                   foreach ($categories as $category) {   ?>
                                <div class="category">
                                    <input type="checkbox" name="categories[]" value="<?=$category['id']?>" <?=(in_array($category['id'], $selected_categories) ? 'checked' : '')?>>
                                    <input type="text" class="update-category" category-id="<?=$category['id']?>" value="<?=$category['category_name']?>">
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

<?php   if (!empty($success)) {    ?>
<script>
    $('input#upload-images').val('');
    $('div.product-images .row').remove();
</script>
<?php   }   ?>