<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| MODELS - WAREHOUSE MODEL
|--------------------------------------------------------------------------
|
| Model for Warehouse table operations.
|
*/
class Warehouse_model extends CI_Model {

    protected $table = 'warehouses';

    /**
     * Get warehouse by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table);
    }

    /**
     * Get warehouse by code
     */
    public function get_by_code($code) {
        $this->db->where('code', $code);
        return $this->db->get($this->table);
    }

    /**
     * Create new warehouse
     */
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update warehouse
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete warehouse
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get all warehouses
     */
    public function get_all() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Count warehouses
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }
}
