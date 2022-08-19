<?php

class Order extends CI_Model {

    /***
     * Construct method for the Order Model
     */
    public function __construct() {

        parent::__construct();
        $this->load->model('Product');

    }

    /***
     * This is the method responsible for updating the order status of a specific order by id
     */
    public function update_order_status_by_id($id) {

        return $this->db->query("UPDATE `orders` SET `order_status` = ? WHERE `orders`.`id` = ?", [
            $this->input->post('update_order_status'),
            $id,
        ]);

    }


    /***
     * This method is responsible in fetching orders | being used in admin order list page and for AJAX Filters (to be created)
     */
    public function get_orders() {

        if ($this->input->get('search_order_details') || $this->input->get('filter_order_by_status')) {

                $result = $this->db->query("SELECT 
                `orders`.*, 
                CONCAT(`billing_information`.`billing_address`, ' ', `billing_information`.`billing_address_2`, ' ', `billing_information`.`billing_city`, ' ', `billing_information`.`billing_zipcode`) AS `billing_address` 
                FROM `orders` INNER JOIN 
                `billing_information` ON
                `orders`.`id` = `billing_information`.`order_id`
                WHERE `orders`.`order_status` LIKE ? AND (`orders`.`id` LIKE ? OR `orders`.`order_status` LIKE ? OR `orders`.`customer_name` LIKE ? OR `orders`.`email_address` LIKE ? OR `orders`.`amount` LIKE ?)", [
                    '%'.$this->input->get('filter_order_by_status').'%',
                    '%'.$this->input->get('search_order_details').'%',
                    '%'.$this->input->get('search_order_details').'%',
                    '%'.$this->input->get('search_order_details').'%',
                    '%'.$this->input->get('search_order_details').'%',
                    '%'.$this->input->get('search_order_details').'%',
                ])->result_array();

        } else {

            $result = $this->db->query("SELECT 
            `orders`.*, 
            CONCAT(`billing_information`.`billing_address`, ' ', `billing_information`.`billing_address_2`, ' ', `billing_information`.`billing_city`, ' ', `billing_information`.`billing_zipcode`) AS `billing_address` 
            FROM `orders` INNER JOIN 
            `billing_information` ON
            `orders`.`id` = `billing_information`.`order_id`")->result_array();

        }

        $page = (!empty($this->input->get('page')) ? (int)$this->input->get('page') : 1);
                
        $query = $this->db->last_query();

        return $this->paginate($page, $query, count($result));

    }

    /***
     * This private method is for pagination | Have set a static value of `6` as offset limit
     */
    private function paginate($page, $query, $numberOfResult) {

        if(empty($page)) {
            $page = 1;
        }
        
        $resultPerPage = 6;
        $numberOfPages = ceil($numberOfResult / $resultPerPage);
        $pageFirstResult = ($page - 1) * $resultPerPage;
        
        $orders = $this->db->query($query. " LIMIT $pageFirstResult, $resultPerPage")->result_array();

        return [
            'orders' => $orders,
            'number_of_pages' => $numberOfPages,
        ];

    }

    /***
     * This method is for fetching specific single order by id
     */
    public function get_single_order_by_id($id) {

        return $this->db->query("SELECT *, `order_details`.`id` AS `order_details_id`
        FROM `order_details` 
        INNER JOIN 
        `orders` ON
        `order_details`.`order_id` = `orders`.`id`
        INNER JOIN `billing_information` ON
        `orders`.`id` = `billing_information`.`order_id`
        INNER JOIN `shipping_information` ON
        `billing_information`.`order_id` = `shipping_information`.`order_id`
        WHERE `orders`.`id` = ?", [$id])->result_array();

    }

    /***
     * This method is responsible in creating a new order 
     */
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
            /***
             * Means that there are errors found by the form validation
             */
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
                (`order_id`, `billing_first_name`, `billing_last_name`, `billing_email_address`, `billing_address`, `billing_address_2`, `billing_city`, `billing_state`, `billing_zipcode`, `created_at`, `updated_at`) 
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
                    (`order_id`, `shipping_first_name`, `shipping_last_name`, `shipping_email_address`, `shipping_address`, `shipping_address_2`, `shipping_city`, `shipping_state`, `shipping_zipcode`, `created_at`, `updated_at`) 
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
                    (`order_id`, `shipping_first_name`, `shipping_last_name`, `shipping_email_address`, `shipping_address`, `shipping_address_2`, `shipping_city`, `shipping_state`, `shipping_zipcode`, `created_at`, `updated_at`) 
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