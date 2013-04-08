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
    * @param   array $Parameters
    * @return  array
    */
   public function Get($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      if ($ID) {
         return self::GetById($Format, $ID);
      } else {
         return self::GetAll($Format);
      }
   }

   /**
    * Find all users
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @return  array
    * @static
    */
   public static function GetAll($Format)
   {
      $Return = array();
      $Return['Resource'] = 'dashboard/user/summary.' . $Format;
      
      return $Return;
   }

   /**
    * Find a specific user
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
      $Return['Arguments']['userid'] = $ID;
      $Return['Resource'] = 'dashboard/profile.' . $Format . DS . $ID . DS . 'false';
      
      return $Return;
   }

   /**
    * POST
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Post($Parameters)
   {
      throw new Exception("Method Not Implemented", 501);
   }

   /**
    * PUT
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Put($Parameters)
   {
      throw new Exception("Method Not Implemented", 501);
   }

   /**
    * DELETE
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Delete($Parameters)
   {
      throw new Exception("Method Not Implemented", 501);
   }
}