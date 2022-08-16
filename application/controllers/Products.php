<?php

class Products extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Product');

    }

    public function index() {

        $data['page_title'] = 'Products Page';
        $this->load->view('products/products-list', $data);

    }

    function products_list() {

        if (empty($this->session->userdata('user_session')['is_admin'])) {
            /**
             * A `non-admin` user is trying to access admin, kill the session and redirect
             */
            $this->session->unset_userdata('user_session');
            return redirect();

        }

        $data['page_title'] = 'A Web Page';
        $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : '');
        $data['product_description'] = (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : '');
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();
        $this->load->view('admin/admin-products-list', $data);

    }

    public function add_new_product() {

        if (empty($this->session->userdata('user_session')['is_admin'])) {
            /**
             * A `non-admin` user is trying to access admin, kill the session and redirect
             */
            $this->session->unset_userdata('user_session');
            return redirect();

        }

        $result = $this->Product->add_new_product();

        if ($result['status'] === 'error') {
            $data['messages'] = ['error' => $result['message']];
        } else if ($result['status'] === 'success') {
            
            $data['messages'] = ['success' => $result['message']];
            /**
             * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
             */        
            $data['product_name'] = '';
            $data['product_description'] = '';
            $data['selected_categories'] = [];
            $data['categories'] = $this->Product->get_categories();

            return $this->load->view('partials/add-new-product-modal', $data);

        }
        
        /**
         * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
         */        
        $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : '');
        $data['product_description'] = (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : '');
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        return $this->load->view('partials/add-new-product-modal', $data);
    }


    function add_new_category() {

        if (empty($this->session->userdata('user_session')['is_admin'])) {
            /**
             * A `non-admin` user is trying to access admin, kill the session and redirect
             */
            $this->session->unset_userdata('user_session');
            return redirect();

        }

        $result = $this->Product->add_new_category($this->input->post('category_name'));

        if ($result['status'] === 'error') {
            $data['messages'] = ['error' => $result['message']];
        } else if ($result['status'] === 'success') {
            $data['messages'] = ['success' => $result['message']];
        }

        /**
         * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
         */        
        $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : '');
        $data['product_description'] = (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : '');
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        return $this->load->view('partials/add-new-product-modal', $data);

    }

    function delete_category($id) {

        if (empty($this->session->userdata('user_session')['is_admin'])) {
            /**
             * A `non-admin` user is trying to access admin, kill the session and redirect
             */
            $this->session->unset_userdata('user_session');
            return redirect();

        }

        $this->Product->delete_category($id);

        /**
         * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
         */        
        $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : '');
        $data['product_description'] = (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : '');
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        return $this->load->view('partials/add-new-product-modal', $data);

    }

    function update_category($id) {

        if (empty($this->session->userdata('user_session')['is_admin'])) {
            /**
             * A `non-admin` user is trying to access admin, kill the session and redirect
             */
            $this->session->unset_userdata('user_session');
            return redirect();

        }

        $result = $this->Product->update_category($id, $this->input->post('category_name'));

        if ($result['status'] === 'error') {
            $data['messages'] = ['error' => $result['message']];
        } else if ($result['status'] === 'success') {
            $data['messages'] = ['success' => $result['message']];
        }

        /**
         * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
         */        
        $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : '');
        $data['product_description'] = (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : '');
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        return $this->load->view('partials/add-new-product-modal', $data);

    }    

}