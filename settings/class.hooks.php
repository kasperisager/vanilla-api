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
    /* Methods */

    /**
     * Code to be run upon enabling the API
     *
     * @since  0.1.0
     * @access public
     * @return void
     */
    public function setup()
    {
        if (!c('API.Secret')) {
            saveToConfig('API.Secret', APIAuth::generateUniqueID());
        }

        // Empty fallback array
        $applicationInfo = array();

        // Load the API application info
        include paths(PATH_APPLICATIONS, 'api/settings/about.php');

        $info    = val('API', $applicationInfo, array());
        $version = val('Version', $info, 'Undefined');

        saveToConfig('API.Version', $version);
    }

    /* Event Handlers */

    /**
     * Map an API request to a resource
     *
     * @since  0.1.0
     * @access public
     * @return void
     */
    public function Gdn_Dispatcher_beforeDispatch_handler()
    {
        $path = APIEngine::getRequestURI();

        // Set the call and resource paths if they exist
        $call     = val(0, $path);
        $resource = val(1, $path);

        // Abandon the dispatch if this isn't an API call with a valid resource
        if ($call != 'api' || !$resource) return;

        APIEngine::setRequestHeaders();

        try {
            // Attempt dispatching the API request
            APIEngine::dispatchRequest();
        } catch (Exception $exception) {
            // As we can't pass an object to WithControllerMethod(), we extract
            // the values we need manually before passing them on. The exception
            // message is Base64 encoded as WithControllerMethod() mangles
            // the formatting.
            $code      = $exception->getCode();
            $message   = base64_encode($exception->getMessage());
            $arguments = array($code, $message);

            // Call the Exception method if an exception is thrown
            Gdn::request()->withControllerMethod('API', 'Exception', $arguments);
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
     * @param  SettingsController $sender
     * @return void
     */
    public function SettingsController_API_create($sender)
    {
        $sender->permission('Garden.Settings.Manage');

        $form = $sender->Form;

        if ($form->authenticatedPostBack()) {
            $secret = c('API.Secret');
            $regen  = $form->buttonExists(t('API.Settings.Refresh.Label'));

            if ($regen) $secret = APIAuth::generateUniqueID();

            $save = array();
            $save['API.Secret'] = $secret;

            if ($form->errorCount() == 0) {
                saveToConfig($save);

                if ($regen) {
                    $icon  = '<span class="InformSprite Refresh"></span>';
                    $text  = t('API.Settings.Refresh.Notification');
                    $class = 'Dismissable HasSprite';

                    $sender->informMessage($icon . $text, $class);
                }
            }
        } else {
            $data = array();
            $data['Secret'] = c('API.Secret');
            $form->setData($data);
        }

        $sender->addSideMenu();
        $sender->setData('Title', t('API.Settings.Title'));
        $sender->render('API', 'settings', 'api');
    }

    /**
     * Renders menu link in the dashboard sidebar
     *
     * @since  0.1.0
     * @access public
     * @param  Gdn_Controller $sender
     * @return void
     */
    public function Base_getAppSettingsMenuItems_handler($sender)
    {
        $menu = $sender->EventArguments['SideMenu'];
        $menu->addLink('Site Settings', t('API.Settings.Title'),
            'dashboard/settings/api', 'Garden.Settings.Manage'
        );
    }
}
