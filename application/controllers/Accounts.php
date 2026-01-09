<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| CONTROLLER - ACCOUNTS (USER MANAGEMENT)
|--------------------------------------------------------------------------
|
| Controller for Account/User management (CRUD).
| Role-based access: ADMIN and KASSUBAG_KUL can access.
|
*/
class Accounts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Account_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->check_login();
        $this->check_role_access();
    }

    /**
     * Check login
     */
    private function check_login() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Check role access
     * Only ADMIN and KASSUBAG_KUL can access accounts management
     */
    private function check_role_access() {
        $role = $this->session->userdata('role');

        if ($role !== 'ADMIN' && $role !== 'KASSUBAG_KUL') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke manajemen akun');
            redirect('dashboard');
        }
    }

    /**
     * Index page - list all accounts
     */
    public function index() {
        $data['accounts'] = $this->Account_model->get_all();
        $data['title'] = 'Manajemen Akun';
        $data['page'] = 'accounts/index';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('accounts/index', $data);
    }

    /**
     * Create account page
     */
    public function create() {
        $data['title'] = 'Tambah Akun';
        $data['page'] = 'accounts/create';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('accounts/create', $data);
    }

    /**
     * Store new account
     */
    public function store() {
        // Form validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_unique_username');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_unique_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[ADMIN,KASSUBAG_KUL,STAFF]');

        // Add custom validation callbacks
        $this->form_validation->set_message('required', '%s tidak boleh kosong');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('accounts/create');
        }

        // Prepare account data
        $account_data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'role' => $this->input->post('role'),
        );

        // Insert account
        if ($this->Account_model->create($account_data)) {
            $this->session->set_flashdata('success', 'Akun berhasil ditambahkan');
            redirect('accounts');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambah akun');
            redirect('accounts/create');
        }
    }

    /**
     * Edit account page
     */
    public function edit($id) {
        $data['account'] = $this->Account_model->get_by_id($id);
        $data['title'] = 'Edit Akun';
        $data['page'] = 'accounts/edit';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('accounts/edit', $data);
    }

    /**
     * Update account
     */
    public function update() {
        $id = $this->input->post('id');

        if (empty($id)) {
            $this->session->set_flashdata('error', 'ID akun tidak valid');
            redirect('accounts');
        }

        // Form validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_unique_username_edit[' . $id . ']');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_unique_email_edit[' . $id . ']');
        $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[ADMIN,KASSUBAG_KUL,STAFF]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('accounts/edit/' . $id);
        }

        // Prepare account data
        $account_data = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
        );

        // Add password if provided
        $password = $this->input->post('password');
        if (!empty($password)) {
            $account_data['password'] = $password;
        }

        // Update account
        if ($this->Account_model->update($id, $account_data)) {
            $this->session->set_flashdata('success', 'Akun berhasil diperbarui');
            redirect('accounts');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui akun');
            redirect('accounts/edit/' . $id);
        }
    }

    /**
     * Delete account
     */
    public function delete($id) {
        $account = $this->Account_model->get_by_id($id);

        if (!$account) {
            $this->session->set_flashdata('error', 'Akun tidak ditemukan');
            redirect('accounts');
        }

        // Prevent deleting currently logged in account
        $current_user_id = $this->session->userdata('user_id');
        if ($id == $current_user_id) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun sendiri saat ini sedang login');
            redirect('accounts');
        }

        if ($this->Account_model->delete($id)) {
            $this->session->set_flashdata('success', 'Akun berhasil dihapus');
            redirect('accounts');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus akun');
            redirect('accounts');
        }
    }

    /**
     * Custom validation: Unique username
     */
    public function _callback_unique_username($username) {
        $account = $this->Account_model->get_by_username($username);
        if ($account) {
            $this->form_validation->set_message('_callback_unique_username', 'Username sudah terpakai');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Custom validation: Unique username on edit
     */
    public function _callback_unique_username_edit($username, $current_id) {
        $account = $this->Account_model->get_by_username($username);
        if ($account && $account->id != $current_id) {
            $this->form_validation->set_message('_callback_unique_username_edit[' . $current_id . ']', 'Username sudah terpakai');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Custom validation: Unique email
     */
    public function _callback_unique_email($email) {
        $account = $this->Account_model->get_by_id($email);
        $this->load->helper('email');
        if ($this->email->check_email($email)) {
            $this->form_validation->set_message('_callback_unique_email', 'Email sudah terpakai');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Custom validation: Unique email on edit
     */
    public function _callback_unique_email_edit($email, $current_id) {
        $this->load->helper('email');
        $account = $this->Account_model->get_by_id($email);
        if ($account && $account->id != $current_id) {
            $this->form_validation->set_message('_callback_unique_email_edit[' . $current_id . ']', 'Email sudah terpakai');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Reset password
     */
    public function reset_password($id) {
        $account = $this->Account_model->get_by_id($id);

        if (!$account) {
            $this->session->set_flashdata('error', 'Akun tidak ditemukan');
            redirect('accounts');
        }

        // Generate new password (simple: password123)
        $new_password = 'password123';

        if ($this->Account_model->update($id, array('password' => $new_password))) {
            $this->session->set_flashdata('success', 'Password berhasil direset ke: ' . $new_password);
            redirect('accounts');
        } else {
            $this->session->set_flashdata('error', 'Gagal mereset password');
            redirect('accounts');
        }
    }
}
