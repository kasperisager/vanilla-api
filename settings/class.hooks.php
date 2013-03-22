<?php if (!defined('APPLICATION')) exit();

/**
 * To be written
 *
 * @package     API
 * @since       0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class API implements Gdn_IPlugin
{
    /**
     * Map the API request to the appropriate controller
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Gdn_Dispatcher_BeforeDispatch_Handler($Sender)
    {
        APIController::_Dispatch();
    }

    /**
     * No setup required
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Setup()
    {
        return TRUE;
    }

    /**
     * No cleanup required
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    public function OnDisable()
    {
        return TRUE;
    }
}