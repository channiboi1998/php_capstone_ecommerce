<?php

class User extends CI_Model {

    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');

    }

    /**
     * Private method for validating email address
     */
    private function validate_email($email) {

        return $this->db->query("SELECT * FROM users WHERE email_address = ?", [$email])->row_array();       

    }

    /**
     * Public method that is used for the user logged in feature
     */
    public function login_user($admin = NULL) {

        $this->form_validation->set_rules('email_address', 'Email Address', 'xss_clean|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'xss_clean|required');

        if ($this->form_validation->run() === FALSE) {
            /**
             * Found an error on form validation, return `error` status.
             */
            return [
                'status' => 'error',
                'message' => validation_errors(), 
            ];

        } else {

            $result = $this->validate_email($this->input->post('email_address'));

            if (!$result) {
                /**
                 * Means that the email address is not found on the database
                 */
                return [
                    'status' => 'error',
                    'message' => '<p>Cannot find email address into the database</p>',
                ];
    
            } else {

                if (password_verify($this->input->post('password'), $result['password']) !== TRUE) {
                    /**
                     * Means that the input field `pasword` does not match with the users record on the database
                     */
                    return [
                        'status' => 'error',
                        'message' => '<p>Email Address and Password does not match</p>',
                    ];

                } else {

                    if ($admin === 1) {

                        if ($result['is_admin'] != 1) {

                            return [
                                'status' => 'error',
                                'message' => '<p>Your account is not admin yet, try contacting administrator for access</p>'
                            ];

                        } else {

                            return [
                                'status' => 'success',
                                'message' => '<p>Successfully Logged in as administrator</p>'
                            ];

                        }

                    } else {

                        /**
                         * Successfully logged in
                         */
                        return [
                            'status' => 'success',
                            'message' => '<p>Successfully logged in</p>',
                        ];

                    }

                }

            }

        }

    }

    /**
     * Method for registration of users
     */
    public function register_user() {

        $this->form_validation->set_rules('first_name', 'First Name', 'xss_clean|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'xss_clean|required');
        $this->form_validation->set_rules('email_address', 'Email Address', 'xss_clean|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'xss_clean|required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'xss_clean|required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            /**
             * Found an error on form validation, return `error` status.
             */
            return [
                'status' => 'error',
                'message' => validation_errors(),
            ];

        } else {
            
            $result = $this->validate_email($this->input->post('email_address'));

            if ($result) {
                /**
                 * Email Address already exist
                 */
                return [
                    'status' => 'error',
                    'message' => '<p>Email Address already exists on the database</p>',
                ];

            } else {
                /**
                 * Inser a new user on the database
                 */
                $this->db->query("INSERT INTO users (`email_address`, `password`, `first_name`, `last_name`, `is_admin`, `created_at`, `updated_at`) 
                VALUES (?, ?, ?, ?, ?, ?, ?)", [
                    $this->input->post('email_address'),
                    password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    $this->input->post('first_name'),
                    $this->input->post('last_name'),
                    0,
                    date('Y-m-d, H:i:s'),
                    date('Y-m-d, H:i:s'),
                ]);

                return [
                    'status' => 'success',
                    'message' => '<p>New User Created</p>',
                ];

            }

        }

    }

}