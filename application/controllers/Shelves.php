<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| CONTROLLER - SHELVES
|--------------------------------------------------------------------------
|
| Controller for Shelf CRUD operations.
|
*/
class Shelves extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Shelf_model');
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
     * Index page - list all shelves
     */
    public function index() {
        $data['shelves'] = $this->Shelf_model->get_with_warehouse();
        $data['title'] = 'Daftar Rak';
        $data['page'] = 'shelves/index';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('shelves/index', $data);
    }

    /**
     * Create shelf page (AJAX)
     */
    public function create($warehouse_id = NULL) {
        if (empty($warehouse_id)) {
            $warehouse_id = $this->input->post('warehouse_id');
        }

        $data['warehouses'] = $this->Warehouse_model->get_all();
        $data['selected_warehouse_id'] = $warehouse_id;
        $data['title'] = 'Tambah Rak';
        $data['page'] = 'shelves/create';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('shelves/create', $data);
    }

    /**
     * Store new shelf (AJAX)
     */
    public function store() {
        // Form validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('code', 'Kode Rak', 'required');
        $this->form_validation->set_rules('warehouse_id', 'Gudang', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array(
                'success' => FALSE,
                'errors' => validation_errors()
            ));
            return;
        }

        $shelf_data = array(
            'code' => $this->input->post('code'),
            'warehouse_id' => $this->input->post('warehouse_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Shelf_model->create($shelf_data)) {
            echo json_encode(array('success' => TRUE, 'message' => 'Rak berhasil ditambahkan'));
        } else {
            echo json_encode(array('success' => FALSE, 'message' => 'Gagal menambah rak'));
        }
    }

    /**
     * Edit shelf page
     */
    public function edit($id) {
        $data['shelf'] = $this->Shelf_model->get_by_id($id);
        $data['warehouses'] = $this->Warehouse_model->get_all();
        $data['title'] = 'Edit Rak';
        $data['page'] = 'shelves/edit';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('shelves/edit', $data);
    }

    /**
     * Update shelf
     */
    public function update() {
        $id = $this->input->post('id');

        if (empty($id)) {
            echo json_encode(array('success' => FALSE, 'message' => 'ID rak tidak valid'));
            return;
        }

        $shelf_data = array(
            'code' => $this->input->post('code'),
            'warehouse_id' => $this->input->post('warehouse_id'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Shelf_model->update($id, $shelf_data)) {
            echo json_encode(array('success' => TRUE, 'message' => 'Rak berhasil diperbarui'));
        } else {
            echo json_encode(array('success' => FALSE, 'message' => 'Gagal memperbarui rak'));
        }
    }

    /**
     * Delete shelf
     */
    public function delete($id) {
        $shelf = $this->Shelf_model->get_by_id($id);

        if (!$shelf) {
            echo json_encode(array('success' => FALSE, 'message' => 'Rak tidak ditemukan'));
            return;
        }

        if ($this->Shelf_model->delete($id)) {
            echo json_encode(array('success' => TRUE, 'message' => 'Rak berhasil dihapus'));
        } else {
            echo json_encode(array('success' => FALSE, 'message' => 'Gagal menghapus rak'));
        }
    }

    /**
     * Get shelves by warehouse (AJAX)
     */
    public function get_by_warehouse() {
        $warehouse_id = $this->input->post('warehouse_id');

        if (empty($warehouse_id)) {
            echo json_encode(array('success' => FALSE, 'message' => 'ID gudang tidak valid'));
            return;
        }

        $shelves = $this->Shelf_model->get_by_warehouse($warehouse_id);

        echo json_encode(array('success' => TRUE, 'data' => $shelves));
    }
}
