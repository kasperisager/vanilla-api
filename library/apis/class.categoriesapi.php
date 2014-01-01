<?php if (!defined('APPLICATION')) exit;

/**
 * Categories API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class CategoriesAPI extends APIMapper
{
    /**
     * Retrieve categories
     *
     * GET /categories
     * GET /categories/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Get($Path)
    {
        static::$Controller = 'Categories';

        $Arg = val(2, $Path)

        if (is_numeric($Arg)) {
            static::$Arguments = array(
                'CategoryID' => $Arg
            );
        } else {
            static::$Method = 'All';
        }
    }

    /**
     * Create categories
     *
     * POST /categories
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Post($Path)
    {
        static::$Application = 'Vanilla';
        static::$Controller  = 'Settings';
        static::$Method      = 'AddCategory';
    }

    /**
     * Update categories
     *
     * PUT /categories/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     * @static
     */
    public static function Put($Path)
    {
        $Arg = val(2, $Path)

        if (!is_numeric($Arg)) {
            throw new Exception("No ID defined", 401);
        }

        static::$Application = 'Vanilla';
        static::$Controller  = 'Settings';
        static::$Method      = 'EditCategory';
        static::$Arguments   = array(
            'CategoryID'   => $Arg,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }

    /**
     * Remove categories
     *
     * DELETE /categories/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     * @static
     */
    public static function Delete($Path)
    {
        $Arg = val(2, $Path)

        if (!is_numeric($Arg)) {
            throw new Exception("No ID defined", 401);
        }

        static::$Application = 'Vanilla';
        static::$Controller  = 'Settings';
        static::$Method      = 'DeleteCategory';
        static::$Arguments   = array(
            'CategoryID'   => $Arg,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }
}
