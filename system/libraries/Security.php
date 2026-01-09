<?php
/**
|--------------------------------------------------------------------------
| SECURITY LIBRARY
|--------------------------------------------------------------------------
|
| Security library for CodeIgniter.
| Handles XSS protection, CSRF protection, and input sanitization.
|
*/

class CI_Security {

    public $xss_hash;
    public $csrf_protection = FALSE;
    public $csrf_token_name = 'ci_csrf_token';
    public $csrf_cookie_name = 'ci_csrf_token';
    public $csrf_expire = 7200;

    protected $_csrf_cookie;
    protected $_csrf_hash;

    public function __construct() {
        $this->xss_hash = bin2hex(openssl_random_pseudo_bytes(32));
        $this->_csrf_set_cookie();
        $this->_csrf_set_hash();
    }

    /**
     * CSRF Set Cookie
     */
    protected function _csrf_set_cookie() {
        $expire = time() + $this->csrf_expire;
        $this->_csrf_cookie = $this->xss_hash;

        setcookie(
            $this->csrf_cookie_name,
            $this->_csrf_cookie,
            $expire,
            '/',
            '',
            FALSE,
            TRUE
        );
    }

    /**
     * CSRF Set Hash
     */
    protected function _csrf_set_hash() {
        $this->_csrf_hash = $this->xss_hash;
    }

    /**
     * CSRF Verify
     */
    public function csrf_verify() {
        if (isset($_POST[$this->csrf_token_name]) && isset($_COOKIE[$this->csrf_cookie_name])) {
            $token = $_POST[$this->csrf_token_name];
            $cookie_hash = $_COOKIE[$this->csrf_cookie_name];

            if ($token === $this->xss_hash) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * CSRF Get Token
     */
    public function get_csrf_token() {
        return $this->_csrf_cookie;
    }

    /**
     * XSS Clean
     */
    public function xss_clean($str, $is_image = FALSE) {
        // Basic XSS protection
        $str = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $str);

        // Remove any attribute starting with "on" or xmlns
        $str = preg_replace('#(<[^>]+[\x00-\x20\"\'/])(on|xmlns)[^>]*>#i', '$1>', $str);

        // Remove javascript: and vbscript: from inputs
        $str = preg_replace('#(<[^>]+)(?:javascript|vbscript|on\w+|on\w+|on\w+\s*>|on\w+\s*[\x22\x27])#i', '$1>', $str);

        return $str;
    }

    /**
     * Sanitize Filename
     */
    public function sanitize_filename($filename) {
        // Remove any directory traversal characters
        $filename = str_replace(array('../', './', '/', '\\'), '', $filename);

        // Remove any potentially malicious characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

        return $filename;
    }

    /**
     * Get CSRF Token Name
     */
    public function get_csrf_token_name() {
        return $this->csrf_token_name;
    }

    /**
     * Get CSRF Cookie Name
     */
    public function get_csrf_cookie_name() {
        return $this->csrf_cookie_name;
    }

    /**
     * Regenerate CSRF Token
     */
    public function csrf_regenerate() {
        $this->xss_hash = bin2hex(openssl_random_pseudo_bytes(32));
        $this->_csrf_set_cookie();
        $this->_csrf_set_hash();
    }
}
