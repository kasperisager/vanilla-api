<?php if (!defined('APPLICATION')) exit();

/**
 * Categories API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class API_Class_Categories extends API_Mapper
{
   /**
    * Retrieve categories
    *
    * GET /categories
    * GET /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    * @return  array
    */
   public function Get($Path)
   {
      if (isset($Path[2])) $ID = $Path[2];

      if (isset($ID)) {
         return self::GetById($ID);
      } else {
         return self::GetAll();
      }
   }

   /**
    * Find all categories
    *
    * GET /categories
    *
    * @since   0.1.0
    * @access  public
    * @return  array
    * @static
    */
   public static function GetAll()
   {
      $Return = array();
      $Return['Controller']   = 'Categories';
      $Return['Method']       = 'All';

      return $Return;
   }

   /**
    * Find a specific category
    *
    * GET /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    * @return  array
    * @static
    */
   public static function GetById($ID)
   {
      $Return = array();
      $Return['Controller']   = 'Categories';
      $Return['Arguments']    = array($ID);

      return $Return;
   }

   /**
    * Creat categories
    *
    * POST /categories
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    * @return  array
    */
   public function Post($Path)
   {
      Gdn_Autoloader::AttachApplication('Vanilla');

      $Return = array();
      $Return['Controller']   = 'Settings';
      $Return['Method']       = 'AddCategory';

      return $Return;
   }

   /**
    * Update categories
    *
    * PUT /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    * @return  array
    */
   public function Put($Path)
   {
      if (!isset($Path[2])) {
         throw new Exception("No ID defined", 401);
      }

      $ID = $Path[2];

      // Make sure the correct application is loaded
      Gdn_Autoloader::AttachApplication('Vanilla');

      $Return = array();
      $Return['Controller']                  = 'Settings';
      $Return['Method']                      = 'EditCategory';
      $Return['Arguments']                   = array($ID);
      $Return['Arguments']['CategoryID']     = $ID;
      $Return['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();

      return $Return;
   }

   /**
    * Remove categories
    *
    * DELETE /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    * @return  array
    */
   public function Delete($Path)
   {
      if (!isset($Path[2])) {
         throw new Exception("No ID defined", 401);
      }

      $ID = $Path[2];

      // Make sure the correct application is loaded
      Gdn_Autoloader::AttachApplication('Vanilla');

      $Return = array();
      $Return['Controller']                  = 'Settings';
      $Return['Method']                      = 'DeleteCategory';
      $Return['Arguments']                   = array($ID);
      $Return['Arguments']['CategoryID']     = $ID;
      $Return['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();

      return $Return;
   }
}