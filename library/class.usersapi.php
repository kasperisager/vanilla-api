<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Users API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\resource(
 *   resourcePath="/users"
 * )
 */
class UsersAPI extends Mapper
{
   /**
    * Retrieve users
    *
    * GET /users
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Get($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      if ($ID) {
         return self::_GetById($Ext, $ID);
      } else {
         return self::_GetAll($Ext);
      }
   }

   /**
    * Find all users
    * 
    * @param  string $Ext
    * @return array
    * 
    * @SWG\api(
    *   path="/users",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       path="/users",
    *       nickname="GetAll",
    *       summary="Get a list of all registered users"
    *     )
    *   )
    * )
    */
   protected function _GetAll($Ext)
   {
      $Return = array();
      $Return['Map'] = 'dashboard/user/summary.' . $Ext;
      
      return $Return;
   }

   /**
    * Find a specific user
    * 
    * @param  string $Ext
    * @param  int $ID
    *
    * @SWG\api(
    *   path="/users/{id}",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       path="/users",
    *       nickname="GetById",
    *       summary="Get a specific user"
    *     )
    *   )
    * )
    */
   protected function _GetById($Ext, $ID)
   {
      $Return = array();
      $Return['Args']['userid'] = $ID;
      $Return['Map'] = 'dashboard/profile.' . $Ext . DS . $ID . DS . 'false';
      
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