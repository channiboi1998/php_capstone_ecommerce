<?php   $this->load->view('templates/admin-header');  ?>
<?php   $this->load->view('templates/admin-navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container admin-orders-list p-5 pb-0">
    <form action="<?=base_url('orders/order_list_paginate')?>" class="row order-search-filters" method="GET">
        <div class="col">
            <input type="text" class="form-control" placeholder="Search Order" name="search_order_details" id="search_order_details">
        </div>
        <div class="col text-right">
            <select name="filter_order_by_status" class="filter-order-by-status form-select" id="filter_order_by_status">
                <option value>Filter by Order Status: Show All</option>
                <option value="processing">Filter by Order Status: Processing</option>
                <option value="cancelled">Filter by Order Status: Cancelled</option>
                <option value="completed">Filter by Order Status: Completed</option>
            </select>
        </div>
    </form>
</section>

<section class="container admin-orders-list p-5" id="orders-list-paginate">

<?php   for ($i = 1; $i<=$number_of_pages; $i++) {  ?>
            <a class="order-list-paginate-page" href="<?=base_url('orders/order_list_paginate?page='.$i)?>"><?=$i?></a>
<?php   }   ?>
    <table class="table">
        <thead>
            <th>Order ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Billing Address</th>
            <th>Total</th>
            <th>Status</th>
        </thead>
        <tbody>
<?php   if (!empty($orders)) {
            foreach($orders as $order) {
?>
            <tr>
                <td><a href="<?=base_url('orders/show/'.$order['id'])?>"><?=$order['id']?></a></td>
                <td><?=$order['customer_name']?></td>
                <td><?=$order['created_at']?></td>
                <td><?=$order['billing_address']?></td>
                <td><?=$order['amount']?></td>
                <td>
                    <form action="<?=base_url('orders/update_order_status/'.$order['id'])?>" method="POST" class="update-order-status-form">
                        <select class="form-select update-order-status" name="update_order_status">
                            <option value="processing" <?=($order['order_status'] === 'processing' ? 'selected' : '')?>>Processing</option>
                            <option value="cancelled" <?=($order['order_status'] === 'cancelled' ? 'selected' : '')?>>Cancelled</option>
                            <option value="completed" <?=($order['order_status'] === 'completed' ? 'selected' : '')?>>Completed</option>
                        </select>
                    </form>
                </td>
            </tr>
<?php
            }
        }
?>
        </tbody>
    </table>
</section>

<?php   $this->load->view('templates/admin-footer');  ?>