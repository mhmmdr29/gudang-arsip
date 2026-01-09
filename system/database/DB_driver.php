<?php
/**
|--------------------------------------------------------------------------
| DATABASE DRIVER
|--------------------------------------------------------------------------
|
| Database driver for CodeIgniter.
| Minimal MySQL driver implementation.
|
*/

class DB_driver {

    public $hostname;
    public $username;
    public $password;
    public $database;
    public $dbdriver;
    public $dbprefix;
    public $pconnect = FALSE;
    public $db_debug = FALSE;
    public $cache_on = FALSE;
    public $cachedir = '';
    public $char_set = 'utf8';
    public $dbcollat = 'utf8_general_ci';
    public $autoinit = TRUE;
    public $stricton = FALSE;

    protected $connection;

    public function __construct($params) {
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    /**
     * Connect to database
     */
    public function initialize() {
        // Create database connection
        $this->connection = new mysqli(
            $this->hostname,
            $this->username,
            $this->password,
            $this->database
        );

        if ($this->connection->connect_error) {
            if ($this->db_debug) {
                echo "Database connection failed: " . $this->connection->connect_error;
            }
            return FALSE;
        }

        // Set charset
        if ($this->char_set) {
            $this->connection->set_charset($this->char_set);
            if ($this->dbcollat) {
                $this->connection->collate($this->dbcollat);
            }
        }

        return TRUE;
    }

    /**
     * Simple Query
     */
    public function query($sql) {
        if (!$this->connection) {
            return FALSE;
        }

        $result = $this->connection->query($sql);

        if ($result === FALSE) {
            if ($this->db_debug) {
                echo "Query failed: " . $sql . " - " . $this->connection->error;
            }
            return FALSE;
        }

        return $result;
    }

    /**
     * Escape string
     */
    public function escape($str) {
        if (!$this->connection) {
            return $str;
        }

        return $this->connection->real_escape_string($str);
    }

    /**
     * Close connection
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * Get connection ID
     */
    public function connection_id() {
        if ($this->connection) {
            return $this->connection->thread_id;
        }
        return FALSE;
    }
}
