<?php if (!defined('APPLICATION')) exit();

/**
 * Mapper interface used for defining API resources
 *
 * By implementing this class, APIs can be defined using pre-defined, abstract
 * methods thus ensuring compatibility with the API mapping mechanism.
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 * @abstract
 */
interface API_IMapper
{
   /**
    * GET
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @abstract
    */
   public function Get($Parameters);

   /**
    * POST
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @abstract
    */
   public function Post($Parameters);

   /**
    * PUT
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @abstract
    */
   public function Put($Parameters);

   /**
    * DELETE
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Parameters
    * @abstract
    */
   public function Delete($Parameters);
}