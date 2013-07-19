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
   public function Get($Path)
   {
      if (isset($Path[2])) $ID = $Path[2];

      if (isset($ID)) {
         self::GetById($ID);
      } else {
         self::GetAll();
      }
   }

   public function GetAll()
   {
      $this->API['Controller'] = 'Activity';
   }

   public function GetById($ID)
   {
      $this->API['Controller']   = 'Activity';
      $this->API['Method']       = 'Item';
      $this->API['Arguments']    = array($ID);
   }
}
