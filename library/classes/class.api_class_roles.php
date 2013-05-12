<?php if (!defined('APPLICATION')) exit();

/**
 * Roles API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class API_Class_Roles extends API_Mapper
{
   public function Get($Path)
   {
      $API['Controller'] = 'Role';
      return $API;
   }

   public function Post($Path)
   {
      $API['Controller']                  = 'Role';
      $API['Method']                      = 'Add';
      $API['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();
      return $API;
   }

   public function Put($Path)
   {
      if (!isset($Path[2])) throw new Exception("No ID defined", 401);

      $ID = $Path[2];

      $API['Controller']                  = 'Role';
      $API['Method']                      = 'Edit';
      $API['Arguments']                   = array($ID);
      $API['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();
      return $API;
   }

   public function Delete($Path)
   {
      if (!isset($Path[2])) throw new Exception("No ID defined", 401);

      $ID = $Path[2];

      $API['Controller']                  = 'Role';
      $API['Method']                      = 'Delete';
      $API['Arguments']                   = array($ID);
      $API['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();
      return $API;
   }
}
