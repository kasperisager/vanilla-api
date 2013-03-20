<?php if (!defined('APPLICATION')) exit();

/**
 * REST Utilities
 *
 * @package     API
 * @since       0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class Utility
{
    /**
     * A little voodoo to turn objects into arrays
     * 
     * @param   object $Data
     * @since   0.1.0
     * @access  public
     */
    public static function Sanitize($Data)
    {
        $Data = json_encode($Data);
        $Data = json_decode($Data, true);
        return $Data;
    }

    public function SetError($Status = 400, $Exception = NULL)
    {
        $this->SetData('Code', $Status);
        $this->SetData('Exception', T($Exception));
    }

    public static function ProcessRequest()
    {
        // get our verb
        $RequestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $ReturnObj     = new Request();
        // we'll store our data here  
        $Data          = array();
      
        switch ($RequestMethod) {

            case 'get':
                $Data = $_GET;
                break;

            case 'post':
                $Data = $_POST;
                break;

        }
  
        // store the method  
        $ReturnObj->SetMethod($RequestMethod);
  
        // set the raw data, so we can access it if needed (there may be  
        // other pieces to your requests)
        $ReturnObj->SetRequestVars($Data);
  
        if(isset($Data['Data'])) {
            // translate the JSON to an Object for use however you want  
            $ReturnObj->SetData(json_decode($Data['Data']));
        }
        
        return $ReturnObj;

    }

    public static function ParsePut()
    {
        // Fetch PUT content and determine Boundary
        $RawData = file_get_contents('php://input');
        $Boundary = substr($RawData, 0, strpos($RawData, "\r\n"));

        // Fetch each part
        $Parts = array_slice(explode($Boundary, $RawData), 1);
        $PutData = array();

        foreach ($Parts as $Part) {
            // If this is the last part, break
            if ($Part == "--\r\n") break; 

            // Separate content from headers
            $Part = ltrim($Part, "\r\n");
            list($RawHeaders, $PutBody) = explode("\r\n\r\n", $Part, 2);

            // Parse the headers list
            $RawHeaders = explode("\r\n", $RawHeaders);
            $headers = array();
            foreach ($RawHeaders as $header) {
                list($Name, $Value) = explode(':', $header);
                $headers[strtolower($Name)] = ltrim($Value, ' '); 
            } 

            // Parse the Content-Disposition to get the field name, etc.
            if (isset($headers['content-disposition'])) {
                $filename = null;
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', 
                    $headers['content-disposition'], 
                    $Matches
                );
                list(, $Type, $Name) = $Matches;
                isset($Matches[4]) and $filename = $Matches[4]; 

                // handle your fields here
                switch ($Name) {
                    // this is a file upload
                    case 'userfile':
                         file_put_contents($filename, $PutBody);
                         break;

                    // default for all other files is to populate $PutData
                    default: 
                         $PutData[$Name] = substr($PutBody, 0, strlen($PutBody) - 2);
                         break;
                } 
            }

        }

        return $PutData;
    }

    public static function SendResponse($Status = 200, $Data = NULL)
    {
        $StatusHeader = 'HTTP/1.1 ' . $Status . ' ' . self::GetStatusCodeMessage($Status);

        // set the status
        header($StatusHeader);
      
        // resources with data are easy  
        if($Data != '') {  
            // send the data  
            return self::Sanitize($Data);
            exit;
        } else {
            // create some body messages
            $Message = '';
      
            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch($Status):

                case 401:
                    $Message = 'You must be authorized to view this page.';  
                    break;
                case 404:  
                    $Message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';  
                    break;
                case 500:  
                    $Message = 'The server encountered an error processing your request.';  
                    break;
                case 501:  
                    $Message = 'The requested method is not implemented.';  
                    break;
            
            endswitch;

            return $Message;

            exit;

        } 
    }

    public static function GetStatusCodeMessage($Status) {

        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $Codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        return (isset($Codes[$Status])) ? $Codes[$Status] : NULL;

    }
}