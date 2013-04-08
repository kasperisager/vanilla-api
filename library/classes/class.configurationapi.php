<?php
/**
 * Configuration API
 *
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
//namespace API\Library\Classes;

if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Configuration API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\resource(
 *   resourcePath="/configuration"
 * )
 */
class ConfigurationAPI extends APIMapper
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
    *   @SWG\operation(
    *     httpMethod="GET",
    *     nickname="GetConfig",
    *     summary="Get the current forum configuration"
    *   )
    * )
    */
   public function Get($Parameters)
   {
      $Format = $Parameters['Format'];

      $Return = array();
      $Return['Resource'] = 'dashboard/settings/configuration.' . $Format;
      $Return['Authenticate'] = 'Required';

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