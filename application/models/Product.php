<?php


class Product extends CI_Model {


    function __construct() {

        parent::__construct();

    }


    /**
     * Method for fetchin all of the categories
     */
    public function get_categories() {

        return $this->db->query("SELECT `id`, `category_name` FROM `categories`")->result_array();

    }


    /**
     * Method for updating the name of a specific category by id, passing the new name value of the category
     */
    public function update_category($id, $value) {

        $this->form_validation->set_rules('category_name', 'New Category Name', 'required|xss_clean');

        if ($this->form_validation->run() === FALSE) {

            return [
                'status' => 'error',
                'message' => validation_errors(),
            ];

        } else {

            $this->db->query("UPDATE `categories` SET `category_name` = ? WHERE `categories`.`id` = ?", [
                $value,
                $id
            ]);

            return [
                'status' => 'success',
                'message' => '<p>Successfully updated a category</p>' 
            ];

        }

    }


    /**
     * Method for deleting a category by id
     */
    public function delete_category($id) {

        /**
         * Delete rows from child table where category_id is = $id
         */
        $this->db->query("DELETE FROM `product_categories` WHERE `category_id` = ?", [$id]);
        $this->db->query("DELETE FROM `categories` WHERE `id` = ?", [$id]);

        return [
            'status' => 'success',
            'message' => '<p>Successfully deleted 1 category</p>'
        ];

    }


    /**
     * Method for adding  a new category on this class
     */
    public function add_new_category() {

        $this->form_validation->set_rules('category_name', 'New Category Name', 'required|xss_clean');

        if ($this->form_validation->run() === FALSE) {

            return [
                'status' => 'error',
                'message' => validation_errors(),
            ];

        } else {

            if ($this->input->post('category_name')) {

                // TEMPORARY DISABLED THIS FEATURE BECAUSE I CANNOT Think weell bro
                // $result = $this->db->query("SELECT * FROM categories WHERE `category_name` = ?", [
                //     $this->input->post('category_name'),
                // ])->row_array();

                // if ($result) {

                //     return [
                //         'status' => 'error',
                //         'message' => '<p><b>'.$this->input->post('category_name').'</b> category already exist.</p>',
                //     ];

                // } else {

                    $this->db->query("INSERT INTO categories (`category_name`, `created_at`, `updated_at`) VALUES (?,?,?)", [
                        $this->input->post('category_name'),
                        date('Y-m-d, H:i:s'),
                        date('Y-m-d, H:i:s'),
                    ]);

                    return [
                        'status' => 'success',
                        'message' => '<p>Successfully added a new product category</p>'
                    ];

                //}

            }

        }

    }


    /**
     * Method for adding a new product on this class
     */
    public function add_new_product() {

        $this->form_validation->set_rules('product_name', 'Product Name', 'required|xss_clean');
        $this->form_validation->set_rules('product_description', 'Product Description', 'required|xss_clean');

        if ($this->form_validation->run() === FALSE) {

            return [
                'status' => 'error',
                'message' => validation_errors(),
            ];

        } else {

            $productImages = [];

            if ($this->input->post('product_images')) {

                foreach($this->input->post('product_images') as $key => $value) {
        
                    $productImages[$key]['file_path'] = $value;
                    $productImages[$key]['is_main'] = ($key == $this->input->post('is_main') ? 1 : 0);
        
                }

            }

            /**
             * Create the the product
             */
            $this->db->query("INSERT INTO products (`product_name`, `product_description`, `product_images`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?)", [
                $this->input->post('product_name'),
                $this->input->post('product_description'),
                json_encode($productImages),
                date('Y-m-d, H:i:s'),
                date('Y-m-d, H:i:s'),
            ]);

            /**
             * Creating this if ever that a new category is created and assigned to this product, we will create an entry to the middle table (product_categories) Since it `Product` and `Cateories` are in many-to-many relationship
             */
            $productId = $this->db->insert_id();

            if($categories = $this->input->post('categories')) {

                foreach ($categories as $category) {

                    $this->db->query("INSERT INTO product_categories (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES (?,?,?,?)", [
                        $productId,
                        $category,
                        date('Y-m-d, H:i:s'),
                        date('Y-m-d, H:i:s'),
                    ]);

                }

            }

            return [
                'status'  => 'success',
                'message' => '<p>Successfully added a new product</p>'
            ];

        }

    }

}