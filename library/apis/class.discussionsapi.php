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
     * @param  array $data
     * @return voDiscussionID
     * @static
     */
    public static function register($data)
    {
        static::get('/', [
            'controller' => 'Discussions',
            'arguments'  => [
                'Page' => val('Page', $data)
            ]
        ]);

        static::get('/[i:DiscussionID]', [
            'controller' => 'Discussion',
            'arguments'  => [
                'Page' => val('Page', $data)
            ]
        ]);

        static::get('/bookmarks', [
            'controller'   => 'Discussions',
            'method'       => 'bookmarked',
            'authenticate' => true,
            'arguments'    => [
                'Page' => val('Page', $data)
            ]
        ]);

        static::get('/mine', [
            'controller'   => 'Discussions',
            'method'       => 'mine',
            'authenticate' => true,
            'arguments'    => [
                'Page' => val('Page', $data)
            ]
        ]);

        static::post('/[i:DiscussionID]/comments', [
            'controller' => 'Post',
            'method'     => 'comment'
        ]);

        static::put('/[i:DiscussionID]', [
            'controller' => 'Post',
            'method'     => 'editDiscussion'
        ]);

        static::put('/comments/[i:DiscussionID]', [
            'controller' => 'Post',
            'method'     => 'editComment'
        ]);

        static::delete('/[i:DiscussionID]', [
            'controller' => 'Discussion',
            'method'     => 'delete'
        ]);

        static::delete('/comments/[i:DiscussionID]', [
            'controller' => 'Discussion',
            'method'     => 'deleteComment'
        ]);
    }
}
