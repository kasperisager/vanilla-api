<?php if (!defined('APPLICATION')) exit();

use Swagger\Swagger;
use Swagger\Annotations as SWG;

/**
 * Vanilla API resource controller
 *
 * Resource controller used to get information about each information about
 * each controller and output this as JSON for Swagger UI to read
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
 */
class ResourcesController extends APIController
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
      return array(
         'apiVersion' => '0.1.0',
         'swaggerVersion' => '1.1',
         'basePath' => Gdn::Request()->Domain() . '/api'
      );
   }

   /**
    * API documentation and visualization using Swagger
    * 
    * @since   0.1.0
    * @access  public
    */
   public function Index($Resource)
   {
      $this->DeliveryType(DELIVERY_TYPE_DATA);
      $this->DeliveryMethod(DELIVERY_METHOD_JSON);

      $this->SetData(self::Meta());

      $Path      = PATH_APPLICATIONS . DS . 'api';
      $Swagger   = new Swagger();
      $Discover   = $Swagger->Discover($Path);
      $Registry   = $Discover->registry;

      if (!$Resource) {

         $Listing = array();

         foreach ($Registry as $Val) {
            $Resource = array(
               'path'        => '/resources'.$Val['resourcePath']
            );
            $Listing[] = $Resource;
         }

         $this->SetData('apis', $Listing);

      } else if ($Resource) {

         $this->SetData($Registry['/'.$Resource]);

      }

      $this->RenderData();
   }

   /**
    * A little voodoo to turn objects into arrays
    * 
    * @param   object $Data
    * @since   0.1.0
    * @access  public
    */
   public function Sanitize($Data)
   {
      $Data = json_encode($Data);
      $Data = json_decode($Data, true);
      return $Data;
   }
}