<?php
/**
|--------------------------------------------------------------------------
| URL HELPER
|--------------------------------------------------------------------------
|
| Provides functions for working with URLs and paths.
|
*/

if (!function_exists('site_url')) {
    /**
     * Site URL
     */
    function site_url($uri = '') {
        $CI =& get_instance();
        return $CI->config->item('base_url') . ltrim($uri, '/');
    }
}

if (!function_exists('base_url')) {
    /**
     * Base URL
     */
    function base_url($uri = '') {
        $CI =& get_instance();
        return $CI->config->item('base_url') . ltrim($uri, '/');
    }
}

if (!function_exists('current_url')) {
    /**
     * Current URL
     */
    function current_url() {
        $CI =& get_instance();
        $url = $CI->config->item('base_url');
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        return $url . substr($request_uri, 1);
    }
}

if (!function_exists('redirect')) {
    /**
     * Header Redirect
     */
    function redirect($uri = '', $method = 'location', $http_response_code = 302) {
        $CI =& get_instance();

        if ( ! preg_match('#^https?://#i', $uri)) {
            $uri = site_url($uri);
        }

        switch($method) {
            case 'refresh' :
                header("Refresh:0;url=".$uri);
                break;
            default :
                header("Location: ".$uri, TRUE, $http_response_code);
                break;
        }
        exit;
    }
}

if (!function_exists('prep_url')) {
    /**
     * Prep URL
     */
    function prep_url($str = '') {
        return $str === '//' ? '/' : $str;
    }
}
