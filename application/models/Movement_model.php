<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| MODELS - MOVEMENT MODEL
|--------------------------------------------------------------------------
|
| Model for Movement table operations.
|
*/
class Movement_model extends CI_Model {

    protected $table = 'movements';

    /**
     * Get movement by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table);
    }

    /**
     * Get movements by archive ID
     */
    public function get_by_archive($archive_id) {
        $this->db->where('archive_id', $archive_id);
        $this->db->order_by('moved_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Create new movement
     */
    public function create($data) {
        $data['moved_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update movement
     */
    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /**
     * Delete movement
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get all movements
     */
    public function get_all() {
        $this->db->order_by('moved_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Count movements
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Get movements with archive details
     */
    public function get_with_archive_details() {
        $this->db->select('movements.*, archives.code as archive_code, archives.name as archive_name');
        $this->db->from($this->table);
        $this->db->join('archives', 'archives.id = movements.archive_id');
        $this->db->order_by('movements.moved_at', 'DESC');
        return $this->db->get()->result();
    }
}
