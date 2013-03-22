<?php if (!defined('APPLICATION')) exit();

/**
 * Discussions API
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class Discussions extends Mapper
{
    /**
     * Retrieve discussions
     *
     * GET /discussions
     * GET /discussions/:id
     * GET /discussions/category/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Get($Params)
    {
        $DiscussionID   = $Params['URI'][2];
        $CategoryID     = $Params['URI'][3];

        if ($DiscussionID == 'category') {
            $Data = array('Map' => 'vanilla/categories' . DS . $CategoryID);
        } else if ($DiscussionID) {
            $Data = array('Map' => 'vanilla/discussion' . DS . $DiscussionID);
        } else {
            $Data = array('Map' => 'vanilla/discussions');
        }

        return $Data;
    }

    /**
     * Create discussions
     *
     * POST /discussions
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Post($Params)
    {
        $Data = array('Map' => 'vanilla/post/discussion');
        return $Data;
    }

    /**
     * Update discussions
     *
     * PUT /discussions/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Put($Params)
    {
        $DiscussionID = $Params['URI'][2];

        if ($DiscussionID) {
            $Map = 'vanilla/post/editdiscussion' . DS . $DiscussionID;
            $Args = array(
                'DiscussionID' => $DiscussionID,
                'TransientKey'  => Gdn::Session()->TransientKey()
            );
        }

        $Data = array('Map' => $Map, 'Args' => $Args);
        return $Data;
    }

    /**
     * Remove discussions
     *
     * DELETE /discussions/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Delete($Params)
    {
        $DiscussionID = $Params['URI'][2];

        if ($DiscussionID) {
            $Map = 'vanilla/discussion/delete' . DS . $DiscussionID;
            $Args = array(
                //'DiscussionID'    => $DiscussionID,
                'TransientKey'  => Gdn::Session()->TransientKey()
            );
        }

        $Data = array('Map' => $Map, 'Args' => $Args);
        return $Data;
    }
}