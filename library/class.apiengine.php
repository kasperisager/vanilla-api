<?php if (!defined('APPLICATION')) exit();

/**
 * The main API engine
 *
 * This class handles authentication and delegation of API requests and their
 * corresponding methods.
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class APIEngine
{
   /**
    * Token-based, per-request authentication
    *
    * This function takes the entire request string and turns the query into an
    * array of data. It then uses all the data to generate a signature the same
    * way it got generated on the client. If the server signature and client
    * token match, the client is considered legimate and the request is served.
    *
    * Based on initial work by Diego Zanella
    * @link    http://careers.stackoverflow.com/diegozanella
    *
    * @since   0.1.0
    * @access  public
    * @static
    */
   public static function Authenticate()
   {
      $Request       = Gdn::Request();
      $PathAndQuery  = $Request->PathAndQuery();
      $ParsedURL     = parse_url($PathAndQuery);

      // Get the values we need for authentication
      $Username      = GetIncomingValue('username');
      $Email         = GetIncomingValue('email');
      $Timestamp     = GetIncomingValue('timestamp');
      $Token         = GetIncomingValue('token');

      // Make sure that the query actually contains data
      if (!isset($ParsedURL['query'])) {
         throw new Exception(T("No authentication query defined"), 401);
      }

      // Now that we're sure the query conatins some data, turn this data into
      // an array which we will later use to analyze each part of the query
      parse_str($ParsedURL['query'], $Request);

      // Unset the client token as we don't want to include it when generating
      // the server signature
      unset($Request['token']);

      // Make sure that either a username or an email has been passed
      if (empty($Username) && empty($Email)) {
         throw new Exception(T("Username or email must be specified"), 401);
      }

      // Make sure that the query contains a timestamp
      if (empty($Timestamp)) {
         throw new Exception(T("A timestamp must be specified"), 401);
      }

      // Make sure that this timestamp is still valid
      if ((abs($Timestamp - time())) > C('API.Expiration')) {
         throw new Exception(T("The request is no longer valid"), 401);
      }

      // Make sure that the query contains a token
      if (empty($Token)) {
         throw new Exception(T("A token must be specified"), 401);
      }

      // Now we check for a username and email (we've already made sure that at
      // least one of them have been passed) and set them if they exist
      (empty($Username)) ?: $Username  = $Request['username'];
      (empty($Email))    ?: $Email     = $Request['email'];

      // Get the ID of the client (user) sending the request
      $UserID = self::GetUserID($Username, $Email);

      // Make sure that the user actually exists
      if (!isset($UserID)) {
         throw new Exception(T("The specified user doesn't exist"), 401);
      }

      // Generate a signature from the passed data the same way it was
      // generated on the client
      $Signature = self::Signature($Request);

      // Make sure that the client token and the server signature match
      if ($Token != $Signature) {
         throw new Exception(T("Token and signature do not match"), 401);
      }

      // Now that we've thoroughly verified the client, start a session for the
      // duration of the request using the User ID we specified earlier
      if ($Token == $Signature) Gdn::Session()->Start(intval($UserID), FALSE);
   }

   /**
    * Generate a signature from a request array
    *
    * This function takes an array of data, sorts the keys alphabetically and
    * generates an HMAC hash using a specified application secret. The hash
    * can then be used to validate incoming API calls as only the client and
    * server knows the secret key used for creating the hash.
    *
    * Based on initial work by Diego Zanella
    * @link    http://careers.stackoverflow.com/diegozanella
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Request Array of request data uesd for generating the
    *                         signature hash
    * @return  string         An HMAC-SHA256 hash generated from the request
    *                         data
    * @static
    */
   public static function Signature($Request)
   {
      // Get the application secret used for generating the hash
      $Secret = C('API.Secret');

      // Sort the data array alphabetically so we always get the same hash no
      // matter how the data was originally sorted
      ksort($Request, SORT_STRING);

      // Generate a signature by taking all the request data values (we're not
      // interested in the keys), delimiting them with a dash (to avoid hash
      // collisions) and making it all lower case as to ensure consistent hash
      // generation
      $Signature = hash_hmac('sha256', strtolower(implode('-', $Request)), $Secret);

      return $Signature;
   }

   /**
    * Get a user ID using either a username or an email
    *
    * Note: if both a username and an email are specified, only the username
    * will be used. This is to prevent abusing of the function by passing two
    * parameters at a time and hoping to get a User ID.
    *
    * Based on initial work by Diego Zanella
    * @link    http://careers.stackoverflow.com/diegozanella
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Username  Username of the user whose ID we wish to get
    * @param   string $Email     Email of the user whose ID we wish to get
    * @return  int|null          User ID if a username or an email has been
    *                            specified, otherwise NULL
    * @static
    */
   public static function GetUserID($Username, $Email)
   {
      // Instantiate a new user model
      $UserModel = new UserModel();

      // Look up the user ID using a username if one has been specified
      if(isset($Username)) {
         return $UserModel->GetByUsername($Username)->UserID;
      }

      // Look up the user ID using an email if one has been specified
      if(isset($Email)) {
         return $UserModel->GetByEmail($Email)->UserID;
      }

      return NULL;
   }

   /**
    * Generates a Universally Unique Identifier, version 4
    *
    * @since   0.1.0
    * @access  public
    * @link    http://en.wikipedia.org/wiki/UUID
    * @return  string A UUID, made up of 32 hex digits and 4 hyphens.
    * @static
    */
   public static function UUIDSecure()
   {
      return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
         // 32 bits for "time_low"
         mt_rand(0, 0xffff), mt_rand(0, 0xffff),

         // 16 bits for "time_mid"
         mt_rand(0, 0xffff),

         // 16 bits for "time_hi_and_version",
         // four most significant bits holds version number 4
         mt_rand(0, 0x0fff) | 0x4000,

         // 16 bits, 8 bits for "clk_seq_hi_res",
         // 8 bits for "clk_seq_low",
         // two most significant bits holds zero and one for variant DCE1.1
         mt_rand(0, 0x3fff) | 0x8000,

         // 48 bits for "node"
         mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
      );
   }

   /**
    * Delegate methods to a specified API class
    *
    * This function takes a request URI and an HTTP method and uses these to
    * delegate an action to the specified API class. In return, the API returns
    * an array of data which we later to map the request to an application or
    * plugin controller.
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Path   The full request path excluding queries
    * @param   string $Method The request method issued by the client
    * @param   string $Class  The class that we wish to delegate an action to
    * @return  array          An array of data returned by the API class
    * @static
    */
   public static function MethodHandler($Path, $Method, $Class)
   {
      $Request = Gdn::Request();

      switch(strtolower($Method)) {

         case 'get':
            $Class->Get($Path);
            $Data = $Class->API;
            break;

         case 'post':
            $Class->Post($Path);
            $Data = $Class->API;

            // Combine the POST request with any custom arguments
            if (isset($Data['Arguments'])) {
               $Merged = array_merge($_POST, $Data['Arguments']);
               $Request->SetRequestArguments(Gdn_Request::INPUT_POST, $Merged);
            }

            $_POST = $Request->Post();

            break;

         case 'put':
            $Class->Put($Path);
            $Data = $Class->API;

            // Garden can't handle PUT requests by default, so trick
            // it into thinking that this is actually a POST
            $Request->RequestMethod('post');

            // Parse any form data and store it
            $_PUT = self::ParseFormData();

            // Combine the PUT request with any custom arguments
            if (isset($Data['Arguments'])) {
               $Merged = array_merge($_PUT, $Data['Arguments']);
               $Request->SetRequestArguments(Gdn_Request::INPUT_POST, $Merged);
            } else {
               $Request->SetRequestArguments(Gdn_Request::INPUT_POST, $_PUT);
            }

            $_POST = $Request->Post();

            break;

         case 'delete':
            $Class->Delete($Path);
            $Data = $Class->API;

            // Garden can't handle DELETE requests by default, so trick
            // it into thinking that this is actually a POST
            $Request->RequestMethod('post');

            // Combine the DELETE request with any custom arguments
            if (isset($Data['Arguments'])) {
               $Arguments = $Data['Arguments'];
               $Request->SetRequestArguments(Gdn_Request::INPUT_POST, $Arguments);
            }

            $_POST = $Request->Post();

            break;

      }

      return $Data;
   }

   /**
    * Map the API request to the appropriate controller
    *
    * @since   0.1.0
    * @access  public
    * @param   object $Request
    */
   public function Dispatch($Request)
   {
      $Session = Gdn::Session();
      $URI     = $Request->RequestURI();
      $URI     = strtolower($URI);
      $Path    = explode('/', $URI);

      // Get the requested resource
      $Resource = (!isset($Path[1])) ? NULL : $Path[1];

      // Turn requested resource into API class and store it
      $Class = ucfirst($Resource) . 'API';

      // Make sure that the requested API class exists
      if (!class_exists($Class)) {
         throw new Exception("No such API class found", 404);
      }

      // Instantiate the requested API class
      $Class = new $Class;

      // Make sure that the requested API class extend the API Mapper class
      if (!is_subclass_of($Class, 'APIMapper')) {
         throw new Exception("API class must extend the API Mapper class", 401);
      }

      // Get the request method issued by the client
      $Method = $Request->RequestMethod();

      // Use the MethodHandler to get data from the API class
      $Data = self::MethodHandler($Path, $Method, $Class);

      // Make sure that the API class returns a controller definition
      if (!isset($Data['Controller'])) {
         throw new Exception("No controller has been defined", 500);
      }

      // Authenticate the request if no valid session exists
      if (isset($Data['Authenticate']) && !Gdn::Session()->IsValid()) {

         // If authentication is required, authenticate the client
         if ($Data['Authenticate']) self::Authenticate();

      } elseif (!Gdn::Session()->IsValid()) {

         // If authentication is optional, only authenticate the client if a
         // username or an email has been specified in the request
         $Username   = GetIncomingValue('username');
         $Email      = GetIncomingValue('email');
         if (!empty($Username) || !empty($Email)) self::Authenticate();

      }

      $Controller = $Data['Controller'];

      // If a method is supplied, set it. Otherwise it's null
      $Method = (isset($Data['Method'])) ? $Data['Method'] : NULL;

      // If arguments are supplied, set them. Otherwise they're an empty array
      $Arguments = (isset($Data['Arguments'])) ? $Data['Arguments'] : array();

      // If an application is supplied, set it. Otherwise it's null
      $Application = (isset($Data['Application'])) ? $Data['Application'] : NULL;

      // Attach the correct application if one has been set
      if ($Application) Gdn_Autoloader::AttachApplication($Application);

      // Map the request to the specified URI
      $Request->WithControllerMethod($Controller, $Method, $Arguments);
   }

   /**
    * Set the header format based on the Request object's HTTP_ACCEPT header
    *
    * @since   1.0.0
    * @access  public
    * @param   object $Request
    */
   public function SetHeaders($Request)
   {
      $Arguments = $Request->Export('Arguments');

      // CORS support
      if (C('API.AllowCORS')) {
         $Headers = 'Origin, X-Requested-With, Content-Type, Accept';
         header('Access-Control-Allow-Origin: *');
         header('Access-Control-Allow-Headers: ' . $Headers);
      }

      // Change response format depending on HTTP_ACCEPT
      $Accept = $Arguments['server']['HTTP_ACCEPT'];
      $Format = ($Accept == 'application/xml') ? 'xml' : 'json';

      $Request->WithDeliveryType(DELIVERY_TYPE_DATA);

      switch ($Format) {
         case 'json':
            $Request->WithDeliveryMethod(DELIVERY_METHOD_JSON);
            break;

         case 'xml':
            $Request->WithDeliveryMethod(DELIVERY_METHOD_XML);
            break;
      }
   }

   /**
    * Parse raw Form Data and return it as an array
    *
    * @since   0.1.0
    * @access  public
    * @return  array Parsed array of data derived from the raw Form Data
    *                submitted in the request.
    * @static
    */
   public static function ParseFormData()
   {
      // Fetch PUT content and determine Boundary
      $RawData    = file_get_contents('php://input');
      $Boundary   = substr($RawData, 0, strpos($RawData, "\r\n"));

      if(empty($Boundary)){
         parse_str($RawData, $Data);
         return $Data;
      }

      // Fetch each part
      $Parts   = array_slice(explode($Boundary, $RawData), 1);
      $PutData = array();

      foreach ($Parts as $Part) {
         // If this is the last part, break
         if ($Part == "--\r\n") break;

         // Separate content from headers
         $Part = ltrim($Part, "\r\n");
         list($RawHeaders, $PutBody) = explode("\r\n\r\n", $Part, 2);

         // Parse the headers list
         $RawHeaders = explode("\r\n", $RawHeaders);
         $headers    = array();
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
