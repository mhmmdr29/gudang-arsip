<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
|
| Database connection settings for CodeIgniter.
| Compatible with phpMyAdmin in XAMPP.
|
*/

$db['default'] = array(
  'dsn'   => '',
  'hostname' => 'localhost',
  'username' => 'root',
  'password' => '',
  'database' => 'gudang_arsip',
  'dbdriver' => 'mysqli',
  'dbprefix' => '',
  'pconnect' => FALSE,
  'db_debug' => (ENVIRONMENT !== 'production'),
  'cache_on' => FALSE,
  'cachedir' => '',
  'char_set' => 'utf8',
  'dbcollat' => 'utf8_general_ci',
  'swap_pre' => '',
  'encrypt' => FALSE,
  'compress' => FALSE,
  'stricton' => FALSE,
  'failover' => array(),
  'save_queries' => FALSE,
);

/* End of file database.php location: ./application/config/database.php */
