<?php   $this->load->view('templates/admin-header');  ?>
<?php   $this->load->view('templates/admin-navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container admin-orders-list p-5">
    <div class="row">
        <div class="col-4">
<?php   if (!empty($order_items)) {   
            foreach ($order_items as $order_item) {
?>
            <h1 class="mb-5">Order ID: <?=$order_item['order_id']?></h1>

            <div class="mb-3 shipping-info">
                <h5>Customer Shipping Info</h5>
                <p>Name: <?=$order_item['billing_first_name']?> <?=$order_item['billing_last_name']?></p>
                <p>Address: <?=$order_item['billing_address']?></p>
                <p>City: <?=$order_item['billing_city']?></p>
                <p>State: <?=$order_item['billing_state']?></p>
                <p>Zip: <?=$order_item['billing_zipcode']?></p>
            </div>
            <div class="mb-3 billing-info">
                <h5>Customer Billing Info</h5>
                <p>Name: <?=$order_item['shipping_first_name']?> <?=$order_item['shipping_last_name']?></p>
                <p>Address: <?=$order_item['shipping_address']?></p>
                <p>City: <?=$order_item['shipping_city']?></p>
                <p>State: <?=$order_item['shipping_state']?></p>
                <p>Zip: <?=$order_item['shipping_zipcode']?></p>
            </div>
<?php
            break;
        }
    }   
?>
        </div>
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
<?php   if (!empty($order_items)) {   
            foreach ($order_items as $order_item) {
?>
                    <tr>
                        <td><?=$order_item['order_details_id']?></td>
                        <td><?=$order_item['product_name']?></td>
                        <td><?=$order_item['price']?></td>
                        <td><?=$order_item['quantity']?></td>
                        <td><?=$order_item['quantity']*$order_item['price']?></td>
                    </tr>
<?php
            }
        }
?>
                </tbody>
            </table>
            <div class="row">
<?php   if (!empty($order_items)) {   
            foreach ($order_items as $order_item) {
?>
                <div class="col">
                    <p>Order Status: <?=$order_item['order_status']?></p>
                </div>
                <div class="col">
                    <p>Sub Total: <?=$order_item['amount']?></p>
                </div>
<?php
                break;
            }
        }   
?>
            </div>
        </div>
    </div>

</section>

<?php   $this->load->view('templates/admin-footer');  ?>