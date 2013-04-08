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
    * @param   array $Parameters
    * @return  array
    */
   public function Get($Parameters)
   {
      if (isset($Parameters['Path'][2])) $ID = $Parameters['Path'][2];

      $Format = $Parameters['Format'];

      if (isset($ID))   return self::GetById($Format, $ID);
      if (!isset($ID))  return self::GetAll($Format);
   }

   /**
    * Find all categories
    *
    * GET /categories
    *
    * @since   0.1.0
    * @access  public
    * @param   string $Format
    * @return  array
    * @static
    */
   public static function GetAll($Format)
   {
      $Return = array();
      $Return['Resource']     = 'vanilla/categories.' . $Format . '/all';
      $Return['Authenticate'] = 'Optional';

      return $Return;
   }

   /**
    * Find a specific category
    *
    * GET /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   string   $Format
    * @param   int      $ID
    * @return  array
    * @static
    */
   public static function GetById($Format, $ID)
   {
      $Return = array();
      $Return['Resource']     = 'vanilla/categories.' . $Format . DS . $ID;
      $Return['Authenticate'] = 'Optional';

      return $Return;
   }

   /**
    * Creat categories
    *
    * POST /categories
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @return  array
    */
   public function Post($Parameters)
   {
      $Format = $Parameters['Format'];

      $Return = array();
      $Return['Map'] = 'vanilla/settings/addcategory.' . $Format;

      return $Return;
   }

   /**
    * Update categories
    *
    * PUT /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @return  array
    */
   public function Put($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      $Return = array();
      $Return['Arguments']['CategoryID']     = $ID;
      $Return['Arguments']['TransientKey']   = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/settings/editcategory.' . $Format . DS . $ID;

      return $Return;
   }

   /**
    * Remove categories
    *
    * DELETE /categories/:id
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @return  array
    */
   public function Delete($Parameters)
   {
      $ID      = $Parameters['Path'][2];
      $Format  = $Parameters['Format'];

      $Return = array();
      $Return['Arguments']['CategoryID']  = $ID;
      $Return['Arguments']['TransientKey'] = Gdn::Session()->TransientKey();
      $Return['Resource'] = 'vanilla/settings/deletecategory.' . $Format . DS . $ID;

      return $Return;
   }
}