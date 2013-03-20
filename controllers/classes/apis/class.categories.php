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
class Categories
{
    /**
     * To be written
     * 
     * @since   0.1.0
     * @access  public
     */
    public function API($CategoryID)
    {
        $Request = Utility::ProcessRequest();
  
        switch($Request->Method) {

            case 'get':   
                self::_Get($CategoryID);
                break;

            case 'post':
                self::_Post($Request);
                break;

            case 'delete':
                self::_Delete($CategoryID);
                break;

            // TODO: There's probable a better way to do a 501 by default
            default:
                $Code = 501;
                $this->SetData('Code', $Code);
                $this->SetData('Exception', T('Not Implemented'));
                $this->RenderData(Utility::SendResponse($Code, $this->Data));
                break;

        }
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

        $CategoryModel = new CategoryModel();

        if ($CategoryID) {

            $Category = $CategoryModel->GetFull($CategoryID)->Result();
            
            if (!empty($Category)) {
                $Code = 200;
                $this->SetData('Category', array_shift($Category));
            } else {
                $Code = 404;
                $this->SetData('Code', $Code);
                $this->SetData('Exception', T('No category with the specified ID exists'));
            }

        } else {

            $Categories = $CategoryModel->GetFull()->Result();

            if (!empty($Categories)) {
                $Code = 200;
                $this->SetData('Categories', array_slice($Categories, $Offset, $Limit));
            } else {
                $Code = 404;
                $this->SetData('Code', $Code);
                $this->SetData('Exception', T('No categories were found'));
            }
        
        }

        $this->RenderData(Utility::SendResponse($Code, $this->Data));
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
        $this->CategoryModel = new CategoryModel();
        $this->Form = $FormModel = new Gdn_Form();
        $this->Form->SetModel($this->CategoryModel);

        // Load all roles with editable permissions.
        $this->RoleArray = $RoleModel->GetArray();

        if ($Session->ValidateTransientKey($TransientKey)) {

            // Form was validly submitted
            $Response = $this->Form->FormValues();

            $IsParent = $this->Form->GetFormValue('IsParent', '0');
            $this->Form->SetFormValue('AllowDiscussions', $IsParent == '1' ? '0' : '1');

            $CategoryID = $this->Form->Save();

            // If no category was created
            if (!$CategoryID) {
                unset($CategoryID);
                Utility::SetError($Code = 409, 'Conflict');
            } else {
                Utility::SetError($Code = 201, 'Created');
                $this->SetData('Category', $this->Form->FormValues());
            }

        } else {
            $this->Form->AddHidden('CodeIsDefined', '0');
            Utility::SetError($Code = 401, 'Unauthorized');
        }

        // Get all of the currently selected role/permission combinations for this junction.
        $Permissions = $PermissionModel->GetJunctionPermissions(array('JunctionID' => isset($CategoryID) ? $CategoryID : 0), 'Category');
        $Permissions = $PermissionModel->UnpivotPermissions($Permissions, TRUE);

        $this->RenderData(Utility::SendResponse($Code, $this->Data));

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

        // Prep models
        $this->Form = new Gdn_Form();
        $this->CategoryModel = new CategoryModel();
        
        // Get category data
        $this->Category = $this->CategoryModel->GetID($CategoryID);

        // Replacement category
        $Replacement = GetIncomingValue('replacement', NULL);

        if (!$this->Category) {
            Utility::SetError($Code = 404, 'The specified category could not be found.');
        } else {

            // Make sure the form knows which item we are deleting.
            $this->Form->AddHidden('CategoryID', $CategoryID);

            // Get a list of categories other than this one that can act as a replacement
            $this->OtherCategories = $this->CategoryModel->GetWhere(
                array(
                    'CategoryID <>' => $CategoryID,
                    // Don't allow a category with discussion to be the replacement for one without discussions (or vice versa)
                    'AllowDiscussions' => $this->Category->AllowDiscussions,
                    'CategoryID >' => 0
                ),
                'Sort'
            );

            if ($Session->ValidateTransientKey($TransientKey)) {

                $ReplacementCategoryID = $Replacement;
                $ReplacementCategory = $this->CategoryModel->GetID($ReplacementCategoryID);
                
                // Error if:
                // 1. The category being deleted is the last remaining category that
                // allows discussions.
                if ($this->Category->AllowDiscussions == '1'
                    && $this->OtherCategories->NumRows() == 0) {
                    $this->Form->AddError('You cannot remove the only remaining category that allows discussions');
                    Utility::SetError(404, 'You cannot remove the only remaining category that allows discussions');
                }

                // 2. The category being deleted allows discussions, and it contains
                // discussions, and there is no replacement category specified.
                if ($this->Form->ErrorCount() == 0
                    && $this->Category->AllowDiscussions == '1'
                    && $this->Category->CountDiscussions > 0
                    && ($ReplacementCategory == FALSE || $ReplacementCategory->AllowDiscussions != '1')) {
                    $this->Form->AddError('You must select a replacement category in order to remove this category.');
                    Utility::SetError(404, 'You must select a replacement category in order to remove this category.');
                }
            
                // 3. The category being deleted does not allow discussions, and it
                // does contain other categories, and there are replacement parent
                // categories available, and one is not selected.
                if ($this->Category->AllowDiscussions == '0'
                    && $this->OtherCategories->NumRows() > 0
                    && !$ReplacementCategory) {
                    if ($this->CategoryModel->GetWhere(array('ParentCategoryID' => $CategoryID))->NumRows() > 0) {
                        $this->Form->AddError('You must select a replacement category in order to remove this category.');
                        Utility::SetError(404, 'You must select a replacement category in order to remove this category.');
                    }
                }
            
                if ($this->Form->ErrorCount() == 0) {
                    // Go ahead and delete the category
                    try {
                        $this->CategoryModel->Delete($this->Category, $ReplacementCategoryID);
                        $Code = 204;
                    } catch (Exception $ex) {
                        Utility::SetError(404, json_encode($ex));
                    }
                }
            }

        }

        $this->RenderData(Utility::SendResponse($Code, $this->Data));

    }

}