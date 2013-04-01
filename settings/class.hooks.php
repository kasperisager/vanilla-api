<?php if (!defined('APPLICATION')) exit();

/**
 * To be written
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 */
class APIHooks implements Gdn_IPlugin
{
   /**
    * Map the API request to the appropriate controller
    *
    * @package API
    * @since   0.1.0
    * @access  public
    */
   public function Gdn_Dispatcher_BeforeDispatch_Handler()
   {
      APIController::_Dispatch();
   }

   /**
    * Make sure the application secret is set
    *
    * @package API
    * @since   0.1.0
    * @access  public
    */
   public function Setup()
   {
      $Secret = C('API.Secret');
      if (!$Secret) {
         $Secret = sha1(mt_rand());
      }
      SaveToConfig('API.Secret', $Secret);
   }

   /**
    * No cleanup required
    *
    * @package API
    * @since 0.1.0
    * @access public
    */
   public function OnDisable()
   {
      return TRUE;
   }
}