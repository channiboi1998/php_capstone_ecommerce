    <nav class="navbar navbar-expand-lg bg-info">
        <div class="container-fluid">
<?php   if (!empty($this->session->userdata('user_session')['is_admin'])) { ?>
            <a class="navbar-brand" href="<?=base_url('dashboard/orders')?>">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('dashboard/orders')?>">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=base_url('dashboard/products')?>">Products</a>
                    </li>
                </ul>

                <div class="d-flex">
                    <a href="<?=base_url('admin/logout')?>" class="text-black">Log Off</a>
                </div>
            </div>
<?php   } else {

?>
            <a class="navbar-brand" href="<?=base_url()?>">Go to Shop</a>

<?php

}  ?>
        </div>
    </nav>