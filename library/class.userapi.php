<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * User API
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * @SWG\Resource(
 *     resourcePath="/users"
 * )
 */
class UsersAPI extends Mapper
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
        $Format = $Params['Format'];
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