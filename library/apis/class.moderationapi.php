<?php if (!defined('APPLICATION')) exit;

/**
 * Moderation API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright (c) 2013-2015 Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class ModerationAPI extends APIMapper
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
        static::get('/bans', [
            'controller' => 'Settings',
            'method'     => 'bans',
            'arguments'  => [
                'Page'   => val('Page', $data)
            ]
        ]);

        static::post('/bans', [
            'controller' => 'Settings',
            'method'     => 'bans',
            'arguments'  => [
                'Action' => 'add'
            ]
        ]);

        static::put('/bans/[i:ID]', [
            'controller' => 'Settings',
            'method'     => 'bans',
            'arguments'  => [
                'Action' => 'edit'
            ]
        ]);

        static::delete('/bans/[i:ID]', [
            'controller' => 'Settings',
            'method'     => 'bans',
            'arguments'  => [
                'Action' => 'delete'
            ]
        ]);
    }
}
