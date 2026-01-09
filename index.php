<?php
/*
|--------------------------------------------------------------------------
| Entry Point for Application
|--------------------------------------------------------------------------
*/
define('BASEPATH', dirname(__DIR__) . '/system');
define('APPPATH', dirname(__DIR__) . '/application/');

// Check if application exists
if (!is_dir(APPPATH)) {
    header('HTTP/1.1 503 Service Unavailable');
    echo 'Your application folder path does not appear to be set correctly.';
    exit(3);
}

// Load CodeIgniter
require_once BASEPATH . 'core/CodeIgniter.php';

/*
|--------------------------------------------------------------------------
| Run Application
|--------------------------------------------------------------------------
|
| Load framework and run application.
|
*/
if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/routes.php')) {
    require_once BASEPATH . 'core/Common.php';

    $CI = &get_instance();
    $CI->load = &load_class('Loader', 'core');
    $CI->load->helper('url');
    $CI->load->helper('security');

    // Load routes
    require_once APPPATH . 'config/' . ENVIRONMENT . '/routes.php';

    // Get route
    $uri = $CI->uri;

    if (isset($routing['default_controller']) && $routing['default_controller']) {
        $routing['default_controller'] = str_replace('/', '', $routing['default_controller']);
    }

    if (empty($uri->uri_string)) {
        $uri->uri_string = $routing['default_controller'];
    }

    $CI->uri->rsegments = array_filter(explode('/', $uri->uri_string), 'strlen');

    if (isset($routing['translate_uri_dashes']) && $routing['translate_uri_dashes']) {
        $CI->uri->uri_string = str_replace('-', '_', $CI->uri->uri_string);
        $CI->uri->rsegments = array_map(function($val) {
            return str_replace('-', '_', $val);
        }, $CI->uri->rsegments);
    }

    $rsegments = $CI->uri->rsegments;

    if (isset($routing['default_controller']) && $routing['default_controller']) {
        $CI->uri->uri_string = $routing['default_controller'];
        $rsegments = array_filter(explode('/', $CI->uri->uri_string), 'strlen');
    }

    // Load controller
    $CI->load->library('controller');

    if (empty($uri->uri_string)) {
        $CI->uri->uri_string = $routing['default_controller'];
        $rsegments = array_filter(explode('/', $CI->uri->uri_string), 'strlen');
    }

    $rsegments_count = count($rsegments);
    $class = isset($rsegments[0]) ? $rsegments[0] : $routing['default_controller'];

    // Check if class exists
    if (!class_exists(ucfirst($class))) {
        show_404($CI);
        exit(4);
    }

    $method = $CI->uri->segment(1);
    if (empty($method)) {
        $method = 'index';
    }

    if (!method_exists($class, $method)) {
        show_404($CI);
        exit(4);
    }

    // Call controller method
    $CI = new $class();
    $CI->$method();
}

function show_404($CI) {
    header('HTTP/1.1 404 Not Found');
    echo $CI->load->view('errors/html/error_404', array(), TRUE);
}
