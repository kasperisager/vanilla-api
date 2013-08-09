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
class SearchAPI extends APIMapper
{
   /**
    * Get search results
    *
    * GET /search
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Get($Path)
   {
      $this->API['Controller'] = 'Search';
   }
}
