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

        static::get('/[i:id]', array(
            'controller' => 'Profile'
        ));

        static::get('/summary', array(
            'controller' => 'Profile',
            'method'     => 'summary'
        ));

        // POST endpoints

        static::post('/', array(
            'controller' => 'User',
            'method'     => 'add'
        ));

        // PUT endpoints

        static::put('/[i:id]', array(
            'controller' => 'User',
            'method'     => 'edit',
        ));

        // DELETE endpoints

        static::delete('/[i:id]/[a:method]', array(
            'controller' => 'User',
            'method'     => 'delete'
        ));
    }
}
