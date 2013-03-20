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
class Session
{
    /**
     * To be written
     * 
     * @since   0.1.0
     * @access  public
     */
    public function API()
    {  
        $Request = Utility::ProcessRequest();
  
        switch($Request->Method) {

            case 'get':    
                self::_Get();
                break;

            default:
                $Code = 501;
                Utility::SetError($Code, 'Not Implemented');
                $this->RenderData(Utility::SendResponse($Code, $this->Data));
                break;

        }
    }

    /**
     * To be written
     * 
     * @since   0.1.0
     * @access  public
     */
    protected function _Get()
    {
        $Session = Gdn::Session();

        if (!Gdn::Session()->IsValid()) {
            $Code = 401;
            $this->SetData('Code', $Code);
            $this->SetData('Exception', T('Unauthorized'));
        } else {
            $Code = 200;
            $this->SetData('Session', $Session);
        }

        $this->RenderData(Utility::SendResponse($Code, $this->Data));
    }

}