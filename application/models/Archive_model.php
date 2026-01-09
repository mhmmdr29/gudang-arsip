<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| MODELS - ARCHIVE MODEL
|--------------------------------------------------------------------------
|
| Model for Archive table operations.
|
*/
class Archive_model extends CI_Model {

    protected $table = 'archives';

    /**
     * Get archive by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table);
    }

    /**
     * Get archives by shelf ID
     */
    public function get_by_shelf($shelf_id) {
        $this->db->where('shelf_id', $shelf_id);
        $this->db->order_by('name', 'ASC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Get archives by warehouse ID
     */
    public function get_by_warehouse($warehouse_id) {
        $this->db->where('warehouse_id', $warehouse_id);
        $this->db->order_by('name', 'ASC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Create new archive
     */
    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update archive
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete archive
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get all archives
     */
    public function get_all() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Search archives
     */
    public function search($keyword) {
        $this->db->like('name', $keyword);
        $this->db->or_like('code', $keyword);
        $this->db->or_like('sub_bag_code', $keyword);
        $this->db->order_by('name', 'ASC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Count archives
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Get archives with shelf and warehouse info
     */
    public function get_with_details() {
        $this->db->select('archives.*, shelves.code as shelf_code, shelves.warehouse_id, warehouses.code as warehouse_code, warehouses.name as warehouse_name');
        $this->db->from($this->table);
        $this->db->join('shelves', 'shelves.id = archives.shelf_id');
        $this->db->join('warehouses', 'warehouses.id = archives.warehouse_id');
        $this->db->order_by('archives.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get archive by code and warehouse ID (unique)
     */
    public function get_by_code_warehouse($code, $warehouse_id) {
        $this->db->where('code', $code);
        $this->db->where('warehouse_id', $warehouse_id);
        return $this->db->get($this->table);
    }
}
