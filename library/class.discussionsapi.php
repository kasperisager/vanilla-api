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
    * @return  array
    */
   public function Get($Params)
   {
      $ID   = $Params['URI'][2];
      $Ext  = $Params['Ext'];

      if ($ID) {
         return self::_GetById($Ext, $ID);
      } else {
         return self::_GetAll($Ext);
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
    * @return  array
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
   protected function _GetAll($Ext)
   {
      $Return = array();
      $Return['Map'] = 'vanilla/discussions.' . $Ext;

      return $Return;
   }

   /**
    * Find a specific discussion
    *
    * GET /discussions/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Ext
    * @param   int $ID
    * @return  array
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
   protected function _GetById($Ext, $ID)
   {
      $Return = array();
      $Return['Map'] = 'vanilla/discussion' . DS . $ID;

      return $Return;
   }

   /**
    * Retrieve a the current user's bookmarked discussions
    *
    * GET /discussions/bookmarked
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Ext
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
   protected function _GetBookmarks($Ext)
   {
      $Return = array();
      $Return['Map'] = 'vanilla/discussions/bookmarked.' . $Ext;

      return $Return;
   }

   /**
    * Retrieve discussions created by the current user
    *
    * GET /discussions/bookmarked
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Ext
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
   protected function _GetMine($Ext)
   {
      $Return = array();
      $Return['Map'] = 'vanilla/discussions/mine.' . $Ext;

      return $Return;
   }

   /**
    * Create discussions
    *
    * POST /discussions
    *
    * To be implemented:
    * POST /discussions/:id/comments
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
      $Ext = $Params['Ext'];

      $Return = array();
      $Return['Map'] = 'vanilla/post/discussion.' . $Ext;

      return $Return;
   }

   /**
    * Update and alter discussions
    *
    * PUT /discussions/:id
    *
    * To be implemented:
    * PUT /discussions/comments/:id
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
      $ID   = $Params['URI'][2];
      $Ext  = $Params['Ext'];

      $Return = array();
      $Return['Args']['DiscussionID'] = $ID;
      $Return['Args']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Map'] = 'vanilla/post/editdiscussion.' . $Ext . DS . $ID;

      return $Return;
   }

   /**
    * Update an existing discussion
    *
    * PUT /discussions/:id
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   string $Ext
    * @param   int $ID
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
   protected function _Put($Ext, $ID)
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
    * @param   string $Ext
    * @param   int $ID
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
   protected function _PutSink($Ext, $ID)
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
    * @param   string $Ext
    * @param   int $ID
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
   protected function _PutAnnounce($Ext, $ID)
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
    * @param   string $Ext
    * @param   int $ID
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
   protected function _PutDismiss($Ext, $ID)
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
    * @param   string $Ext
    * @param   int $ID
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
   protected function _PutClose($Ext, $ID)
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
    * @param   string $Ext
    * @param   int $ID
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
   protected function _PutBookmark($Ext, $ID)
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
      $ID   = $Params['URI'][2];
      $Ext  = $Params['Ext'];

      $Return = array();
      $Return['Args']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Map'] = 'vanilla/discussion/delete.' . $Ext . DS . $ID;

      return $Return;
   }
}