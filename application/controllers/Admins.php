<?php

class Admins extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('User');

    }


    public function logout() {

        $this->session->unset_userdata('user_session');
        return redirect('admin');

    }

    public function login() {

        /**
         * Just a security feature | Session Checking | Checking if current `user_session` if exist, is an admin.
         */
        if ($this->session->userdata('user_session')) {

            if (!empty($this->session->userdata('user_session')['is_admin'])) {
                /**
                 * An admin is currently logged-in, redirect to orders
                 */
                return redirect('dashboard/orders');

            } else {
                /**
                 * A `non-admin` user is trying to access admin, kill the session and redirect
                 */
                $this->session->unset_userdata('user_session');
                return redrect();

            }

        }

        $data['page_title'] = 'Admin Login Page';

        if ($this->input->post()) {
            /**
             * Admin Login POST method is submitted, process the admin-login
             */

            /**
             * Adding parameter `1` on the `User` model process, so it will know that the user to be process should be an admin
             */
            $result = $this->User->login_user(1);

            if($result['status'] === 'error') {

                $this->session->set_flashdata('messages', ['error' => $result['message']]);
                $this->load->view('admin/admin-login', $data);

            } else {

                $this->session->set_userdata('user_session', [
                    'email_address' => $this->input->post('email_address'),
                    'is_admin' => TRUE,
                ]);

                return redirect('dashboard/orders');

            }

        } else {
            /**
             * Admin Login POST method is not yet submitted, Just display the login form
             */
            $this->load->view('admin/admin-login', $data);

        }

    }

    public function orders() {

        if (empty($this->session->userdata('user_session')['is_admin'])) {
            /**
             * A `non-admin` user is trying to access admin, kill the session and redirect
             */
            $this->session->unset_userdata('user_session');
            return redirect();

        }

        $data['page_title'] = 'Dashboard Orders';
        $this->load->view('admin/admin-orders-list', $data);

    }


}