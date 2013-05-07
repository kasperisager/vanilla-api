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
class API_Class_Users extends API_Mapper
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
    * @return  array
    */
   public function Get($Path)
   {
      if (isset($Path[2])) $ID = $Path[2];

      if (isset($ID)) {
         return self::GetById($ID);
      } else {
         return self::GetAll();
      }
   }

   /**
    * Find all users
    *
    * GET /users
    *
    * @since   0.1.0
    * @access  public
    * @return  array
    * @static
    */
   public static function GetAll()
   {
      $API['Controller']   = 'User';
      $API['Method']       = 'Summary';
      return $API;
   }

   /**
    * Find a specific user
    *
    * GET /users/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    * @return  array
    * @static
    */
   public static function GetById($ID)
   {
      $API['Controller']            = 'Profile';
      $API['Arguments']             = array($ID);
      $API['Arguments']['userid']   = $ID;
      return $API;
   }
}