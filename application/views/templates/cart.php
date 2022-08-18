<?php   $this->load->view('templates/header');  ?>
<?php   $this->load->view('templates/navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

<section class="container checkout-cart-page p-5">
    <div id="table-cart-items">
        <table class="table cart-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
<?php   if (!empty($cart_items)) {
                foreach ($cart_items as $cart_item) {
?>
                <tr>
                    <td><img src="<?=$cart_item['product_image']?>" alt="">    <?=$cart_item['product_name']?></td>
                    <td>$<?=$cart_item['product_price']?></td>
                    <td class="row">
                        <div class="col">
                            <span class="item-count">
                                <form action="<?=base_url('orders/edit_qty_in_cart/'.$cart_item['product_id'])?>" method="POST" class="edit-cart-quantity">
                                    <input type="number" name="quantity" min="1" class="form-control quantity" value="<?=$cart_item['quantity']?>">
                                </form>
                            </span>
                        </div>
                        <div class="col">
                            <i class="bi bi-trash" class="delete-item-cart" data-url="<?=base_url('orders/delete_in_cart/'.$cart_item['product_id'])?>"></i>
                        </div>
                    </td>
                    <td>$<?=$cart_item['total_price']?></td>
                </tr>
<?php
                }
            } else {
                echo 'No data available in cart';
            }
?>
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th>Order Total:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>$<?=$cart_total?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <form action="<?=base_url('carts')?>" method="POST" class="checkout-form form-validation"  data-cc-on-file="false"
							data-stripe-publishable-key="<?=$this->config->item('stripe_key') ?>" id="payment-form">
        <div class="shipping-information">
            <h3 class="mb-3">Shipping Information</h3>
            <div class="row mb-3">
                <div class="col">
                    <label for="shipping_first_name">First Name:</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="shipping_first_name" value="<?=$shipping_first_name?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="shipping_last_name">Last Name:</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="shipping_last_name" value="<?=$shipping_last_name?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="shipping_email_address">Email Address:</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="shipping_email_address" value="<?=$shipping_email_address?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="shipping_address">Address</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="shipping_address" value="<?=$shipping_address?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="shipping_address_2">Address 2</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="shipping_address_2" value="<?=$shipping_address_2?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="shipping_city">City</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="shipping_city" value="<?=$shipping_city?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="shipping_state">State</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="shipping_state" value="<?=$shipping_state?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="shipping_zipcode">Zipcode</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="shipping_zipcode" value="<?=$shipping_zipcode?>">
                </div>
            </div>
        </div>
        <div class="billing-information">
            <h3 class="mb-3">Billing Information</h3>
            <label for="same_as_billing" class="mb-3">
                Same as Billing
                <input type="checkbox" id="same_as_billing" name="same_as_billing" <?=(!empty($this->input->post('same_as_billing')) ? 'checked' : '')?>>
            </label>
            <div class="row mb-3">
                <div class="col">
                    <label for="billing_first_name">First Name:</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="billing_first_name" value="<?=$billing_first_name?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="billing_last_name">Last Name:</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="billing_last_name" value="<?=$billing_last_name?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="billing_email_address">Email Address:</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="billing_email_address" value="<?=$billing_email_address?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="billing_address">Address</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="billing_address" value="<?=$billing_address?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="billing_address_2">Address 2</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="billing_address_2" value="<?=$billing_address_2?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="billing_city">City</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="billing_city" value="<?=$billing_city?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="billing_state">State</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="billing_state" value="<?=$billing_state?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="billing_zipcode">Zipcode</label>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="billing_zipcode" value="<?=$billing_zipcode?>">
                </div>
            </div>
        </div>
        <div class="payment-section">
            <div class='form-row row'>
                <div class='mb-3 col-xs-12 form-group required'>
                    <label class='control-label'>Name on Card</label>
                    <input class='form-control' size='4' type='text'>
                </div>
            </div>
            <div class='form-row row mb-3'>
                <div class='col-xs-12 form-group required'>
                    <label class='control-label'>Card Number</label>
                    <input autocomplete='off' class='form-control card-number' size='20' type='text'>
                </div>
            </div>
            <div class='form-row row mb-3'>
                <div class='col-xs-12 col-md-4 form-group cvc required'>
                    <label class='control-label'>CVC</label>
                    <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311'
                        size='4' type='text'>
                </div>
                <div class='col-xs-12 col-md-4 form-group expiration required'>
                    <label class='control-label'>Expiration Month</label>
                    <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
                </div>
                <div class='col-xs-12 col-md-4 form-group expiration required'>
                    <label class='control-label'>Expiration Year</label>
                    <input class='form-control card-expiry-year' placeholder='YYYY' size='4'
                        type='text'>
                </div>
            </div>
            <div class='form-row row mb-3'>
                <div class='col-md-12 error form-group hide'>
                    <div class='alert-danger alert'>Error occured while making the payment.</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <input type="submit" class="form-control btn btn-success" value="Pay">
                </div>
            </div>
        </div>
    </form>
</section>

<?php   $this->load->view('templates/footer');  ?>