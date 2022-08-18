<?php   $this->load->view('templates/header');  ?>
<?php   $this->load->view('templates/navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container single-product-page p-5">
    <div class="content-info">
        <a href="<?=base_url()?>">Go Back to dashboard</a>
        <h1 class="mt-5 mb-5"><?=$product['product_name']?></h1>
    </div>
    <div class="row mb-5">
        <div class="col-5">
            <div class="main-image mt-5 mb-5">
                <img src="<?=$main_image?>" alt="">
            </div>
<?php   if (!empty($images = $product['product_images'])) { ?>
            <div class="carousel product-gallery mt-5 mb-5">
<?php
            foreach ($images as $image) {
?>
                <div class="carousel-cell p-3">
                    <img src="<?=$image['file_path']?>" alt="">
                </div>
<?php
            }
?>
            </div>
<?php
        }
?>
        </div>
        <div class="col">
            <h3 class="mb-3">Valued at $<?=$product['product_price']?></h3>
            <div class="product-description text-justify mb-3">
                <p><?=$product['product_description']?></p>
            </div>
            <div class="text-right mb-3">
                <form action="<?=base_url('orders/add_to_cart/'.$product['id'])?>" method="POST" class="add-to-cart">
                    <input type="submit" class="btn btn-success" value="Buy">
                    <select name="quantity" id="" class="form-select">
<?php   for ($i=1; $i<=3; $i++) {   ?>
                        <option value="<?=$i?>">Get me <?=$i?> ($<?=$i*$product['product_price']?>)</option>
<?php   }   ?>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="similar-items">
        <div class="col product-list p-3">
            <div class="row">
                <h3 class="mb-3">Similar Items</h3>
<?php   if (!empty($similar_items['products'])) {
            foreach($similar_items['products'] as $similar_item) {
?> 
                <div class="col-sm-6 col-md-2 product">
                    <div class="product-image border mb-3">
                        <a href="<?=base_url('products/show/'.$similar_item['product_id'])?>">
                            <img src="<?=$similar_item['product_images']?>" alt="">
                            <p class="price">$<?=$similar_item['product_price']?></p>
                        </a>
                    </div>
                    <a href="#">
                        <p class="product-name"><?=$similar_item['product_name']?></p>
                    </a>
                </div>
<?php       }
        }
?>          </div>
        </div>
    </div>
</section>

<?php   $this->load->view('templates/footer');  ?>