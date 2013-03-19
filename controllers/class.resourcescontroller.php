<?php if (!defined('APPLICATION')) exit();

/**
 * Resource controller used to get information about each information about
 * each controller and output this as JSON for Swagger UI to read
 *
 * @package     API
 * @since       0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class ResourcesController extends APIController
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
     * @param   string $Resource
     * @since   0.1.0
     * @access  public
     */
    public function Index($Resource)
    {
        
        $Request = UtilityController::ProcessRequest();  
  
        switch($Request->Method):

            case 'get':  
                
                self::Get($Resource);

                break;

        endswitch;

    }

    /**
     * Render the JSON resource listing
     *
     * @param   string $Resource
     * @since   0.1.0
     * @access  public
     */
    public function Get($Resource = NULL)
    {
        $Apis = array(
            'apis' => array(
                array(
                    'path' => '/resources/categories',
                    'description' => 'Operations related to categories'
                ),
                array(
                    'path' => '/resources/session',
                    'description' => 'Operations related to sessions'
                )
            )
        );

        if ($Resource):

            $Resource = $Resource.'Controller';

            if (is_subclass_of($Resource, get_parent_class($this))):

                $Resource = new $Resource;
                $Resource = $Resource->Resource();
                $Data = array_merge(parent::Meta(), $Apis, $Resource);
                $this->RenderData(UtilityController::SendResponse(200, $Data));

            else:

                $Errors = array(
                    'errorResponses' => array(
                        array(
                            'code' => 404,
                            'reason' => T('Not Found')
                        )
                    )
                );

                $this->RenderData(UtilityController::SendResponse(200, $Errors));

            endif;
        else:
            $Data = array_merge(parent::Meta(), $Apis);
            $this->RenderData(UtilityController::SendResponse(200, $Data));
        endif;
    }

}