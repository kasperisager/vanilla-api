<?php if (!defined('APPLICATION')) exit;

/**
 * Categories API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class CategoriesAPI extends APIMapper
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
            'controller' => 'Categories',
            'method'     => 'all'
        ));

        static::get('/:id', array(
            'arguments' => array(':id')
        ));

        // POST endpoints

        static::post('/', array(
            'application' => 'Vanilla',
            'controller'  => 'Settings',
            'method'      => 'addCategory'
        ));

        // PUT endpoints

        static::put('/:id', array(
            'application' => 'Vanilla',
            'controller'  => 'Settings',
            'method'      => 'addCategory',
            'arguments'   => array(':id')
        ));

        // DELETE endpoints

        static::delete('/:id', array(
            'application' => 'Vanilla',
            'controller'  => 'Settings',
            'method'      => 'deleteCategory',
            'arguments'   => array(':id')
        ));
    }
}
