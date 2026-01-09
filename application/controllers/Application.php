<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| APPLICATION FRONT CONTROLLER
|--------------------------------------------------------------------------
|
| Front controller for loading dashboard and routing.
|
*/
class Application extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Index page - redirect to dashboard
     */
    public function index() {
        redirect('dashboard');
    }
}
