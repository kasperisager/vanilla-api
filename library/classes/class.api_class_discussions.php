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
class API_Class_Discussions implements API_Mapper
{
   /**
    * Retrieve discussions
    *
    * GET /discussions
    * GET /discussions/:id
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
    * @access  public
    * @param   string $Format
    * @return  array
    */
   public static function GetAll($Format)
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
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    * @static
    */
   public static function GetById($Format, $ID)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/discussion.' . $Format . DS . $ID;

      return $Return;
   }

   /**
    * Retrieve a the current user's bookmarked discussions
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @return  array
    */
   public static function GetBookmarks($Format)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/discussions/bookmarked.' . $Format;

      return $Return;
   }

   /**
    * Retrieve discussions created by the current user
    * 
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @return  array
    * @static
    */
   public static function GetMine($Format)
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
    * @return  array
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
    * @access  public
    * @param   string $Format
    * @return  array
    * @static
    */
   public static function PostDiscussion($Format)
   {
      $Return = array();
      $Return['Resource'] = 'vanilla/post/discussion.' . $Format;

      return $Return;
   }

   /**
    * Create a new comment
    *
    * @since   0.1.0
    * @access  public
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    * @static
    */
   public static function PostComment($Format, $ID)
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
    * @return  array
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
    * @access  public
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    * @static
    */
   public static function PutDiscussion($Format, $ID)
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
    * @access  public
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    * @static
    */
   public static function PutComment($Format, $ID)
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
    * @return  array
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
    * @static
    */
   public static function DeleteDiscussion($Format, $ID)
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
    * @static
    */
   public static function DeleteComment($Format, $ID)
   {
      $Return = array();
      $TransientKey = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/discussion/deletecomment.' . $Format . DS . $ID . DS . $TransientKey;

      return $Return;
   }
}