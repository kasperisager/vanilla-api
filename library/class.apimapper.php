<?php if (!defined('APPLICATION')) exit();

/**
 * Abstract Mapper class used for defining API resources
 *
 * By extending this class, APIs can be defined using pre-defined, abstract
 * methods thus ensuring compatibility with the API mapping mechanism.
 *
 * Using abstractions over an interface also allows us to define common
 * functions available for classes extending our abstrat class. This will
 * hopefully prove useful when we'll need to implement utility functions
 * in the API.
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
abstract class APIMapper
{
   /**
    * GET
    *
    * @since   0.1.0
    * @access  protected
    */
   abstract protected function Get($Parameters);

   /**
    * POST
    *
    * @since   0.1.0
    * @access  protected
    */
   abstract protected function Post($Parameters);

   /**
    * PUT
    *
    * @since   0.1.0
    * @access  public
    */
   abstract protected function Put($Parameters);

   /**
    * DELETE
    *
    * @since   0.1.0
    * @access  public
    */
   abstract protected function Delete($Parameters);
}