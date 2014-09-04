<?php if (!defined('APPLICATION')) exit;

/**
 * Discussions API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class DiscussionsAPI extends APIMapper
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
            'controller' => 'Discussions'
        ));

        static::get('/[i:id]', array(
            'controller' => 'Discussion'
        ));

        static::get('/bookmarks', array(
            'controller'   => 'Discussion',
            'method'       => 'bookmarked',
            'authenticate' => true
        ));

        static::get('/mine', array(
            'controller'   => 'Discussion',
            'method'       => 'mine',
            'authenticate' => true
        ));

        // POST endpoints

        static::post('/', array(
            'controller' => 'Post',
            'method'     => 'discussion'
        ));

        static::post('/[i:id]/comments', array(
            'controller' => 'Post',
            'method'     => 'comment'
        ));

        // PUT endpoints

        static::put('/[i:id]', array(
            'controller' => 'Post',
            'method'     => 'discussion'
        ));

        static::put('/comments/[i:id]', array(
            'controller' => 'Post',
            'method'     => 'comment'
        ));

        // DELETE endpoints

        static::delete('/[i:id]', array(
            'controller' => 'Discussion',
            'method'     => 'delete'
        ));

        static::delete('/comments/[i:id]', array(
            'controller' => 'Discussion',
            'method'     => 'deleteComment'
        ));
    }
}
