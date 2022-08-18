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
</section>

<?php   $this->load->view('templates/footer');  ?>