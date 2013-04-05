<?php if (!defined('APPLICATION')) exit();

//if (C('Debug')) error_reporting(E_ALL);

use Swagger\Swagger;
use \Doctrine\Common\Cache\PhpFileCache;

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
    * @since   0.1.0
    * @access  public
    * @return  array
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
         $this->Title(T('Vanilla API'));

         // Vanilla 2.1 goodie - sections!
         if (method_exists('Gdn_Theme', 'Section')) {
            Gdn_Theme::Section('APIDocumentation');
         }

         $Breadcrumbs = array();
         $Breadcrumbs['Name'] = T('Vanilla API');
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
    * @access  public
    * @param   string $Wiki
    */
   public function Wiki($Wiki = NULL)
   {
      // Vanilla 2.1 goodie - sections!
      if (method_exists('Gdn_Theme', 'Section')) Gdn_Theme::Section('APIWiki');

      // Define the API Wiki remote feed and local cache
      $RemoteData = 'https://github.com/kasperisager/VanillaAPI/wiki.atom';
      $CacheData  = PATH_CACHE . '/VanillaAPI/wiki_entries.cache';

      // If the cache folder hasn't been create yet, create it
      if (!is_dir(PATH_CACHE . '/VanillaAPI')) mkdir(PATH_CACHE . '/VanillaAPI');

      // If no cache data is found or the cache data is older than 1 hour,
      // pull in the remote feed and cache it
      if (!file_exists($CacheData) || time() - filemtime($CacheData) >= 3600) {
         file_put_contents($CacheData, file_get_contents($RemoteData));
      }

      // Get the contents of the cache or use the remote feed if caching has
      // been turned off in the configuration
      $Data = file_get_contents(C('API.Wiki.Cache') ? $CacheData : $RemoteData);
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

         if (!isset($Entries[$Wiki])) return self::Error(404);
         
         $Entry = $Entries[$Wiki];

         $Title = $Entry['Title'];

         $Breadcrumbs = array();
         $Breadcrumbs['Name'] = T($Title);
         $Breadcrumbs['Url']  = '/api/wiki/' . $Wiki;

         $this->Title(T($Title));
         $this->SetData('Entry', $Entry);

      } else {

         $Breadcrumbs = array();
         $Breadcrumbs['Name'] = T('Wiki');
         $Breadcrumbs['Url']  = '/api/wiki';

         $this->Title(T('Wiki'));
         $this->SetData('Entry', $Entries['home']);

      }

      // $this->SetData('Sidebar', $Entries['sidebar']['Content']);
      // $this->SetData('Footer', $Entries['footer']['Content']);

      $this->SetData('Breadcrumbs', array(
         array('Name' => T('Vanilla API'), 'Url' => '/api'), $Breadcrumbs
      ));
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
    * @access  public
    * @param   string $Resource
    */
   public function Resources($Resource = NULL)
   {
      $this->DeliveryType(DELIVERY_TYPE_DATA);
      $this->DeliveryMethod(DELIVERY_METHOD_JSON);

      try {

         $Root       = PATH_ROOT;
         $Cache      = PATH_CACHE . DS . 'VanillaAPI';
         $Data       = array();
         $Data       = array_merge(self::Meta(), $Data);

         // Crawl the entire installation and cache all API documentation
         // unless caching of docs has been turned off
         if (C('API.Docs.Cache')) {
            $PhpFileCache = new PhpFileCache($Cache, '.cache');
            $Swagger = new \Swagger\Swagger($Root, NULL, $PhpFileCache);
         } else {
            $Swagger = Swagger::discover($Root);
         }

         if ($Resource) {

            // Get the cached registry
            $Registry = $Swagger->getRegistry();

            // Find the requested resource in the registry
            $Resource = $Registry['/' . $Resource]->apis;

            // If a resource doesn't exist throw a "Not Found"
            if (!$Resource) throw new Exception(404);

            $Data['apis']  = $Resource;
            $this->SetData($Data);

         } else {

            // Get the resource list
            $Resources = $Swagger->getResourceList(TRUE, FALSE);

            $Listing = array();

            foreach ($Resources['apis'] as $Api) {
               // Trim .{format} from resource path since we only want
               // to deliver JSON and not XML to Swagger UI
               $Path = str_replace('.{format}', NULL, $Api['path']);

               $Resource = array();
               $Resource['path'] = $Path;

               $Listing[] = $Resource;
            }

            $Data['apis']  = $Listing;
            $this->SetData($Data);

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
    * Expose the session object
    *
    * @since   0.1.0
    * @access  public
    */
   public function Session()
   {  
      $Request    = Gdn::Request();
      $Arguments  = $Request->Export('Arguments');

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

      $this->SetData('Session', self::Sanitize(Gdn::Session()));
      $this->RenderData();
   }

   public function Debug()
   {
      $Secret        = C('API.Secret');
      $Request       = Gdn::Request();
      $Request       = $Request->PathAndQuery();
      $ParsedURL     = parse_url($Request);
      parse_str($ParsedURL['query'], $Request);

      $Username   = $Request['username'];
      $Email      = $Request['email'];
      $Timestamp  = $Request['timestamp'];
      $Token      = $Request['token'];

      unset($Request['token']);

      $UserID     = self::GetUserID($Username, $Email);
      $Signature  = self::Signature($Request, $Secret);
      
      if ($Token == $Signature) {
         Gdn::Session()->Start(intval($UserID), FALSE);
      }

      $Data                = array();
      $Data['Username']    = $Username;
      $Data['Email']       = $Email;
      $Data['UserID']      = $UserID;
      $Data['Timestamp']   = $Timestamp;
      $Data['Token']       = $Token;
      $Data['Signature']   = $Signature;
      $Data['Request']     = $Request;
      $Data['Session']     = self::Sanitize(Gdn::Session());

      $this->SetData($Data);
      $this->RenderData();
   }

   /**
    * A little voodoo to turn objects into arrays
    *
    * @since   0.1.0
    * @access  public
    * @param   object $Data
    * @return  array
    */
   public static function Sanitize($Data)
   {
      $Data = json_encode($Data);
      $Data = json_decode($Data, true);
      return $Data;
   }

   /**
    * Very simple "single-use only" authentication
    *
    * @since   0.1.0
    * @access  public
    */
   protected static function Authenticate()
   {
      try {

         $Request       = Gdn::Request();
         $Request       = $Request->PathAndQuery();
         $ParsedURL     = parse_url($Request);

         parse_str($ParsedURL['query'], $Request);

         /*if (!isset($Request['username'])
            || !isset($Request['email'])) {
            throw new Exception('Username or email must be specified', 401);
         }

         if (!isset($Request['timestamp'])
            || (abs($Request['timestamp'] - time())) > C('API.Timeout')) {
               throw new Exception('Timeout expired', 401);
         }*/

         /*if (!isset($Request['token'])
            || $Request['token'] != self::Signature($Request, $Secret)) {
            throw new Exception('Signature invalid', 401);
         }*/

         $Username      = $Request['username'];
         $Email         = $Request['email'];
         $Timestamp     = $Request['timestamp'];
         $Token         = $Request['token'];

         unset($Request['token']);

         $UserID        = self::GetUserID($Username, $Email);
         $Signature     = self::Signature($Request);
         
         if ($Token == $Signature) Gdn::Session()->Start(intval($UserID), FALSE);

      } catch (Exception $Exception) {
         return;
      }
   }

   /**
    * Generate a signature from a request array
    *
    * This function takes an array of data, sorts the keys alphabetically and
    * generates an HMAC hash using a specified application secret. The hash
    * can then be used to validate incoming API calls as only the client and
    * server knows the secret key used for creating the hash.
    *
    * @since   0.1.0
    * @param   array $Request
    * @return  string
    */
   protected static function Signature($Request)
   {
      $Secret = C('API.Secret');

      ksort($Request, SORT_STRING);

      $Signature = hash_hmac('sha256', strtolower(implode('-', $Request)), $Secret);

      return $Signature;
   }

   protected static function GetUserID($Username, $Email)
   {
      $UserModel = new UserModel();

      if(isset($UserName)) return $UserModel->GetByUsername($UserName)->UserID;

      if(isset($Email))    return $UserModel->GetByEmail($Email)->UserID;

      return;
   }

   /**
    * Map the API request to the appropriate controller
    *
    * @since   0.1.0
    * @access  public
    */
   public function _Dispatch()
   {
      $Request    = Gdn::Request();
      $URI        = $Request->RequestURI();

      // Intercept any request with the following format: /api/:resource
      $Intercept  = preg_match('/^api\/(\w+)/i', $URI, $Matches);

      try {

         // Intercept API requests and store the requested class
         if ($Intercept && $Matches) {

            $Class = $Matches[1] . 'API';

            // Abandon dispatch if any of these methods are requested
            $Methods = array('resources', 'wiki', 'session', 'debug');
            foreach ($Methods as $Method) {
               if (strtolower($Class) == $Method . 'api') return;
            }

            // If no API class is found throw a "Not Found"
            if (!class_exists($Class)) throw new Exception(404);
            
            $Class         = new $Class;
            $Method        = $Request->RequestMethod();
            $Environment   = $Request->Export('Environment');
            $Arguments     = $Request->Export('Arguments');
            $Parsed        = $Request->Export('Parsed');

            // Change response format depending on HTTP_ACCEPT
            $Accept  = $Arguments['server']['HTTP_ACCEPT'];
            $Ext     = (strpos($Accept, 'json')) ? 'json' : 'xml';

            $Params                 = array();
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

            // Authenticate the request if no valid session exists
            if (!Gdn::Session()->IsValid()) self::Authenticate();
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
    * @access  public
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
}