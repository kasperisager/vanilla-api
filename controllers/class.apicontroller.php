<?php if (!defined('APPLICATION')) exit();

/**
 * The main API controller
 *
 * @package API
 * @version 0.1.0
 * @author Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright © 2013
 * @license http://opensource.org/licenses/MIT MIT
 */
class APIController extends Gdn_Controller {

    /**
     * Do-nothing construct to let children constructs bubble up.
     *
     * @access public
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Serve the API data
     * 
     * @since 0.1.0
     * @access public
     */
    public function Initialize() {

        // Two data types are supported: JSON and XML
        $Alt = GetIncomingValue('alt', 'json');

        // Deliver the API data as JSON or XML
        $this->DeliveryType(DELIVERY_TYPE_DATA);
        if ($Alt == 'xml'):
            $this->DeliveryMethod(DELIVERY_METHOD_XML);
            $this->SetHeader('Content-Type', 'application/xml; charset=utf-8');
        else:
            $this->DeliveryMethod(DELIVERY_METHOD_JSON);
            $this->SetHeader('Content-Type', 'application/json; charset=utf-8');
        endif;

        $this->SetHeader('Host', Gdn::Request()->Domain().'/api');

        parent::Initialize();

    }

    /**
     * Information about the API
     *
     * This info array is included in all API controllers as to enable Swagger
     * to crawl and list the entire API.
     * 
     * @return array
     * @since 0.1.0
     * @access public
     */
    public function Meta() {

        return array(
            'apiVersion' => '0.1.0',
            'swaggerVersion' => '1.1',
            'basePath' => Gdn::Request()->Domain().'/api'
        );

    }

    /**
     * A little voodoo to turn objects into arrays
     * 
     * @param object $Data
     * @since 0.1.0
     * @access public
     */
    public function Sanitize($Data) {
        $Data = json_encode($Data);
        $Data = json_decode($Data, true);
        return $Data;
    }

    /**
     * API documentation and visualization using Swagger
     * 
     * @since 0.1.0
     * @access public
     */
    public function Index() {

        // Deliver docs as standard XHTML and not JSON
        $this->DeliveryType(DELIVERY_TYPE_ALL);
        $this->DeliveryMethod(DELIVERY_METHOD_XHTML);
        $this->SetHeader('Content-Type', 'text/html; charset=utf-8');

        if ($this->DeliveryType() == DELIVERY_TYPE_ALL):

            // Build the head asset
            $this->Head = new HeadModule($this);
            $this->Title(T('API Documentation'));

            /**
             * For later implementation in API v2
             *
             * Gdn_Theme::Section('ApiDocumentation');
             */

            $this->Menu->HighlightRoute('/api');
            $this->SetData('Breadcrumbs',array(
                array(
                    'Name' => T('API Documentation'),
                    'Url' => '/api')
                )
            );

            // General resources
            $this->AddJsFile('jquery.js');

            // Documentation resources
            $this->AddJsFile('jquery.slideto.min.js');
            $this->AddJsFile('jquery.wiggle.min.js');
            $this->AddJsFile('jquery.ba-bbq.min.js');
            $this->AddJsFile('handlebars.js');
            $this->AddJsFile('underscore-min.js');
            $this->AddJsFile('backbone-min.js');
            $this->AddJsFile('swagger.js');
            $this->AddJsFile('swagger-ui.js');
            $this->AddJsFile('highlight.js');

            $this->AddCssFile('screen.css');
            $this->AddCssFile('highlight.default.css');

        endif;

        $this->MasterView = 'api';
        $this->Render();

    }

}