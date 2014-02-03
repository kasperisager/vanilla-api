<?php if (!defined('APPLICATION')) exit;

/**
 * Activities API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class ActivitiesAPI extends APIMapper
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
            'controller' => 'Activity'
        ));

        static::get('/:id', array(
            'method'    => 'item',
            'arguments' => array(':id')
        ));

        // POST endpoints

        static::post('/', array(
            'controller' => 'Activity',
            'method'     => 'post'
        ));

        // DELETE endpoints

        static::delete('/:id', array(
            'controller' => 'Activity',
            'method'     => 'delete',
            'arguments'  => array(':id')
        ));
    }
}
