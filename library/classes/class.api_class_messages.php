<?php if (!defined('APPLICATION')) exit();

/**
 * Messages API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class API_Class_Messages implements API_Mapper
{
   /**
    * Retrieve Vanilla configuration
    *
    * GET /messages
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @return  array
    */
   public function Get($Parameters)
   {
      $Format = $Parameters['Format'];

      $Return = array();
      $Return['Resource'] = 'messages/all.' . $Format;

      return $Return;
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