<?php

class Products extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('Product');

    }

    
    /**
     * The index method of this `Product Controller` | Lists all of the products
     */
    public function index() {

        $data['page_title'] = 'Products Page';
        $this->load->view('products/products-list', $data);

    }


    /**
     * Lists all of the products | This is also where the `add-new-product` Modal is instantiated
     */
    function products_list() {

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
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        $this->load->view('admin/admin-products-list', $data);

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

                    $fileData = $this->upload->data(); 
                    $uploadData[$i]['file_name'] = $fileData['file_name']; 
                    $uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s"); 

                    $data['uploaded_images'][] = [
                        'filename' => $fileData['file_name'],
                        'filepath' => base_url().'uploads/'.$fileData['file_name'],
                    ];

                } else {  

                    $errorUploadType .= $_FILES['file']['name'].' | ';  

                }
            }
            
            if($data['uploaded_images']) {

                $html = '';

                    foreach ($data['uploaded_images'] as $key => $image) {
                        
                        $html .= '<div class="row border rounded-2 mb-2">';
                        $html .= '      <input type="hidden" name="product_images[]" value="'.$image['filepath'].'">';
                        $html .= '      <div class="col-2 my-auto"><input type="radio" name="is_main" value="'.$key.'"><p class="main-photo-label">Main Photo</p></div>';
                        $html .= '      <div class="col-2 my-auto"><i class="bi bi-list"></i></div>';
                        $html .= '      <div class="col-2 my-auto"><img src="'.$image['filepath'].'" alt=""></div>';
                        $html .= '      <div class="col my-auto"><p class="product-image-title">'.substr($image['filename'], 0, 20).' ...</p></div>';
                        $html .= '      <div class="col-2 my-auto text-right"><i class="bi bi-trash"></i></div>';
                        $html .= '</div>';

                    }

                echo $html;

            }
            
        }

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
            $data['selected_categories'] = [];
            $data['categories'] = $this->Product->get_categories();
            $data['success'] = TRUE;

            return $this->load->view('partials/add-new-product-modal', $data);

        }
        
    }


    /**
     * The Method for adding a new category on this class
     */
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
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        return $this->load->view('partials/category-list', $data);

    }


    /**
     * The delete-a-category method for this class
     */
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
        //$data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        //$data['categories'] = $this->Product->get_categories();

        //return $this->load->view('partials/category-list', $data);

    }


    /**
     * The update-one-category for this class
     */
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
        $data['selected_categories'] = (!empty($this->input->post('categories')) ? $this->input->post('categories') : []);
        $data['categories'] = $this->Product->get_categories();

        return $this->load->view('partials/category-list', $data);

    }    

}