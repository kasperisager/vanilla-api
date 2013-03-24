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
 * @SWG\resource(
 *   resourcePath="/categories"
 * )
 */
class CategoriesAPI extends Mapper
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
        $Format     = $Params['Format'];
        if ($CategoryID) {
            return self::_GetById($Format, $CategoryID);
        } else {
            return self::_GetAll($Format);
        }
    }

    /**
     * Find all categories
     *
     * GET /categories
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\api(
     *   path="/categories",
     *   @SWG\operations(
     *     @SWG\operation(
     *       httpMethod="GET",
     *       nickname="GetAll",
     *       summary="Find all categories",
     *       notes="Respects permissions"
     *     )
     *   )
     * )
     */
    protected function _GetAll($Format)
    {
        return array('Map' => 'vanilla/categories.' . $Format . '/all');
    }

    /**
     * Find a specific category
     *
     * GET /categories/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     *
     * @SWG\api(
     *   path="/categories/{categoryid}",
     *   @SWG\operations(
     *     @SWG\operation(
     *       httpMethod="GET",
     *       nickname="GetAll",
     *       summary="Find a specific category",
     *       notes="Respects permissions",
     *       @SWG\parameters(
     *         @SWG\parameter(
     *           allowMultiple="false",
     *           name="CategoryID",
     *           description="ID of category that needs to be fetched",
     *           paramType="path",
     *           required="true",
     *           dataType="int"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    protected function _GetById($Format, $CategoryID)
    {
        return array('Map' => 'vanilla/categories.' . $Format . DS . $CategoryID);
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
     * @SWG\api(
     *   path="/categories",
     *   @SWG\operations(
     *     @SWG\operation(
     *       httpMethod="POST",
     *       nickname="Post",
     *       summary="Create a new category",
     *       notes="Respects permissions"
     *     )
     *   )
     * )
     */
    public function Post($Params)
    {
        $Format = $Params['Format'];
        return array('Map' => 'vanilla/settings/addcategory.' . $Format);
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
     * @SWG\api(
     *   path="/categories/{categoryid}",
     *   @SWG\operations(
     *     @SWG\operation(
     *       httpMethod="PUT",
     *       nickname="Put",
     *       summary="Update an existing category",
     *       notes="Respects permissions",
     *       @SWG\parameter(
     *         allowMultiple="false",
     *         name="CategoryID",
     *         description="ID of category that needs to be updated",
     *         paramType="path",
     *         required="true",
     *         dataType="int"
     *       ),
     *       @SWG\parameter(
     *         allowMultiple="false",
     *         name="Name",
     *         description="Existing or new name of the category",
     *         paramType="body",
     *         required="true",
     *         dataType="string"
     *       ),
     *       @SWG\parameter(
     *         allowMultiple="false",
     *         name="UrlCode",
     *         description="Existing or new URL code of the category",
     *         paramType="body",
     *         required="true",
     *         dataType="string"
     *       )
     *     )
     *   )
     * )
     */
    public function Put($Params)
    {
        $CategoryID = $Params['URI'][2];
        $Format     = $Params['Format'];
        $Map = 'vanilla/settings/editcategory.' . $Format . DS . $CategoryID;
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
     * @SWG\api(
     *   path="/categories/{categoryid}",
     *   @SWG\operations(
     *     @SWG\operation(
     *       httpMethod="DELETE",
     *       nickname="Delete",
     *       summary="Delete an existing category",
     *       notes="Respects permissions",
     *       @SWG\parameters(
     *         @SWG\parameter(
     *           allowMultiple="false",
     *           name="CategoryID",
     *           description="ID of category that needs to be deleted",
     *           paramType="path",
     *           required="true",
     *           dataType="int"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function Delete($Params)
    {
        $CategoryID = $Params['URI'][2];
        $Format     = $Params['Format'];
        $Map = 'vanilla/settings/deletecategory.' . $Format . DS . $CategoryID;
        $Args = array(
            'CategoryID'    => $CategoryID,
            'TransientKey'  => Gdn::Session()->TransientKey()
        );
        return array('Map' => $Map, 'Args' => $Args);
    }
}