<?php if (!defined('APPLICATION')) exit();

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
     * @since   0.1.0
     * @access  public
     * @param   array $Path
     */
    public function Get($Path)
    {
        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if ($ID) {

            $this->API['Controller'] = 'Discussion';
            $this->API['Arguments']  = array(
                'DiscussionID' => $ID
            );

        } else {

            $this->API['Controller'] = 'Discussions';

            //$this->API['Method'] = 'Bookmarked';

            //$this->API['Method'] = 'Mine';

        }
    }

    /**
     * Create discussions and comments
     *
     * POST /discussions
     * POST /discussions/:id/comments
     *
     * @since   0.1.0
     * @access  public
     * @param   array $Path
     */
    public function Post($Path)
    {
        $this->API['Controller'] = 'Post';

        $ID      = (isset($Path[2])) ? $Path[2] : FALSE;
        $Comment = (isset($Path[3])) ? $Path[3] : FALSE;

        if ($ID && $Comment && $Comment == 'comments') {

            $this->API['Method']    = 'Comment';
            $this->API['Arguments'] = array(
                'DiscussionID' => $ID,
                'TransientKey' => Gdn::Session()->TransientKey()
            );

        } else {

            $this->API['Method'] = 'Discussion';

        }
    }

    /**
     * Update and alter discussions
     *
     * PUT /discussions/:id
     * PUT /discussions/comments/:id
     *
     * @since   0.1.0
     * @access  public
     * @param   array $Path
     * @throws  Exception
     */
    public function Put($Path)
    {
        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if (!$ID) {
            throw new Exception("No ID defined", 401);
        }

        $this->API['Controller'] = 'Post';
        $this->API['Arguments']  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );

        if ($ID == 'comments') {

            $ID = (isset($Path[3])) ? $Path[3] : FALSE;

            $this->API['Method']    = 'EditComment';
            $this->API['Arguments'] = array(
                'CommentID' => $ID
            );

        } else {

            $this->API['Method']    = 'EditDiscussion';
            $this->API['Arguments'] = array(
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
     * @since   0.1.0
     * @access  public
     * @param   array $Path
     * @throws  Exception
     */
    public function Delete($Path)
    {
        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if (!$ID) {
            throw new Exception("No ID defined", 401);
        }

        $this->API['Controller'] = 'Discussion';
        $this->API['Arguments']  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );

        if ($ID == 'comments') {

            $ID = (isset($Path[3])) ? $Path[3] : FALSE;

            $this->API['Method']    = 'DeleteComment';
            $this->API['Arguments'] = array(
                'CommentID'    => $ID,
            );

        } else {

            $this->API['Method']    = 'Delete';
            $this->API['Arguments'] = array(
                'DiscussionID' => $ID,
            );

        }
    }
}
