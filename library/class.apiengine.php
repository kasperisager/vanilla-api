<?php if (!defined('APPLICATION')) exit;

/**
 * API engine class
 *
 * Handles dispatching API requests and their corresponding methods.
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
    /* Properties */

    /**
     * HTTP methods supported by the API
     *
     * If any other methods are used, the API dispatcher will throw a 405 Method
     * Not Implement exception.
     *
     * @since  0.1.0
     * @access public
     * @var    array
     * @static
     */
    public static $supports = array('get', 'post', 'put', 'delete', 'head', 'options');

    /**
     * Exploded request URI
     *
     * @since  0.1.0
     * @access protected
     * @var    null|array
     * @static
     */
    protected static $requestUri;

    /**
     * Request method
     *
     * @since  0.1.0
     * @access protected
     * @var    null|string
     * @static
     */
    protected static $requestMethod;

    /**
     * Array of request arguments
     *
     * @since  0.1.0
     * @access protected
     * @var    null|array
     * @static
     */
    protected static $requestArguments;

    /**
     * Array of server arguments
     *
     * @since  0.1.0
     * @access protected
     * @var    null|array
     * @static
     */
    protected static $serverArguments;

    /* Methods */

    /**
     * Map the API request to the corrosponding controller
     *
     * @since  0.1.0
     * @access public
     * @throws Exception
     * @return void
     * @static
     */
    public static function dispatchRequest()
    {
        $request = Gdn::request();
        $path    = static::getRequestUri();
        $method  = static::getRequestMethod();

        // Before we do anything else, let's make sure the request method is
        // supported. If not, let the client know
        if (!in_array($method, static::$supports)) {
            throw new Exception(t('API.Error.MethodNotAllowed'), 405);
        }

        // Attempt authentication if no valid session exists
        if (!Gdn::session()->isValid()) {
            $username = getIncomingValue('username');
            $email    = getIncomingValue('email');

            // Only authenticate the client if a username or an email has been
            // specified in the request
            if ($username || $email) APIAuth::authenticateRequest();
        }

        // Get the requested resource
        $resource = val(1, $path);

        // Turn requested resource into API class and store it
        $class = ucfirst($resource) . 'API';

        // Make sure that the requested API class exists
        if (!class_exists($class)) {
            throw new Exception(t('API.Error.Class.Invalid'), 404);
        }

        // Make sure that the requested API class extends the API Mapper
        if (!is_subclass_of($class, 'APIMapper')) {
            throw new Exception(t('API.Error.Mapper'), 500);
        }

        // Instantiate the API class
        $class = new $class;

        // Is this a write-method?
        $write = in_array($method, array('post', 'put', 'delete'));

        // If write-method, get request arguments sent by client
        $data = ($write) ? static::getRequestArguments() : array();

        $dispatch = static::map($resource, $class, $path, $method, $data);

        if ($write) {
            // Authentication is always required for write-methods
            $dispatch['authenticate'] = true;

            // Always attach transient key as last argument for write-methods
            $dispatch['arguments']['TransientKey'] = Gdn::session()->transientKey();

            // As Garden doesn't take PUT and DELETE requests into account when
            // verifying requests using IsPostBack() and IsAuthencatedPostBack(),
            // we need to mask PUTs and DELETEs as POSTs.
            $request->requestMethod('post');

            // Add any API-specific arguments to the requests arguments
            $request->setRequestArguments(Gdn_Request::INPUT_POST, array_merge(
                val('arguments', $dispatch, array()), static::getRequestArguments()
            ));

            // Set the PHP $_POST global as the result of any form data picked
            // up by Garden.
            $_POST = $request->post();
        }

        // Make sure that the API class returns a controller definition
        if (!$controller = val('controller', $dispatch)) {
            throw new Exception(t('API.Error.Controller.Missing'), 500);
        }

        // If the endpoint requires authentication and none has been provided,
        // throw an error
        if (val('authenticate', $dispatch) && !Gdn::session()->isValid()) {
            throw new Exception(t('API.Error.AuthRequired'), 401);
        }

        // Attach the correct application if one has been set
        if ($application = val('application', $dispatch)) {
            Gdn_Autoloader::attachApplication($application);
        }

        $method    = val('method', $dispatch);
        $arguments = val('arguments', $dispatch);

        // Map the request to the specified URI
        Gdn::request()->withControllerMethod($controller, $method, $arguments);
    }

    /**
     * Map a resource to its corresponding controller
     *
     * @since  0.1.0
     * @access public
     * @param  array  $path   URI path array
     * @param  string $method HTTP method
     * @param  array  $data   Request arguments
     * @return array
     * @final
     * @static
     */
    final public static function map($resource, $class, $path, $method, $data)
    {
        // Get all API endpoints
        $endpoints = $class->endpoints($path, $data);

        if ($method == 'options') {
            $supports      = strtoupper(implode(', ', $class::supports()));
            $documentation = array();

            foreach ($endpoints as $method => $endpoints) {
                foreach ($endpoints as $endpoint => $data) {
                    $documentation[$method][] = paths($resource, $endpoint);
                }
            }

            $documentation = base64_encode(json_encode($documentation));

            $controller   = 'API';
            $method       = 'options';
            $arguments    = array($supports, $documentation);
            $authenticate = false; // OPTIONS are always public
        } else {
            $match = $path;

            foreach ($path as $index => $part) {
                // Remove the `api` and `[endpoint]` parts from the URI
                if ($part == 'api' || $part == $resource) unset($match[$index]);

                // If part of the URI is numeric, assume it's a variable `:id`
                if (is_numeric($part)) $match[$index] = ':id';
            }

            $match = array_values($match); // Reset array values

            // Get all endpoints for this specific method
            $endpoints = val(strtoupper($method), $endpoints);

            $resource = paths(DS . implode(DS, $match));

            // Get the first available endpoint for this method
            $first = array_shift(array_values($endpoints));

            // If no endpoint was found, throw a 405 Method Not Implemented
            if (!$endpoint = val($resource, $endpoints)) {
                throw new Exception(t('API.Error.MethodNotAllowed'), 405);
            }

            // If a controller isn't set for this endpoint, assume it uses the
            // same controller as the first available endpoint
            $controller = val('controller', $endpoint, val('controller', $first));

            // Set the controller, defaulting it to `index`
            $method = val('method', $endpoint, 'index');

            // Set optional controller arguments, defaulting to an empty array
            // if no arguments have been specified
            $arguments = val('arguments', $endpoint, array());

            // Does this endpoint require authentication?
            $authenticate = val('authenticate', $endpoint);

            // Replace instances of `:id` with the appropriate value from the
            // requested URI. I.e. `/foo/:id/bar` would cause the `:id` param
            // for the `FooID` argument to be set as the second value of the
            // requested URI.
            $offset    = 2; // Pop off first two paths (`api` and `[endpoint]`)
            $position  = array_search(':id', $match);
            $arguments = array_replace($arguments, array_fill_keys(
                array_keys($arguments, ':id'), val($offset + $position, $path))
            );
        }

        return array(
            'controller'   => $controller,
            'method'       => $method,
            'arguments'    => $arguments,
            'authenticate' => $authenticate
        );
    }

    /**
     * Set the header format based on the Request object's HTTP_ACCEPT header
     *
     * @since  1.0.0
     * @access public
     * @return void
     * @static
     */
    public static function setRequestHeaders()
    {
        // CORS support (experimental)
        if (c('API.AllowCORS')) {
            $headers = 'Origin, X-Requested-With, Content-Type, Accept';

            header('Access-Control-Allow-Origin: *', true);
            header('Access-Control-Allow-Headers: ' . $headers, true);
        }

        // Allow enabling JSONP using API.AllowJSONP
        if (c('API.AllowJSONP')) {
            saveToConfig('Garden.AllowJSONP', true, false);
        }

        $request = Gdn::request();

        switch (static::getRequestMethod()) {
            // If HEAD or DELETE request, only deliver status
            case 'head':
            case 'delete':
                $request->withDeliveryType(DELIVERY_TYPE_BOOL);
                break;

            // Otherwise, only deliver the actual data
            default:
                $request->withDeliveryType(DELIVERY_TYPE_DATA);
                break;
        }

        // Change response format depending on HTTP_ACCEPT
        switch (static::getServerArguments('HTTP_ACCEPT')) {
            case 'text/xml':
            case 'application/xml':
                $request->withDeliveryMethod(DELIVERY_METHOD_XML);
                break;

            case 'application/json':
            case 'application/javascript': // For JSONP
            default:
                $request->withDeliveryMethod(DELIVERY_METHOD_JSON);
                break;
        }
    }

    /**
     * Get the full Request URI path array
     *
     * I.e. "/foo/bar" would result in the following array: array('foo', 'bar')
     *
     * @since  0.1.0
     * @access public
     * @return array The full URI path array
     * @static
     */
    public static function getRequestUri()
    {
        if (static::$requestUri === null) {
            $Uri = Gdn::request()->requestUri();
            static::$requestUri = explode('/', strtolower($Uri));
        }

        return static::$requestUri;
    }

    /**
     * Get the Request method
     *
     * @since  0.1.0
     * @access public
     * @return string The Request method
     * @static
     */
    public static function getRequestMethod()
    {
        if (static::$requestMethod === null) {
            $method = Gdn::request()->requestMethod();
            static::$requestMethod = strtolower($method);
        }

        return static::$requestMethod;
    }

    /**
     * Get and parse any request input
     *
     * @todo Add optional support for form-data when doing POSTs and this is
     *       required in the case of binary uploads
     *
     * @since  0.1.0
     * @access public
     * @return array The arguments sent along with the request
     * @static
     */
    public static function getRequestArguments()
    {
        if (static::$requestArguments === null) {
            // Read the PHP input buffer. This can only be done ONCE, so we need
            // to make sure that we store the data
            $data = file_get_contents('php://input');

            // Get the content type of the input
            $type = static::getServerArguments('HTTP_CONTENT_TYPE');

            switch ($type) {
                case 'text/xml':
                case 'application/xml':
                    $XML  = (array) simplexml_load_string($data);
                    $data = json_decode(json_encode($XML), true);
                    break;

                case 'application/json':
                    $data = json_decode($data, true);
                    break;

                default:
                    throw new Exception(t('API.Error.ContentType') . $type, 400);
                    break;
            }

            static::$requestArguments = $data;
        }

        return static::$requestArguments;
    }

    /**
     * Convenience method for accessing server arguments
     *
     * Returns either the full list of server arguments ($_SERVER) or the value
     * of a specific key if one is passed.
     *
     * @since  0.1.0
     * @access public
     * @param  bool|string $key The specific key to search for
     * @return array|mixed Full array of server arguments or specific value
     * @static
     */
    public static function getServerArguments($key = false)
    {
        $request = Gdn::request();
        $Server  = Gdn_Request::INPUT_SERVER;

        if (static::$serverArguments === null) {
            static::$serverArguments = $request->getRequestArguments($Server);
        }

        $arguments = static::$serverArguments;

        // If a key was specified, return the value of that key. Otherwise
        // return the entire array of server arguments.
        return ($key) ? strtolower(val($key, $arguments)) : $arguments;
    }
}
