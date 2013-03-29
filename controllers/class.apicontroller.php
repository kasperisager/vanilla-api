<?php if (!defined('APPLICATION')) exit();

/**
 * Vanilla API Main controller
 *
 * The Vanilla API lets you interface with Vanilla in a REST-like way
 * using the standard HTTP verbs GET, POST, PUT and DELETE.
 *
 * @package    API
 * @version    0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 */

/**
 * Main API controller
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 */
class APIController extends Gdn_Controller
{
   /**
    * Do-nothing construct to let children constructs bubble up.
    *
    * @access public
    */
   public function __construct()
   {
      parent::__construct();
   }

   /**
    * Do-nothing initialize to let children initializers bubble up.
    * 
    * @since   0.1.0
    * @access  public
    */
   public function Initialize()
   {
      parent::Initialize();
   }

   /**
    * API documentation and visualization using Swagger
    * 
    * @since   0.1.0
    * @access  public
    */
   public function Index()
   {
      if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {

         // Build the head asset
         $this->Head = new HeadModule($this);
         $this->Title(T('API Documentation'));

         // Vanilla 2.1 goodie
         if (method_exists('Gdn_Theme', 'Section')) {
            Gdn_Theme::Section('ApiDocumentation');
         }

         $this->Menu->HighlightRoute('/api');
         $this->SetData('Breadcrumbs',array(
            array(
               'Name' => T('API Documentation'),
               'Url' => '/api')
            )
         );

         // Documentation resources
         $Dist = 'applications/api/node_modules/swagger-ui/dist';

         $this->AddJsFile($Dist . '/lib/jquery-1.8.0.min.js');
         $this->AddJsFile($Dist . '/lib/jquery.slideto.min.js');
         $this->AddJsFile($Dist . '/lib/jquery.slideto.min.js');
         $this->AddJsFile($Dist . '/lib/jquery.wiggle.min.js');
         $this->AddJsFile($Dist . '/lib/jquery.ba-bbq.min.js');
         $this->AddJsFile($Dist . '/lib/handlebars-1.0.rc.1.js');
         $this->AddJsFile($Dist . '/lib/underscore-min.js');
         $this->AddJsFile($Dist . '/lib/backbone-min.js');
         $this->AddJsFile($Dist . '/lib/swagger.js');
         $this->AddJsFile($Dist . '/lib/highlight.7.3.pack.js');
         $this->AddJsFile($Dist . '/swagger-ui.js');

         $this->AddCssFile($Dist . '/css/hightlight.default.css');

         $this->AddCssFile('api.css');

      }

      $this->MasterView = 'api';
      $this->Render();
   }

   /**
    * Map the API request to the appropriate controller
    *
    * @package API
    * @since   0.1.0
    * @access  public
    */
   public function _Dispatch()
   {
      $Request      = Gdn::Request();
      $URI         = $Request->RequestURI();

      // Intercept API requests and store the requested class
      if (preg_match('/^api\/(\w+)/i', $URI, $Matches)) {

         $Class = $Matches[1].'API';

          if (!$Class == NULL && class_exists($Class)) {
            $Class = new $Class;
         } else {
            return;
         }

         $Method       = $Request->RequestMethod();
         $Environment   = $Request->Export('Environment');
         $Arguments     = $Request->Export('Arguments');
         $Parsed       = $Request->Export('Parsed');

         // Only deliver data - nothing else is needed
         $Request->WithDeliveryType(DELIVERY_TYPE_DATA);
         $Request->OutputFormat('json');

         // Change response format depending on HTTP_ACCEPT
         $Accept = $Arguments['server']['HTTP_ACCEPT'];
         $Format = (strpos($Accept, 'json')) ? 'json' : 'xml';

         switch ($Format) {
            case 'xml':
               $Request->WithDeliveryMethod(DELIVERY_METHOD_XML);
               break;

            case 'json':
               $Request->WithDeliveryMethod(DELIVERY_METHOD_JSON);
               break;
         }

         $Params = array(
            'Environment'   => $Environment,
            'Arguments'    => $Arguments,
            'Parsed'      => $Parsed,
            'Format'      => $Format,
            'URI'         => explode('/', $URI)
         );

         switch(strtolower($Method)) {

            case 'get':
               $Data = $Class->Get($Params);
               break;

            case 'post':
               $Data = $Class->Post($Params);
               break;

            case 'put':
               $Data = $Class->Put($Params);

               // Garden can't handle PUT requests by default, so trick
               // it into thinking that this is actually a POST
               $Request->RequestMethod('post');

               $_PUT = self::ParsePut();

               // Combine the PUT request with any custom arguments
               $Request->SetRequestArguments(
                  Gdn_Request::INPUT_POST, array_merge(
                     $_PUT,
                     $Data['Args']
                  )
               );

               $_POST = $Request->Post();

               break;

            case 'delete':
               $Data = $Class->Delete($Params);

               // Garden can't handle DELETE requests by default, so trick
               // it into thinking that this is actually a POST
               $Request->RequestMethod('post');

               // Combine the DELETE request with any custom arguments
               $Request->SetRequestArguments(
                  Gdn_Request::INPUT_POST, $Data['Args']
               );

               $_POST = $Request->Post();

               break;

         }

         if ($Data['Map']) {
            $Request->WithURI($Data['Map']);
         }
      }
   }

   /**
    * Parse and return PUT data
    *
    * @author  netcoder <http://stackexchange.com/users/229735>
    * @package API
    * @since   0.1.0
    * @access  public
    */
   public static function ParsePut()
   {
      // Fetch PUT content and determine Boundary
      $RawData = file_get_contents('php://input');
      $Boundary = substr($RawData, 0, strpos($RawData, "\r\n"));

      if(empty($Boundary)){
         parse_str($RawData, $Data);
         return $Data;
      }

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
                   $PutData[$Name] = substr(
                     $PutBody, 0, strlen($PutBody) - 2
                  );
                   break;
            } 
         }

      }

      return $PutData;
   }
}