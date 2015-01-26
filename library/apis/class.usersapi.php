<?php if (!defined('APPLICATION')) exit;

/**
 * Users API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright (c) 2013-2015 Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class UsersAPI extends APIMapper
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
            'controller'   => 'User',
            'authenticate' => true,
            'arguments'    => [
                'Page' => val('Page', $data)
            ]
        ]);

        static::get('/[i:UserID]', [
            'controller' => 'Profile'
        ]);

        static::get('/summary', [
            'controller' => 'User',
            'method'     => 'summary'
        ]);

        static::post('/', [
            'controller' => 'User',
            'method'     => 'add'
        ]);

        static::put('/[i:UserID]', [
            'controller' => 'User',
            'method'     => 'edit'
        ]);

        static::delete('/[i:UserID]', [
            'controller' => 'User',
            'method'     => 'delete',
            'arguments'  => [
                'Method' => val('Method', $data)
            ]
        ]);
    }
}
