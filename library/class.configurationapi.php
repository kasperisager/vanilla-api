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
    * @package API
    * @since   0.1.0
    * @access  public
    * @param   array $Params
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
   public function Get($Params)
   {
      $Ext = $Params['Ext'];

      $Return = array();
      $Return['Map'] = 'dashboard/settings/configuration.' . $Ext;

      return $Return;
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