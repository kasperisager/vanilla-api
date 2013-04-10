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
class API_Class_Discussions extends API_Mapper
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
    * @return  array
    */
   public function Get($Path)
   {
      if (isset($Path[2])) $ID = $Path[2];

      if (isset($ID))
         return self::GetById($ID);
      else
         return self::GetAll();
   }

   /**
    * Find all discussions
    *
    * GET /discussions
    *
    * @since   0.1.0
    * @access  public
    * @return  array
    * @static
    */
   public static function GetAll()
   {
      $Return = array();
      $Return['Controller'] = 'Discussions';

      return $Return;
   }

   /**
    * Find a specific discussion
    *
    * GET /discussions/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    * @return  array
    * @static
    */
   public static function GetById($ID)
   {
      $Return = array();
      $Return['Controller']   = 'Discussion';
      $Return['Arguments']    = array($ID);

      return $Return;
   }

   /**
    * Retrieve a the current user's bookmarked discussions
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @return  array
    * @static
    */
   public static function GetBookmarks()
   {
      $Return = array();
      $Return['Controller']   = 'Discussions';
      $Return['Method']       = 'Bookmarked';
      $Return['Arguments']    = array($ID);

      return $Return;
   }

   /**
    * Retrieve discussions created by the current user
    * 
    * @since   0.1.0
    * @access  public
    * @return  array
    * @static
    */
   public static function GetMine()
   {
      $Return = array();
      $Return['Controller']   = 'Discussions';
      $Return['Method']       = 'Mine';
      $Return['Arguments']    = array($ID);

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
    * @param   array $Path
    * @return  array
    */
   public function Post($Path)
   {
      if (isset($Path[2])) $ID      = $Path[2];
      if (isset($Path[3])) $Comment = $Path[3];

      if (isset($ID) && isset($Comment) && $Comment == 'comments')
         return self::PostComment($ID);
      else
         return self::PostDiscussion();
   }

   /**
    * Create a new discussion
    *
    * POST /discussions
    *
    * @since   0.1.0
    * @access  public
    * @return  array
    * @static
    */
   public static function PostDiscussion()
   {
      $Return = array();
      $Return['Controller']   = 'Post';
      $Return['Method']       = 'Discussion';

      return $Return;
   }

   /**
    * Create a new comment
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    * @return  array
    * @static
    */
   public static function PostComment($ID)
   {
      $Return = array();
      $Return['Controller']                  = 'Post';
      $Return['Method']                      = 'Comment';
      $Return['Arguments']                   = array($ID);
      $Return['Arguments']['DiscussionID']   = $ID;
      $Return['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();

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
    * @param   array $Path
    * @return  array
    */
   public function Put($Path)
   {
      if (!isset($Path[2]))
         throw new Exception("No ID defined", 401);

      $ID = $Path[2];

      if (isset($ID) && $ID == 'comments') {
         $ID = $Path[3];
         return self::PutComment($ID);
      } elseif (isset($ID)) {
         return self::PutDiscussion($ID);
      }
   }

   /**
    * Update an existing discussion
    *
    * PUT /discussions/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    * @return  array
    * @static
    */
   public static function PutDiscussion($ID)
   {
      $Return = array();
      $Return['Controller']                  = 'Post';
      $Return['Method']                      = 'EditDiscussion';
      $Return['Arguments']                   = array($ID);
      $Return['Arguments']['DiscussionID']   = $ID;
      $Return['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();

      return $Return;
   }

   /**
    * Update an existing comment
    *
    * PUT /discussions/comments/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    * @return  array
    * @static
    */
   public static function PutComment($ID)
   {
      $Return = array();
      $Return['Controller']                  = 'Post';
      $Return['Method']                      = 'EditComment';
      $Return['Arguments']                   = array($ID);
      $Return['Arguments']['CommentID']      = $ID;
      $Return['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();

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
    * @param   array $Path
    * @return  array
    */
   public function Delete($Path)
   {
      if (!isset($Path[2]))
         throw new Exception("No ID defined", 401);

      $ID = $Path[2];

      if (isset($ID) && $ID == 'comments') {
         $ID = $Path[3];
         return self::DeleteComment($ID);
      } elseif (isset($ID)) {
         return self::DeleteDiscussion($ID);
      }
   }

   /**
    * Remove a discussion
    *
    * DELETE /discussions/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    * @return  array
    * @static
    */
   public static function DeleteDiscussion($ID)
   {
      $Return = array();
      $Return['Controller']                  = 'Discussion';
      $Return['Method']                      = 'Delete';
      $Return['Arguments']                   = array($ID);
      $Return['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();

      return $Return;
   }

   /**
    * Remove a discussion
    *
    * DELETE /discussions/comments/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    * @return  array
    * @static
    */
   public static function DeleteComment($ID)
   {
      $TransientKey = Gdn::Session()->TransientKey();

      $Return = array();
      $Return['Controller']                  = 'Discussion';
      $Return['Method']                      = 'DeleteComment';
      $Return['Arguments']                   = array($ID, $TransientKey);

      return $Return;
   }
}