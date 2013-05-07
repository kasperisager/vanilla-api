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
      $API['Controller']   = 'Categories';
      $API['Method']       = 'All';

      return $API;
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
      $API['Controller']   = 'Categories';
      $API['Arguments']    = array($ID);

      return $API;
   }

   /**
    * Create categories
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

      $API['Controller']   = 'Settings';
      $API['Method']       = 'AddCategory';

      return $API;
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
      Gdn_Autoloader::AttachApplication('Vanilla');

      if (!isset($Path[2])) throw new Exception("No ID defined", 401);

      $ID = $Path[2];

      $API['Controller']                  = 'Settings';
      $API['Method']                      = 'EditCategory';
      $API['Arguments']                   = array($ID);
      $API['Arguments']['CategoryID']     = $ID;
      $API['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();

      return $API;
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
      Gdn_Autoloader::AttachApplication('Vanilla');

      if (!isset($Path[2])) {
         throw new Exception("No ID defined", 401);
      }

      $ID = $Path[2];

      $API['Controller']                  = 'Settings';
      $API['Method']                      = 'DeleteCategory';
      $API['Arguments']                   = array($ID);
      $API['Arguments']['CategoryID']     = $ID;
      $API['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();

      return $API;
   }
}