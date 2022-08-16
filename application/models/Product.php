<?php


class Product extends CI_Model {


    function __construct() {

        parent::__construct();

    }

    public function get_categories() {

        return $this->db->query("SELECT `id`, `category_name` FROM `categories`")->result_array();

    }

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

    public function add_new_category() {

        $this->form_validation->set_rules('category_name', 'New Category Name', 'required|xss_clean');

        if ($this->form_validation->run() === FALSE) {

            return [
                'status' => 'error',
                'message' => validation_errors(),
            ];

        } else {

            if ($this->input->post('category_name')) {

                $result = $this->db->query("SELECT * FROM categories WHERE `category_name` = ?", [
                    $this->input->post('category_name'),
                ])->row_array();

                if ($result) {

                    return [
                        'status' => 'error',
                        'message' => '<p><b>'.$this->input->post('category_name').'</b> category already exist.</p>',
                    ];

                } else {

                    $this->db->query("INSERT INTO categories (`category_name`, `created_at`, `updated_at`) VALUES (?,?,?)", [
                        $this->input->post('category_name'),
                        date('Y-m-d, H:i:s'),
                        date('Y-m-d, H:i:s'),
                    ]);

                    return [
                        'status' => 'success',
                        'message' => '<p>Successfully added a new product category</p>'
                    ];

                }

            }

        }

    }

    public function add_new_product() {

        $this->form_validation->set_rules('product_name', 'Product Name', 'required|xss_clean');
        $this->form_validation->set_rules('product_description', 'Product Description', 'required|xss_clean');

        if ($this->form_validation->run() === FALSE) {

            return [
                'status' => 'error',
                'message' => validation_errors(),
            ];

        } else {

            $this->db->query("INSERT INTO products (`product_name`, `product_description`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?)", [
                $this->input->post('product_name'),
                $this->input->post('product_description'),
                date('Y-m-d, H:i:s'),
                date('Y-m-d, H:i:s'),
            ]);

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