<?php 


class Orders extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Product');
        $this->load->model('Order');
        //$this->session->unset_userdata('cart_session');

    }

    /***
     * This method is specifically updating the selected product's quantity in cart by the user via AJAX
     */
    public function edit_qty_in_cart($id) {

        $currentCartItems = $this->session->userdata('cart_session');

        foreach ($currentCartItems as $key => $currentCartItem) {
    
            if ($currentCartItem['product_id'] == $id) {

                $currentCartItems[$key]['quantity'] = $this->input->post('quantity');
                $productExist = 1;
                break;
                
            }

        }

        /***
         * Pass on the new cart values to the cart session
         */
        $this->session->set_userdata('cart_session', $currentCartItems);

        /***
         * Fetch the product price per items in cart, also calculate the total price per item
         */
        $data['cart_total'] = 0;

        if (!empty($this->session->userdata('cart_session'))) {

            foreach ($data['cart_items'] = $this->session->userdata('cart_session') as $key => $cartItem) {

                $data['cart_items'][$key]['product_price'] = $this->Product->get_product_price_by_id($cartItem['product_id'])['product_price'];
                $data['cart_items'][$key]['total_price'] = $data['cart_items'][$key]['product_price'] * $data['cart_items'][$key]['quantity'];
                $data['cart_total'] = $data['cart_total'] + $data['cart_items'][$key]['total_price'];
    
            }

        }

        $this->load->view('partials/cart-content', $data);
        
    }


    /***
     * This method is for deleting the selected product in cart via AJAX
     */
    public function delete_in_cart($id) {

        $currentCartItems = $this->session->userdata('cart_session');

        $keyToDelete = 0;

        foreach ($currentCartItems as $key => $currentCartItem) {

            if ($currentCartItem['product_id'] == $id) {

                $keyToDelete = $key;
                break;
                
            }

        }

        unset($currentCartItems[$keyToDelete]);

        /***
         * Pass on the new cart values to the cart session
         */
        $this->session->set_userdata('cart_session', $currentCartItems);

        /***
         * Fetch the product price per items in cart, also calculate the total price per item
         */
        $data['cart_total'] = 0;

        if (!empty($this->session->userdata('cart_session'))) {

            foreach ($data['cart_items'] = $this->session->userdata('cart_session') as $key => $cartItem) {

                $data['cart_items'][$key]['product_price'] = $this->Product->get_product_price_by_id($cartItem['product_id'])['product_price'];
                $data['cart_items'][$key]['total_price'] = $data['cart_items'][$key]['product_price'] * $data['cart_items'][$key]['quantity'];
                $data['cart_total'] = $data['cart_total'] + $data['cart_items'][$key]['total_price'];
    
            }

        }

        $this->load->view('partials/cart-content', $data);

    }

    /***
     * Method for just updating the header total via AJAX
     */
    public function fetch_cart_total() {

        $currentCartItemsCount = 0;

        foreach ($this->session->userdata('cart_session') as $currentCartItem) {

            $currentCartItemsCount = $currentCartItemsCount + $currentCartItem['quantity'];

        }

        echo $currentCartItemsCount;

    }

    /***
     * This is the default method on showing up the cart to users
     */
    public function carts() {
        
        /***
         * Fetch the product price per items in cart, also calculate the total price per item
         */
        $data['cart_total'] = 0;

        foreach ($data['cart_items'] = $this->session->userdata('cart_session') as $key => $cartItem) {

            $data['cart_items'][$key]['product_price'] = $this->Product->get_product_price_by_id($cartItem['product_id'])['product_price'];
            $data['cart_items'][$key]['total_price'] = $data['cart_items'][$key]['product_price'] * $data['cart_items'][$key]['quantity'];
            $data['cart_total'] = $data['cart_total'] + $data['cart_items'][$key]['total_price'];

        }

        $data['shipping_first_name'] = (!empty($this->input->post('shipping_first_name')) ? $this->input->post('shipping_first_name') : '');
        $data['shipping_last_name'] = (!empty($this->input->post('shipping_last_name')) ? $this->input->post('shipping_last_name') : '');
        $data['shipping_email_address'] = (!empty($this->input->post('shipping_email_address')) ? $this->input->post('shipping_email_address') : '');
        $data['shipping_address'] = (!empty($this->input->post('shipping_address')) ? $this->input->post('shipping_address') : '');
        $data['shipping_address_2'] = (!empty($this->input->post('shipping_address_2')) ? $this->input->post('shipping_address_2') : '');
        $data['shipping_city'] = (!empty($this->input->post('shipping_city')) ? $this->input->post('shipping_city') : '');
        $data['shipping_state'] = (!empty($this->input->post('shipping_state')) ? $this->input->post('shipping_state') : '');
        $data['shipping_zipcode'] = (!empty($this->input->post('shipping_zipcode')) ? $this->input->post('shipping_zipcode') : '');

        $data['billing_first_name'] = (!empty($this->input->post('billing_first_name')) ? $this->input->post('billing_first_name') : '');
        $data['billing_last_name'] = (!empty($this->input->post('billing_last_name')) ? $this->input->post('billing_last_name') : '');
        $data['billing_email_address'] = (!empty($this->input->post('billing_email_address')) ? $this->input->post('billing_email_address') : '');
        $data['billing_address'] = (!empty($this->input->post('billing_address')) ? $this->input->post('billing_address') : '');
        $data['billing_address_2'] = (!empty($this->input->post('billing_address_2')) ? $this->input->post('billing_address_2') : '');
        $data['billing_city'] = (!empty($this->input->post('billing_city')) ? $this->input->post('billing_city') : '');
        $data['billing_state'] = (!empty($this->input->post('billing_state')) ? $this->input->post('billing_state') : '');
        $data['billing_zipcode'] = (!empty($this->input->post('billing_zipcode')) ? $this->input->post('billing_zipcode') : '');

        $data['page_title'] = '(Carts Page) I Dojo eCommerce';

        if ($this->input->post()) {

            $result = $this->Order->create_order();

            if ($result['status'] === 'error') {
                /***
                 * Means that there is an error found during the submit of order, return the error
                 */
                $this->session->set_flashdata('messages', ['error' => $result['message']]);
                return $this->load->view('templates/cart', $data);

            } else if ($result['status'] === 'success') {
                /***
                 * Means that the submit of order went successfully, redirect the user to the prdouct catalog page
                 */
                $this->session->set_flashdata('messages', ['success' => $result['message']]);
                $this->session->set_userdata('cart_session', []);
                return redirect();

            }

        } else {
            /***
             * Sumbmit order form is not yet submitted, just display the data in cart
             */
            $this->load->view('templates/cart', $data);

        }

    }

    /***
     * This method is for setting up the cart session | Also returns the updated total items in cart for AJAX
     */
    public function add_to_cart($productId) {

        $result = $this->Product->get_product_by_id($productId);

        if ($result) {

            $currentCartItems = $this->session->userdata('cart_session');

            if(!empty($currentCartItems)) {
                /***
                 * Means that the cart is not empty
                 */
                $productExist = 0;

                foreach ($currentCartItems as $key => $currentCartItem) {
    
                    if ($currentCartItem['product_id'] == $result['id']) {

                        $currentCartItems[$key]['quantity'] = $currentCartItems[$key]['quantity'] + $this->input->post('quantity');
                        $productExist = 1;
                        break;
                        
                    }

                }

                if ($productExist == 1) {
                    /***
                     * Means that the product already exist on the cart and have already updated the quantity of the product in cart. Just Proceed now.
                     */

                } else {
                    /***
                     * Means that the product is not found on the cart, Insert the product on the cart now
                     */

                    /***
                     * Get the main image of the product to be passed on the `cart` session | $addItem is where we store the values of the new product to be distributed on the cart
                     */
                    $addItem['product_id'] = $result['id'];
                    $addItem['product_name'] = $result['product_name'];
                    $addItem['quantity'] = $this->input->post('quantity');

                    foreach($result['product_images'] as $imageKey => $image) {

                        if ($image['is_main'] == 1) {

                            $addItem['product_image'] = $result['product_images'][$imageKey]['file_path'];
                            break;

                        }

                    }

                    $currentCartItems[] = $addItem;

                }

            } else {
                /***
                 * Means that the cart is empty, Just add the product on the empty cart
                 */

                /***
                 * Get the main image of the product to be passed on the `cart` session | $addItem is where we store the values of the new product to be distributed on the cart
                 */
                $addItem['product_id'] = $result['id'];
                $addItem['product_name'] = $result['product_name'];
                $addItem['quantity'] = $this->input->post('quantity');

                foreach($result['product_images'] as $imageKey => $image) {

                    if ($image['is_main'] == 1) {

                        $addItem['product_image'] = $result['product_images'][$imageKey]['file_path'];
                        break;

                    }

                }

                $currentCartItems[] = $addItem;

            }

            /***
             * Pass on the new cart values to the cart session
             */
            $this->session->set_userdata('cart_session', $currentCartItems);

        }

    }

}