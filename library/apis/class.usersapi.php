<?php if (!defined('APPLICATION')) exit();

/**
 * Users API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class UsersAPI extends APIMapper
{
   /**
    * Retrieve users
    *
    * GET /users
    * GET /users/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Get($Path)
   {
      if (isset($Path[2])) $ID = $Path[2];

      if (isset($ID)) {
         self::GetById($ID);
      } else {
         self::GetAll();
      }
   }

   /**
    * Find all users
    *
    * GET /users
    *
    * @since   0.1.0
    * @access  public
    */
   public function GetAll()
   {
      $this->API['Controller']   = 'User';
      $this->API['Method']       = 'Summary';
   }

   /**
    * Find a specific user
    *
    * GET /users/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    */
   public function GetById($ID)
   {
      $this->API['Controller']   = 'Profile';
      $this->API['Arguments']    = array(
         'User'   => $ID,
         'UserID' => $ID
         );
   }

   /**
    * Create a new user
    *
    * POST /users
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Post($Path)
   {
      $this->API['Controller']   = 'User';
      $this->API['Method']       = 'Add';
      $this->API['Arguments']    = array(
         'TransientKey' => Gdn::Session()->TransientKey()
         );
   }
}
