<?php if (!defined('APPLICATION')) exit();

/**
 * Roles API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class RolesAPI extends APIMapper
{
   /**
    * Retrieve roles
    *
    * GET /roles
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Get($Path)
   {
      $this->API['Controller'] = 'Role';
   }

   /**
    * Create roles
    *
    * POST /roles
    *
    * @since   0.1.0
    * @access  public
    * @param   array $Path
    */
   public function Post($Path)
   {
      $this->API['Controller']   = 'Role';
      $this->API['Method']       = 'Add';
      $this->API['Arguments']    = array(
         'TransientKey' => Gdn::Session()->TransientKey()
         );
   }

   /**
    * Update an existing role
    *
    * PUT /roles/:id
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

      $this->API['Controller']   = 'Role';
      $this->API['Method']       = 'Edit';
      $this->API['Arguments']    = array(
         'RoleID'       => $ID,
         'TransientKey' => Gdn::Session()->TransientKey()
         );
   }

   /**
    * Delete an existing role
    *
    * DELETE /users/:id
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

      $this->API['Controller']   = 'Role';
      $this->API['Method']       = 'Delete';
      $this->API['Arguments']    = array(
         'RoleID'       => $ID,
         'TransientKey' => Gdn::Session()->TransientKey()
         );
   }
}
