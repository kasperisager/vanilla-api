<?php if (!defined('APPLICATION')) exit;

/**
 * Logs API
 *
 * @package   API
 * @since     0.4.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class LogsAPI extends APIMapper
{
    /**
     * Register API endpoints
     *
     * @since  0.4.0
     * @access public
     * @param  array $data
     * @return void
     * @static
     */
    public static function register($data)
    {
        static::get('/spam', [
            'controller'   => 'Log',
            'method'       => 'spam',
            'authenticate' => true
        ]);

        static::get('/moderation', [
            'controller'   => 'Log',
            'method'       => 'moderation',
            'authenticate' => true
        ]);

        static::get('/edits', [
            'controller'   => 'Log',
            'method'       => 'edits',
            'authenticate' => true
        ]);
    }
}
