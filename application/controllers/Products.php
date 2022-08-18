<?php

class Products extends CI_Controller {


    public function __construct() {

        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('Product');

    }


    /**
     * Table Refresh on every AJAX call
     */
    public function product_list_paginate($parameter = NULL) {

        $data = $this->Product->get_products();

        $data['search_name'] = (!empty($this->input->get('search_name')) ? '&search_name='.$this->input->get('search_name') : '');

        $this->load->view('partials/products-list-paginate', $data);

    }


    /**
     * Lists all of the products | This is also where the `add-new-product` Modal is instantiated
     */
    public function products_list() {

        if (empty($this->session->userdata('user_session')['is_admin'])) {
            /**
             * A `non-admin` user is trying to access admin, kill the session and redirect
             */
            $this->session->unset_userdata('user_session');
            return redirect();

        }

        /**
         * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
         */     
        $data['page_title'] = 'A Web Page';
        $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : '');
        $data['product_description'] = (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : '');
        $data['product_price'] = (!empty($this->input->post('product_price')) ? $this->input->post('product_price') : '');
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        $result = $this->Product->get_products();
        $data['products'] = $result['products'];
        $data['number_of_pages'] = $result['number_of_pages'];

        $this->load->view('admin/admin-products-list', $data);

    }

    /**
     * The Method responsible for updating the product
     */
    public function update_product($id) {
        
        $result = $this->Product->update_product_by_id($id);
        
        if ($result['status'] === 'error') {
            $data['messages'] = ['error' => $result['message']];
        } else if ($result['status'] === 'success') {
            $data['messages'] = ['success' => $result['message']];
        }
        //add toast here or create a function to append on messages
        print_r($data['messages']);

    }

    /**
     * The Method responsible for placing the current values of product before update method
     */
    public function edit_product($id) {

        $product = $this->Product->get_product_by_id($id);

        $data['product_id'] = $product['id'];
        $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : $product['product_name']);
        $data['product_description'] =  (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : $product['product_description']);
        $data['product_price'] = (!empty($this->input->post('product_price')) ? $this->input->post('product_price') : $product['product_price']);
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : explode(',', $product['categories']));
        $data['categories'] = $this->Product->get_categories();
        $data['product_images'] = $product['product_images'];
        $this->load->view('partials/edit-new-product-modal', $data);

    }


    /**
     * The Method responsible for deleting the products
     */
    public function delete_product($id) {

        $this->Product->delete_product($id);

    }
    

    /**
     * The method for uploading the images into the server
     */
    public function upload_images() {

        if (!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0) { 

            $filesCount = count($_FILES['files']['name']); 
            
            $data['uploaded_images'] = [];

            for ($i = 0; $i < $filesCount; $i++) { 
                $_FILES['file']['name']         = $_FILES['files']['name'][$i]; 
                $_FILES['file']['type']         = $_FILES['files']['type'][$i]; 
                $_FILES['file']['tmp_name']     = $_FILES['files']['tmp_name'][$i]; 
                $_FILES['file']['error']        = $_FILES['files']['error'][$i]; 
                $_FILES['file']['size']         = $_FILES['files']['size'][$i]; 
                 
                $uploadPath = 'uploads/'; 
                $config['upload_path'] = $uploadPath; 
                $config['allowed_types'] = 'jpg|jpeg|png|gif'; 
                 
                $this->load->library('upload', $config); 
                $this->upload->initialize($config); 
                 
                if ($this->upload->do_upload('file')) { 
                    /**
                     * All Good, Proceed to uploading the files to the server
                     */
                    $fileData = $this->upload->data(); 
                    $uploadData[$i]['file_name'] = $fileData['file_name']; 
                    $uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s"); 

                    $data['uploaded_images'][] = [
                        'filename' => $fileData['file_name'],
                        'filepath' => base_url().'uploads/'.$fileData['file_name'],
                    ];

                } else {  

                    /**
                     * Means that there is an error on the file upload process | Might be incorrect file type or etc.
                     */
                    $errorUploadType .= $_FILES['file']['name'].' | ';  

                }
            }
            
            /**
             * Check if there is an image/s successfully uploaded to the server | create an HTML structure `jquery UI sortable` to be passed on the AJAX script to view 
             */
            if ($data['uploaded_images']) {

                $html = '';

                    foreach ($data['uploaded_images'] as $key => $image) {
                        
                        $html .= '<div class="row border rounded-2 mb-2">';
                        $html .= '      <input type="hidden" name="product_images[]" value="'.$image['filepath'].'">';
                        $html .= '      <div class="col-2 my-auto"><input type="radio" name="is_main" value="'.$key.'"><p class="main-photo-label">Main Photo</p></div>';
                        $html .= '      <div class="col-2 my-auto"><i class="bi bi-list"></i></div>';
                        $html .= '      <div class="col-2 my-auto"><img src="'.$image['filepath'].'" alt=""></div>';
                        $html .= '      <div class="col my-auto"><p class="product-image-title">...'.substr($image['filename'], -10).'</p></div>';
                        $html .= '      <div class="col-2 my-auto text-right"><i class="bi bi-trash"></i></div>';
                        $html .= '</div>';

                    }

                echo $html;

            }
            
        }

    }

    /**
     * This method is when the user wants to create a new product, display a blank form via AJAX
     */
    public function prepare_add_new_form() {

        $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : '');
        $data['product_description'] = (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : '');
        $data['product_price'] = (!empty($this->input->post('product_price')) ? $this->input->post('product_price') : '');
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        $this->load->view('partials/add-new-product-modal-prepare', $data);
    }

    /**
     * The Method for adding a new product on this class
     */
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

            /**
             * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
             */        
            $data['product_name'] = (!empty($this->input->post('product_name')) ? $this->input->post('product_name') : '');
            $data['product_description'] = (!empty($this->input->post('product_description')) ? $this->input->post('product_description') : '');
            $data['product_price'] = (!empty($this->input->post('product_price')) ? $this->input->post('product_price') : '');
            $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
            $data['categories'] = $this->Product->get_categories();

            return $this->load->view('partials/add-new-product-modal', $data);

        } else if ($result['status'] === 'success') {
            
            $data['messages'] = ['success' => $result['message']];
            /**
             * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
             */        
            $data['product_name'] = '';
            $data['product_description'] = '';
            $data['product_price'] = '';
            $data['selected_categories'] = [];
            $data['categories'] = $this->Product->get_categories();
            $data['success'] = TRUE;

            return $this->load->view('partials/add-new-product-modal', $data);

        }
        
    }


    /**
     * The Method for adding a new category on this class
     */
    public function add_new_category() {

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
        //Add toast message here or create a JS function to append the message to the frontend

        /**
         * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
         */        
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        return $this->load->view('partials/category-list', $data);

    }


    /**
     * The delete-a-category method for this class
     */
    public function delete_category($id) {

        if (empty($this->session->userdata('user_session')['is_admin'])) {
            /**
             * A `non-admin` user is trying to access admin, kill the session and redirect
             */
            $this->session->unset_userdata('user_session');
            return redirect();

        }

        $this->Product->delete_category($id);
        //Add Toast in the future here after succesful deletion

    }


    /**
     * The update-one-category for this class
     */
    public function update_category($id) {

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
        //Add toast here or create a JS function to show message on the frontend

        /**
         * Setting up previous data | Important also so that admin won't have to re-populate fields when there is an error
         */
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        return $this->load->view('partials/category-list', $data);

    }    

}