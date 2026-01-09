<?php
/**
|--------------------------------------------------------------------------
| COMMON FILE
|--------------------------------------------------------------------------
|
| Common functions and framework core.
| Loads config, sets error handlers, manages routing.
|
*/

// Define Base Path
define('BASEPATH', str_replace("\\", "/", __DIR__));
define('APPPATH', BASEPATH . 'application/');
define('VIEWPATH', BASEPATH . 'application/views/');
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/**
|--------------------------------------------------------------------------
| APP FOLDER
|--------------------------------------------------------------------------
*/
$application_folder = 'application';

/**
|--------------------------------------------------------------------------
| SYSTEM FOLDER
|--------------------------------------------------------------------------
*/
$system_folder = 'system';

/**
|--------------------------------------------------------------------------
| INDEX FILE
|--------------------------------------------------------------------------
*/
$index_page = 'index.php';

/**
|--------------------------------------------------------------------------
| URL PROTOCOL
|--------------------------------------------------------------------------
*/
define('URL_PROTOCOL', empty($_SERVER['HTTPS']) ? 'http' : 'https');

/**
|--------------------------------------------------------------------------
| BASE URL
|--------------------------------------------------------------------------
*/
$config['base_url'] = 'http://localhost/gudang-arsip/';

/**
|--------------------------------------------------------------------------
| ENCRYPTION KEY
|--------------------------------------------------------------------------
*/
$config['encryption_key'] = bin2hex(openssl_random_pseudo_bytes(16));

/**
|--------------------------------------------------------------------------
| SESSION DRIVER
|--------------------------------------------------------------------------
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = APPPATH . 'cache/sessions/';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/**
|--------------------------------------------------------------------------
| COOKIE RELATED PREFERENCES
|--------------------------------------------------------------------------
*/
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;

/**
|--------------------------------------------------------------------------
| STANDARDIZE NEWLINES
|--------------------------------------------------------------------------
*/
$config['standardize_newlines'] = FALSE;

/**
|--------------------------------------------------------------------------
| GLOBAL XSS FILTERING
|--------------------------------------------------------------------------
*/
$config['global_xss_filtering'] = FALSE;

/**
|--------------------------------------------------------------------------
| CSRF PROTECTION
|--------------------------------------------------------------------------
*/
$config['csrf_protection'] = FALSE;

/**
|--------------------------------------------------------------------------
| LOGGING
|--------------------------------------------------------------------------
*/
$config['log_threshold'] = (ENVIRONMENT !== 'production') ? 1 : 4;
$config['log_path'] = APPPATH . 'logs/';
$config['log_file_extension'] = '';
$config['log_file_date'] = 'Ymd';
$config['log_file_permissions'] = 0644;

/**
|--------------------------------------------------------------------------
| ERROR REPORTING
|--------------------------------------------------------------------------
*/
if (ENVIRONMENT === 'development' OR ENVIRONMENT === 'testing') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

/**
|--------------------------------------------------------------------------
| RUN APP
|--------------------------------------------------------------------------
*/
// Load Common
require_once BASEPATH . 'core/Common.php';

// Load Session Library
if (is_file(APPPATH . 'libraries/Session.php')) {
    require_once APPPATH . 'libraries/Session.php';
}

// Load Input Library
if (is_file(APPPATH . 'core/Input.php')) {
    require_once APPPATH . 'core/Input.php';
}

// Load Output Library
if (is_file(APPPATH . 'core/Output.php')) {
    require_once APPPATH . 'core/Output.php';
}

// Load URI Library
if (is_file(APPPATH . 'core/URI.php')) {
    require_once APPPATH . 'core/URI.php';
}

// Load Config
require_once APPPATH . 'config/config.php';
require_once APPPATH . 'config/database.php';
require_once APPPATH . 'config/routes.php';

// Load Helpers
require_once APPPATH . 'helpers/url_helper.php';
require_once APPPATH . 'helpers/form_helper.php';
require_once APPPATH . 'helpers/security_helper.php';

// Load Security Library
if (is_file(APPPATH . 'libraries/Security.php')) {
    require_once APPPATH . 'libraries/Security.php';
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

/*
|--------------------------------------------------------------------------
| LOAD THE CONTROLLER
|--------------------------------------------------------------------------
*/
// Get route
$controller = isset($_SERVER['CI_URI']) ? parse_url('http://localhost/gudang-arsip/' . $_SERVER['CI_URI'], PHP_URL_PATH) : 'dashboard';
$controller = str_replace(array('/', '.html', '.htm', '.php'), '', $controller);

// Check if controller exists
if (file_exists(APPPATH . 'controllers/' . ucfirst($controller) . '.php')) {
    require_once APPPATH . 'core/CodeIgniter.php';

    $CI = new CodeIgniter();
    $CI->uri->uri_string = $controller;
    $CI->uri->rsegments = explode('/', $controller);

    // Initialize libraries
    $CI->input = & load_class('Input', 'core');
    $CI->output = & load_class('Output', 'core');
    $CI->security = & load_class('Security', 'core');
    $CI->uri = & load_class('URI', 'core');

    // Load helpers
    $CI->load->helper('url');
    $CI->load->helper('form');

    // Load libraries
    $CI->load->library('session');
    $CI->load->library('form_validation');

    // Load models
    $CI->load->model('Account_model');
    $CI->load->model('Archive_model');
    $CI->load->model('Warehouse_model');
    $CI->load->model('Shelf_model');
    $CI->load->model('Movement_model');
    $CI->load->model('Borrowing_model');

    // Call controller
    $CI->$controller();

} else {
    // Load database and display 404
    require_once BASEPATH . 'system/database/DB_driver.php';

    $db = new DB_driver();
    $db->initialize();

    $CI = new stdClass();
    $CI->output = new stdClass();
    $CI->output->set_status_header(404);
    $CI->output->set_output(file_get_contents(APPPATH . 'views/errors/html/error_404.php'));
    $CI->output->_display();
}

/* End of file index.php Location: ./index.php */
