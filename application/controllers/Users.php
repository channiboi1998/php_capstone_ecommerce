<?php

class Users extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('User');

    }

    public function logout() {

        $this->session->unset_userdata('user_session');
        return redirect('users/login');

    }

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