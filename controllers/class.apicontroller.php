<?php if (!defined('APPLICATION')) exit();

/**
 * API Controller
 *
 * This controller mainly handles rendering data from the API engine
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class APIController extends Gdn_Controller
{
   /**
    * Output exceptions in either JSON or XML
    *
    * @param [type] $Code    [description]
    * @param [type] $Message [description]
    */
   public function Exception($Code, $Message)
   {
      $this->SetData(array(
         'Code'      => intval($Code),
         'Exception' => $Message
      ));
      $this->Render();
   }
}
