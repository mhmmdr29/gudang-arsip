<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| CONTROLLER - MOVEMENTS
|--------------------------------------------------------------------------
|
| Controller for Movement (Pindah Arsip) operations.
|
*/
class Movements extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Movement_model');
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
     * Index page - list all movements
     */
    public function index() {
        $data['movements'] = $this->Movement_model->get_with_archive_details();
        $data['title'] = 'Riwayat Pemindahan Arsip';
        $data['page'] = 'movements/index';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('movements/index', $data);
    }

    /**
     * Create movement page
     */
    public function create() {
        $data['archives'] = $this->Archive_model->get_all();
        $data['title'] = 'Pindah Arsip';
        $data['page'] = 'movements/create';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('movements/create', $data);
    }

    /**
     * Store new movement
     */
    public function store() {
        $archive_id = $this->input->post('archive_id');
        $previous_shelf_id = $this->input->post('previous_shelf_id');
        $new_shelf_id = $this->input->post('new_shelf_id');
        $moved_by = $this->session->userdata('username');

        if (empty($archive_id) || empty($previous_shelf_id) || empty($new_shelf_id)) {
            $this->session->set_flashdata('error', 'Semua field diperlukan');
            redirect('movements/create');
        }

        // Get archive details
        $archive = $this->Archive_model->get_by_id($archive_id);
        if (!$archive) {
            $this->session->set_flashdata('error', 'Arsip tidak ditemukan');
            redirect('movements/create');
        }

        // Get shelf names
        $this->load->model('Shelf_model');
        $previous_shelf = $this->Shelf_model->get_by_id($previous_shelf_id);
        $new_shelf = $this->Shelf_model->get_by_id($new_shelf_id);

        if (!$previous_shelf || !$new_shelf) {
            $this->session->set_flashdata('error', 'Rak tidak ditemukan');
            redirect('movements/create');
        }

        // Prepare movement data
        $movement_data = array(
            'archive_id' => $archive_id,
            'archive_name' => $archive->name,
            'archive_code' => $archive->code,
            'previous_shelf' => $previous_shelf->code,
            'new_shelf' => $new_shelf->code,
            'moved_by' => $moved_by,
            'moved_at' => date('Y-m-d H:i:s')
        );

        // Insert movement
        if ($this->Movement_model->create($movement_data)) {
            $this->session->set_flashdata('success', 'Arsip berhasil dipindahkan');
            redirect('movements');
        } else {
            $this->session->set_flashdata('error', 'Gagal memindahkan arsip');
            redirect('movements/create');
        }
    }

    /**
     * Edit movement page
     */
    public function edit($id) {
        $data['movement'] = $this->Movement_model->get_by_id($id);
        $data['archives'] = $this->Archive_model->get_all();
        $data['title'] = 'Edit Pemindahan';
        $data['page'] = 'movements/edit';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('movements/edit', $data);
    }

    /**
     * Update movement
     */
    public function update() {
        $id = $this->input->post('id');

        if (empty($id)) {
            $this->session->set_flashdata('error', 'ID movement tidak valid');
            redirect('movements');
        }

        $archive_id = $this->input->post('archive_id');
        $previous_shelf_id = $this->input->post('previous_shelf_id');
        $new_shelf_id = $this->input->post('new_shelf_id');
        $moved_by = $this->session->userdata('username');

        $archive = $this->Archive_model->get_by_id($archive_id);
        if (!$archive) {
            $this->session->set_flashdata('error', 'Arsip tidak ditemukan');
            redirect('movements/edit/' . $id);
        }

        $this->load->model('Shelf_model');
        $previous_shelf = $this->Shelf_model->get_by_id($previous_shelf_id);
        $new_shelf = $this->Shelf_model->get_by_id($new_shelf_id);

        if (!$previous_shelf || !$new_shelf) {
            $this->session->set_flashdata('error', 'Rak tidak ditemukan');
            redirect('movements/edit/' . $id);
        }

        // Prepare movement data
        $movement_data = array(
            'archive_id' => $archive_id,
            'archive_name' => $archive->name,
            'archive_code' => $archive->code,
            'previous_shelf' => $previous_shelf->code,
            'new_shelf' => $new_shelf->code,
            'moved_by' => $moved_by,
            'moved_at' => date('Y-m-d H:i:s')
        );

        if ($this->Movement_model->update($id, $movement_data)) {
            $this->session->set_flashdata('success', 'Riwayat pemindahan berhasil diperbarui');
            redirect('movements');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui riwayat pemindahan');
            redirect('movements/edit/' . $id);
        }
    }

    /**
     * Delete movement
     */
    public function delete($id) {
        $movement = $this->Movement_model->get_by_id($id);

        if (!$movement) {
            $this->session->set_flashdata('error', 'Riwayat pemindahan tidak ditemukan');
            redirect('movements');
        }

        if ($this->Movement_model->delete($id)) {
            $this->session->set_flashdata('success', 'Riwayat pemindahan berhasil dihapus');
            redirect('movements');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus riwayat pemindahan');
            redirect('movements');
        }
    }
}
