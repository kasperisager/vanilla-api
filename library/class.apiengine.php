<?php if (!defined('APPLICATION')) exit;

/**
 * The main API engine
 *
 * This class handles authentication and delegation of API requests and their
 * corresponding methods.
 *
 * The API engine itself is currently closed off for subclassing. This will
 * hopefully change in the future once the engine is stable and ready for
 * general consumption and extension.
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 * @final
 */
final class APIEngine
{
    /**
     * Token-based, per-request authentication
     *
     * This method takes the entire request string and turns the query into an
     * array of data. It then uses all the data to generate a signature the same
     * way it got generated on the client. If the server signature and client
     * token match, the client is considered legimate and the request is served.
     *
     * Based on initial work by Diego Zanella
     * @link http://careers.stackoverflow.com/diegozanella
     *
     * @since  0.1.0
     * @access public
     * @throws Exception
     * @static
     */
    public static function AuthenticateRequest()
    {
        $Request      = Gdn::Request();
        $PathAndQuery = $Request->PathAndQuery();
        $ParsedURL    = parse_url($PathAndQuery);

        // Get the values we need for authentication (defaults are FALSE)
        $Username  = GetIncomingValue('username');
        $Email     = GetIncomingValue('email');
        $Timestamp = GetIncomingValue('timestamp');
        $Token     = GetIncomingValue('token');

        // Turn the request query data into an array which we will later use to
        // analyze each part of the query
        parse_str(val('query', $ParsedURL, array()), $Request);

        // Unset the client token as we don't want to include it when generating
        // the server signature
        unset($Request['token']);

        // Unset DeliveryType and DeliveryMethod
        unset($Request['DeliveryType']);
        unset($Request['DeliveryMethod']);

        // Make sure that either a username or an email has been passed
        if (!$Username && !$Email) {
            throw new Exception("Authentication failed: Username or email must be specified", 401);
        }

        // Make sure that the query contains a timestamp
        if (!$Timestamp) {
            throw new Exception("Authentication failed: A timestamp must be specified", 401);
        }

        // Make sure that this timestamp is still valid
        if ((abs($Timestamp - time())) > C('API.Expiration')) {
            throw new Exception("Authentication failed: The request is no longer valid", 401);
        }

        // Make sure that the query contains a token
        if (!$Token) {
            throw new Exception("Authentication failed: A token must be specified", 401);
        }

        // Get the ID of the client (user) sending the request
        $UserID = static::GetUserID($Username, $Email);

        // Throw an error if no user was found
        if (!$UserID) {
            throw new Exception("Authentication failed: The specified user doesn't exist", 401);
        }

        // Generate a signature from the passed data the same way it was
        // generated on the client
        $Signature = static::GenerateSignature($Request);

        // Make sure that the client token and the server signature match
        if ($Token != $Signature) {
            throw new Exception("Authentication failed: Token and signature do not match", 401);
        }

        // Now that we've thoroughly verified the client, start a session for the
        // duration of the request using the User ID we specified earlier
        if ($Token == $Signature) {
            Gdn::Session()->Start(intval($UserID), FALSE);
        }
    }

    /**
     * Map the API request to the appropriate controller
     *
     * @since  0.1.0
     * @access public
     * @throws Exception
     * @static
     */
    public static function DispatchRequest()
    {
        // Attempt authentication if no valid session exists
        if (!Gdn::Session()->IsValid()) {
            $Username = GetIncomingValue('username');
            $Email    = GetIncomingValue('email');

            // Only authenticate the client if a username or an email has been
            // specified in the request
            if ($Username || $Email) static::AuthenticateRequest();
        }

        // Get the requested resource
        $Resource = val(1, static::GetRequestPathArray());

        // Turn requested resource into API class and store it
        $Class = ucfirst($Resource) . 'API';

        // Make sure that the requested API class exists
        if (!class_exists($Class)) {
            throw new Exception("The requested API was not found", 404);
        }

        // Make sure that the requested API class extend the API Mapper class
        if (!is_subclass_of($Class, 'APIMapper')) {
            throw new Exception("APIs must be subclassed from the API Mapper", 500);
        }

        // Delegate request to an API class
        static::HandleClassRequest($Class);

        // Make sure that the API class returns a controller definition
        if (!$Controller = $Class::$Controller) {
            throw new Exception("No controller has been defined in the API", 500);
        }

        // If the endpoint requires authentication and none has been provided,
        // throw an error
        if ($Class::$Authenticate && !Gdn::Session()->IsValid()) {
            throw new Exception("Authentication required for this endpoint", 401);
        }

        // Attach the correct application if one has been set
        if ($Application = $Class::$Application) {
            Gdn_Autoloader::AttachApplication($Application);
        }

        // Map the request to the specified URI
        Gdn::Request()->WithControllerMethod($Controller, $Class::$Method, $Class::$Arguments);
    }

    /**
     * Delegate methods to a specified API class
     *
     * This function takes a request object and uses it to delegate an action to
     * a specified API class.
     *
     * @since  0.1.0
     * @access public
     * @param  object $Class The requested API class
     * @static
     */
    public static function HandleClassRequest($Class)
    {
        $Request = Gdn::Request();
        $Path    = static::GetRequestPathArray();
        $Method  = strtolower($Request->RequestMethod());

        if ($Method == 'get') {
            $Class::Get($Path);
            return; // There's nothing more to a GET request
        }

        $Data = static::GetRequestInput();

        switch ($Method) {
            case 'post':
                $Class::Post($Path, $Data);
                break;
            
            case 'put':
                $Class::Put($Path, $Data);
                break;

            case 'delete':
                $Class::Delete($Path, $Data);
                break;
        }

        // Authentication is always required for POST, PUT and DELETE requests
        $Class::$Authenticate = TRUE;

        // As Garden doesn't take PUT and DELETE requests into account when
        // verifying requests using IsPostBack() and IsAuthencatedPostBack(),
        // we need to mask PUTs and DELETEs as POSTs.
        $Request->RequestMethod('post');

        // Add any API-specific arguments to the requests arguments
        $Request->SetRequestArguments(Gdn_Request::INPUT_POST, array_merge(
            $Class::$Arguments, $Data
        ));

        // Set the PHP $_POST variable as the result of any form data picked up
        // by Garden.
        $_POST = $Request->Post();
    }

    /**
     * Set the header format based on the Request object's HTTP_ACCEPT header
     *
     * @since  1.0.0
     * @access public
     * @static
     */
    public static function SetRequestHeaders()
    {
        // CORS support (experimental)
        if (C('API.AllowCORS')) {
            $Headers = 'Origin, X-Requested-With, Content-Type, Accept';

            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: ' . $Headers);
        }

        // Allow enabling JSONP using API.AllowJSONP
        if (C('API.AllowJSONP')) {
            SaveToConfig('Garden.AllowJSONP', TRUE, FALSE);
        }

        $Request = Gdn::Request();

        // Only deliver the actual data - no views or other funky stuff
        $Request->WithDeliveryType(DELIVERY_TYPE_DATA);

        // Change response format depending on HTTP_ACCEPT
        switch (static::GetServerArguments('HTTP_ACCEPT')) {
            case 'text/xml':
            case 'application/xml':
                $Request->WithDeliveryMethod(DELIVERY_METHOD_XML);
                break;

            case 'application/json':
            case 'application/javascript': // For JSONP
            default:
                $Request->WithDeliveryMethod(DELIVERY_METHOD_JSON);
                break;
        }
    }

    /**
     * Generate a signature from an array of request data (query strings)
     *
     * This function takes an array of data, sorts the keys alphabetically and
     * generates an HMAC hash using a specified application secret. The hash
     * can then be used to validate incoming API calls as only the client and
     * server knows the secret key used for creating the hash.
     *
     * Based on initial work by Diego Zanella
     * @link http://careers.stackoverflow.com/diegozanella
     *
     * @since  0.1.0
     * @access public
     * @param  array $Data Array of request data uesd for generating the hash
     * @return string      An HMAC-SHA256 hash generated from the request data
     * @static
     */
    public static function GenerateSignature($Data)
    {
        // Sort the data array alphabetically so we always get the same hash no
        // matter how the data was originally sorted
        ksort($Data, SORT_STRING);

        // Generate a signature by taking all the request data values (we're not
        // interested in the keys), delimiting them with a dash (to avoid hash
        // collisions) and making it all lower case as to ensure consistent hash
        // generation
        $Signature = hash_hmac('sha256', strtolower(implode('-', $Data)), C('API.Secret'));

        return $Signature;
    }

    /**
     * Generates a Universally Unique Identifier, version 4
     *
     * @link http://en.wikipedia.org/wiki/UUID
     * 
     * @since  0.1.0
     * @access public
     * @return string A UUID, made up of 32 hex digits and 4 hyphens.
     * @static
     */
    public static function GenerateUniqueID()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res"
            // 8 bits for "clk_seq_low"
            // Two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Get a user ID using either a username or an email
     *
     * Note: if both a username and an email are specified, only the username
     * will be used. This is to prevent abusing of the function by passing two
     * parameters at a time and hoping to get a User ID.
     *
     * Based on initial work by Diego Zanella
     * @link http://careers.stackoverflow.com/diegozanella
     *
     * @since  0.1.0
     * @access public
     * @param  bool|string $Username Username of the user whose ID we wish to get
     * @param  bool|string $Email    Email of the user whose ID we wish to get
     * @return bool|int              User ID if a username or an email has been
     *                               specified, otherwise FALSE
     * @static
     */
    public static function GetUserID($Username, $Email)
    {
        $UserModel = new UserModel();

        // Look up the user ID using a username if one has been specified
        if ($Username) return $UserModel->GetByUsername($Username)->UserID;

        // Look up the user ID using an email if one has been specified
        if ($Email) return $UserModel->GetByEmail($Email)->UserID;

        return FALSE;
    }

    /**
     * Convenience method for accessing server arguments
     * 
     * Returns either the full list of server arguments ($_SERVER) or the value
     * of a specific key if one is passed.
     *
     * @since  0.1.0
     * @access public
     * @param  bool|string $Key The specific key to search for
     * @return array|mixed Full array of server arguments or specific value
     * @static
     */
    public static function GetServerArguments($Key = FALSE)
    {
        // Get the server arguments from the request object
        $Arguments = Gdn::Request()->GetRequestArguments(Gdn_Request::INPUT_SERVER);

        // If a key was specfici, return the value of that key. Otherwise
        // return the entire array of server arguments.
        return ($Key) ? strtolower(val($Key, $Arguments)) : $Arguments;
    }

    /**
     * Get the full Request URI path array
     *
     * I.e. "/foo/bar" would result in the following array: array('foo', 'bar')
     *
     * This is a simple convenience method used throughout the application.
     *
     * @since  0.1.0
     * @access public
     * @return array The full URI path array
     * @static
     */
    public static function GetRequestPathArray()
    {
        return explode('/', strtolower(Gdn::Request()->RequestURI()));
    }

    /**
     * Get and handle any request input
     * 
     * This get the contensts of php://input and turn them into an associative
     * array that can be used in PHP.
     *
     * THIS CAN ONLY BE CALLED ONCE PER REQUEST!
     *
     * @todo Add optional support for form-data when doing POSTs and this is
     *       required in the case of binary uploads
     * 
     * @since  0.1.0
     * @access public
     * @return array The contents of php://input as an array
     * @static
     */
    public static function GetRequestInput()
    {
        $Data = array();

        if ($Data = file_get_contents('php://input')) {
            switch (static::GetServerArguments('HTTP_CONTENT_TYPE')) {
                case 'text/xml':
                case 'application/xml':
                    $XML  = (array) simplexml_load_string($Data);
                    $Data = json_decode(json_encode($XML), TRUE);
                    break;
                
                case 'application/json':
                    $Data = json_decode($Data, TRUE);
                    break;

                default:
                    throw new Exception("Unsupported content type: ${Type}", 400);
                    break;
            }
        }

        return $Data;
    }
}
