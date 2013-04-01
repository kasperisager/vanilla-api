<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Session API
 *
 * This method is not used for anything besides documentation purposes as the
 * API controller takes care of exposing the session object
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\resource(
 *   resourcePath="/session"
 * )
 */
class SessionAPI extends Mapper
{
   /**
    * Info about current user session
    *
    * GET /session
    *
    * @package API
    * @since   0.1.0
    * @access  public
    *
    * @SWG\api(
    *   path="/session",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       nickname="GetSession",
    *       summary="Information about the current user session",
    *       notes="Respects permissions"
    *     )
    *   )
    * )
    */
   public function Get($Params)
   {
      return FALSE;
   }

   /**
    * POST
    *
    * @package API
    * @since   0.1.0
    * @access  public
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
    */
   public function Delete($Params)
   {
      return 501;
   }
}