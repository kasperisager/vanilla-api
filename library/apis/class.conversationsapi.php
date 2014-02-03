<?php if (!defined('APPLICATION')) exit;

/**
 * Conversations API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class ConversationsAPI extends APIMapper
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
            'controller'   => 'Messages',
            'method'       => 'all',
            'authenticate' => true
        ));

        static::get('/:id', array(
            'arguments'    => array(':id'),
            'authenticate' => true
        ));

        // POST endpoints

        static::post('/', array(
            'controller' => 'Messages',
            'method'     => 'add'
        ));

        static::post('/:id/messages', array(
            'method'    => 'addMessage',
            'arguments' => array(':id')
        ));

        // DELETE endpoints

        static::delete('/:id', array(
            'controller' => 'Messages',
            'method'     => 'clear',
            'arguments'  => array(':id')
        ));
    }
}
