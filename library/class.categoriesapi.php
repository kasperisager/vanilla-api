<?php if (!defined('APPLICATION')) exit();

use Swagger\Annotations as SWG;

/**
 * Categories API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright © 2013
 * @license    http://opensource.org/licenses/MIT MIT
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
      $ID   = $Params['URI'][2];
      $Ext  = $Params['Ext'];

      if ($ID) {
         return self::_GetById($Ext, $ID);
      } else {
         return self::_GetAll($Ext);
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
   protected function _GetAll($Ext)
   {
      $Return = array();
      $Return['Map'] = 'vanilla/categories.' . $Ext . '/all';

      return $Return;
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
   protected function _GetById($Ext, $ID)
   {
      $Return = array();
      $Return['Map'] = 'vanilla/categories.' . $Ext . DS . $ID;

      return $Return;
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
      $Ext = $Params['Ext'];

      $Return = array();
      $Return['Map'] = 'vanilla/settings/addcategory.' . $Ext;

      return $Return;
   }

   /**
    * Update categories
    *
    * PUT /categories/:id
    *
    * To be implemented:
    * PUT /categories/follow/:id
    * PUT /categories/read/:id
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
      $ID   = $Params['URI'][2];
      $Ext  = $Params['Ext'];

      $Return = array();
      $Return['Args']['CategoryID'] = $ID;
      $Return['Args']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Map'] = 'vanilla/settings/editcategory.' . $Ext . DS . $ID;
      
      return $Return;
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
      $ID   = $Params['URI'][2];
      $Ext  = $Params['Ext'];

      $Return = array();
      $Return['Args']['CategoryID'] = $ID;
      $Return['Args']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Map'] = 'vanilla/settings/deletecategory.' . $Ext . DS . $ID;

      return $Return;
   }
}