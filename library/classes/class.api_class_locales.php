<?php if (!defined('APPLICATION')) exit();

//use Swagger\Annotations as SWG;

/**
 * Locales API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 *
 * @SWG\resource(
 *   resourcePath="/locales"
 * )
 */
class API_Class_Locales extends API_Mapper
{
   /**
    * GET
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Get($Parameters)
   {
      throw new Exception("Method Not Implemented", 501);
   }

   /**
    * POST
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Post($Parameters)
   {
      throw new Exception("Method Not Implemented", 501);
   }

   /**
    * PUT
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Put($Parameters)
   {
      throw new Exception("Method Not Implemented", 501);
   }

   /**
    * DELETE
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    */
   public function Delete($Parameters)
   {
      throw new Exception("Method Not Implemented", 501);
   }
}