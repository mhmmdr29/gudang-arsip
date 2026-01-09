<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| CONTROLLER - ARCHIVES
|--------------------------------------------------------------------------
|
| Controller for Archive CRUD operations.
|
*/
class Archives extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Archive_model');
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
     * Index page - list all archives
     */
    public function index() {
        $data['archives'] = $this->Archive_model->get_with_details();
        $data['title'] = 'Daftar Arsip';
        $data['page'] = 'archives/index';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('archives/index', $data);
    }

    /**
     * Create archive page
     */
    public function create() {
        $data['warehouses'] = $this->Warehouse_model->get_all();
        $data['title'] = 'Tambah Arsip';
        $data['page'] = 'archives/create';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('archives/create', $data);
    }

    /**
     * Store new archive
     */
    public function store() {
        // Form validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Nama Arsip', 'required');
        $this->form_validation->set_rules('code', 'Kode Arsip', 'required');
        $this->form_validation->set_rules('sub_bag_code', 'Kode Sub Bag', 'required');
        $this->form_validation->set_rules('year_created', 'Tahun Dibuat', 'required|numeric');
        $this->form_validation->set_rules('retention_period', 'Masa Retensi', 'required|numeric');
        $this->form_validation->set_rules('shelf_id', 'Rak', 'required|numeric');
        $this->form_validation->set_rules('warehouse_id', 'Gudang', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('archives/create');
        }

        // Handle file upload
        $file_path = NULL;
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $upload_config = array(
                'upload_path' => APPPATH . '../assets/uploads/archives/',
                'allowed_types' => 'application/pdf',
                'file_name' => time() . '_' . $_FILES['file']['name'],
                'overwrite' => TRUE,
                'max_size' => 5120 * 1024 // 5MB
            );

            $this->load->library('upload', $upload_config);
            if (!$this->upload->do_upload('file')) {
                $this->session->set_flashdata('error', $this->upload->display_errors('file'));
                redirect('archives/create');
            }

            $file_path = 'assets/uploads/archives/' . $this->upload->data('file_name');
        }

        // Prepare archive data
        $archive_data = array(
            'name' => $this->input->post('name'),
            'code' => $this->input->post('code'),
            'sub_bag_code' => $this->input->post('sub_bag_code'),
            'year_created' => $this->input->post('year_created'),
            'retention_period' => $this->input->post('retention_period'),
            'shelf_id' => $this->input->post('shelf_id'),
            'warehouse_id' => $this->input->post('warehouse_id'),
            'file_path' => $file_path
        );

        // Insert archive
        if ($this->Archive_model->create($archive_data)) {
            $this->session->set_flashdata('success', 'Arsip berhasil ditambahkan');
            redirect('archives');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambah arsip');
            redirect('archives/create');
        }
    }

    /**
     * Edit archive page
     */
    public function edit($id) {
        $data['archive'] = $this->Archive_model->get_by_id($id);
        $data['warehouses'] = $this->Warehouse_model->get_all();
        $data['shelves'] = $this->Shelf_model->get_by_warehouse($data['archive']->warehouse_id);
        $data['title'] = 'Edit Arsip';
        $data['page'] = 'archives/edit';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('archives/edit', $data);
    }

    /**
     * Update archive
     */
    public function update() {
        $id = $this->input->post('id');

        if (empty($id)) {
            $this->session->set_flashdata('error', 'ID arsip tidak valid');
            redirect('archives');
        }

        // Handle file upload
        $file_path = $this->input->post('existing_file_path');
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $upload_config = array(
                'upload_path' => APPPATH . '../assets/uploads/archives/',
                'allowed_types' => 'application/pdf',
                'file_name' => time() . '_' . $_FILES['file']['name'],
                'overwrite' => TRUE,
                'max_size' => 5120 * 1024
            );

            $this->load->library('upload', $upload_config);
            if ($this->upload->do_upload('file')) {
                $file_path = 'assets/uploads/archives/' . $this->upload->data('file_name');
            }
        }

        // Prepare archive data
        $archive_data = array(
            'name' => $this->input->post('name'),
            'code' => $this->input->post('code'),
            'sub_bag_code' => $this->input->post('sub_bag_code'),
            'year_created' => $this->input->post('year_created'),
            'retention_period' => $this->input->post('retention_period'),
            'shelf_id' => $this->input->post('shelf_id'),
            'warehouse_id' => $this->input->post('warehouse_id'),
            'file_path' => $file_path
        );

        // Update archive
        if ($this->Archive_model->update($id, $archive_data)) {
            $this->session->set_flashdata('success', 'Arsip berhasil diperbarui');
            redirect('archives');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui arsip');
            redirect('archives/edit/' . $id);
        }
    }

    /**
     * Delete archive
     */
    public function delete($id) {
        $archive = $this->Archive_model->get_by_id($id);

        if (!$archive) {
            $this->session->set_flashdata('error', 'Arsip tidak ditemukan');
            redirect('archives');
        }

        // Delete file if exists
        if (!empty($archive->file_path)) {
            $file_path = APPPATH . '../' . $archive->file_path;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Delete archive from database
        if ($this->Archive_model->delete($id)) {
            $this->session->set_flashdata('success', 'Arsip berhasil dihapus');
            redirect('archives');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus arsip');
            redirect('archives');
        }
    }

    /**
     * Search archives
     */
    public function search() {
        $keyword = $this->input->get('keyword');

        if (empty($keyword)) {
            redirect('archives');
        }

        $data['archives'] = $this->Archive_model->search($keyword);
        $data['keyword'] = $keyword;
        $data['title'] = 'Hasil Pencarian Arsip: ' . $keyword;
        $data['page'] = 'archives/search';

        $this->load->view('templates/dashboard', $data);
        $this->load->view('archives/index', $data);
    }
}
