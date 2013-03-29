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
 * @SWG\Resource(
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
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    */
   public function Get($Params)
   {
      $Format = $Params['Format'];
      if (Gdn::Session()->CheckPermission(
         array(
            'Garden.Users.Add',
            'Garden.Users.Edit',
            'Garden.Users.Delete'
         )
      )) {
         return array('Map' => 'dashboard/user.' . $Format);
      } else {
         return array('Map' => 'dashboard/user/summary.' . $Format);
      }
   }

   /**
    * POST
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    */
   public function Post($Params)
   {
      return TRUE;
   }

   /**
    * PUT
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    */
   public function Put($Params)
   {
      return TRUE;
   }

   /**
    * DELETE
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    */
   public function Delete($Params)
   {
      return TRUE;
   }
}