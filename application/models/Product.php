<?php

class Product extends CI_Model {


    public function __construct() {

        parent::__construct();

    }

    public function get_product_by_id($id) {

        $product = $this->db->query("SELECT `products`.*, GROUP_CONCAT(`product_categories`.`category_id`) AS `categories`
                                    FROM `products` LEFT JOIN 
                                    `product_categories` ON 
                                    `products`.`id` = `product_categories`.`product_id` 
                                    WHERE `products`.`id` = ? GROUP BY `products`.`id`", [$id])->row_array();

        $product['product_images'] = json_decode($product['product_images'], TRUE);
        
        return $product;

    }

    private function paginate($page, $query, $numberOfResult) {

        if(empty($page)) {
            $page = 1;
        }
        
        $resultPerPage = 5;
        $numberOfPages = ceil($numberOfResult / $resultPerPage);
        $pageFirstResult = ($page - 1) * $resultPerPage;
        
        $products = $this->db->query($query. " LIMIT $pageFirstResult, $resultPerPage")->result_array();

        /**
         * Check if there is results from the last query. If there is, Check if there is product images.
         */
        if ($products) {

            foreach ($products as $key => $product) {

                $productImages = json_decode($products[$key]['product_images'], TRUE);

                if ($productImages) {

                    foreach($productImages as $imageKey => $image) {
    
                        if ($image['is_main'] == 1) {
                            $products[$key]['product_images'] = $productImages[$imageKey]['file_path'];
                        }
    
                    }

                }

            }
            
        }

        return [
            'products' => $products,
            'number_of_pages' => $numberOfPages,
        ];

    }


    /**
     * Method to fetch the products | planning to make this dynamic as well [e.g if there is a parameter/condition on the method]
     */
    public function get_products() {

        if ($searched_name = $this->input->get('search_name')) {

            $result = $this->db->query("SELECT * FROM `products` WHERE `product_name` LIKE ?", ['%'.$searched_name.'%'])->result_array();

        } else {

            $result = $this->db->query("SELECT * FROM `products`")->result_array();

        }

        $page = (!empty($this->input->get('page')) ? (int)$this->input->get('page') : 1);
        
        $query = $this->db->last_query();

        return $this->paginate($page, $query, count($result));

    }

    /**
     * Method for deleting the products (via GET method on the controller)
     */
    public function delete_product($id) {

        $this->db->query("DELETE FROM `product_categories` WHERE `product_id` = ?", [$id]);
        $this->db->query("DELETE FROM `products` WHERE `id` = ?", [$id]);

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
     * The method responsible for updating the product by ID
     */
    public function update_product_by_id($id) {

        $this->form_validation->set_rules('product_name', 'Product Name', 'required|xss_clean');
        $this->form_validation->set_rules('product_description', 'Product Description', 'required|xss_clean');
        $this->form_validation->set_rules('product_price', 'Product Price', 'required|xss_clean|numeric');

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
            $this->db->query("UPDATE `products` 
                                SET `product_name` = ?, `product_description` = ?, `product_price` = ?, `product_images` = ?, `updated_at` = NOW()
                                WHERE `products`.`id` = ?", [
                                $this->input->post('product_name'),
                                $this->input->post('product_description'),
                                $this->input->post('product_price'),
                                json_encode($productImages),
                                $id,
                            ]);

            $this->db->query("DELETE FROM `product_categories` WHERE `product_id` = ?", [$id]);

            if($categories = $this->input->post('categories')) {

                foreach ($categories as $category) {

                    $this->db->query("INSERT INTO product_categories (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES (?,?,?,?)", [
                        $id,
                        $category,
                        date('Y-m-d, H:i:s'),
                        date('Y-m-d, H:i:s'),
                    ]);

                }

            }

            return [
                'status'  => 'success',
                'message' => '<p>Successfully updated product</p>'
            ];

        }

    }    

    /**
     * Method for adding a new product on this class
     */
    public function add_new_product() {

        $this->form_validation->set_rules('product_name', 'Product Name', 'required|xss_clean');
        $this->form_validation->set_rules('product_description', 'Product Description', 'required|xss_clean');
        $this->form_validation->set_rules('product_price', 'Product Price', 'required|xss_clean|numeric');

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
            $this->db->query("INSERT INTO products (`product_name`, `product_description`, `product_price`, `product_images`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?)", [
                $this->input->post('product_name'),
                $this->input->post('product_description'),
                $this->input->post('product_price'),
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