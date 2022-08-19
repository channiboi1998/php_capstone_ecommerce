<?php   for ($i = 1; $i<=$number_of_pages; $i++) {  ?>
            <a class="order-list-paginate-page" 
            href="<?=base_url('orders/order_list_paginate'.(!empty($form_get_link) ? $form_get_link.'&page='.$i : '?page='.$i))?>">
                <?=$i?>
            </a>
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