<?php if (!defined('APPLICATION')) exit();

// Load SwaggerSwagger and PhpFileCache
use Swagger\Swagger;
use \Doctrine\Common\Cache\PhpFileCache;

/**
 * API Docs Controller
 *
 * This class handles documentation and visualization of the API. It uses
 * Swagger PHP to provide Swagger-compliant JSON documents to the Swagger UI
 * which runs on the front end. It also integrates with the public Github Wiki
 * to users have easy access to documentation directly in the API Explorer.
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 */
class DocsController extends APIController
{
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
    * Initialize the API Explorer
    * 
    * @since   0.1.0
    * @access  public
    */
   public function Initialize()
   {
      parent::Initialize();

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

   /**
    * API documentation and visualization using Swagger UI
    * 
    * @since   0.1.0
    * @access  public
    */
   public function Explorer()
   {
      // Set page title
      $this->Title(T('Vanilla API'));

      // Vanilla 2.1 goodie - sections!
      if (method_exists('Gdn_Theme', 'Section'))
         Gdn_Theme::Section('APIDocumentation');

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
      if (!file_exists($CacheData) || time() - filemtime($CacheData) >= 3600)
         file_put_contents($CacheData, file_get_contents($RemoteData));

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

         if (!isset($Entries[$Wiki]))
            throw new Exception("Entry not found", 404);
            
         $Entry = $Entries[$Wiki];
         $Title = $Entry['Title'];

         $this->Title(T($Title));
         $this->SetData('Entry', $Entry);

      } else {
         $this->Title(T('Wiki'));
         $this->SetData('Entry', $Entries['home']);
      }

      // $this->SetData('Sidebar', $Entries['sidebar']['Content']);
      // $this->SetData('Footer', $Entries['footer']['Content']);

      $this->MasterView = 'api';
      $this->Render();
   }

   /**
    * Vanilla API Resource Listing
    *
    * Resource listing used to get information about each information about
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

         // If a resource doesn't exist throw a "Not Found"
         if (!isset($Registry['/' . $Resource]->apis))
            throw new Exception("Resource not found", 404);

         // Find the requested resource in the registry
         $Resource = $Registry['/' . $Resource]->apis;

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

      $this->RenderData();
   }
}