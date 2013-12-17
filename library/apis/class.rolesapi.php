<?php if (!defined('APPLICATION')) exit;

/**
 * Roles API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class RolesAPI extends APIMapper
{
    /**
     * Retrieve roles
     *
     * GET /roles
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Get($Path)
    {
        static::$Controller = 'Role';
    }

    /**
     * Create roles
     *
     * POST /roles
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Post($Path)
    {
        static::$Controller = 'Role';
        static::$Method     = 'Add';
        static::$Arguments  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }

    /**
     * Update an existing role
     *
     * PUT /roles/:id
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

        static::$Controller = 'Role';
        static::$Method     = 'Edit';
        static::$Arguments  = array(
            'RoleID'       => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }

    /**
     * Delete an existing role
     *
     * DELETE /roles/:id
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

        static::$Controller = 'Role';
        static::$Method     = 'Delete';
        static::$Arguments  = array(
            'RoleID'       => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }
}
