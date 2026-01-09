<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| CONTROLLER - AUTH
|--------------------------------------------------------------------------
|
| Controller for authentication (login, logout, check session).
|
*/
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Account_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    /**
     * Login page
     */
    public function login() {
        // Check if already logged in
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    /**
     * Process login
     */
    public function process_login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Validate input
        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username dan password diperlukan');
            redirect('auth/login');
        }

        // Check login
        $account = $this->Account_model->check_password($username, $password);

        if ($account) {
            // Set session data
            $session_data = array(
                'user_id' => $account->id,
                'username' => $account->username,
                'email' => $account->email,
                'role' => $account->role,
                'logged_in' => TRUE
            );
            $this->session->set_userdata($session_data);

            // Update last login
            $this->Account_model->update_last_login($account->id);

            // Redirect based on role
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah');
            redirect('auth/login');
        }
    }

    /**
     * Logout
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    /**
     * Check session (AJAX)
     */
    public function check_session() {
        $logged_in = $this->session->userdata('logged_in');
        echo json_encode(array(
            'logged_in' => $logged_in ? TRUE : FALSE,
            'user' => $logged_in ? array(
                'username' => $this->session->userdata('username'),
                'role' => $this->session->userdata('role'),
                'email' => $this->session->userdata('email')
            ) : NULL
        ));
    }
}
