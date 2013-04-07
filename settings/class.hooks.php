<?php if (!defined('APPLICATION')) exit();

/**
 * API Hooks
 *
 * The API hooks handles hooking into different events throught Garden and its
 * applications. More specifically, this class hooks into the dispatcher to
 * handle API request mapping and also hooks the dashboard settings controller
 * to render the Application Interface settings menu.
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class APIHooks implements Gdn_IPlugin
{
   /**
    * Map the API request to the appropriate controller
    *
    * @since   0.1.0
    * @access  public
    */
   public function Gdn_Dispatcher_BeforeDispatch_Handler()
   {
      $API = new APIController();
      $API->_Dispatch();
   }

   /**
    * API Settings
    *
    * This function sets up and renders a settings page where the API
    * configuration can be changed.
    * 
    * @param SettingsController $Sender
    */
   public function SettingsController_API_Create($Sender) {
      $Sender->Permission('Garden.Settings.Manage');
      
      if ($Sender->Form->AuthenticatedPostBack()) {

         $Secret  = C('API.Secret');
         $Regen   = $Sender->Form->ButtonExists('Re-generate');

         if ($Regen) $Secret = self::UUIDSecure();

         $Save = array();
         $Save['API.Secret'] = $Secret;
         
         if ($Sender->Form->ErrorCount() == 0) {
            SaveToConfig($Save);
            if ($Regen) {
               $Sender->InformMessage(
                  '<span class="InformSprite Refresh"></span>
                  Refresh the page to see the new Application Secret.',
                  'Dismissable HasSprite'
               );
            }
         }

      } else {
         $Data = array();
         $Data['Secret'] = C('API.Secret');
         $Sender->Form->SetData($Data);
      }
      
      $Sender->AddSideMenu();
      $Sender->SetData('Title', 'Application Interface');
      $Sender->Render('API', 'settings', 'api');
   }
   
   /**
    * Adds an "API" menu to the "Forum Settings" section in the dashboard
    * 
    * @param Gdn_Controller $Sender 
    */
   public function Base_GetAppSettingsMenuItems_Handler($Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Site Settings', T('Application Interface'),
                     'dashboard/settings/api', 'Garden.Settings.Manage'
      );
   }

   /**
    * Generates a Universally Unique IDentifier, version 4.
    *
    * @see http://en.wikipedia.org/wiki/UUID
    * @return string A UUID, made up of 32 hex digits and 4 hyphens.
    */
   protected static function UUIDSecure()
   {
      return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
         // 32 bits for "time_low"
         mt_rand(0, 0xffff), mt_rand(0, 0xffff),

         // 16 bits for "time_mid"
         mt_rand(0, 0xffff),

         // 16 bits for "time_hi_and_version",
         // four most significant bits holds version number 4
         mt_rand(0, 0x0fff) | 0x4000,

         // 16 bits, 8 bits for "clk_seq_hi_res",
         // 8 bits for "clk_seq_low",
         // two most significant bits holds zero and one for variant DCE1.1
         mt_rand(0, 0x3fff) | 0x8000,

         // 48 bits for "node"
         mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
      );
   }

   /**
    * Code to be run upon enabling the API
    *
    * @since   0.1.0
    * @access  public
    */
   public function Setup()
   {
      if (!C('API.Secret')) SaveToConfig('API.Secret', self::UUIDSecure());

      if (!Gdn::PluginManager()->CheckPlugin('Logger'))
         throw new Exception("Please install Logger before enabling the API");

      $ApplicationInfo = array();
      include CombinePaths(array(PATH_APPLICATIONS . DS . 'api/settings/about.php'));
      $Version = ArrayValue('Version', ArrayValue('API', $ApplicationInfo, array()), 'Undefined');
      SaveToConfig('API.Version', $Version);  
   }
}