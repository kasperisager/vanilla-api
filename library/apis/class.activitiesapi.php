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
     * @param  array $data
     * @return void
     * @static
     */
    public static function register($data)
    {
        static::get('/', [
            'controller' => 'Activity',
            'arguments'  => [
                'Filter' => val('Filter', $data),
                'Page'   => val('Page', $data)
            ]
        ]);

        static::get('/[i:ActivityID]', [
            'controller' => 'Activity',
            'method'     => 'item'
        ]);

        static::post('/', [
            'controller' => 'Activity',
            'method'     => 'post',
            'arguments'  => [
                'Notify' => val('Notify', $data)
            ]
        ]);

        static::delete('/[i:ActivityID]', [
            'controller' => 'Activity',
            'method'     => 'delete'
        ]);
    }
}
