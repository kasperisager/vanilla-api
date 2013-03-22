<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Categories API
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * @SWG\Resource(
 *     resourcePath="/categories"
 * )
 */
class Categories extends Mapper
{
    /**
     * Retrieve categories
     *
     * GET /categories
     * GET /categories/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Get($Params)
    {
        $CategoryID = $Params['URI'][2];
        if ($CategoryID) {
            return self::_GetById($CategoryID);
        } else {
            return self::_GetAll();
        }
    }

    /**
     * Find all categories
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/categories",
     *     @SWG\operations(
     *         @SWG\Operation(
     *             httpMethod="GET",
     *             nickname="GetAll",
     *             summary="Find all categories",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    protected function _GetAll()
    {
        return array('Map' => 'vanilla/categories/all');
    }

    /**
     * Find a specific category
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/categories/{id}",
     *     @SWG\operations(
     *         @SWG\Operation(
     *             httpMethod="GET",
     *             nickname="GetAll",
     *             summary="Find a specific category",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    protected function _GetById($CategoryID)
    {
        return array('Map' => 'vanilla/categories' . DS . $CategoryID);
    }

    /**
     * Creat categories
     *
     * POST /categories
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/categories",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="POST",
     *             nickname="Post",
     *             summary="Create a new category",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    public function Post($Params)
    {
        return array('Map' => 'vanilla/settings/addcategory');
    }

    /**
     * Update categories
     *
     * PUT /categories/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/categories/{id}",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="PUT",
     *             nickname="Put",
     *             summary="Update an existing category",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    public function Put($Params)
    {
        $CategoryID = $Params['URI'][2];
        $Map = 'vanilla/settings/editcategory' . DS . $CategoryID;
        $Args = array(
            'CategoryID' => $CategoryID,
            'TransientKey'  => Gdn::Session()->TransientKey()
        );
        return array('Map' => $Map, 'Args' => $Args);
    }

    /**
     * Remove categories
     *
     * DELETE /categories/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\Api(
     *     path="/categories/{id}",
     *     @SWG\operations(
     *         @SWG\operation(
     *             httpMethod="DELETE",
     *             nickname="Delete",
     *             summary="Delete an existing category",
     *             notes="Respects permissions"
     *         )
     *     )
     * )
     */
    public function Delete($Params)
    {
        $CategoryID = $Params['URI'][2];
        $Map = 'vanilla/settings/deletecategory' . DS . $CategoryID;
        $Args = array(
            'CategoryID'    => $CategoryID,
            'TransientKey'  => Gdn::Session()->TransientKey()
        );
        return array('Map' => $Map, 'Args' => $Args);
    }
}