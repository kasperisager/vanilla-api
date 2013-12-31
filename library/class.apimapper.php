<?php if (!defined('APPLICATION')) exit;

/**
 * Mapper class for defining APIs
 *
 * By extending this class, API classes can define their own GET, POST, PUT and
 * DELETE operations. If a given method is not extended by the API class, a 501
 * Method Not Implemented error will simply be thrown.
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 * @abstract
 */
abstract class APIMapper
{
    /**
     * Controller to call when requesting the API (Required).
     *
     * @since  0.1.0
     * @access public
     * @var    string|null
     * @static
     */
    public static $Controller;

    /**
     * Method to call on the controller specified earlier (Optional).
     *
     * @since  0.1.0
     * @access public
     * @var    string
     * @static
     */
    public static $Method = 'Index';

    /**
     * Application in which the controller can be found (Optional).
     *
     * Useful for when dealing with non-uniquely named controller such as the
     * "Settings" controller that exists in both the "Vanilla" and "Dashboard"
     * applications.
     *
     * In case no application is explicitly specified, Garden will simply look
     * for whichever application that contains the specified controller.
     *
     * @since  0.1.0
     * @access public
     * @var    null|string
     * @static
     */
    public static $Application;

    /**
     * Array of named arguments to pass along to the controller method (Optional).
     *
     * @since  0.1.0
     * @access public
     * @var    array
     * @static
     */
    public static $Arguments = array();

    /**
     * Whether or not to force user authentication.
     *
     * @since  0.1.0
     * @access public
     * @var    bool
     * @static
     */
    public static $Authenticate = FALSE;

    /**
     * API class GET operation
     *
     * This method will be run when a GET request is sent to a given API class.
     *
     * @since  0.1.0
     * @access public
     * @throws Exception
     * @static
     */
    public static function Get()
    {
        throw new Exception("Method Not Implemented", 501);
    }

    /**
     * API class POST operation
     *
     * This method will be run when a POST request is sent to a given API class.
     *
     * @since  0.1.0
     * @access public
     * @throws Exception
     * @static
     */
    public static function Post()
    {
        throw new Exception("Method Not Implemented", 501);
    }

    /**
     * API class PUT operation
     *
     * This method will be run when a PUT request is sent to a given API class.
     *
     * @since  0.1.0
     * @access public
     * @throws Exception
     * @static
     */
    public static function Put()
    {
        throw new Exception("Method Not Implemented", 501);
    }

    /**
     * API class DELETE operation
     *
     * This method will be run when a DELETE request is sent to a given API class.
     *
     * @since  0.1.0
     * @access public
     * @throws Exception
     * @static
     */
    public static function Delete()
    {
        throw new Exception("Method Not Implemented", 501);
    }
}
