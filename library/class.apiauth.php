<?php if (!defined('APPLICATION')) exit;

/**
 * API authentication class
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 * @final
 */
final class APIAuth
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
     * @return void
     * @static
     */
    public static function authenticateRequest()
    {
        $username = getIncomingValue('username');
        $email    = getIncomingValue('email');

        if (!$username && !$email) {
            throw new Exception(t('API.Error.User.Missing'), 401);
        }

        if (!$userID = static::getUserID($username, $email)) {
            throw new Exception(t('API.Error.User.Invalid'), 401);
        }

        if (!$timestamp = getIncomingValue('timestamp')) {
            throw new Exception(t('API.Error.Timestamp.Missing'), 401);
        }

        // Make sure that request is still valid
        if ((abs($timestamp - time())) > c('API.Expiration')) {
            throw new Exception(t('API.Error.Timestamp.Invalid'), 401);
        }

        if (!$token = getIncomingValue('token')) {
            throw new Exception(t('API.Error.Token.Missing'), 401);
        }

        $parsedUrl = parse_url(Gdn::request()->pathAndQuery());

        // Turn the request query data into an array to be used in the token
        // generation
        parse_str(val('query', $parsedUrl, array()), $data);

        // Unset the values we don't want to include in the token generation
        unset($data['token'], $data['DeliveryType'], $data['DeliveryMethod']);

        if ($token != $signature = static::generateSignature($data)) {
            throw new Exception(t('API.Error.Token.Invalid'), 401);
        }

        // Now that the client has been thoroughly verified, start a session for
        // the duration of the request using the User ID specified earlier
        if ($token == $signature) Gdn::session()->start(intval($userID), false);
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
     * @param  array $data Array of request data uesd for generating the hash
     * @return string      An HMAC-SHA256 hash generated from the request data
     * @static
     */
    public static function generateSignature($data)
    {
        // Sort the data array alphabetically so we always get the same hash no
        // matter how the data was originally sorted
        ksort($data, SORT_STRING);

        // Generate a signature by taking all the request data values (we're not
        // interested in the keys), delimiting them with a dash (to avoid hash
        // collisions) and making it all lower case as to ensure consistent hash
        // generation
        $signature = hash_hmac('sha256', strtolower(implode('-', $data)), c('API.Secret'));

        return $signature;
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
    public static function generateUniqueID()
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
     * Note: If both a username and an email are specified, only the username
     * will be used. This is to prevent abusing of the function by passing two
     * parameters at a time and hoping to get a User ID.
     *
     * Based on initial work by Diego Zanella
     * @link http://careers.stackoverflow.com/diegozanella
     *
     * @since  0.1.0
     * @access public
     * @param  bool|string $username Username of the user whose ID we wish to get
     * @param  bool|string $email    Email of the user whose ID we wish to get
     * @return bool|int              User ID if a username or an email has been
     *                               specified, otherwise false
     * @static
     */
    public static function getUserID($username, $email)
    {
        $userModel = new UserModel();

        // Look up the user ID using a username if one has been specified
        if ($username) return $userModel->getByUsername($username)->UserID;

        // Look up the user ID using an email if one has been specified
        if ($email) return $userModel->getByEmail($email)->UserID;

        return false;
    }
}
