<?php if (!defined('APPLICATION')) exit;

/**
 * API engine class
 *
 * Handles dispatching API requests and their corresponding methods.
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright (c) 2013-2015 Kasper Kronborg Isager
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
     * Not Implemented exception.
     *
     * @since  0.1.0
     * @access public
     * @var    array
     * @static
     */
    public static $supportedMethods = ['get', 'post', 'put', 'delete', 'head', 'options'];

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
        $request       = Gdn::request();
        $requestUri    = static::getRequestUri();
        $requestMethod = static::getRequestMethod();

        if (!in_array($requestMethod, static::$supportedMethods)) {
            throw new Exception(t('API.Error.MethodNotAllowed'), 405);
        }

        if (!Gdn::session()->isValid()) {
            $username = getIncomingValue('username');
            $email    = getIncomingValue('email');

            if ($username || $email) {
                APIAuth::authenticateRequest();
            }
        }

        $resource = val(1, $requestUri);

        $apiClass = ucfirst($resource) . 'API';

        if (!class_exists($apiClass)) {
            throw new Exception(
                sprintf(t('API.Error.Class.Invalid'), $apiClass),
                404
            );
        }

        if (!is_subclass_of($apiClass, 'APIMapper')) {
            throw new Exception(t('API.Error.Mapper'), 500);
        }

        $apiClass = new $apiClass;

        $isWriteMethod = in_array($requestMethod, ['post', 'put', 'delete']);

        $requestArguments = ($isWriteMethod) ? static::getRequestArguments() : [];

        $dispatch = static::map($resource, $apiClass, $requestUri, $requestMethod, $requestArguments);

        $controller = $dispatch['controller'];

        if (!$controller) {
            throw new Exception(t('API.Error.Controller.Missing'), 500);
        }

        $inputData = array_merge($requestArguments, $dispatch['arguments']);

        if ($isWriteMethod) {
            // Set the transient key since we no longer have a front-end that
            // takes care of doing it for us
            $inputData['TransientKey'] = Gdn::session()->transientKey();

            // Authentication is always required for write-methods
            $dispatch['authenticate'] = true;

            // As Garden doesn't take PUT and DELETE requests into account when
            // verifying requests using IsPostBack() and IsAuthencatedPostBack(),
            // we need to mask PUTs and DELETEs as POSTs.
            $request->requestMethod('post');

            // Add any API-specific arguments to the requests arguments
            $request->setRequestArguments(Gdn_Request::INPUT_POST, $inputData);

            // Set the PHP $_POST global as the result of any form data picked
            // up by Garden.
            $_POST = $request->post();
        }

        if ($dispatch['authenticate'] && !Gdn::session()->isValid()) {
            throw new Exception(t('API.Error.AuthRequired'), 401);
        }

        $application = $dispatch['application'];

        if ($application) {
            Gdn_Autoloader::attachApplication($application);
        }

        $method    = $dispatch['method'];
        $arguments = $dispatch['arguments'];

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
     * @return array          Dispatch instruction for Garden.
     * @static
     */
    public static function map($resource, $class, $path, $method, $data)
    {
        $router = new AltoRouter();
        $router->setBasePath('/api');

        $endpoints = $class->endpoints($data);

        if ($method == 'options') {
            $supports      = strtoupper(implode(', ', $class::supports()));
            $documentation = [];

            foreach ($endpoints as $method => $endpoints) {
                foreach ($endpoints as $endpoint => $data) {
                    $documentation[$method][] = paths($resource, $endpoint);
                }
            }

            $documentation = base64_encode(json_encode($documentation));

            return [
                'application'  => 'API',
                'controller'   => 'API',
                'method'       => 'options',
                'arguments'    => [$supports, $documentation],
                'authenticate' => false
            ];
        } else {
            // Register all endpoints in the router
            foreach ($endpoints as $method => $endpoints) {
                foreach ($endpoints as $endpoint => $data) {
                    $endpoint = '/' . $resource . rtrim($endpoint, '/');

                    $router->map($method, $endpoint, $data);
                }
            }

            $match = $router->match('/' . rtrim(join('/', $path), '/'));

            if (!$match) {
                throw new Exception(t('API.Error.MethodNotAllowed'), 405);
            }

            $target = val('target', $match);

            $arguments = array_merge(
                val('params', $match, []),
                val('arguments', $target, [])
            );

            return [
                'application'  => val('application', $target, false),
                'controller'   => val('controller', $target),
                'method'       => val('method', $target, 'index'),
                'authenticate' => val('authenticate', $target),
                'arguments'    => $arguments
            ];
        }
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
        }
    }

    /**
     * Get the full Request URI path array
     *
     * I.e. "/foo/bar" would result in the following array: ['foo', 'bar']
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

            if (empty($data)) {
                return static::$requestArguments = [];
            }

            // Get the content type of the input
            $type = static::getServerArguments('CONTENT_TYPE');

            switch ($type) {
                case 'text/xml':
                case 'application/xml':
                    $XML  = (array) simplexml_load_string($data);
                    $data = json_decode(json_encode($XML), true);
                    break;

                case 'application/json':
                case 'application/javascript': // For JSONP
                    $data = json_decode($data, true);
                    break;

                default:
                    throw new Exception(
                        sprintf(t('API.Error.ContentType'), $type),
                        400
                    );
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
        $server  = Gdn_Request::INPUT_SERVER;

        if (static::$serverArguments === null) {
            static::$serverArguments = $request->getRequestArguments($server);
        }

        $arguments = static::$serverArguments;

        // If a key was specified, return the value of that key. Otherwise
        // return the entire array of server arguments.
        return ($key) ? strtolower(val($key, $arguments)) : $arguments;
    }
}
