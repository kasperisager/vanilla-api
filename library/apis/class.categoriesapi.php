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
class CategoriesAPI extends APIMapper
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
    */
   public function Get($Path)
   {
      if (isset($Path[2])) $ID = $Path[2];

      (isset($ID)) ? self::GetById($ID) : self::GetAll();
   }

   /**
    * Find all categories
    *
    * GET /categories
    *
    * @since   0.1.0
    * @access  public
    */
   public function GetAll()
   {
      $this->API['Controller']   = 'Categories';
      $this->API['Method']       = 'All';
   }

   /**
    * Find a specific category
    *
    * GET /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   int $ID
    */
   public function GetById($ID)
   {
      $this->API['Controller']   = 'Categories';
      $this->API['Arguments']    = array(
         'CategoryID' => $ID
         );
   }

   /**
    * Create categories
    *
    * POST /categories
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Post($Path)
   {
      $this->API['Application']  = 'Vanilla';
      $this->API['Controller']   = 'Settings';
      $this->API['Method']       = 'AddCategory';
   }

   /**
    * Update categories
    *
    * PUT /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Put($Path)
   {
      if (!isset($Path[2])) {
         throw new Exception("No ID defined", 401);
      }

      $ID = $Path[2];

      $this->API['Application']  = 'Vanilla';
      $this->API['Controller']   = 'Settings';
      $this->API['Method']       = 'EditCategory';
      $this->API['Arguments']    = array(
         'CategoryID'   => $ID,
         'TransientKey' => Gdn::Session()->TransientKey()
         );
   }

   /**
    * Remove categories
    *
    * DELETE /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Delete($Path)
   {
      if (!isset($Path[2])) {
         throw new Exception("No ID defined", 401);
      }

      $ID = $Path[2];

      $this->API['Application']  = 'Vanilla';
      $this->API['Controller']   = 'Settings';
      $this->API['Method']       = 'DeleteCategory';
      $this->API['Arguments']    = array(
         'CategoryID'   => $ID,
         'TransientKey' => Gdn::Session()->TransientKey()
         );
   }
}
