<?php if (!defined('APPLICATION')) exit();

/**
 * To be written
 *
 * @package API
 * @since 0.1.0
 * @author Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright © 2013
 * @license http://opensource.org/licenses/MIT MIT
 */
class UsersController extends APIController
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
    public function Index()
    {
        
        $Request = UtilityController::ProcessRequest();  
  
        switch($Request->Method):

            case 'get':  
                
                self::Get();

                break;

        endswitch;

    }

    /**
     * To be written
     *
     * @param   string $Resource
     * @since   0.1.0
     * @access  public
     */
    public function Get()
    {

    }

}