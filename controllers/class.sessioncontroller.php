<?php if (!defined('APPLICATION')) exit();

/**
 * API to access the current user session
 *
 * @package     API
 * @since       0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class SessionController extends APIController
{

    /**
     * To be written
     * 
     * @since   0.1.0
     * @access  public
     */
    public function Initialize()
    {
        UtilityController::Initialize();
    }

    /**
     * To be written
     * 
     * @since   0.1.0
     * @access  public
     */
    public function Index()
    {
        
        $Request = UtilityController::ProcessRequest();  
  
        switch($Request->Method):

            case 'get':  
                
                self::Get();

                break;

            case 'post':
                


                break;

        endswitch;

    }

    /**
     * To be written
     * 
     * @since   0.1.0
     * @access  public
     */
    public function Get()
    {

        $Session = Gdn::Session();

        if (!Gdn::Session()->IsValid()):

            $Errors = array(
                'errorResponses' => array(
                    array(
                        'code' => 401,
                        'reason' => T('Unauthorized')
                    )
                )
            );

            $this->RenderData(UtilityController::SendResponse(401, $Errors));

        else:

            $this->RenderData(UtilityController::SendResponse(200, $Data));

        endif;

    }

    /**
     * Define resource readable by Swagger
     * 
     * @return  array
     * @since   0.1.0
     * @access  public
     */
    public function Resource()
    {

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