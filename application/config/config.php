<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| SITE NAME
|--------------------------------------------------------------------------
|
| This is the site name. For example, "My CodeIgniter Site"
|
*/
$config['site_name'] = 'Sistem Gudang Arsip';

/*
|--------------------------------------------------------------------------
| SITE URL
|--------------------------------------------------------------------------
|
| Base URL of the site.
|
*/
$config['base_url'] = 'http://localhost:3000';

/*
|--------------------------------------------------------------------------
| INDEX FILE
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've
| renamed it to something else.
|
*/
$config['index_page'] = 'index.php';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| Determine the protocol being used for the request.
|
*/
$config['uri_protocol'] = 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all urls generated
| by CodeIgniter. For more information please see the user guide.
|
*/
$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| DEFAULT CONTROLLER
|--------------------------------------------------------------------------
|
| Name of the controller that should be used when no controller is specified.
|
*/
$config['default_controller'] = 'auth';

/*
|--------------------------------------------------------------------------
| ALLOWED CHARACTERS
|--------------------------------------------------------------------------
|
| List of allowed characters for filenames. This helps prevent XSS attacks.
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| ENABLE QUERY STRINGS
|--------------------------------------------------------------------------
|
| Set to FALSE to disable query strings.
|
*/
$config['enable_query_strings'] = FALSE;

/*
|--------------------------------------------------------------------------
| CONTROLLER TRIGGER
|--------------------------------------------------------------------------
|
| Set to TRUE if you want to enable trigger in your controller.
|
*/
$config['controller_trigger'] = '';

/*
|--------------------------------------------------------------------------
| COMPOSED VIEWS
|--------------------------------------------------------------------------
|
| Set to TRUE if you want to use CodeIgniter's built-in composed view.
|
*/
$config['compose_views'] = FALSE;

/*
|--------------------------------------------------------------------------
| COMPOSED VIEWS CLASS
|--------------------------------------------------------------------------
|
| Name of the class that handles composed views.
|
*/
$config['composed_view_class'] = '';

/*
|--------------------------------------------------------------------------
| ENCRYPTION KEY
|--------------------------------------------------------------------------
|
| Random string used for encrypting sessions and other sensitive data.
|
*/
$config['encryption_key'] = bin2hex(openssl_random_pseudo_bytes(16));

/*
|--------------------------------------------------------------------------
| SESSION DRIVER
|--------------------------------------------------------------------------
|
| 'files' - default, uses native PHP file session
| 'database' - uses database for sessions
| 'redis' - uses Redis for sessions
|
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = APPPATH . 'cache/sessions/';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| COOKIE RELATED PREFERENCES
|--------------------------------------------------------------------------
|
*/
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;

/*
|--------------------------------------------------------------------------
| STANDARDIZE NEWLINES
|--------------------------------------------------------------------------
|
| Set to TRUE if you want to globally use \n instead of \r\n
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| GLOBAL XSS FILTERING
|--------------------------------------------------------------------------
|
| Set to TRUE to enable XSS filtering for all requests.
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| CSRF PROTECTION
|--------------------------------------------------------------------------
|
| Set to TRUE to enable CSRF protection.
|
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| CROSS SITE REQUEST FORGERY PROTECTION
|--------------------------------------------------------------------------
|
| Set to TRUE to enable CSRF protection.
|
*/
$config['csrf_protection_method'] = 'cookie';

/*
|--------------------------------------------------------------------------
| CONTENT SECURITY POLICY
|--------------------------------------------------------------------------
|
| Set to TRUE to enable Content Security Policy.
|
*/
$config['content_security_policy'] = FALSE;

/*
|--------------------------------------------------------------------------
| UPLOAD FILES SETTINGS
|--------------------------------------------------------------------------
|
*/
$config['upload_path'] = APPPATH . 'uploads/';
$config['allowed_types'] = '*';
$config['file_name'] = 'archive';
$config['max_size'] = 5120;
$config['max_width'] = 0;
$config['max_height'] = 0;
$config['encrypt_name'] = FALSE;

/*
|--------------------------------------------------------------------------
| ALLOW GET REQUEST
|--------------------------------------------------------------------------
|
| Set to FALSE to disable GET requests.
|
*/
$config['allow_get_array'] = TRUE;
$config['allow_get_request'] = TRUE;

/*
|--------------------------------------------------------------------------
| LOGGING
|--------------------------------------------------------------------------
|
| Set to TRUE to enable logging.
|
*/
$config['log_threshold'] = (ENVIRONMENT !== 'production') ? 1 : 4;
$config['log_path'] = APPPATH . 'logs/';
$config['log_file_extension'] = '';
$config['log_file_date'] = 'Ymd';
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| CACHE
|--------------------------------------------------------------------------
|
| Set to FALSE to disable caching.
|
*/
$config['cache_path'] = APPPATH . 'cache/';
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| COMPRESSION
|--------------------------------------------------------------------------
|
| Set to TRUE to enable compression.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| PROXY HOSTS
|--------------------------------------------------------------------------
|
| Array of proxy hosts.
|
*/
$config['proxy_hosts'] = array();
