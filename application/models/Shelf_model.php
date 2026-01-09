<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| MODELS - SHELF MODEL
|--------------------------------------------------------------------------
|
| Model for Shelf table operations.
|
*/
class Shelf_model extends CI_Model {

    protected $table = 'shelves';

    /**
     * Get shelf by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table);
    }

    /**
     * Get shelves by warehouse ID
     */
    public function get_by_warehouse($warehouse_id) {
        $this->db->where('warehouse_id', $warehouse_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Get shelf by code and warehouse ID
     */
    public function get_by_code_warehouse($code, $warehouse_id) {
        $this->db->where('code', $code);
        $this->db->where('warehouse_id', $warehouse_id);
        return $this->db->get($this->table);
    }

    /**
     * Create new shelf
     */
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update shelf
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete shelf
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get all shelves
     */
    public function get_all() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Count shelves
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Get shelves with warehouse name
     */
    public function get_with_warehouse() {
        $this->db->select('shelves.*, warehouses.name as warehouse_name, warehouses.location as warehouse_location');
        $this->db->from($this->table);
        $this->db->join('warehouses', 'warehouses.id = shelves.warehouse_id');
        $this->db->order_by('shelves.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
