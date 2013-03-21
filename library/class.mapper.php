<?php if (!defined('APPLICATION')) exit();

/**
 * Abstract Mapper class used for deining API resources
 *
 * By extending this class, APIs can be defined using pre-defined, abstract
 * methods thus ensuring compatibility with the API mapping mechanism.
 *
 * Using abstractions over an interface also allows us to define common
 * functions available for classes extending our abstrat class. This will
 * hopefully prove useful when we'll need to implement utility functions
 * in the API.
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
abstract class Mapper
{
    /**
     * GET
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    abstract public function Get($Params);

    /**
     * POST
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    abstract public function Post($Params);

    /**
     * PUT
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    abstract public function Put($Params);

    /**
     * DELETE
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    abstract public function Delete($Params);
}