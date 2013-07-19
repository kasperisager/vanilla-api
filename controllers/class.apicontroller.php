<?php if (!defined('APPLICATION')) exit();

/**
 * A brief description of the controller.
 *
 * Your app will automatically be able to find any models from your app when you instantiate them.
 * You can also access the UserModel and RoleModel (in Dashboard) from anywhere in the framework.
 *
 * @since 1.0
 * @package Skeleton
 */
class APIController extends Gdn_Controller
{
   /**
    * Always called by dispatcher before controller's requested method.
    *
    * @since 1.0
    * @access public
    */
   public function Initialize()
   {
      parent::Initialize();
   }

   public function Exception($Code, $Message)
   {
      $this->SetData(array(
         'Code'      => intval($Code),
         'Exception' => $Message
      ));
      $this->Render();
   }
}
