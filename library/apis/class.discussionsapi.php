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
      if (isset($Path[2])) $ID = $Path[2];

      (isset($ID)) ? self::GetById($ID) : self::GetAll();
   }

   /**
    * Find all discussions
    *
    * GET /discussions
    *
    * @since   0.1.0
    * @access  public
    */
   public function GetAll()
   {
      $this->API['Controller'] = 'Discussions';
   }

   /**
    * Find a specific discussion
    *
    * GET /discussions/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    */
   public function GetById($ID)
   {
      $this->API['Controller']            = 'Discussion';
      $this->API['Args']['DiscussionID']  = $ID;
   }

   /**
    * Retrieve a the current user's bookmarked discussions
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    */
   public function GetBookmarks()
   {
      $this->API['Controller']   = 'Discussions';
      $this->API['Method']       = 'Bookmarked';
   }

   /**
    * Retrieve discussions created by the current user
    *
    * @since   0.1.0
    * @access  public
    */
   public function GetMine()
   {
      $this->API['Controller']   = 'Discussions';
      $this->API['Method']       = 'Mine';
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
      if (isset($Path[2])) $ID      = $Path[2];
      if (isset($Path[3])) $Comment = $Path[3];

      if (isset($ID) && isset($Comment) && $Comment == 'comments') {
         self::PostComment($ID);
      } else {
         self::PostDiscussion();
      }
   }

   /**
    * Create a new discussion
    *
    * POST /discussions
    *
    * @since   0.1.0
    * @access  public
    */
   public function PostDiscussion()
   {
      $this->API['Controller']   = 'Post';
      $this->API['Method']       = 'Discussion';
   }

   /**
    * Create a new comment
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    */
   public function PostComment($ID)
   {
      $this->API['Controller']            = 'Post';
      $this->API['Method']                = 'Comment';
      $this->API['Args']['DiscussionID']  = $ID;
      $this->API['Args']['TransientKey']  = Gdn::Session()->TransientKey();
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
    */
   public function Put($Path)
   {
      if (!isset($Path[2])) throw new Exception("No ID defined", 401);

      $ID = $Path[2];

      if ($ID == 'comments') {
         $ID = $Path[3];
         self::PutComment($ID);
      } else {
         self::PutDiscussion($ID);
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
    */
   public function PutDiscussion($ID)
   {
      $this->API['Controller']            = 'Post';
      $this->API['Method']                = 'EditDiscussion';
      $this->API['Args']['DiscussionID']  = $ID;
      $this->API['Args']['TransientKey']  = Gdn::Session()->TransientKey();
   }

   /**
    * Update an existing comment
    *
    * PUT /discussions/comments/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    */
   public function PutComment($ID)
   {
      $this->API['Controller']            = 'Post';
      $this->API['Method']                = 'EditComment';
      $this->API['Args']['CommentID']     = $ID;
      $this->API['Args']['TransientKey']  = Gdn::Session()->TransientKey();
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
    */
   public function Delete($Path)
   {
      if (!isset($Path[2])) {
         throw new Exception("No ID defined", 401);
      }

      $ID = $Path[2];

      if (isset($ID) && $ID == 'comments') {
         $ID = $Path[3];
         self::DeleteComment($ID);
      } elseif (isset($ID)) {
         self::DeleteDiscussion($ID);
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
    */
   public function DeleteDiscussion($ID)
   {
      $this->API['Controller']            = 'Discussion';
      $this->API['Method']                = 'Delete';
      $this->API['Args']['DiscussionID']  = $ID;
      $this->API['Args']['TransientKey']  = Gdn::Session()->TransientKey();
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
    */
   public function DeleteComment($ID)
   {
      $TransientKey = Gdn::Session()->TransientKey();

      $this->API['Controller']            = 'Discussion';
      $this->API['Method']                = 'DeleteComment';
      $this->API['Args']['CommentID']     = $ID;
      $this->API['Args']['TransientKey']  = Gdn::Session()->TransientKey();
   }
}
