<?php if (!defined('APPLICATION')) exit;

/**
 * Mapper class providing common methods for defining APIs
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 * @abstract
 */
abstract class APIMapper extends Gdn_Pluggable implements iAPI
{
    /* Properties */

    /**
     * Endpoints supported by the API
     *
     * @since  0.1.0
     * @access protected
     * @var    null|array
     * @static
     */
    protected static $endpoints;

    /**
     * Methods supported by the API
     *
     * The OPTIONS and HEAD methods are always supported.
     *
     * @since  0.1.0
     * @access protected
     * @var    array
     * @static
     */
    protected static $supports = array('options', 'head');

    /* Methods */

    /**
     * Provide read-only access to the available endpoints
     *
     * @since  0.1.0
     * @access public
     * @return array Array of available endpoints
     * @final
     */
    final public function endpoints($path, $data)
    {
        if (static::$endpoints === null) {
            // Register API endpoints specific by the API class
            static::register($path, $data);

            // Fire event to allow overriding and registering new endpoints
            // outside the API class itself
            $this->fireAs(get_called_class())->fireEvent('register');
        }

        return static::$endpoints;
    }

    /**
     * Find and return methods supported by the called endpoint
     *
     * @since  0.1.0
     * @access public
     * @return array
     * @final
     * @static
     */
    final public static function supports()
    {
        // Check if these methods are supported
        $check = array('get', 'post', 'put', 'delete');

        foreach (static::$endpoints as $method => $endpoints) {
            $method   = strtolower($method);
            $supports = static::$supports;

            // Make sure the method is valid and not already marked as being
            // supported, and if so then add it to the list
            if (in_array($method, $check) || !in_array($method, $supports)) {
                static::$supports[] = $method;
            }
        }

        return static::$supports;
    }

    /**
     * Method for registering an API GET endpoint
     *
     * @since  0.1.0
     * @access public
     * @param  stirng $endpoint The endpoint to register
     * @param  array  $data     Endpoint mapping data
     * @return void
     * @final
     * @static
     */
    final public static function get($endpoint, $data)
    {
        static::endpoint('GET', $endpoint, $data);
    }

    /**
     * Method for registering an API POST endpoint
     *
     * @since  0.1.0
     * @access public
     * @return void
     * @final
     * @static
     */
    final public static function post($endpoint, $data)
    {
        static::endpoint('POST', $endpoint, $data);
    }

    /**
     * Method for registering and API PUT endpoint
     *
     * @since  0.1.0
     * @access public
     * @return void
     * @final
     * @static
     */
    final public static function put($endpoint, $data)
    {
        static::endpoint('PUT', $endpoint, $data);
    }

    /**
     * Method for registering an API DELETE endpoint
     *
     * @since  0.1.0
     * @access public
     * @return void
     * @final
     * @static
     */
    final public static function delete($endpoint, $data)
    {
        static::endpoint('DELETE', $endpoint, $data);
    }

    /**
     * Register an API endpoint
     *
     * @since  0.1.0
     * @access public
     * @param  string $method   HTTP method
     * @param  string $endpoint Endpoint to register
     * @param  array  $data     Endpoint mapping data (controller, etc.)
     * @return void
     * @final
     * @static
     */
    final protected static function endpoint($method, $endpoint, $data)
    {
        static::$endpoints[$method][$endpoint] = $data;
    }
}
