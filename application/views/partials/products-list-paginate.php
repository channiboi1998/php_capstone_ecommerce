<?php   for ($i = 1; $i<=$number_of_pages; $i++) {  ?>
            <a class="product-list-paginate-page" href="<?=base_url('products/product_list_paginate?page='.$i.$search_name)?>"><?=$i?></a>
<?php   }   ?>
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
        } else {    ?>
                    <div>No data available .....</div>
                    <script>$('a.product-list-paginate-page:nth-child(1)').click();</script>
<?php   }   ?>
                </tbody>
            </table>