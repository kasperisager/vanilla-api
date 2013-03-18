<?php if (!defined('APPLICATION')) exit();

/**
 * SSLControllers Plugin
 *
 * @author Derek Donnelly <derek@derekdonnelly.com>
 */
class SSLControllers implements Gdn_IPlugin {
    
    // Class defaults
    protected $_SSLSupport = TRUE;
    protected $_SecureControllers = array('APIController'); // Default controllers to secure at setup
    protected $_SecureSession = FALSE;
    protected $_UsePopups = FALSE;
    protected $_ProtocolWebRoot = '';
    
    const HTTP_PROTOCOL  = 'http://';
    const HTTPS_PROTOCOL = 'https://';
    
    /**
     * Base_Render_Before
     *
     * @param object $Sender
     */
    public function Base_Render_Before(&$Sender) {
        
        // Don't proceed if the sender is Leaving
        if((Isset($Sender->Leaving) && ($Sender->Leaving))) return;
        
        // Get the SSL support & popup config settings
        $SSLSupport = Gdn::Config('Garden.SSL', $this->_SSLSupport);
        $UsePopups  = Gdn::Config('Garden.UsePopups', $this->_UsePopups);
        
        // Add protocol webroot & use popups definitions regardless
        $Sender->AddDefinition('WebRoot', $this->_WebRoot());
        $Sender->AddDefinition('UsePopups', $UsePopups);
        
        // Set the authenticator protocol regardless (Always use SSL if available?)
        $this->_SetAuthenticatorProtocol(($SSLSupport) ? self::HTTPS_PROTOCOL : self::HTTP_PROTOCOL);
        
        // Get url info
        $URLSecure = $this->_URLSecure();
        
        // Exit if we don't have SSL support and if the page is not running on https
        if(!$SSLSupport && !$URLSecure) return;
        
        // Set variables
        $Session = Gdn::Session();
        $ControllerName = $Sender->ControllerName;
        $RequestMethod = $Sender->RequestMethod;
        $SControllers = Gdn::Config('Garden.SecureControllers', $this->_SecureControllers);
        $SSession = Gdn::Config('Garden.SecureSession', $this->_SecureSession);
        $pageURL = $this->_PageURL();
        
        // Add a small jQuery helper to expose the protocol WebRoot to js calls (Might be useful) and override default popup behaviour
        $Sender->AddJsFile('plugins/SSLControllers/sslcontrollerhelper.js');
        
        // Check if the sender is a secure controller or if we should secure the session
        if(($SSLSupport) && (in_array($ControllerName, $SControllers) || ($SSession && $Session->IsValid()))) {
            
            // Check if the controller has a form and that it is not posting back
            if(Isset($Sender->Form) && ($Sender->Form->IsPostBack() !== TRUE)) {
                
                // Check if the current connection is secure
                if(!$URLSecure) {
                    
                    // Get the secure page url and update the RedirectUrl if set to the original
                    $NewPageURL = $this->_GetUrlProtocol($pageURL, self::HTTPS_PROTOCOL);
                    if($Sender->RedirectUrl == $pageURL) $Sender->RedirectUrl = $NewPageURL;
                    
                    // Redirect back to controller using the secure url
                    Redirect($NewPageURL);
                }
            }
        }
        else { // Unsecure controller
            
            // Make sure the controller is not on/still using a secure connection
            if($URLSecure) {
                
                // Get the unsecure page url and update the RedirectUrl if set to the original
                $NewPageURL = $this->_GetUrlProtocol($pageURL, self::HTTP_PROTOCOL);
                if($Sender->RedirectUrl == $pageURL) $Sender->RedirectUrl = $NewPageURL;
                
                // Redirect back to controller using the unsecure url
                Redirect($NewPageURL);
            }
        }        
    }
    
    /**
     * Retrieve page url
     *
     * @param boolean $AddPort
     * @return string
     */
    protected function _PageURL($AddPort = FALSE) {
        $URL = 'http';
        if((Isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")) 
            $URL .= "s";
        $URL .= "://";
        
        // Set the protocol web root for use elsewhere in the class
        $this->_ProtocolWebRoot = $URL . $_SERVER["SERVER_NAME"] . '/';
        
        // Adding the port will cause problems with redirects if used
        if(($AddPort) && (Isset($_SERVER["SERVER_PORT"])) && ($_SERVER["SERVER_PORT"] != "80")) 
            $URL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        else
            $URL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

        return $URL;
    }

    /**
     * Get url with protocol
     *
     * @param string $URL
     * @param string $Protocol
     * @return string
     */
    protected function _GetUrlProtocol($URL = '', $Protocol = '') {
        // Error trapping
        $URL = ($URL != '') ? $URL : $this->_PageURL();
        $Protocol = ($Protocol != '') ? $Protocol : self::HTTP_PROTOCOL;
        if($Protocol != self::HTTP_PROTOCOL && $Protocol != self::HTTPS_PROTOCOL) return;
        
        // Remove current protocol
        if(substr($URL, 0, 8) == self::HTTPS_PROTOCOL) $URL = substr($URL, 8);
        if(substr($URL, 0, 7) == self::HTTP_PROTOCOL) $URL = substr($URL, 7);
        
        return $Protocol . $URL;
    }
    
    /**
     * Check if a url is secure
     *
     * @param string $URL
     * @return boolean
     */
    protected function _URLSecure($URL = '') {
        $URL = ($URL != '') ? $URL : $this->_PageURL();
        return (substr($URL, 0, 8) == self::HTTPS_PROTOCOL) ? TRUE : FALSE;
    }

    /**
     * Get a valid protocol
     *
     * @param string $Protocol
     * @return string
     */
    protected function _GetValidProtocol($Protocol = '') {
        $Protocol = ($Protocol != '') ? $Protocol : self::HTTP_PROTOCOL;
        return (($Protocol != self::HTTP_PROTOCOL) && ($Protocol != self::HTTPS_PROTOCOL)) ? self::HTTP_PROTOCOL : $Protocol;
    }
    
    /**
     * Get the web root with the protocol included
     *
     * @return string
     */
    protected function _WebRoot() {
        if($this->_ProtocolWebRoot == '') $this->_PageURL();
        return $this->_ProtocolWebRoot;
    }
    
    /**
     * Set Authenticator Protocol
     *
     * @param string $Protocol
     */
    protected function _SetAuthenticatorProtocol($Protocol = '') {
        $Protocol = $this->_GetValidProtocol($Protocol);
        Gdn::Authenticator()->Protocol(rtrim($Protocol, '://'));
    }
    
    /**
     * Used by the javascript helper
     *
     * @param object $Sender
     */
    public function PluginController_GetWebRoot_Create(&$Sender) {
        echo json_encode(array("WebRoot" => $this->_WebRoot())); 
    }

    /**
     * Run Setup
     *
     */
    public function Setup() {
        
        // Get user defaults and if the server can support SSL
        $Domain       = Gdn::Config('Garden.Domain', '');
        $SSLSpecified = Gdn::Config('Garden.SSL', FALSE);
        $SControllers = Gdn::Config('Garden.SecureControllers', $this->_SecureControllers);
        $SSession     = Gdn::Config('Garden.SecureSession', $this->_SecureSession);
        $UsePopups    = Gdn::Config('Garden.UsePopups', $this->_UsePopups);
        
        // Any value other then FALSE is true
        if($SSLSpecified) $this->_SSLSupport = TRUE; 
        
        // Remove any protocol prefixes from the domain
        if(($Domain != '') && ($Domain !== FALSE))
        {
            if(substr($Domain, 0, 7) == self::HTTP_PROTOCOL)
                $Domain = substr($Domain, 7);
            else if(substr($Domain, 0, 8) == self::HTTPS_PROTOCOL)
                $Domain = substr($Domain, 8);
        }
        
        // Do a system check on SSL support
        // TODO: Find out if this is the correct/best way to do this
        if(!$this->_SSLSupport)
        {
            $SSLCheck = @fsockopen('ssl://' . $Domain, 443, $errno, $errstr, 30); // Not working for me at the moment? Might be my staging server. Error: Name or service not known?
            $this->_SSLSupport = ($SSLCheck) ? TRUE : FALSE;
            fclose($SSLCheck);
        }
                
        // Update the config
        $Config = Gdn::Factory(Gdn::AliasConfig);
        $Config->Load(PATH_CONF.DS.'config.php', 'Save');
        $Config->Set('Garden.SSL', $this->_SSLSupport, TRUE); // Override what the user specified if we know it to be different
        $Config->Set('Garden.SecureControllers', $SControllers, TRUE); // Override okay as we have the user value
        $Config->Set('Garden.SecureSession', $SSession, TRUE); // Override okay as we have user value
        $Config->Set('Garden.UsePopups', $UsePopups, FALSE); // Override okay as we have user value
        $Config->Set('Garden.Domain', $Domain, TRUE); // Override the current domain as we will need it protocol free
        $Config->Save();
    }
}