<?php if (!defined('APPLICATION')) exit;

/**
 * Users API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class UsersAPI extends APIMapper
{
    /**
     * Retrieve users
     *
     * GET /users
     * GET /users/:id
     * GET /users/summary
     * 
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Get($Path)
    {
        $Arg = val(2, $Path);

        if (is_numeric($Arg)) {
            static::$Controller = 'Profile';
            static::$Arguments  = array(
                'User'   => $Arg,
                'UserID' => $Arg
            );
        } else if ($Arg == 'summary') {
            static::$Controller = 'User';
            static::$Method     = 'Summary';
        } else {
            static::$Authenticate = TRUE;
            static::$Controller   = 'User';
        }
    }

    /**
     * Create a new user
     *
     * POST /users
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Post($Path)
    {
        static::$Controller = 'User';
        static::$Method     = 'Add';
        static::$Arguments  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }

    /**
     * Update an existing user
     *
     * PUT /users/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     * @static
     */
    public static function Put($Path)
    {
        if (!$ID = val(2, $Path)) {
            throw new Exception("No ID defined", 401);
        }

        static::$Controller = 'User';
        static::$Method     = 'Edit';
        static::$Arguments  = array(
            'UserID'       => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }

    /**
     * Delete an existing user
     *
     * DELETE /users/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     * @static
     */
    public static function Delete($Path)
    {
        if (!$ID = val(2, $Path)) {
            throw new Exception("No ID defined", 401);
        }

        static::$Controller = 'User';
        static::$Method     = 'Delete';
        static::$Arguments  = array(
            'UserID'       => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }
}
