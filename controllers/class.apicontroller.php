<?php if (!defined('APPLICATION')) exit();

/**
 * API Controller
 *
 * This controller mainly handles rendering data from the API engine
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class APIController extends Gdn_Controller
{
    /**
     * Output API exceptions
     *
     * @since  0.1.0
     * @access public
     * @param  int    $Code    Error code
     * @param  string $Message Error message
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
