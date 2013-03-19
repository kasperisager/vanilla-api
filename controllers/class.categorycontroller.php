<?php if (!defined('APPLICATION')) exit();

/**
 * API to access categories
 *
 * @package     API
 * @since       0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class CategoryController extends APIController
{

    /**
     * Define resources used in controller
     * 
     * @since   0.1.0
     * @access  public
     */
    public $Uses = array('CategoryModel', 'Form');

    /**
     * To be written
     * 
     * @since   0.1.0
     * @access  public
     */
    public function Initialize()
    {
        UtilityController::Initialize();
    }

    /**
     * To be written
     * 
     * @since   0.1.0
     * @access  public
     */
    public function Index($CategoryID)
    {
        $Request = UtilityController::ProcessRequest();
  
        switch($Request->Method):

            case 'get':
                
                self::_Get($CategoryID);

                break;

            case 'post':
                
                self::_Post($Request);

                break;

            // TODO: There's probable a better way to do a 501 by default
            default:
                
                $Response = array(
                    'errorResponses' => array(
                        array(
                            'code' => 501,
                            'reason' => 'Not Implemented'
                        )
                    )
                );

                $this->RenderData(UtilityController::SendResponse(501, $Response));

                break;

        endswitch;
    }

    /**
     * To be written
     * 
     * GET /category
     * GET /category/:id
     *
     * TODO: Return error when Category ID isn't found
     * 
     * @param   int $CategoryID
     * @since   0.1.0
     * @access  public
     */
    protected function _Get($CategoryID = NULL)
    {
        $Limit = GetIncomingValue('limit', null);
        $Offset = GetIncomingValue('offset', null);

        $CategoryModel = $this->CategoryModel;

        if ($CategoryID):

            $Categories = $CategoryModel->GetID($CategoryID);

        elseif (is_null($Limit) && is_null($Offset)):

            $Categories = $CategoryModel->GetFull()->Result();

        else:

            $Categories = array_slice($CategoryModel->GetFull($CategoryID)->Result(), $Offset, $Limit);
        
        endif;

        $Data = array();

        // If a category ID has been passed, simply parse the category as-is
        // Don't parse the category if the category ID is 0 or less
        if ($CategoryID && $CategoryID > 0):
            $Data = $Categories;
        else:

            // Do a little filtering of the categories
            foreach ($Categories as $Category):

                // Don't add the category tree root
                if (!is_null($Category->ParentCategoryID)):

                    // Add each entry in the object to the data array
                    $Data[] = $Category;

                endif;

            endforeach;
        endif;

        $this->RenderData(UtilityController::SendResponse(200, $Data));
    }

    /**
     * To be written
     * 
     * POST /category { "Category/Name": name, "Category/UrlCode": url }
     * 
     * @param   int $CategoryID
     * @since   0.1.0
     * @access  public
     */
    protected function _Post($Request)
    {

        // Security
        $Session = Gdn::Session();
        $TransientKey = $Session->TransientKey();

        // Prep models
        $RoleModel = new RoleModel();
        $PermissionModel = Gdn::PermissionModel();

        $this->Form->SetModel($this->CategoryModel);
        //$this->Form->AddHidden('Category/TransientKey', $Session->TransientKey());

        // Load all roles with editable permissions.
        $this->RoleArray = $RoleModel->GetArray();

        if ($Session->ValidateTransientKey($TransientKey)):

            // Form was validly submitted
            $Response = $this->Form->FormValues();

            $CategoryID = $this->Form->Save();

            // If no category was created due to ID conflict
            if (!$CategoryID):  

                unset($CategoryID);

                $Code = 409;
                $Response = array(
                    'errorResponses' => array(
                        array(
                            'code' => $Code,
                            'reason' => 'Conflict'
                        )
                    )
                );

            endif;

        else:

            $Code = 401;
            $Response = array(
                'code' => $Code,
                'reason' => 'Unauthorized'
            );

            $this->RenderData(UtilityController::SendResponse(401, $Response));

        endif;

        // Get all of the currently selected role/permission combinations for this junction.
        $Permissions = $PermissionModel->GetJunctionPermissions(array('JunctionID' => isset($CategoryID) ? $CategoryID : 0), 'Category');
        $Permissions = $PermissionModel->UnpivotPermissions($Permissions, TRUE);

        $this->RenderData(UtilityController::SendResponse($Code, $Response));

    }

    /**
     * Define resource readable by Swagger
     * 
     * @return  array
     * @since   0.1.0
     * @access  public
     */
    public static function Resource()
    {
        return array(
            'resourcePath' => '/categories',
            'apis' => array(
                array(
                    'path' => '/categories',
                    'description' => T('Operations related to categories'),
                    'operations' => array(
                        array(
                            'httpMethod' => 'GET',
                            'nickname' => 'categories',
                            'summary' => 'List all categories',
                            'notes' => T('Only categories that you have permission to access will be listed.'),
                            'parameters' => array(
                                array(
                                    'name' => 'limit',
                                    'description' => T('Limit the number of categories retrieved'),
                                    'paramType' => 'query',
                                    'required' => false,
                                    'allowMultiple' => false,
                                    'dataType' => 'integer'
                                ),
                                array(
                                    'name' => 'offset',
                                    'description' => T('Offset the categories relative to how you\'ve sorted them'),
                                    'paramType' => 'query',
                                    'required' => false,
                                    'allowMultiple' => false,
                                    'dataType' => 'integer'
                                )
                            )
                        )
                    )
                ),
                array(
                    'path' => '/categories/{id}',
                    'description' => T('Operations related to categories'),
                    'operations' => array(
                        array(
                            'httpMethod' => 'GET',
                            'nickname' => 'category_id',
                            'summary' => T('Find a category by its unique ID'),
                            'notes' => T('Only categories that you have permission to access will be listed.'),
                            'parameters' => array(
                                array(
                                    'name' => 'id',
                                    'description' => T('The unique ID for the category you wish to retrieve'),
                                    'paramType' => 'path',
                                    'required' => true,
                                    'allowMultiple' => false,
                                    'dataType' => 'integer'
                                )
                            )
                        ),
                        array(
                            'httpMethod' => 'POST',
                            'nickname' => 'category_id',
                            'summary' => T('Find a category by its unique ID'),
                            'notes' => T('Only categories that you have permission to access will be listed.'),
                            'parameters' => array(
                                array(
                                    'name' => 'id',
                                    'description' => T('The unique ID for the category you wish to retrieve'),
                                    'paramType' => 'path',
                                    'required' => true,
                                    'allowMultiple' => false,
                                    'dataType' => 'integer'
                                )
                            )
                        )
                    )
                )
            )
        );
    }

}