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

      if (isset($ID)) {
         return self::GetById($Format, $ID);
      } else {
         return self::GetAll($Format);
      }
   }

   /**
    * Find all discussions
    *
    * GET /discussions
    *
    * @since   0.1.0
    * @access  protected
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
   protected function GetAll($Format)
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
    * @access  protected
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
   protected function GetById($Format, $ID)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/discussion.' . $Format . DS . $ID;

      return $Return;
   }

   /**
    * Retrieve a the current user's bookmarked discussions
    *
    * @since   0.1.0
    * @access  protected
    * @param   string $Format
    */
   protected function GetBookmarks($Format)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/discussions/bookmarked.' . $Format;

      return $Return;
   }

   /**
    * Retrieve discussions created by the current user
    * 
    * @since   0.1.0
    * @access  protected
    * @param   string $Format
    */
   protected function GetMine($Format)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/discussions/mine.' . $Format;

      return $Return;
   }

   /**
    * Create discussions and comments
    *
    * POST /discussions
    * POST /discussions/:id/comments
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Post($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Comment = $Parameters['Path'][3];
      $Format  = $Parameters['Format'];

      if (isset($ID) && isset($Comment) && $Comment == 'comments') {
         return self::PostComment($Format, $ID);
      } else {
         return self::PostDiscussion($Format);
      }
   }

   /**
    * Create a new discussion
    *
    * POST /discussions
    *
    * @since   0.1.0
    * @access  protected
    * @param   string $Format
    * @return  array
    * 
    * @SWG\api(
    *   path="/discussions",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="POST",
    *       nickname="PostDiscussion",
    *       summary="Create a new discussion",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   protected function PostDiscussion($Format)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/post/discussion.' . $Format;

      return $Return;
   }

   /**
    * Create a new comment
    * 
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    *
    * @SWG\api(
    *   path="/discussions/{id}/comments",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="POST",
    *       nickname="PostComment",
    *       summary="Create a new comment",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   protected function PostComment($Format, $ID)
   {
      $Return = array();
      $Return['Arguments']['DiscussionID'] = $ID;
      $Return['Arguments']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/post/comment.' . $Format . DS . $ID;

      return $Return;
   }

   /**
    * Update and alter discussions
    *
    * PUT /discussions/:id
    * PUT /discussions/comments/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Put($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      if (isset($ID) && $ID == 'comments') {
         $ID = $Parameters['Path'][3];
         return self::PutComment($Format, $ID);
      } elseif (isset($ID)) {
         return self::PutDiscussion($Format, $ID);
      }
   }

   /**
    * Update an existing discussion
    *
    * PUT /discussions/:id
    *
    * @since   0.1.0
    * @access  protected
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
   protected function PutDiscussion($Format, $ID)
   {
      $Return = array();
      $Return['Arguments']['DiscussionID'] = $ID;
      $Return['Arguments']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/post/editdiscussion.' . $Format . DS . $ID;

      return $Return;
   }

   /**
    * Update an existing comment
    *
    * PUT /discussions/comments/:id
    *
    * @since   0.1.0
    * @access  protected
    * @param   string $Format
    * @param   int $ID
    *
    * @SWG\api(
    *   path="/discussions/comments/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="PUT",
    *       nickname="PutComment",
    *       summary="Update an existing comment",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   protected function PutComment($Format, $ID)
   {
      $Return = array();
      $Return['Arguments']['CommentID'] = $ID;
      $Return['Arguments']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/post/editcomment.' . $Format . DS . $ID;

      return $Return;
   }

   /**
    * Remove discussions and comments
    *
    * DELETE /discussions/:id
    * DELETE /discussions/comments/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Delete($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      if (isset($ID) && $ID == 'comments') {
         $ID = $Parameters['Path'][3];
         return self::DeleteComment($Format, $ID);
      } elseif (isset($ID)) {
         return self::DeleteDiscussion($Format, $ID);
      }
   }

   /**
    * Remove a discussion
    *
    * DELETE /discussions/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    *
    * @SWG\api(
    *   path="/discussions/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="DELETE",
    *       nickname="DeleteDiscussion",
    *       summary="Delete an existing discussion",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   protected function DeleteDiscussion($Format, $ID)
   {
      $Return = array();
      $Return['Arguments']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/discussion/delete.' . $Format . DS . $ID;

      return $Return;
   }

   /**
    * Remove a discussion
    *
    * DELETE /discussions/comments/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    *
    * @SWG\api(
    *   path="/discussions/comments/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="DELETE",
    *       nickname="DeleteComment",
    *       summary="Delete an existing comment",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   protected function DeleteComment($Format, $ID)
   {
      $Return = array();
      $TransientKey = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/discussion/deletecomment.' . $Format . DS . $ID . DS . $TransientKey;

      return $Return;
   }
}