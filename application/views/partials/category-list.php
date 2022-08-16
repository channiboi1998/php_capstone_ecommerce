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