<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| CONTROLLER - WAREHOUSES
|--------------------------------------------------------------------------
|
| Controller for Warehouse CRUD operations.
|
*/
class Warehouses extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Warehouse_model');
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
     * Index page - list all warehouses
     */
    public function index() {
        $data['warehouses'] = $this->Warehouse_model->get_all();
        $data['title'] = 'Daftar Gudang';
        $data['page'] = 'warehouses/index';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('warehouses/index', $data);
    }

    /**
     * Create warehouse page
     */
    public function create() {
        $data['title'] = 'Tambah Gudang';
        $data['page'] = 'warehouses/create';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('warehouses/create', $data);
    }

    /**
     * Store new warehouse
     */
    public function store() {
        // Form validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('code', 'Kode Gudang', 'required');
        $this->form_validation->set_rules('name', 'Nama Gudang', 'required');
        $this->form_validation->set_rules('location', 'Lokasi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('warehouses/create');
        }

        $warehouse_data = array(
            'code' => $this->input->post('code'),
            'name' => $this->input->post('name'),
            'location' => $this->input->post('location'),
        );

        if ($this->Warehouse_model->create($warehouse_data)) {
            $this->session->set_flashdata('success', 'Gudang berhasil ditambahkan');
            redirect('warehouses');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambah gudang');
            redirect('warehouses/create');
        }
    }

    /**
     * Edit warehouse page
     */
    public function edit($id) {
        $data['warehouse'] = $this->Warehouse_model->get_by_id($id);
        $data['title'] = 'Edit Gudang';
        $data['page'] = 'warehouses/edit';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('warehouses/edit', $data);
    }

    /**
     * Update warehouse
     */
    public function update() {
        $id = $this->input->post('id');

        if (empty($id)) {
            $this->session->set_flashdata('error', 'ID gudang tidak valid');
            redirect('warehouses');
        }

        $warehouse_data = array(
            'code' => $this->input->post('code'),
            'name' => $this->input->post('name'),
            'location' => $this->input->post('location'),
        );

        if ($this->Warehouse_model->update($id, $warehouse_data)) {
            $this->session->set_flashdata('success', 'Gudang berhasil diperbarui');
            redirect('warehouses');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui gudang');
            redirect('warehouses/edit/' . $id);
        }
    }

    /**
     * Delete warehouse
     */
    public function delete($id) {
        $warehouse = $this->Warehouse_model->get_by_id($id);

        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Gudang tidak ditemukan');
            redirect('warehouses');
        }

        if ($this->Warehouse_model->delete($id)) {
            $this->session->set_flashdata('success', 'Gudang berhasil dihapus');
            redirect('warehouses');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus gudang');
            redirect('warehouses');
        }
    }
}
