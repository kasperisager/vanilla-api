<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Discussions API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\resource(
 *   resourcePath="/discussions"
 * )
 */
class DiscussionsAPI extends APIMapper
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
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @return  array
    */
   public function Get($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      if ($ID) {
         return self::_GetById($Format, $ID);
      } else {
         return self::_GetAll($Format);
      }
   }

   /**
    * Find all discussions
    *
    * GET /discussions
    *
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
   protected function _GetAll($Format)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/discussions.' . $Format;

      return $Return;
   }

   /**
    * Find a specific discussion
    *
    * GET /discussions/:id
    *
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
   protected function _GetById($Format, $ID)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/discussion' . DS . $ID;

      return $Return;
   }

   /**
    * Retrieve a the current user's bookmarked discussions
    *
    * GET /discussions/bookmarked
    *
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
      $Return = array();
      $Return['Resource'] = 'vanilla/discussions/bookmarked.' . $Format;

      return $Return;
   }

   /**
    * Retrieve discussions created by the current user
    *
    * GET /discussions/bookmarked
    *
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
   protected function _GetMine($Format)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/discussions/mine.' . $Format;

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
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
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
   public function Post($Parameters)
   {
      $Format = $Parameters['Format'];

      $Return = array();
      $Return['Resource'] = 'vanilla/post/discussion.' . $Format;

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
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Put($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      $Return = array();
      $Return['Arguments']['DiscussionID'] = $ID;
      $Return['Arguments']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/post/editdiscussion.' . $Format . DS . $ID;

      return $Return;
   }

   /**
    * Update an existing discussion
    *
    * PUT /discussions/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
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
   protected function _Put($Format, $ID)
   {

   }

   /**
    * Sink/unsink an existing discussion
    *
    * PUT /discussions/sink/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
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
   protected function _PutSink($Format, $ID)
   {

   }

   /**
    * Announce/unannounce an existing discussion
    *
    * PUT /discussions/announce/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
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
   protected function _PutAnnounce($Format, $ID)
   {

   }

   /**
    * Dismiss an announced discussion
    *
    * PUT /discussions/dismiss/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
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
   protected function _PutDismiss($Format, $ID)
   {

   }

   /**
    * Close/open an existing discussion
    *
    * PUT /discussions/close/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
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
   protected function _PutClose($Format, $ID)
   {

   }

   /**
    * Bookmark/unbookmark an existing discussion
    *
    * PUT /discussions/bookmark/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
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
   protected function _PutBookmark($Format, $ID)
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
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
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
   public function Delete($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      $Return = array();
      $Return['Arguments']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/discussion/delete.' . $Format . DS . $ID;

      return $Return;
   }
}