<?php
/**
|--------------------------------------------------------------------------
| SESSION LIBRARY
|--------------------------------------------------------------------------
|
| Session management library for CodeIgniter.
| Uses native PHP sessions.
|
*/

class CI_Session {

    public $sess_expiration = 7200;
    public $sess_encrypt_cookie = FALSE;
    public $sess_use_database = FALSE;
    public $sess_table_name = '';
    public $sess_expire_on_close = FALSE;
    public $sess_match_ip = FALSE;
    public $sess_match_useragent = FALSE;
    public $sess_time_to_update = 300;
    public $sess_regenerate_destroy = FALSE;
    public $cookie_name = 'ci_session';
    public $cookie_path = '/';
    public $cookie_domain = '';
    public $cookie_secure = FALSE;
    public $cookie_httponly = FALSE;
    public $sess_data_driver = 'files';
    public $sess_save_path = APPPATH . 'cache/sessions/';

    protected $_sess_cookie_name;
    protected $_sess_expiration = '';
    protected $_sess_cookie_name = '';
    protected $_userdata = array();
    protected $_flashdata = array();
    protected $_flashdata_key = 'flashnew:old';

    public function __construct($params = array()) {
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }

        $this->_sess_cookie_name = $this->cookie_name;
        $this->_sess_expiration = $this->sess_expiration;

        // Clean expired sessions
        $this->_sess_gc();

        // Initialize session
        $this->_sess_init();
    }

    /**
     * Initialize session
     */
    protected function _sess_init() {
        // Start native session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set session data
     */
    public function set_userdata($newdata = array()) {
        if (is_array($newdata)) {
            foreach ($newdata as $key => $val) {
                $this->_userdata[$key] = $val;
            }
        }
    }

    /**
     * Get session data
     */
    public function userdata($item = NULL) {
        if ($item === NULL) {
            return $this->_userdata;
        }

        return isset($this->_userdata[$item]) ? $this->_userdata[$item] : NULL;
    }

    /**
     * Check if session exists
     */
    public function has_userdata($item) {
        return isset($this->_userdata[$item]);
    }

    /**
     * Unset session data
     */
    public function unset_userdata($newdata = array()) {
        if (is_array($newdata)) {
            foreach ($newdata as $key) {
                unset($this->_userdata[$key]);
                if (isset($_SESSION[$this->_sess_cookie_name][$key])) {
                    unset($_SESSION[$this->_sess_cookie_name][$key]);
                }
            }
        }
    }

    /**
     * Destroy session
     */
    public function sess_destroy() {
        $this->_userdata = array();
        $this->_flashdata = array();

        $_SESSION = array();

        // Delete session cookie
        if (ini_get('session.use_cookies')) {
            if (isset($_COOKIE[$this->_sess_cookie_name])) {
                setcookie($this->_sess_cookie_name, '', time() - 42000, $this->cookie_path, $this->cookie_domain, $this->cookie_secure, $this->cookie_httponly);
            }
        }
    }

    /**
     * Set flash data
     */
    public function set_flashdata($newdata = array(), $newval = '') {
        if (is_array($newdata)) {
            foreach ($newdata as $key => $val) {
                $flashdata_key = $this->_flashdata_key . ':new:' . $key;
                $this->_flashdata[$flashdata_key] = $val;
            }
        }

        if ($newval !== '') {
            $this->set_userdata(array($this->_flashdata_key . ':old' => $newval));
        }
    }

    /**
     * Keep flash data
     */
    public function keep_flashdata($newdata = array()) {
        if (is_array($newdata)) {
            foreach ($newdata as $key => $val) {
                $flashdata_key = $this->_flashdata_key . ':new:' . $key;
                $this->_flashdata[$flashdata_key] = $val;
            }
        }
    }

    /**
     * Get flash data
     */
    public function flashdata($key = NULL) {
        if ($key === NULL) {
            $flashdata = array();

            foreach (array_keys($this->_flashdata) as $key) {
                if (strpos($key, ':old:')) {
                    $flashdata[str_replace(':old:', '', $key)] = $this->_flashdata[$key];
                }
            }

            return $flashdata;
        }

        $flashdata_key = $this->_flashdata_key . ':new:' . $key;

        if (isset($this->_flashdata[$flashdata_key])) {
            return $this->_flashdata[$flashdata_key];
        }

        return FALSE;
    }

    /**
     * Get old flash data
     */
    public function old_flashdata($key) {
        $flashdata_key = $this->_flashdata_key . ':old:' . $key;

        if (isset($this->_flashdata[$flashdata_key])) {
            return $this->_flashdata[$flashdata_key];
        }

        return FALSE;
    }

    /**
     * Session garbage collection
     */
    protected function _sess_gc() {
        // Simple garbage collection - delete sessions older than expiration
        if ($this->sess_expiration > 0) {
            $files = glob($this->sess_save_path . 'sess_*');

            if ($files !== FALSE && is_array($files)) {
                foreach ($files as $file) {
                    if (filemtime($file) < (time() - $this->sess_expiration)) {
                        @unlink($file);
                    }
                }
            }
        }
    }

    /**
     * Get session ID
     */
    public function sess_id() {
        return session_id();
    }

    /**
     * Regenerate session ID
     */
    public function sess_regenerate($destroy = FALSE) {
        session_regenerate_id($destroy);
    }

    /**
     * Get all session data
     */
    public function all_userdata() {
        return $this->_userdata;
    }
}
