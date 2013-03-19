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

            case 'delete':
                
                self::_Delete($CategoryID, $Request);

                break;

            // TODO: There's probable a better way to do a 501 by default
            default:
                
                $Response = array(
                    'Code' => 501,
                    'Exception' => T('Not Implemented')
                );

                $this->RenderData(UtilityController::SendResponse(501, $Response));

                break;

        endswitch;
    }

    /**
     * To be written
     * 
     * GET /category ? limit & offset
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
        $Limit = GetIncomingValue('limit', NULL);
        $Offset = GetIncomingValue('offset', NULL);

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

        // Check permission
        $this->Permission('Vanilla.Categories.Manage');

        // Prep models
        $RoleModel = new RoleModel();
        $PermissionModel = Gdn::PermissionModel();
        $this->Form->SetModel($this->CategoryModel);

        // Load all roles with editable permissions.
        $this->RoleArray = $RoleModel->GetArray();

        if ($Session->ValidateTransientKey($TransientKey)):

            // Form was validly submitted
            $Response = $this->Form->FormValues();

            $IsParent = $this->Form->GetFormValue('IsParent', '0');
            $this->Form->SetFormValue('AllowDiscussions', $IsParent == '1' ? '0' : '1');

            $CategoryID = $this->Form->Save();

            // If no category was created
            if (!$CategoryID):  

                unset($CategoryID);

                $Code = 409;
                $Response = array(
                    'Code' => $Code,
                    'Exception' => 'Conflict'
                );

            endif;

        else:

            $this->Form->AddHidden('CodeIsDefined', '0');

            $Code = 401;
            $Response = array(
                'Code' => $Code,
                'Exception' => 'Unauthorized'
            );

        endif;

        // Get all of the currently selected role/permission combinations for this junction.
        $Permissions = $PermissionModel->GetJunctionPermissions(array('JunctionID' => isset($CategoryID) ? $CategoryID : 0), 'Category');
        $Permissions = $PermissionModel->UnpivotPermissions($Permissions, TRUE);

        $this->RenderData(UtilityController::SendResponse($Code, $Response));

    }

    /**
     * To be written
     * 
     * DELETE /category/:id ? replacement
     * 
     * @param   int $CategoryID
     * @since   0.1.0
     * @access  public
     */
    protected function _Delete($CategoryID = NULL)
    {

        // Security
        $Session = Gdn::Session();
        $TransientKey = $Session->TransientKey();

        // Check permission
        $this->Permission('Vanilla.Categories.Manage');

        // Get category data
        $this->Category = $this->CategoryModel->GetID($CategoryID);

        $Replacement = GetIncomingValue('replacement', NULL);

        if ($this->Category):

            // Get a list of categories other than this one that can act as a replacement
            $this->OtherCategories = $this->CategoryModel->GetWhere(
                array(
                    'CategoryID <>' => $CategoryID,
                    'AllowDiscussions' => $this->Category->AllowDiscussions, // Don't allow a category with discussion to be the replacement for one without discussions (or vice versa)
                    'CategoryID >' => 0
                ),
                'Sort'
            );

            if ($Session->ValidateTransientKey($TransientKey)):

                $ReplacementCategory = $this->CategoryModel->GetID($Replacement);

                // Error if:
                // 1. The category being deleted is the last remaining category that
                // allows discussions.
                if ($this->Category->AllowDiscussions == '1'
                    && $this->OtherCategories->NumRows() == 0):

                    $this->Form->AddError('You cannot remove the only remaining category that allows discussions');

                endif;
                
                // 2. The category being deleted allows discussions, and it contains
                // discussions, and there is no replacement category specified.
                if ($this->Form->ErrorCount() == 0
                    && $this->Category->AllowDiscussions == '1'
                    && $this->Category->CountDiscussions > 0
                    && ($ReplacementCategory == FALSE || $ReplacementCategory->AllowDiscussions != '1')):

                    $this->Form->AddError('You must select a replacement category in order to remove this category.');

                endif;
                
            
                // 3. The category being deleted does not allow discussions, and it
                // does contain other categories, and there are replacement parent
                // categories available, and one is not selected.
                if ($this->Category->AllowDiscussions == '0'
                    && $this->OtherCategories->NumRows() > 0
                    && !$ReplacementCategory):

                    if ($this->CategoryModel->GetWhere(array('ParentCategoryID' => $CategoryID))->NumRows() > 0):
                        $this->Form->AddError('You must select a replacement category in order to remove this category.');
                    endif;

                endif;

                if ($this->Form->ErrorCount() == 0) {
                    // Go ahead and delete the category
                    try {

                        $this->CategoryModel->Delete($this->Category, $Replacement);

                        $Code = 204;

                    } catch (Exception $Exception) {

                        $Code = 400;
                        $Response = array(
                            'Code' => $Code,
                            'Exception' => json_decode($Exception)
                        );

                    }
                }

            endif;

        else:

            $Code = 400;
            $Response = array(
                'Code' => $Code,
                'Exception' => T('Bad request')
            );

        endif;

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