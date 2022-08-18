<?php 


class Orders extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Product');
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
        $data['page_title'] = '(Carts Page) I Dojo eCommerce';
        $this->load->view('templates/cart', $data);

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