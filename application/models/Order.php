<?php

class Order extends CI_Model {


    public function __construct() {

        parent::__construct();
        $this->load->model('Product');

    }

    public function create_order() {

        /***
         * Validation rules for the billing
         */
        $this->form_validation->set_rules('shipping_first_name', 'Shipping Information: First Name', 'required|xss_clean');
        $this->form_validation->set_rules('shipping_last_name', 'Shipping Information: Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('shipping_email_address', 'Shipping Information: Email Address', 'required|valid_email|xss_clean');
        $this->form_validation->set_rules('shipping_address', 'Shipping Information: Address', 'required|xss_clean');
        $this->form_validation->set_rules('shipping_address_2', 'Shipping Information: Address 2', 'xss_clean');
        $this->form_validation->set_rules('shipping_city', 'Shipping Information: City', 'required|xss_clean');
        $this->form_validation->set_rules('shipping_state', 'Shipping Information: State', 'required|xss_clean');
        $this->form_validation->set_rules('shipping_zipcode', 'Shipping Information: Zipcode', 'required|numeric|xss_clean');

        /***
         * Validation rules for the shipping
         */
        if (empty($this->input->post('same_as_billing'))) {

            $this->form_validation->set_rules('billing_first_name', 'Billing Information: First Name', 'required|xss_clean');
            $this->form_validation->set_rules('billing_last_name', 'Billing Information: Last Name', 'required|xss_clean');
            $this->form_validation->set_rules('billing_email_address', 'Billing Information: Email Address', 'required|valid_email|xss_clean');
            $this->form_validation->set_rules('billing_address', 'Billing Information: Address', 'required|xss_clean');
            $this->form_validation->set_rules('billing_address_2', 'Billing Information: Address 2', 'xss_clean');
            $this->form_validation->set_rules('billing_city', 'Billing Information: City', 'required|xss_clean');
            $this->form_validation->set_rules('billing_state', 'Billing Information: State', 'required|xss_clean');
            $this->form_validation->set_rules('billing_zipcode', 'Billing Information: Zipcode', 'required|numeric|xss_clean');   

        }

        if ($this->form_validation->run() === FALSE) {

            return [
                'status' => 'error',
                'message' => validation_errors(),
            ];

        } else {

            $amount = 0;

            /***
             * Fetch the total amount of all of the items | Did the calculation of cart here (via product id on session) thinking it would be safer
             */
            foreach ($cartItems = $this->session->userdata('cart_session') as $key => $cartItem) {

                $itemAmount = $this->Product->get_product_price_by_id($cartItem['product_id']);
                $amount = $amount + $itemAmount['product_price'] * $cartItem['quantity'];
                $cartItems[$key]['product_price'] = $itemAmount['product_price'];
    
            }
    
            if ($cartItems == []) {
                /***
                 * Means that the cart session is empty
                 */
                return [
                    'status' => 'error',
                    'message' => '<p>Your cart is empty, Add an item to your cart first!</p>',
                ];

            } else {

                /***
                 * Create the order on `orders` table
                 */
                $this->db->query("INSERT INTO `orders` (`order_status`, `customer_name`, `email_address`, `amount`, `created_at`, `updated_at`) 
                                VALUES ('processing', ?, ?, ?, NOW(), NOW())", [
                                    $this->input->post('shipping_first_name'). ' '.$this->input->post('shipping_last_name'),
                                    $this->input->post('shipping_email_address'),
                                    $amount,
                                ]);

                $orderId = $this->db->insert_id();

                /***
                 * Create the payment via Stripe | Loadup stripe library to create payment
                 */
                require_once('application/libraries/stripe-php/init.php');

                \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
                
                \Stripe\Charge::create ([
                        "amount" => 100 * $amount,
                        "currency" => "USD",
                        "source" => $this->input->post('stripeToken'),
                        "description" => "Dojo E-Commerce Payment for order #".$orderId, 
                ]);

                /***
                 * Create a record on `order_details` for each of the products in cart
                 */
                foreach ($cartItems as $cartItem) {
                    
                    $this->db->query("INSERT INTO `order_details` (`order_id`, `product_name`, `price`, `quantity`, `created_at`, `updated_at`) 
                    VALUES (?, ?, ?, ?, NOW(), NOW())", [
                        $orderId,
                        $cartItem['product_name'],
                        $cartItem['product_price'],
                        $cartItem['quantity'],
                    ]);

                }          

                /***
                 * Insert the billing information of the user into the database
                 */
                $this->db->query("INSERT INTO `billing_information` 
                (`order_id`, `first_name`, `last_name`, `email_address`, `address`, `address_2`, `city`, `state`, `zipcode`, `created_at`, `updated_at`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())", [
                    $orderId,
                    $this->input->post('shipping_first_name'),
                    $this->input->post('shipping_last_name'),
                    $this->input->post('shipping_email_address'),
                    $this->input->post('shipping_address'),
                    $this->input->post('shipping_address_2'),
                    $this->input->post('shipping_city'),
                    $this->input->post('shipping_state'),
                    $this->input->post('shipping_zipcode'),
                ]);

                if (!empty($this->input->post('same_as_billing'))) {

                    $this->db->query("INSERT INTO `shipping_information` 
                    (`order_id`, `first_name`, `last_name`, `email_address`, `address`, `address_2`, `city`, `state`, `zipcode`, `created_at`, `updated_at`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())", [
                        $orderId,
                        $this->input->post('shipping_first_name'),
                        $this->input->post('shipping_last_name'),
                        $this->input->post('shipping_email_address'),
                        $this->input->post('shipping_address'),
                        $this->input->post('shipping_address_2'),
                        $this->input->post('shipping_city'),
                        $this->input->post('shipping_state'),
                        $this->input->post('shipping_zipcode'),
                    ]);

                } else {

                    $this->db->query("INSERT INTO `shipping_information` 
                    (`order_id`, `first_name`, `last_name`, `email_address`, `address`, `address_2`, `city`, `state`, `zipcode`, `created_at`, `updated_at`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())", [
                        $orderId,
                        $this->input->post('billing_first_name'),
                        $this->input->post('billing_last_name'),
                        $this->input->post('billing_email_address'),
                        $this->input->post('billing_address'),
                        $this->input->post('billing_address_2'),
                        $this->input->post('billing_city'),
                        $this->input->post('billing_state'),
                        $this->input->post('billing_zipcode'),
                    ]);

                }

                return [
                    'status' => 'success',
                    'message' => '<p>Your order has been placed, wait for atleast 12 to 24 Hours for us to process your order. Thank you!</p>',
                ];
                
            }

        }

    }

}