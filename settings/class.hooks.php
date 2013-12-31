<?php if (!defined('APPLICATION')) exit;

/**
 * API hooks for hooking into Garden and its applications
 *
 * The API hooks handles hooking into different events throughout Garden and its
 * applications. More specifically, this class hooks into the dispatcher to
 * handle API request mapping and also hooks the dashboard settings controller
 * to render the Application Interface settings menu.
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class APIHooks implements Gdn_IPlugin
{
    /// Methods ///

    /**
     * Code to be run upon enabling the API
     *
     * @since  0.1.0
     * @access public
     */
    public function Setup()
    {
        if (!C('API.Secret')) {
            SaveToConfig('API.Secret', APIEngine::GenerateUniqueID());
        }

        // Empty fallback array
        $ApplicationInfo = array();

        // Load the API application info
        include PATH_APPLICATIONS . DS . 'api/settings/about.php';

        $APIInfo = val('API', $ApplicationInfo, array());
        $Version = val('Version', $APIInfo, 'Undefined');

        SaveToConfig('API.Version', $Version);
    }

    /// Event Handlers ///

    /**
     * Map an API request to a resource
     *
     * @since  0.1.0
     * @access public
     */
    public function Gdn_Dispatcher_BeforeDispatch_Handler()
    {
        $Request = Gdn::Request();
        $Path    = APIEngine::TranslateRequestToPath($Request);

        // Set the call and resource paths if they exist
        $Call     = val(0, $Path);
        $Resource = val(1, $Path);

        // Abandon the dispatch if this isn't an API call with a valid resource
        if ($Call != 'api' || !$Resource) return;

        APIEngine::SetHeaders($Request);

        try {
            // Attempt dispatching the API request
            APIEngine::DispatchRequest($Request);
        } catch (Exception $Exception) {
            // The Exception method will need a code and a message
            // As we can't pass an object to WithControllerMethod(), we extract
            // the values manually before passing them on.
            $Code    = $Exception->getCode();
            $Message = $Exception->getMessage();

            // Call the Exception method if an exception is thrown
            $Request->WithControllerMethod('API', 'Exception', array($Code, $Message));
        }
    }

    /**
     * Render the settings menu in the dashboard
     *
     * This function sets up and renders a settings page where the API
     * configuration can be changed.
     *
     * @since  0.1.0
     * @access public
     * @param  SettingsController $Sender
     */
    public function SettingsController_API_Create($Sender)
    {
        $Sender->Permission('Garden.Settings.Manage');

        if ($Sender->Form->AuthenticatedPostBack()) {
            $Secret = C('API.Secret');
            $Regen  = $Sender->Form->ButtonExists('Re-generate');

            if ($Regen) $Secret = APIEngine::GenerateUniqueID();

            $Save = array();
            $Save['API.Secret'] = $Secret;

            if ($Sender->Form->ErrorCount() == 0) {
                SaveToConfig($Save);

                if ($Regen) {
                    $Sender->InformMessage(
                        '<span class="InformSprite Refresh"></span>'
                        . T("Refresh the page to see the new Application Secret."),
                        'Dismissable HasSprite'
                    );
                }
            }
        } else {
            $Data = array();
            $Data['Secret'] = C('API.Secret');
            $Sender->Form->SetData($Data);
        }

        $Sender->AddSideMenu();
        $Sender->SetData('Title', T("Application Interface"));
        $Sender->Render('API', 'settings', 'api');
    }

    /**
     * Renders menu link in the dashboard sidebar
     *
     * @since  0.1.0
     * @access public
     * @param  Gdn_Controller $Sender
     */
    public function Base_GetAppSettingsMenuItems_Handler($Sender)
    {
        $Menu = $Sender->EventArguments['SideMenu'];
        $Menu->AddLink('Site Settings', T("Application Interface"),
            'dashboard/settings/api', 'Garden.Settings.Manage'
        );
    }
}
