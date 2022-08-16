<?php   $this->load->view('templates/header');  ?>
<?php   $this->load->view('templates/navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container products-list-page p-5">
    <div class="row border">
        <div class="col-sm-12 col-md-4 p-3">
            <form action="#" method="POST">
                <input type="text" class="form-control rounded-0 mb-3" placeholder="Search product name">
                <ul class="category-list">
                    <li><a href="#" class="text-black">T shirts (25)</a></li>
                    <li><a href="#" class="text-black">Shoes (35)</a></li>
                    <li><a href="#" class="text-black">Cups (5)</a></li>
                    <li><a href="#" class="text-black">Fruits (105)</a></li>
                    <li><a href="#" class="fst-italic">Show All Categories</a></li>
                </ul>
            </form>
        </div>
        <div class="col product-list p-3">
            <div class="row">
                <div class="col-sm-6 col-md-4 product">
                    <div class="product-image border mb-3">
                        <img src="<?=base_url('assets/img/img-placeholder.png')?>" alt="">
                        <p class="price">$19.99</p>
                    </div>
                    <p class="product-name">Casino Watch</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php   $this->load->view('templates/footer');  ?>
