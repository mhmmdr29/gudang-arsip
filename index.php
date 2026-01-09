<?php
/**
|--------------------------------------------------------------------------
|  APPLICATION DIRECTORY NAME
|--------------------------------------------------------------------------
|
| If you use this application as a subfolder of the main website,
| change the value here: /
|
| http://example.com/ --> /
| http://example.com/codeigniter/ --> /codeigniter/
|
*/
$application_folder = 'application';

/**
|--------------------------------------------------------------------------
|  SYSTEM DIRECTORY NAME
|--------------------------------------------------------------------------
|
| This variable must contain the name of your system folder.
| The system folder can also be named something other than application or system
| if you want more security you should rename it.
|
*/
$system_folder = 'system';

/**
|--------------------------------------------------------------------------
|  INDEX FILE
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. In that case, you should specify the name here.
|
*/
$index_page = 'index.php';

/**
|--------------------------------------------------------------------------
|  URL PROTOCOL
|--------------------------------------------------------------------------
|
| Set to FORCE_HTTPS or FORCE_HTTP based on your needs.
|
*/
define('URL_PROTOCOL', empty($_SERVER['HTTPS']) ? 'http' : 'https');

/**
|--------------------------------------------------------------------------
|  BASE URL
|--------------------------------------------------------------------------
|
| Base URL of your site.
|
*/
// Change this to your base URL
$base_url = 'http://localhost:3000';

/**
|--------------------------------------------------------------------------
|  AUTOLOAD CONTROLLER
|--------------------------------------------------------------------------
|
| Set to TRUE if you want to use auto-loading for controllers.
|
*/
$autoload['packages'] = array();

/**
|--------------------------------------------------------------------------
|  AUTOLOAD HELPER
|--------------------------------------------------------------------------
|
| Set to TRUE if you want to use auto-loading for helpers.
|
*/
$autoload['helper'] = array('url', 'form');

/**
|--------------------------------------------------------------------------
|  AUTOLOAD LIBRARIES
|--------------------------------------------------------------------------
|
| Set to TRUE if you want to use auto-loading for libraries.
|
*/
$autoload['libraries'] = array('session', 'database', 'form_validation');

/**
|--------------------------------------------------------------------------
|  ERROR LOGGING
|--------------------------------------------------------------------------
|
| Set to TRUE if you want to see errors.
|
*/
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/**
|--------------------------------------------------------------------------
|  DATABASE CONFIG
|--------------------------------------------------------------------------
|
| Database connection settings are stored in application/config/database.php
|
*/

/**
|--------------------------------------------------------------------------
|  COMPOSED VIEWS
|--------------------------------------------------------------------------
|
| Set to TRUE if you want to use CodeIgniter's composed view.
|
*/
$autoload['view'] = array('templates/dashboard', 'templates/');

/*
|--------------------------------------------------------------------------
|  ROUTES
|--------------------------------------------------------------------------
|
| You can override the default routing here.
|
*/
$route['default_controller'] = 'application';

/*
|--------------------------------------------------------------------------
|  CUSTOM CONFIG VALUES
|--------------------------------------------------------------------------
|
| Add your custom config values here or override existing values.
|
*/

require_once BASEPATH . 'core/CodeIgniter.php';

require_once BASEPATH . 'core/Common.php';

/*
|--------------------------------------------------------------------------
|  RUN APP
|--------------------------------------------------------------------------
|
| Load the CI framework and run the app.
|
*/
if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/config.php')) {
    require_once APPPATH . 'config/' . ENVIRONMENT . '/config.php';
}

require_once APPPATH . 'core/Common.php';

if (file_exists(APPPATH . 'controllers/' . $routing['default_controller'] . '.php')) {
    require_once APPPATH . 'controllers/' . $routing['default_controller'] . '.php';
}

$CI = & get_instance();
$CI->uri = & load_class('URI', 'core');
$CI->output = & load_class('Output', 'core');
$CI->security = & load_class('Security', 'core');
$CI->input = & load_class('Input', 'core');

/*
|--------------------------------------------------------------------------
|  LOAD THE CONTROLLER
|--------------------------------------------------------------------------
*/
include APPPATH . 'controllers/' . $routing['default_controller'] . '.php';

/*
|--------------------------------------------------------------------------
|  END OF FILE
|--------------------------------------------------------------------------
*/
