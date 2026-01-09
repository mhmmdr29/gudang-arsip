<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| MODELS - BORROWING MODEL
|--------------------------------------------------------------------------
|
| Model for Borrowing table operations.
|
*/
class Borrowing_model extends CI_Model {

    protected $table = 'borrowings';

    /**
     * Get borrowing by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table);
    }

    /**
     * Get borrowings by archive ID
     */
    public function get_by_archive($archive_id) {
        $this->db->where('archive_id', $archive_id);
        $this->db->order_by('borrowed_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Get all borrowings
     */
    public function get_all() {
        $this->db->order_by('borrowed_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Get active borrowings (not returned)
     */
    public function get_active() {
        $this->db->where('returned_at', NULL);
        $this->db->order_by('borrowed_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Create new borrowing
     */
    public function create($data) {
        $data['borrowed_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Return archive
     */
    public function return_archive($id) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array(
            'returned_at' => date('Y-m-d H:i:s')
        ));
    }

    /**
     * Count borrowings
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Get borrowings with archive details
     */
    public function get_with_archive_details() {
        $this->db->select('borrowings.*, archives.code as archive_code, archives.name as archive_name');
        $this->db->from($this->table);
        $this->db->join('archives', 'archives.id = borrowings.archive_id');
        $this->db->order_by('borrowings.borrowed_at', 'DESC');
        return $this->db->get()->result();
    }
}
