<?php if (!defined('APPLICATION')) exit;

/**
 * Comments API
 *
 * @package   API
 * @since     0.2.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class CommentsAPI extends APIMapper
{
    /**
     * Register API endpoints
     *
     * @since  0.2.0
     * @access public
     * @param  array $data
     * @return void
     * @static
     */
    public static function register($data)
    {
        static::put('/[i:CommentID]', [
            'controller' => 'Post',
            'method'     => 'editComment'
        ]);

        static::delete('/[i:CommentID]', [
            'controller' => 'Discussion',
            'method'     => 'deleteComment',
            'arguments'  => [
                'TransientKey' => Gdn::session()->transientKey()
            ]
        ]);
    }
}
