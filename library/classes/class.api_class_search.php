<?php if (!defined('APPLICATION')) exit();

/**
 * Search API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class API_Class_Search extends API_Mapper
{
   public function Get($Path)
   {
      $API['Controller'] = 'Search';
      return $API;
   }
}
