<div class="modal-dialog">
        <div class="modal-content">
            <form action="<?=base_url('products/update_product/'.$product_id)?>" enctype="multipart/form-data" method="POST" class="edit-product">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-0" id="load-partial-add-new-product-modal">
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
                </div>
                <div class="modal-body pt-0">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="upload-images" name="files[]" multiple>
                            </div>
                        </div>
                        <div class="col-12 product-images">
<?php               if (!empty($product_images)) {
                        foreach ($product_images as $key => $image) {
?>
                        <div class="row border rounded-2 mb-2">
                            <input type="hidden" name="product_images[]" value="<?=$image['file_path']?>">
                            <div class="col-2 my-auto">
                                <input type="radio" name="is_main" value="<?=$key?>" <?=($image['is_main'] == 1 ? 'checked' : '')?>>
                                <p class="main-photo-label">Main Photo</p>
                            </div>
                            <div class="col-2 my-auto">
                                <i class="bi bi-list"></i>
                            </div>
                            <div class="col-2 my-auto">
                                <img src="<?=$image['file_path']?>" alt="">
                            </div>
                            <div class="col my-auto">
                                <p class="product-image-title">...<?=substr($image['file_path'], -10)?></p>
                            </div>
                            <div class="col-2 my-auto text-right">
                                <i class="bi bi-trash"></i>
                            </div>
                        </div>
<?php
                        }
}
?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel-add-edit" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary save-add-edit">Save</button>
                </div>
            </form>
        </div>
    </div>