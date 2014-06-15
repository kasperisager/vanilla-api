<?php if (!defined('APPLICATION')) exit;

/**
 * Moderation API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class ModerationAPI extends APIMapper
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

        static::get('/bans', array(
            'controller' => 'Settings',
            'method'     => 'bans'
        ));

        // POST endpoints
        
        static::post('/bans/[a:add]', array(
            'controller' => 'Settings',
            'method'     => 'bans'
        ));

        // PUT endpoints
        
        static::put('/bans/[i:id]', array(
            'controller' => 'Settings',
            'method'     => 'bans'
        ));
    }
}
