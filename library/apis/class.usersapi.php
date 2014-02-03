<?php if (!defined('APPLICATION')) exit;

/**
 * Users API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class UsersAPI extends APIMapper
{
    /**
     * Register API endpoints
     *
     * @since  0.1.0
     * @access public
     * @param  array $path
     * @param  array $data
     * @return void
     * @static
     */
    public static function register($path, $data)
    {
        // GET endpoints

        static::get('/', array(
            'controller'   => 'User',
            'authenticate' => true

        ));

        static::get('/:id', array(
            'controller' => 'Profile',
            'arguments'  => array(':id', ':id')
        ));

        static::get('/summary', array(
            'method' => 'summary'
        ));

        // POST endpoints

        static::post('/', array(
            'controller' => 'User',
            'method'     => 'add'
        ));

        // PUT endpoints

        static::put('/:id', array(
            'controller' => 'User',
            'method'     => 'edit',
            'arguments'  => array(':id')
        ));

        // DELETE endpoints

        static::delete('/:id', array(
            'controller' => 'User',
            'method'     => 'delete',
            'arguments'  => array(':id', val('Method', $data))
        ));
    }
}
