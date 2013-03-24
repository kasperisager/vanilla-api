<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Discussions API
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * @SWG\Resource(
 *     resourcePath="/discussions"
 * )
 */
class DiscussionsAPI extends Mapper
{
    /**
     * Retrieve discussions
     *
     * GET /discussions
     * GET /discussions/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Get($Params)
    {
        $DiscussionID = $Params['URI'][2];
        if ($DiscussionID) {
            return self::_GetById($DiscussionID);
        } else {
            return self::_GetAll();
        }
    }

    /**
     * Find all discussions
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/discussions",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="GET",
     *             path="/discussions",
     *             nickname="GetAll",
     *             summary="Find all discussions",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    protected function _GetAll()
    {
        return array('Map' => 'vanilla/discussions');
    }

    /**
     * Find a specific discussion
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/discussions/{id}",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="GET",
     *             nickname="GetById",
     *             summary="Find a specific discussion",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    protected function _GetById($DiscussionID)
    {
        return array('Map' => 'vanilla/discussion' . DS . $DiscussionID);
    }

    /**
     * Create discussions
     *
     * POST /discussions
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/discussions",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="POST",
     *             nickname="Post",
     *             summary="Create a new discussion",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    public function Post($Params)
    {
        return array('Map' => 'vanilla/post/discussion');
    }

    /**
     * Update discussions
     *
     * PUT /discussions/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/discussions/{id}",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="PUT",
     *             nickname="Put",
     *             summary="Update an existing discussion",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    public function Put($Params)
    {
        $DiscussionID = $Params['URI'][2];
        $Map = 'vanilla/post/editdiscussion' . DS . $DiscussionID;
        $Args = array(
            'DiscussionID' => $DiscussionID,
            'TransientKey'  => Gdn::Session()->TransientKey()
        );
        return array('Map' => $Map, 'Args' => $Args);
    }

    /**
     * Remove discussions
     *
     * DELETE /discussions/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/discussions/{id}",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="DELETE",
     *             nickname="Delete",
     *             summary="Delete an existing discussion",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    public function Delete($Params)
    {
        $DiscussionID = $Params['URI'][2];
        $Map = 'vanilla/discussion/delete' . DS . $DiscussionID;
        $Args = array(
            'TransientKey'  => Gdn::Session()->TransientKey()
        );
        return array('Map' => $Map, 'Args' => $Args);
    }
}