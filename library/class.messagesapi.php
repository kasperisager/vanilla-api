<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Messages API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\Resource(
 *   resourcePath="/messages"
 * )
 */
class MessagesAPI extends Mapper
{
   /**
    * Retrieve Vanilla configuration
    *
    * GET /messages
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    * @return  array
    *
    * @SWG\api(
    *   path="/messages",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       nickname="GetMessages",
    *       summary="Get the current user's messages"
    *     )
    *   )
    * )
    */
   public function Get($Params)
   {
      $Format = $Params['Format'];
      return array('Map' => 'messages/all.' . $Format);
   }

   /**
    * POST
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    * @return  bool
    */
   public function Post($Params)
   {
      return 501;
   }

   /**
    * PUT
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    * @return  bool
    */
   public function Put($Params)
   {
      return 501;
   }

   /**
    * DELETE
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
    * @return  bool
    */
   public function Delete($Params)
   {
      return 501;
   }
}