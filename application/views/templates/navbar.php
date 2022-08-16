    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?=base_url()?>">Dojo E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('products')?>">Products</a>
                    </li>
                </ul>
                <div class="d-flex">
<?php   if (!empty($this->session->userdata('user_session'))) {
?>
                    <p><?=$this->session->userdata('user_session')['email_address']?></p>
                    <a href="#" class="text-black cart-count">Cart (empty)</a>
                    <a href="<?=base_url('users/logout')?>" class="text-black logout-button">Logout</a>
<?php
} else {
?>
                    <a href="#" class="text-black">Cart (empty)</a>
<?php
}
?>
                </div>
            </div>
        </div>
    </nav>