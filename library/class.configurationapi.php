<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Configuration API
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * @SWG\Resource(
 *     resourcePath="/configuration"
 * )
 */
class ConfigurationAPI extends Mapper
{
    /**
     * Retrieve Vanilla configuration
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/configuration",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="GET",
     *             path="/configuration",
     *             nickname="GetConfig",
     *             summary="Get the current forum configuration"
     *         )
     *     )
     * )
     */
    public function Get($Params)
    {
        return array('Map' => 'dashboard/settings/configuration');
    }

    protected function _GetThemes() {

    }

    protected function _GetLocales() {
        
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