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
 * @SWG\Resource(
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
    * @SWG\Api(
    *   path="/configuration",
    *   @SWG\operations(
    *     @SWG\operation(
    *       httpMethod="GET",
    *       path="/configuration",
    *       nickname="GetConfig",
    *       summary="Get the current forum configuration"
    *     )
    *   )
    * )
    */
   public function Get($Params)
   {
      $Format = $Params['Format'];
      return array('Map' => 'dashboard/settings/configuration.' . $Format);
   }

   protected function _GetThemes() {

   }

   protected function _GetLocales() {
      
   }

   /**
    * POST
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @return  bool
    */
   public function Post()
   {
      return TRUE;
   }

   /**
    * PUT
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @return  bool
    */
   public function Put()
   {
      return TRUE;
   }

   /**
    * DELETE
    *
    * @package API
    * @since   0.1.0
    * @access  public
    * @return  bool
    */
   public function Delete()
   {
      return TRUE;
   }
}