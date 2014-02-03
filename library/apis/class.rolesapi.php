<?php if (!defined('APPLICATION')) exit;

/**
 * Roles API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class RolesAPI extends APIMapper
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
            'controller'   => 'Role',
            'authenticate' => true
        ));

        static::get('/:id', array(
            'arguments'    => array(':id'),
            'authenticate' => true
        ));

        // POST endpoints

        static::post('/', array(
            'controller' => 'Role',
            'method'     => 'add'
        ));

        // PUT endpoints

        static::put('/:id', array(
            'controller' => 'Role',
            'method'     => 'edit',
            'arguments'  => array(':id')
        ));

        // DELETE endpoints

        static::delete('/:id', array(
            'controller' => 'Role',
            'method'     => 'delete',
            'arguments'  => array(':id')
        ));
    }
}
