<?php if (!defined('APPLICATION')) exit();

use Swagger\Swagger;

/**
 * Vanilla API Main controller
 *
 * The Vanilla API lets you interface with Vanilla in a RESTlike way
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
    * Allow registration of docs
    * 
    * @var     array
    * @since   0.1.0
    * @access  public
    */
   public $Register = array();

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
    * Initialize the API Explorer if needed
    * 
    * @since   0.1.0
    * @access  public
    */
   public function Initialize()
   {
      parent::Initialize();

      if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {

         // Build the head asset
         $this->Head = new HeadModule($this);

         // Swagger UI
         $this->AddJsFile('jquery-1.8.0.min.js');
         $this->AddJsFile('jquery.slideto.min.js');
         $this->AddJsFile('jquery.slideto.min.js');
         $this->AddJsFile('jquery.wiggle.min.js');
         $this->AddJsFile('jquery.ba-bbq.min.js');
         $this->AddJsFile('handlebars-1.0.rc.1.js');
         $this->AddJsFile('underscore-min.js');
         $this->AddJsFile('backbone-min.js');
         $this->AddJsFile('swagger.js');
         $this->AddJsFile('highlight.7.3.pack.js');
         $this->AddJsFile('swagger-ui.js');

         // Bootstrap
         $this->AddJsFile('bootstrap-dropdown.js');

         // Default stylesheet
         $this->AddCssFile('api.css');

      }
   }

   /**
    * Information about the API
    *
    * This info array is included in all API resources as to enable Swagger
    * to crawl and list the entire API.
    * 
    * @return  array
    * @since   0.1.0
    * @access  public
    */
   public function Meta()
   {
      $Meta = array();
      $Meta['apiVersion']     = '0.1.0';
      $Meta['swaggerVersion'] = '1.1';
      $Meta['basePath']       = Gdn::Request()->Domain() . '/api';
      
      return $Meta;
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

         // Set page title
         $this->Title(T('API Documentation'));

         // Vanilla 2.1 goodie
         if (method_exists('Gdn_Theme', 'Section')) {
            Gdn_Theme::Section('APIDocumentation');
         }

         $Breadcrumbs = array();
         $Breadcrumbs['Name'] = T('API Documentation');
         $Breadcrumbs['Url']  = '/api';

         $this->Menu->HighlightRoute('/api');
         $this->SetData('Breadcrumbs', array($Breadcrumbs));

      }

      $this->MasterView = 'api';
      $this->Render();
   }

   /**
    * Usage and development documentation from the Github wiki
    * 
    * @since   0.1.0
    * @param   string $Wiki
    * @access  public
    */
   public function Wiki($Wiki = NULL)
   {
      $RemoteData = 'https://github.com/kasperisager/VanillaAPI/wiki.atom';
      $CacheData  = PATH_CACHE.'/xml/api_wiki_entries.xml';

      if (!is_dir(PATH_CACHE.'/xml')) mkdir(PATH_CACHE.'/xml');

      if (!file_exists($CacheData) || time() - filemtime($CacheData) >= 3600) {
         file_put_contents($CacheData, file_get_contents($RemoteData));
      }

      $Data = file_get_contents($CacheData);
      $Data = self::Sanitize(simplexml_load_string($Data));

      // Regex for matching relative images and links
      $Href = "#(<\s*a\s+[^>]*href\s*=\s*[\"'])(?!http)([^\"'>]+)([\"'>]+)#";
      $Src  = "#(<\s*img\s+[^>]*src\s*=\s*[\"'])(?!http)([^\"'>]+)([\"'>]+)#";

      $Entries = array();

      foreach ($Data['entry'] as $Entry) {

         $Title   = Gdn_Format::Clean($Entry['title']);
         $Content = Gdn_Format::Raw($Entry['content']);
         $Updated = Gdn_Format::FuzzyTime($Entry['updated']);

         $Content = preg_replace($Href, '$1https://github.com$2$3', $Content);
         $Content = preg_replace($Src, '$1https://github.com$2$3', $Content);

         $Page = array();
         $Page['Title']    = ucfirst($Title);
         $Page['Content']  = $Content;
         $Page['Updated']  = $Updated;

         $Entries[$Title] = $Page;

      }

      if ($Wiki) {
         $this->SetData('Entry', $Entries[$Wiki]);
      } else {
         $this->SetData('Entry', $Entries['home']);
      }

      // $this->SetData('Sidebar', $Entries['sidebar']['Content']);
      // $this->SetData('Footer', $Entries['footer']['Content']);

      if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {

         // Set page title
         $this->Title(T('API Wiki'));

         // Vanilla 2.1 goodie
         if (method_exists('Gdn_Theme', 'Section')) {
            Gdn_Theme::Section('APIWiki');
         }

         $Breadcrumbs = array();
         $Breadcrumbs['Name'] = T('API Wiki');
         $Breadcrumbs['Url']  = '/api/wiki';

         $this->Menu->HighlightRoute('/api');
         $this->SetData('Breadcrumbs', array($Breadcrumbs));
         
      }

      $this->MasterView = 'api';
      $this->Render();
   }

   /**
    * Vanilla API resource method
    *
    * Resource method used to get information about each information about
    * each controller and output this as JSON for Swagger UI to read
    * 
    * @since   0.1.0
    * @param   string $Resource
    * @access  public
    */
   public function Resources($Resource = NULL)
   {
      $this->DeliveryType(DELIVERY_TYPE_DATA);
      $this->DeliveryMethod(DELIVERY_METHOD_JSON);

      try {

         // Automatic API docs discovery
         if ($Resource) {

            $Class = $Resource . 'API';

            // If a resource doesn't exist throw a "Not Found"
            if (!class_exists($Class)) throw new Exception(404);

            $Swagger = new Swagger();
            $Class   = new $Class;

            $Docs = new ReflectionClass($Class);
            $Docs = dirname($Docs->getFilename());

            $Discover = $Swagger->discover($Docs);
            $Registry = $Discover->registry;

            $this->SetData(self::Meta());
            $this->SetData($Registry['/'.$Resource]);

         } else {

            $Registry = $this->Register;

            // Register core API docs
            $Registry['Session']       = '/session';
            $Registry['Configuration'] = '/configuration';
            $Registry['Categories']    = '/categories';
            $Registry['Discussions']   = '/discussions';
            $Registry['Messages']      = '/messages';
            $Registry['Users']         = '/users';

            // Allow plugins and applications to register docs
            $this->FireEvent('Register');

            $Listing = array();

            foreach ($Registry as $Description => $Path) {
               $Resource = array();
               $Resource['path'] = '/resources' . $Path;
               $Resource['description'] = $Description;
               $Listing[] = $Resource;
            }

            $this->SetData(self::Meta());
            $this->SetData('apis', $Listing);

         }         

      } catch (Exception $Exception) {
         $Code = intval($Exception->getMessage());
         $Message = Gdn_Controller::StatusCode($Code);
         $this->SetData('Code', $Code);
         $this->SetData('Exception', $Message);
      }

      $this->RenderData();
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
      $Request    = Gdn::Request();
      $URI        = $Request->RequestURI();
      $Intercept  = preg_match('/^api\/(\w+)/i', $URI, $Matches);

      try {

         // Intercept API requests and store the requested class
         if ($Intercept && $Matches) {

            $Class = $Matches[1] . 'API';

            // Abandon dispatch if resources or wiki method is requested
            if (strtolower($Class) == 'resources' . 'api') return;
            if (strtolower($Class) == 'wiki' . 'api') return;

            // If no API class is found throw a "Not Found"
            if (!class_exists($Class)) throw new Exception(404);
            
            $Class = new $Class;

            $Method        = $Request->RequestMethod();
            $Environment   = $Request->Export('Environment');
            $Arguments     = $Request->Export('Arguments');
            $Parsed        = $Request->Export('Parsed');

            // Only deliver data - nothing else is needed
            $Request->WithDeliveryType(DELIVERY_TYPE_DATA);

            // Change response format depending on HTTP_ACCEPT
            $Accept  = $Arguments['server']['HTTP_ACCEPT'];
            $Ext     = (strpos($Accept, 'json')) ? 'json' : 'xml';

            // Handle reponse format using WithDeliveryMethod() if it exists
            if (method_exists($Request, 'WithDeliveryMethod')) {

               switch ($Ext) {
                  case 'xml':
                     $Request->WithDeliveryMethod(DELIVERY_METHOD_XML);
                     break;

                  case 'json':
                     $Request->WithDeliveryMethod(DELIVERY_METHOD_JSON);
                     break;
               }

            }

            $Params = array();
            $Params['Environment']  = $Environment;
            $Params['Arguments']    = $Arguments;
            $Params['Parsed']       = $Parsed;
            $Params['Ext']          = $Ext;
            $Params['URI']          = explode('/', $URI);

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
                  if (isset($Data['Args'])) {
                     $Request->SetRequestArguments(
                        Gdn_Request::INPUT_POST, array_merge(
                           $_PUT, $Data['Args']
                        )
                     );
                  } else {
                     $Request->SetRequestArguments(
                        Gdn_Request::INPUT_POST, $_PUT
                     );
                  }

                  $_POST = $Request->Post();

                  break;

               case 'delete':
                  $Data = $Class->Delete($Params);

                  // Garden can't handle DELETE requests by default, so trick
                  // it into thinking that this is actually a POST
                  $Request->RequestMethod('post');

                  // Combine the DELETE request with any custom arguments
                  if (isset($Data['Args'])) {
                     $Request->SetRequestArguments(
                        Gdn_Request::INPUT_POST, $Data['Args']
                     );
                  }

                  $_POST = $Request->Post();

                  break;

            }

            // If data returns a numeric value throw it as an error
            if (is_numeric($Data)) throw new Exception($Data);

            // Throw generic error if no map is defined
            if (!isset($Data['Map'])) throw new Exception(500);  
            
            // Map the request to the specified URI
            $Request->WithURI($Data['Map']);
         }

      } catch (Exception $Exception) {
         $Exception = $Exception->getMessage();
         $Request->WithControllerMethod('API', 'Error', array($Exception));
      }
   }

   /**
    * Method for handling errors
    * 
    * @param   string|int $Exception
    * @return  array
    */
   public function Error($Exception)
   {
      $Request    = Gdn::Request();
      $Arguments  = $Request->Export('Arguments');

      // Only deliver data - nothing else is needed
      $this->DeliveryType(DELIVERY_TYPE_DATA);

      // Change response format depending on HTTP_ACCEPT
      $Accept  = $Arguments['server']['HTTP_ACCEPT'];
      $Ext     = (strpos($Accept, 'json')) ? 'json' : 'xml';

      switch ($Ext) {
         case 'xml':
            $this->DeliveryMethod(DELIVERY_METHOD_XML);
            break;

         case 'json':
            $this->DeliveryMethod(DELIVERY_METHOD_JSON);
            break;
      }

      if (is_numeric($Exception)) {
         $Code = $Exception;
         $Exception = Gdn_Controller::StatusCode($Exception);
         $this->SetData('Code', intval($Code));
      }

      $this->SetData('Exception', $Exception);
      $this->RenderData();
   }

   /**
    * Parse and return PUT data
    *
    * @author  netcoder <http://stackexchange.com/users/229735>
    * @package API
    * @since   0.1.0
    * @access  public
    * @return  array
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

   /**
    * A little voodoo to turn objects into arrays
    * 
    * @param   object $Data
    * @since   0.1.0
    * @access  public
    * @return  array
    */
   public function Sanitize($Data)
   {
      $Data = json_encode($Data);
      $Data = json_decode($Data, true);
      return $Data;
   }
}