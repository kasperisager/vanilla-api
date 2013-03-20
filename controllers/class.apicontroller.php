<?php if (!defined('APPLICATION')) exit();

require_once 'classes/class.utility.php';
require_once 'classes/class.request.php';

require_once 'classes/apis/class.session.php';
require_once 'classes/apis/class.categories.php';

/**
 * The main API controller
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class APIController extends Gdn_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Initialize()
    {
        parent::Initialize();
        self::Deliver();
    }

    public function Session()
    {
        Session::API();
    }

    public function Categories($CategoryID)
    {
        Categories::API($CategoryID);
    }

    protected function Deliver()
    {
        // Set default delivery type and method
        $this->DeliveryType(DELIVERY_TYPE_DATA);
        $this->DeliveryMethod(DELIVERY_METHOD_JSON);
        $this->SetHeader('Content-Type', 'application/xml; charset=utf-8');

        // Two data types are supported: JSON and XML
        // Allow access to these via query strings too
        $Query = strtolower(GetIncomingValue('alt'));
        $Accept = Utility::ProcessRequest()->HttpAccept;

        // Only serve XML if specifically requested to
        if ($Accept == 'application/xml' || $Accept != 'application/json' && $Query == 'xml') {
            $this->DeliveryMethod(DELIVERY_METHOD_XML);
            $this->SetHeader('Content-Type', 'application/xml; charset=utf-8');
        }
    }

}