<?php if (!defined('APPLICATION')) exit();

/**
 * API to access the current user session
 *
 * @package API
 * @since 0.1.0
 * @author Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright © 2013
 * @license http://opensource.org/licenses/MIT MIT
 */
class SessionController extends APIController {

    /**
     * Define resources used in controller
     * 
     * @since 0.1.0
     * @access public
     */
    public $Uses = array('Session');

    /**
     * Build the session API and render it as JSON
     * 
     * @since 0.1.0
     * @access public
     */
    public function Index() {

        $Session = Gdn::Session();

        $Data = $Session;

        if (!Gdn::Session()->IsValid()):

            http_response_code(401);

            $Data = array(
                'errorResponses' => array(
                    array(
                        'code' => 401,
                        'reason' => T('Unauthorized')
                    )
                )
            );

        endif;

        $this->RenderData(parent::Sanitize($Data));

    }

    /**
     * Define resource readable by Swagger
     * 
     * @return array
     * @since 0.1.0
     * @access public
     */
    public function Resource() {

        return array(
            'resourcePath' => '/session',
            'apis' => array(
                array(
                    'path' => '/session',
                    'description' => 'Operations related to sessions',
                    'operations' => array(
                        array(
                            'httpMethod' => 'GET',
                            'nickname' => 'session',
                            'summary' => 'Information about current user session'
                        )
                    )
                )
            )
        );

    }

}