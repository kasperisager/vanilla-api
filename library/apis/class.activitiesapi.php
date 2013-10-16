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
      $this->API['Controller'] = 'Activity';

      $ID = (isset($Path[2])) ? $Path[2] : FALSE;

      if ($ID) {

         $this->API['Method']    = 'Item';
         $this->API['Arguments'] = array(
            'ActivityID' => $ID
         );

      }
   }
}
