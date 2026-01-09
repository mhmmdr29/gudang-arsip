<?php
/**
|--------------------------------------------------------------------------
| BOOTSTRAP FILE
|--------------------------------------------------------------------------
|
| Bootstraps the framework.
| Loads common functions and configuration.
|
*/

// Check if PHP version is compatible
if (version_compare(PHP_VERSION, '7.2', '<')) {
    die('Your PHP version must be 7.2 or higher to run CodeIgniter.');
}

// Load Composer autoloader if exists
if (file_exists(APPPATH . 'vendor/autoload.php')) {
    require_once APPPATH . 'vendor/autoload.php';
}

// Define a custom error handler so we can log PHP errors
set_error_handler('_error_handler');
set_exception_handler('_exception_handler');
register_shutdown_function('_shutdown_handler');

/**
 * Error Handler
 */
function _error_handler($severity, $message, $filepath, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }

    $error = new stdClass();
    $error->severity = $severity;
    $error->message = $message;
    $error->filepath = $filepath;
    $error->line = $line;

    // Log error to file
    log_message('error', json_encode($error));
}

/**
 * Exception Handler
 */
function _exception_handler($exception) {
    log_message('error', $exception->getMessage());
    show_error($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
}

/**
 * Shutdown Handler
 */
function _shutdown_handler() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
        log_message('error', $error['message']);
        show_error('Fatal Error', $error['message'], $error['file'], $error['line']);
    }
}

/**
 * Show Error Page
 */
function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
    $status_code = abs($status_code);
    if ($status_code < 100) {
        $status_code = 500;
    } elseif ($status_code > 599) {
        $status_code = 500;
    }

    set_status_header($status_code);
    $message = htmlspecialchars($message, ENT_COMPAT, 'UTF-8');

    if (file_exists(APPPATH . 'views/errors/html/' . $template . '.php')) {
        echo view(APPPATH . 'views/errors/html/' . $template . '.php', array(
            'heading' => $heading,
            'message' => $message
        ));
    } else {
        echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $heading . '</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 50px 20px; }
        .error-container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; margin-bottom: 20px; }
        .error-message { color: #6c757d; line-height: 1.6; }
        .back-link { display: block; margin-top: 20px; color: #0d6efd; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>' . $heading . '</h1>
        <div class="error-message">' . $message . '</div>
        <a href="' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/') . '" class="back-link">← Kembali ke Halaman Utama</a>
    </div>
</body>
</html>';
    }

    exit(1);
}

/**
 * Log Message
 */
function log_message($level, $message) {
    $levels = array('error' => 1, 'debug' => 2, 'info' => 3, 'all' => 4);

    if (is_numeric($level)) {
        $level = $levels[$level];
    }

    $log_path = APPPATH . 'logs/log-' . date('Y-m-d') . '.php';

    // Create log file if not exists
    if (!file_exists($log_path)) {
        file_put_contents($log_path, '<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\'); ?>' . PHP_EOL);
    }

    $timestamp = date('Y-m-d H:i:s');
    $log_message = '[' . $timestamp . '] ' . strtoupper($level) . ' - ' . $message . PHP_EOL;

    file_put_contents($log_path, $log_message, FILE_APPEND);
}

/**
 * Load Class
 */
function &load_class($class, $directory = 'libraries', $prefix = 'CI_') {
    static $_classes = array();

    $name = strtolower($class);

    if (isset($_classes[$name])) {
        return $_classes[$name];
    }

    if (file_exists(APPPATH . $directory . '/' . $class . '.php')) {
        require_once(APPPATH . $directory . '/' . $class . '.php');

        $is_subclass = FALSE;

        foreach (get_declared_classes() as $c) {
            if (is_subclass_of($c, $prefix . $name)) {
                $is_subclass = TRUE;
                break;
            }
        }

        $CI =& get_instance();

        if ($is_subclass === FALSE) {
            $_classes[$name] = new $class();
        } else {
            $_classes[$name] = new $prefix . $name();
        }

        if ($CI) {
            $CI->$name = &$_classes[$name];
        }

        return $_classes[$name];
    } else {
        die('Unable to locate the specified class: ' . $class . '.php');
    }

    return FALSE;
}

/**
 * Get Instance
 */
function &get_instance() {
    return CI_Controller::get_instance();
}

/**
 * Is CLI
 */
function is_cli() {
    return (php_sapi_name() === 'cli' or defined('STDIN'));
}

/**
 * Remove Invisible Characters
 */
function remove_invisible_characters($str) {
    return preg_replace('/[\x00-\x1F\x7F\x9F]/u', '', $str);
}
