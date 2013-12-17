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
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Get($Path)
    {
        if ($ID = val(2, $Path)) {

            static::$Controller = 'Discussion';
            static::$Arguments  = array(
                'DiscussionID' => $ID
            );

        } else {

            static::$Controller = 'Discussions';

            //static::$Method = 'Bookmarked';

            //static::$Method = 'Mine';

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

        $ID      = val(2, $Path);
        $Comment = val(3, $Path);

        if ($ID && $Comment && $Comment == 'comments') {

            static::$Method    = 'Comment';
            static::$Arguments = array(
                'DiscussionID' => $ID,
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
        ;

        if (!$ID = val(2, $Path)) {
            throw new Exception("No ID defined", 401);
        }

        static::$Controller = 'Post';
        static::$Arguments  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );

        if ($ID == 'comments') {

            $ID = val(3, $Path);

            static::$Method    = 'EditComment';
            static::$Arguments = array(
                'CommentID' => $ID
            );

        } else {

            static::$Method    = 'EditDiscussion';
            static::$Arguments = array(
                'DiscussionID' => $ID
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
        ;

        if (!$ID = val(2, $Path)) {
            throw new Exception("No ID defined", 401);
        }

        static::$Controller = 'Discussion';
        static::$Arguments  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );

        if ($ID == 'comments') {

            $ID = val(3, $Path);

            static::$Method    = 'DeleteComment';
            static::$Arguments = array(
                'CommentID'    => $ID,
            );

        } else {

            static::$Method    = 'Delete';
            static::$Arguments = array(
                'DiscussionID' => $ID,
            );

        }
    }
}
