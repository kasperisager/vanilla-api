<?php if (!defined('APPLICATION')) exit;

/**
 * Interface that all API classes must follow
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
interface iAPI
{
    /**
     * Register API endpoints
     *
     * Endpoints are registered using the get(), post(), put() and delete()
     * methods defined in the API Mapper:
     *
     *     static::get('/bar/:id', array(
     *         'controller' => 'foo',
     *         'method'     => 'bar'
     *         'arguments'  => array('FooID' => ':id')
     *     ));
     *
     * @since  0.1.0
     * @access public
     * @param  array $path The Request URI in array format
     * @param  array $data Request arguments sent by the client
     * @return void
     * @static
     */
    public static function register($path, $data);
}
