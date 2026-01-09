<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
|
| Custom routing for CodeIgniter application.
|
*/

$route['default_controller'] = 'auth';
$route['translate_uri_dashes'] = FALSE;

// Module routes
$route['auth'] = 'auth/login';
$route['archives'] = 'archives';
$route['warehouses'] = 'warehouses';
$route['shelves'] = 'shelves';
$route['movements'] = 'movements';
$route['borrowings'] = 'borrowings';
$route['accounts'] = 'accounts';

// Dashboard
$route['dashboard'] = 'dashboard';
