<?php if (!defined('APPLICATION')) exit();

/**
 * Configuration API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
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
    * @param   array $Path
    */
   public function Get($Path)
   {
      $this->API['Application']  = 'Dashboard';
      $this->API['Controller']   = 'Settings';
      $this->API['Method']       = 'Configuration';
      $this->API['Authenticate'] = TRUE;
   }
}
