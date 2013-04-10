<?php if (!defined('APPLICATION')) exit();

/**
 * Messages API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class API_Class_Messages extends API_Mapper
{
   /**
    * Retrieve messages
    *
    * GET /messages
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    * @return  array
    */
   public function Get($Path)
   {
      $Return = array();
      $Return['Controller']   = 'Messages';
      $Return['Method']       = 'All';

      return $Return;
   }
}