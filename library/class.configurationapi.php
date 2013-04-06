<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Configuration API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\resource(
 *   resourcePath="/configuration"
 * )
 */
class ConfigurationAPI extends Mapper
{
   /**
    * Retrieve Vanilla configuration
    *
    * GET /configuration
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @return  array
    *
    * @SWG\api(
    *   path="/configuration",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       nickname="GetConfig",
    *       summary="Get the current forum configuration"
    *     )
    *   )
    * )
    */
   public function Get($Parameters)
   {
      $Ext = $Parameters['Ext'];

      $Return = array();
      $Return['Map'] = 'dashboard/settings/configuration.' . $Ext;

      return $Return;
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
      return 501;
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
      return 501;
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
      return 501;
   }
}