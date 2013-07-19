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
    * If you use a constructor, always call parent.
    * Delete this if you don't need it.
    *
    * @access public
    */
   public function __construct()
   {
      parent::__construct();
   }

   /**
    * This is a good place to include JS, CSS, and modules used by all methods of this controller.
    *
    * Always called by dispatcher before controller's requested method.
    *
    * @since 1.0
    * @access public
    */
   public function Initialize()
   {
      parent::Initialize();
   }

   public function Exception()
   {
      # code...
   }
}
