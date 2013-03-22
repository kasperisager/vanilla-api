<?php if (!defined('APPLICATION')) exit();

/**
 * User API
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class User extends Mapper
{
    /**
     * GET
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Get($Params)
    {
        $Data = array('Map' => 'dashboard/profile');
        return $Data;
    }

    /**
     * POST
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Post($Params)
    {
        return TRUE;
    }

    /**
     * PUT
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Put($Params)
    {
        return TRUE;
    }

    /**
     * DELETE
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Delete($Params)
    {
        return TRUE;
    }
}