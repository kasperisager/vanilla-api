<?php if (!defined('APPLICATION')) exit;

/**
 * Discussions API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class DiscussionsAPI extends APIMapper
{
    /**
     * Retrieve discussions
     *
     * GET /discussions
     * GET /discussions/:id
     * GET /discussions/mine
     * GET /discussions/bookmarks
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Get($Path)
    {
        static::$Controller = 'Discussions';

        $Arg = val(2, $Path);

        if (is_numeric($Arg)) {
            static::$Controller = 'Discussion';
            static::$Arguments  = array(
                'DiscussionID' => $Arg
            );
        } else if ($Arg == 'bookmarks') {
            static::$Method       = 'Bookmarked';
            static::$Authenticate = TRUE;
        } else if ($Arg == 'mine') {
            static::$Method       = 'Mine';
            static::$Authenticate = TRUE;
        }
    }

    /**
     * Create discussions and comments
     *
     * POST /discussions
     * POST /discussions/:id/comments
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Post($Path)
    {
        static::$Controller = 'Post';

        $Arg = val(2, $Path);

        if (is_numeric($Arg) && val(3, $Path) == 'comments') {
            static::$Method    = 'Comment';
            static::$Arguments = array(
                'DiscussionID' => $Arg,
                'TransientKey' => Gdn::Session()->TransientKey()
            );
        } else {
            static::$Method = 'Discussion';
        }
    }

    /**
     * Update and alter discussions
     *
     * PUT /discussions/:id
     * PUT /discussions/comments/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     * @static
     */
    public static function Put($Path)
    {
        $Arg = val(2, $Path);

        if (!$Arg) throw new Exception("No ID defined", 401);

        static::$Controller = 'Post';
        static::$Arguments  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );

        if (is_numeric($Arg)) {
            static::$Method    = 'EditDiscussion';
            static::$Arguments = array(
                'DiscussionID' => $Arg
            );
        } else if ($Arg == 'comments') {
            static::$Method    = 'EditComment';
            static::$Arguments = array(
                'CommentID' => val(3, $Path)
            );
        }
    }

    /**
     * Remove discussions and comments
     *
     * DELETE /discussions/:id
     * DELETE /discussions/comments/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     * @static
     */
    public static function Delete($Path)
    {
        $Arg = val(2, $Path);

        if (!$Arg) throw new Exception("No ID defined", 401);

        static::$Controller = 'Discussion';
        static::$Arguments  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );

        if (is_numeric($Arg)) {
            static::$Method    = 'Delete';
            static::$Arguments = array(
                'DiscussionID' => $Arg
            );
        } else if ($Arg == 'comments') {
            static::$Method    = 'DeleteComment';
            static::$Arguments = array(
                'CommentID' => val(3, $Path)
            );
        }
    }
}
