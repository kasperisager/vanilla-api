<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Discussions API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\resource(
 *   resourcePath="/discussions"
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
    * To be implemented:
    * GET /discussions/bookmarks
    * GET /discussions/mine
    * GET /discussions/unread
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    */
   public function Get($Params)
   {
      $DiscussionID  = $Params['URI'][2];
      $Format        = $Params['Format'];
      if ($DiscussionID) {
         return self::_GetById($Format, $DiscussionID);
      } else {
         return self::_GetAll($Format);
      }
   }

   /**
    * Find all discussions
    *
    * GET /discussions
    *
    * @package API
    * @since   0.1.0
    * @access  public
    *
    * @SWG\api(
    *   path="/discussions",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       nickname="GetAll",
    *       summary="Find all discussions",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   protected function _GetAll()
   {
      return array('Map' => 'vanilla/discussions');
   }

   /**
    * Find a specific discussion
    *
    * GET /discussions/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   int $DiscussionID
    *
    * @SWG\api(
    *   path="/discussions/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       nickname="GetById",
    *       summary="Find a specific discussion",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   protected function _GetById($Format, $DiscussionID)
   {
      return array('Map' => 'vanilla/discussion' . DS . $DiscussionID);
   }

   /**
    * Retrieve a the current user's bookmarked discussions
    *
    * GET /discussions/bookmarked
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    *
    * @SWG\api(
    *   path="/discussions/bookmarks",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       nickname="GetBookmarked",
    *       summary="Find a users bookmarked discussions"
    *     )
    *   )
    * )
    */
   protected function _GetBookmarks($Format)
   {
      return array('Map' => 'vanilla/discussions/bookmarked');
   }

   /**
    * Retrieve discussions created by the current user
    *
    * GET /discussions/bookmarked
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    *
    * @SWG\api(
    *   path="/discussions/mine",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       nickname="GetMine",
    *       summary="Find discussions created by the current user"
    *     )
    *   )
    * )
    */
   protected function _GetMine()
   {
      return array('Map' => 'vanilla/discussions/mine');
   }

   /**
    * Create discussions
    *
    * POST /discussions
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    *
    * @SWG\api(
    *   path="/discussions",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="POST",
    *       nickname="Post",
    *       summary="Create a new discussion",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   public function Post($Params)
   {
      $Format = $Params['Format'];
      return array('Map' => 'vanilla/post/discussion.' . $Format);
   }

   /**
    * Update and alter discussions
    *
    * PUT /discussions/:id
    *
    * To be implemented:
    * PUT /discussions/sink/:id
    * PUT /discussions/announce:id
    * PUT /discussions/dismiss/:id
    * PUT /discussions/close/:id/
    * PUT /discussions/bookmark/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    */
   public function Put($Params)
   {
      $DiscussionID  = $Params['URI'][2];
      $Format        = $Params['Format'];
      $Map = 'vanilla/post/editdiscussion' . DS . $DiscussionID;
      $Args = array(
         'DiscussionID' => $DiscussionID,
         'TransientKey'  => Gdn::Session()->TransientKey()
      );
      return array('Map' => $Map, 'Args' => $Args);
   }

   /**
    * Update an existing discussion
    *
    * PUT /discussions/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @param   int $DiscussionID
    *
    * @SWG\api(
    *   path="/discussions/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="PUT",
    *       nickname="PutDiscussion",
    *       summary="Update an existing discussion",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   protected function _Put($Format, $DiscussionID)
   {

   }

   /**
    * Sink/unsink an existing discussion
    *
    * PUT /discussions/sink/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @param   int $DiscussionID
    *
    * @SWG\api(
    *   path="/discussions/sink/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="PUT",
    *       nickname="PutSink",
    *       summary="Sink/unsink an existing discussion",
    *       notes="This is a convenience operation. The same result can be accomplished using <code>PUT /discussions/:id</code>"
    *     )
    *   )
    * )
    */
   protected function _PutSink($Format, $DiscussionID)
   {

   }

   /**
    * Announce/unannounce an existing discussion
    *
    * PUT /discussions/announce/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @param   int $DiscussionID
    *
    * @SWG\api(
    *   path="/discussions/announce/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="PUT",
    *       nickname="PutAnnounce",
    *       summary="Announce/unannounce an existing discussion",
    *       notes="This is a convenience operation. The same result can be accomplished using <code>PUT /discussions/:id</code>"
    *     )
    *   )
    * )
    */
   protected function _PutAnnounce($Format, $DiscussionID)
   {

   }

   /**
    * Dismiss an announced discussion
    *
    * PUT /discussions/dismiss/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @param   int $DiscussionID
    *
    * @SWG\api(
    *   path="/discussions/dismiss/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="PUT",
    *       nickname="PutDismiss",
    *       summary="Dismiss an announced discussion",
    *       notes="This is a convenience operation. The same result can be accomplished using <code>PUT /discussions/:id</code>"
    *     )
    *   )
    * )
    */
   protected function _PutDismiss($Format, $DiscussionID)
   {

   }

   /**
    * Close/open an existing discussion
    *
    * PUT /discussions/close/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @param   int $DiscussionID
    *
    * @SWG\api(
    *   path="/discussions/close/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="PUT",
    *       nickname="PutClose",
    *       summary="Close/open an existing discussion",
    *       notes="This is a convenience operation. The same result can be accomplished using <code>PUT /discussions/:id</code>"
    *     )
    *   )
    * )
    */
   protected function _PutClose($Format, $DiscussionID)
   {

   }

   /**
    * Bookmark/unbookmark an existing discussion
    *
    * PUT /discussions/bookmark/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @param   int $DiscussionID
    *
    * @SWG\api(
    *   path="/discussions/bookmark/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="PUT",
    *       nickname="PutBookmark",
    *       summary="Bookmark/unbookmark an existing discussion",
    *       notes="This is a convenience operation. The same result can be accomplished using <code>PUT /discussions/:id</code>"
    *     )
    *   )
    * )
    */
   protected function _PutBookmark($Format, $DiscussionID)
   {

   }

   /**
    * Remove discussions
    *
    * DELETE /discussions/:id
    *
    * To be implemented:
    * DELETE /discussions/bookmarks/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    *
    * @SWG\api(
    *   path="/discussions/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="DELETE",
    *       nickname="Delete",
    *       summary="Delete an existing discussion",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   public function Delete($Params)
   {
      $DiscussionID  = $Params['URI'][2];
      $Format        = $Params['Format'];
      $Map = 'vanilla/discussion/delete' . DS . $DiscussionID;
      $Args = array(
         'TransientKey'  => Gdn::Session()->TransientKey()
      );
      return array('Map' => $Map, 'Args' => $Args);
   }
}