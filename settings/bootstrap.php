<?php if (!defined('APPLICATION')) exit();

/**
 * Composer autoloader
 *
 * NOTE: Composer modules are not currently used
 */
require PATH_APPLICATIONS.DS.'api'.DS.'vendor'.DS.'autoload.php';

/**
 * Make sure the http_response_code function is available
 */
if (!function_exists('http_response_code')) {
    function http_response_code($newcode = NULL) {
        static $code = 200;
        if ($newcode !== NULL):
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent())
                $code = $newcode;
        endif;
        return $code;
    }
}