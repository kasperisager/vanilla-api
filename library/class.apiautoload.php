<?php
/**
 * API Autoloader
 *
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
namespace API\Library;

if (!defined('APPLICATION')) exit();

/**
 * Register vendor and class autoloaders
 * 
 * The latter is merely for the sake of being able to have a more semantic and
 * clean directory structure where all API classes live within their own folder
 * inside the library directory.
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class APIAutoload
{
   /**
    * Load vendor libraries autoloader and register core API class autoloader
    *
    * @access public
    */
   public function __construct()
   {
      require PATH_APPLICATIONS . DS . 'api/vendors/autoload.php';
      spl_autoload_register(array($this, 'Autoload'));
   }

   /**
    * Autoload a specified API class
    *
    * Using the GetClass function, Autoload checks if the specified class is a
    * core API class and if it is, it includes the class in the autoloader.
    *
    * @access  protected
    * @param   string $Class  The class to be included in the autoloader
    */
   protected function Autoload($Class)
   {
      $Autoload   = $this->GetClass($Class);
      if ($Autoload) include_once $Autoload;
   }

   /**
    * Get a core API class
    *
    * This function checks if the specified class belongs to the core API
    * classes by making sure the class exists within the classes directory.
    *
    * @access  protected
    * @param   string $Class  The class to be checked
    * @return  string|bool    If the class is part of the core API classes, the
    *                         full path to the class is returned. If not, the
    *                         function will return FALSE instead.
    * @static
    */
   protected static function GetClass($Class)
   {
      $File       = 'class.' . strtolower($Class) . '.php';
      $Classes    = PATH_APPLICATIONS . DS . 'api/library/classes';
      $Path       = $Classes . DS . $File;

      if (file_exists($Path)) return $Path;

      return FALSE;
   }
}