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
 * @SWG\resource(
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
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
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
   public function Get($Parameters)
   {
      $Format = $Parameters['Format'];
      return array('Resource' => 'messages/all.' . $Format);
   }

   /**
    * POST
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @return  bool
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
    * @return  bool
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
    * @return  bool
    */
   public function Delete($Parameters)
   {
      throw new Exception("Method Not Implemented", 501);
   }
}