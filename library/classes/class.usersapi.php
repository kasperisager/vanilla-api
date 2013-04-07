<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Users API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\resource(
 *   resourcePath="/users"
 * )
 */
class UsersAPI extends APIMapper
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
         return self::_GetById($Format, $ID);
      } else {
         return self::_GetAll($Format);
      }
   }

   /**
    * Find all users
    *
    * @since   0.1.0
    * @access  protected
    * @param   string $Format
    * @return  array
    * 
    * @SWG\api(
    *   path="/users",
    *   @SWG\operation(
    *     httpMethod="GET",
    *     path="/users",
    *     nickname="GetAll",
    *     summary="Get a list of all registered users"
    *   )
    * )
    */
   protected function _GetAll($Format)
   {
      $Return = array();
      $Return['Resource'] = 'dashboard/user/summary.' . $Format;
      
      return $Return;
   }

   /**
    * Find a specific user
    *
    * @since   0.1.0
    * @access  protected
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    *
    * @SWG\api(
    *   path="/users/{id}",
    *   @SWG\operation(
    *     httpMethod="GET",
    *     path="/users",
    *     nickname="GetById",
    *     summary="Get a specific user"
    *   )
    * )
    */
   protected function _GetById($Format, $ID)
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