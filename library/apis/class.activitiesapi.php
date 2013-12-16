<?php if (!defined('APPLICATION')) exit();

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
     */
    public function Get($Path)
    {
        $this->API['Controller'] = 'Activity';

        $ID = val(2, $Path);

        if ($ID) {
            $this->API['Method']    = 'Item';
            $this->API['Arguments'] = array(
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
     */
    public function Post($Path)
    {
        $this->API['Controller']   = 'Activity';
        $this->API['Method']       = 'Post';
        $this->API['Authenticate'] = TRUE;
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
     */
    public function Delete($Path)
    {
        $ID = val(2, $Path);

        if (!$ID) {
            throw new Exception("No ID defined", 401);
        }

        $this->API['Controller'] = 'Activity';
        $this->API['Method']     = 'Delete';
        $this->API['Arguments']  = array(
            'DiscussionID' => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
        $this->API['Authenticate'] = TRUE;
    }
}
