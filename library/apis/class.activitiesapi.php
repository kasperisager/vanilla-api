<?php if (!defined('APPLICATION')) exit();

/**
 * Activities API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class ActivitiesAPI extends APIMapper
{
   /**
    * Retrieve activity items
    *
    * GET /activities
    * GET /activities/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Get($Path)
   {
      if (isset($Path[2])) $ID = $Path[2];

      (isset($ID)) ? self::GetById($ID) : self::GetAll();
   }

   /**
    * Retrieve all activity items
    *
    * GET /activities
    *
    * @since   0.1.0
    * @access  public
    */
   public function GetAll()
   {
      $this->API['Controller'] = 'Activity';
   }

   /**
    * Retrieve a specific activity item
    *
    * GET /activities/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    */
   public function GetById($ID)
   {
      $this->API['Controller']   = 'Activity';
      $this->API['Method']       = 'Item';
      $this->API['Arguments']    = array(
         'ActivityID' => $ID
         );
   }
}
