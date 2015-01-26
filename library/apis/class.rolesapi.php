<?php if (!defined('APPLICATION')) exit;

/**
 * Roles API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright (c) 2013-2015 Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class RolesAPI extends APIMapper
{
    /**
     * Register API endpoints
     *
     * @since  0.1.0
     * @access public
     * @param  array $data
     * @return void
     * @static
     */
    public static function register($data)
    {
        static::get('/', [
            'controller'   => 'Role',
            'authenticate' => true
        ]);

        static::get('/[i:RoleID]', [
            'controller'   => 'Role',
            'authenticate' => true
        ]);

        static::post('/', [
            'controller' => 'Role',
            'method'     => 'add'
        ]);

        static::put('/[i:RoleID]', [
            'controller' => 'Role',
            'method'     => 'edit'
        ]);

        static::delete('/[i:RoleID]', [
            'controller' => 'Role',
            'method'     => 'delete'
        ]);
    }
}
