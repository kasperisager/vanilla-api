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
    * Find all users
    *
    * @since   0.1.0
    * @access  public
    * @return  array
    * @static
    */
   public static function GetAll()
   {
      $Return = array();
      $Return['Controller']   = 'User';
      $Return['Method']       = 'Summary';
      
      return $Return;
   }

   /**
    * Find a specific user
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
      $Return['Controller']            = 'Profile';
      $Return['Arguments']             = array($ID);
      $Return['Arguments']['userid']   = $ID;
      
      return $Return;
   }
}