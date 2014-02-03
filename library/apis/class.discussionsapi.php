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

        static::get('/:id', array(
            'controller' => 'Discussion',
            'arguments'  => array(':id')
        ));

        static::get('/bookmarks', array(
            'method'       => 'bookmarked',
            'authenticate' => true
        ));

        static::get('/mine', array(
            'method'       => 'mine',
            'authenticate' => true
        ));

        // POST endpoints

        static::post('/', array(
            'controller' => 'Post',
            'method'     => 'discussion'
        ));

        static::post('/:id/comments', array(
            'method'    => 'comment',
            'arguments' => array(':id')
        ));

        // PUT endpoints

        static::put('/:id', array(
            'controller' => 'Post',
            'method'     => 'editDiscussion'
        ));

        static::put('/comments/:id', array(
            'method' => 'editDiscussion'
        ));

        // DELETE endpoints

        static::delete('/:id', array(
            'controller' => 'Discussion',
            'method'     => 'delete',
            'arguments'  => array(':id')
        ));

        static::delete('/comments/:id', array(
            'method'    => 'deleteComment',
            'arguments' => array(':id')
        ));
    }
}
