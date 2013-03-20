<?php if (!defined('APPLICATION')) exit();

/**
 * REST Request handler
 *
 * @package     API
 * @since       0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class Request
{
    public $Data;
    public $RequestVars;
    public $HttpAccept;
    public $Method;

    public function __construct()
    {
        $this->RequestVars      = array();
        $this->Data             = '';
        $this->Method           = 'get';
        $this->HttpAccept       = $_SERVER['HTTP_ACCEPT'];
    }

    public function SetData($Data)
    {
        $this->Data = $Data;
    }

    public function SetMethod($Method)
    {
        $this->Method = $Method;
    }

    public function SetRequestVars($RequestVars)
    {
        $this->RequestVars = $RequestVars;
    }

    public function GetData()
    {
        return $this->Data;
    }

    public function GetMethod()
    {
        return $this->Method;
    }

    public function GetHttpAccept()
    {
        return $this->HttpAccept;
    }

    public function GetRequestVars()
    {
        return $this->RequestVars;
    }
}