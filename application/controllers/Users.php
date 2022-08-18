<?php

class Users extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('User');
        $this->load->model('Product');

    }


    /**
     * This method is for displaying the single product
     */
    public function show($id) {

        $data['product'] = $this->Product->get_product_by_id($id);
        $data['page_title'] = '(Product Page) '.$data['product']['product_name'].' | Dojo E-commerce';

        $data['similar_items'] = $this->Product->get_similar_items_by_categories($data['product']['categories'], $id);

        /**
         * Fetching the `main_image` of the product
         */
        if (!empty($images = $data['product']['product_images'])) {
            foreach ($images as $image) {
                if ($image['is_main'] == 1) { 
                    $data['main_image'] = $image['file_path'];
                    break;
                }
            }
        }
        
        $this->load->view('products/single-product', $data);

    }


    /**
     * This method is used for products list page
     */
    public function products() {

        $data['page_title'] = 'Products Page';

        $result = $this->Product->get_products();
        $data['products'] = $result['products'];
        $data['categories'] = $this->Product->get_product_categories();
        $data['search_name'] = !empty($this->input->get('search_name')) ? $this->input->get('search_name') : '';
        $data['number_of_pages'] = [];
        /**
         * Check if there is a GET category
         */
        if ($search_name = $this->input->get('search_name')) {

            for ($i=1; $i<=$result['number_of_pages']; $i++) {
                $data['number_of_pages'][] = [
                    'page_number' => $i,
                    'url' => base_url('products?search_name='.$search_name.'&page='.$i),
                ];
            }

        } else if ($category_id = $this->input->get('category_id')) {

            for ($i=1; $i<=$result['number_of_pages']; $i++) {
                $data['number_of_pages'][] = [
                    'page_number' => $i,
                    'url' => base_url('products?category_id='.$category_id.'&page='.$i),
                ];
            }

        } else {

            for ($i=1; $i<=$result['number_of_pages']; $i++) {
                $data['number_of_pages'][] = [
                    'page_number' => $i,
                    'url' => base_url('products?page='.$i),
                ];
            }

        }

        $this->load->view('products/products-list', $data);

    }


    /**
     * This method is for users to logout
     */
    public function logout() {

        $this->session->unset_userdata('user_session');
        return redirect('users/login');

    }


    /**
     * This method is for the login of the user
     */
    public function login() {


        /**
         * Check if there is a current user session | Redirect if there's any
         */
        if ($this->session->userdata('user_session')) {

            return redirect('products');

        }        

        $data['page_title'] = 'Login Page';

        if ($this->input->post()) {

            $data['email_address'] = $this->input->post('email_address');
            $data['password'] = $this->input->post('password');

            $result = $this->User->login_user();

            if ($result['status'] === 'error') {
                
                $this->session->set_flashdata('messages', ['error' => $result['message']]);
                $this->load->view('users/login', $data);
                
            } else if ($result['status'] === 'success') {

                /**
                 * Create `user_session` session with email address as it's values
                 */
                $this->session->set_userdata('user_session', [
                    'email_address' => $data['email_address'],
                ]);

                $this->session->set_flashdata('messages', ['success' => $result['message']]);
                return redirect('products');

            }

        } else {
            /**
             * Login POST method is not yet initiated, Just display the login form
             */
            $this->load->view('users/login', $data);

        }

    }

    
    /**
     * This method is for the registration of users
     */
    public function register() {

        /**
         * Check if there is a current user session | Redirect if theres any
         */
        if ($this->session->userdata('user_session')) {

            return redirect('products');

        }

        $data['page_title'] = 'Registration Page';

        if($this->input->post()) {

            /**
             * Initiate data values | Important for validations, to pass values to view if there is an error so the user wont have to repopulate fields
             */
            $data['first_name'] = $this->input->post('first_name');
            $data['last_name'] = $this->input->post('last_name');
            $data['email_address'] = $this->input->post('email_address');
            $data['password'] = $this->input->post('password');

            /**
             * Registration POST method is submitted. Process creation of account
             */
            $result = $this->User->register_user();

            if ($result['status'] === 'error') {
                /**
                 * Error found in registering the user
                 */
                $this->session->set_flashdata('messages', ['error' => $result['message']]);
                return $this->load->view('users/register', $data);

            } else if ($result['status'] === 'success') {
                /**
                 * Success on creating a new user on the database | Create `user_session` session with email address as it's values
                 */
                $this->session->set_userdata('user_session', [
                    'email_address' => $data['email_address'],
                ]);

                $this->session->set_flashdata('messages', ['success' => $result['message']]);
                return redirect('products');

            }

        } else {
            /**
             * Registration POST method is not yet submitted, just display the form
             */
            return $this->load->view('users/register', $data);

        }

    }

}