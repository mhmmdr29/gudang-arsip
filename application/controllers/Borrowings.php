<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| CONTROLLER - BORROWINGS
|--------------------------------------------------------------------------
|
| Controller for Borrow (Pinjam Arsip) operations.
|
*/
class Borrowings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Borrowing_model');
        $this->load->model('Archive_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->check_login();
    }

    /**
     * Check login and role access
     */
    private function check_login() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Index page - list all borrowings
     */
    public function index() {
        $data['borrowings'] = $this->Borrowing_model->get_with_archive_details();
        $data['title'] = 'Riwayat Peminjaman Arsip';
        $data['page'] = 'borrowings/index';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('borrowings/index', $data);
    }

    /**
     * Create borrowing page
     */
    public function create() {
        $data['archives'] = $this->Archive_model->get_all();
        $data['title'] = 'Pinjam Arsip';
        $data['page'] = 'borrowings/create';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('borrowings/create', $data);
    }

    /**
     * Store new borrowing
     */
    public function store() {
        $archive_id = $this->input->post('archive_id');
        $borrower_name = $this->input->post('borrower_name');
        $moved_by = $this->session->userdata('username');

        if (empty($archive_id) || empty($borrower_name)) {
            $this->session->set_flashdata('error', 'Semua field diperlukan');
            redirect('borrowings/create');
        }

        $archive = $this->Archive_model->get_by_id($archive_id);
        if (!$archive) {
            $this->session->set_flashdata('error', 'Arsip tidak ditemukan');
            redirect('borrowings/create');
        }

        $borrowing_data = array(
            'archive_id' => $archive_id,
            'archive_name' => $archive->name,
            'archive_code' => $archive->code,
            'borrower_name' => $borrower_name,
            'borrowed_at' => date('Y-m-d H:i:s')
        );

        if ($this->Borrowing_model->create($borrowing_data)) {
            $this->session->set_flashdata('success', 'Arsip berhasil dipinjamkan');
            redirect('borrowings');
        } else {
            $this->session->set_flashdata('error', 'Gagal meminjam arsip');
            redirect('borrowings/create');
        }
    }

    /**
     * Return archive
     */
    public function return_archive($id) {
        $this->check_login();

        $this->load->helper('url');

        if ($this->Borrowing_model->return_archive($id)) {
            $this->session->set_flashdata('success', 'Arsip berhasil dikembalikan');
            redirect('borrowings');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengembalikan arsip');
            redirect('borrowings');
        }
    }

    /**
     * Delete borrowing
     */
    public function delete($id) {
        $borrowing = $this->Borrowing_model->get_by_id($id);

        if (!$borrowing) {
            $this->session->set_flashdata('error', 'Riwayat peminjaman tidak ditemukan');
            redirect('borrowings');
        }

        if ($this->Borrowing_model->delete($id)) {
            $this->session->set_flashdata('success', 'Riwayat peminjaman berhasil dihapus');
            redirect('borrowings');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus riwayat peminjaman');
            redirect('borrowings');
        }
    }
}
