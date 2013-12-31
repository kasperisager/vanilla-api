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
     * Retrieve activity items
     *
     * GET /activities
     * GET /activities/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Get($Path)
    {
        static::$Controller = 'Activity';

        if ($ID = val(2, $Path)) {
            static::$Method    = 'Item';
            static::$Arguments = array(
                'ActivityID' => $ID
            );
        }
    }

    /**
     * Post an activity item
     *
     * POST /activity
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Post($Path)
    {
        static::$Controller   = 'Activity';
        static::$Method       = 'Post';
        static::$Authenticate = TRUE;
    }

    /**
     * Remove an activity item
     *
     * DELETE /activity/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     * @static
     */
    public static function Delete($Path)
    {
        if (!$ID = val(2, $Path)) {
            throw new Exception("No ID defined", 401);
        }

        static::$Controller = 'Activity';
        static::$Method     = 'Delete';
        static::$Arguments  = array(
            'DiscussionID' => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
        static::$Authenticate = TRUE;
    }
}
