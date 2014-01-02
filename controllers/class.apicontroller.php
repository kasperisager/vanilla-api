<?php if (!defined('APPLICATION')) exit;

/**
 * API Controller
 *
 * This controller mainly handles rendering data from the API engine.
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
     * Render API exceptions
     *
     * @since  0.1.0
     * @access public
     * @param  int|string $Code    Error code
     * @param  string     $Message Base64-encoded error message
     */
    public function exception($code, $message)
    {
        header("HTTP/1.0 ${code}", true, $code);

        $this->setData(array(
            'Code'      => intval($code),
            'Exception' => base64_decode(htmlspecialchars($message))
        ));

        $this->renderData();
    }

    /**
     * Render API OPTIONS requests
     *
     * @since  0.1.0
     * @access public
     * @param  string $methods       Comma-separated string of allowed methods
     * @param  string $documentation API documentation (Base64 encoded JSON)
     */
    public function options($methods, $documentation)
    {
        header("Allow: ${methods}", true);

        $this->setData(json_decode(base64_decode($documentation), true));

        $this->renderData();
    }
}
