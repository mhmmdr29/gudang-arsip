<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| MODELS - ACCOUNT MODEL
|--------------------------------------------------------------------------
|
| Model for Account (User) table operations.
|
*/
class Account_model extends CI_Model {

    protected $table = 'accounts';

    /**
     * Get account by username
     */
    public function get_by_username($username) {
        $this->db->where('username', $username);
        return $this->db->get($this->table);
    }

    /**
     * Get account by ID
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table);
    }

    /**
     * Check password
     */
    public function check_password($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        return $this->db->get($this->table);
    }

    /**
     * Create new account
     */
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update account
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete account
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get all accounts
     */
    public function get_all() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Get accounts by role
     */
    public function get_by_role($role) {
        $this->db->where('role', $role);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Count accounts
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Update last login
     */
    public function update_last_login($id) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array(
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }
}
