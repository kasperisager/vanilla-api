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
class API_Class_Activities extends API_Mapper
{
   public function Get($Path)
   {
      if (isset($Path[2])) $ID = $Path[2];

      if (isset($ID)) {
         return self::GetById($ID);
      } else {
         return self::GetAll();
      }
   }

   public function GetAll()
   {
      $API['Controller'] = 'Activity';
      return $API;
   }

   public function GetById($ID)
   {
      $API['Controller']   = 'Activity';
      $API['Method']       = 'Item';
      $API['Arguments']    = array($ID);
      return $API;
   }
}
