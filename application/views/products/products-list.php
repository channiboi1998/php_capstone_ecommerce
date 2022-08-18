<?php   $this->load->view('templates/header');  ?>
<?php   $this->load->view('templates/navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container products-list-page p-5">
    <div class="row border">
        <div class="col-sm-12 col-md-4 p-3">
            <form action="#" method="GET" class="search-name">
                <input type="text" id="search_name" name="search_name" class="form-control rounded-0 mb-3" placeholder="Search product name" value="<?=$search_name?>">
                <ul class="category-list">
<?php   if (!empty($categories)) {
            foreach ($categories as $category) {
?>
                    <li><a href="<?=base_url('products?category_id='.$category['id'])?>" class="text-black"><?=$category['category_name']?> (<?=$category['count']?>)</a></li>
<?php        
            }
        }
?>
                </ul>
                <a href="<?=base_url()?>">Clear Filters</a>
            </form>
        </div>
        <div class="col product-list p-3">
            <div class="row">
<?php   if (!empty($products)) {
            foreach ($products as $product) {   
?>
                <div class="col-sm-6 col-md-4 product">
                    <div class="product-image border mb-3">
                        <a href="<?=base_url('products/show/'.$product['id'])?>">
                            <img src="<?=$product['product_images']?>" alt="">
                            <p class="price">$<?=$product['product_price']?></p>
                        </a>
                    </div>
                    <a href="<?=base_url('products/show/'.$product['id'])?>">
                        <p class="product-name"><?=$product['product_name']?></p>
                    </a>
                </div>
<?php       }
        }   
?>
            </div>
            <div class="pagination mb-3">
<?php   
        if (!empty($number_of_pages)) {
            if (count($number_of_pages) != 1) {
                foreach ($number_of_pages as $page) {
?>
                <a href="<?=$page['url']?>"><?=$page['page_number']?></a>
<?php
                }
            }
        } else {
            echo 'No data found';
        }
?>
            </div>
        </div>
    </div>
</section>

<?php   $this->load->view('templates/footer');  ?>
