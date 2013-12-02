<?php if (!defined('APPLICATION')) exit();

/**
 * Users API
 *
 * @package    API
 * @since      0.1.0
 * @author     Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright  Copyright 2013 © Kasper Kronborg Isager
 * @license    http://opensource.org/licenses/MIT MIT
 */
class UsersAPI extends APIMapper
{
    /**
     * Retrieve users
     *
     * GET /users
     * GET /users/:id
     *
     * @since   0.1.0
     * @access  public
     * @param   array $Path
     */
    public function Get($Path)
    {
        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if (!$ID) {

            $this->API['Controller'] = 'Profile';
            $this->API['Arguments']  = array(
                'User'   => $ID,
                'UserID' => $ID
            );

        } else {

            $this->API['Controller'] = 'User';

        }
    }

    /**
     * Create a new user
     *
     * POST /users
     *
     * @since   0.1.0
     * @access  public
     * @param   array $Path
     */
    public function Post($Path)
    {
        $this->API['Controller'] = 'User';
        $this->API['Method']     = 'Add';
        $this->API['Arguments']  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }

    /**
     * Update an existing user
     *
     * PUT /users/:id
     *
     * @since   0.1.0
     * @access  public
     * @param   array $Path
     * @throws  Exception
     */
    public function Put($Path)
    {
        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if (!$ID) {
            throw new Exception("No ID defined", 401);
        }

        $this->API['Controller'] = 'User';
        $this->API['Method']     = 'Edit';
        $this->API['Arguments']  = array(
            'UserID'       => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }

    /**
     * Delete an existing user
     *
     * DELETE /users/:id
     *
     * @since   0.1.0
     * @access  public
     * @param   array $Path
     * @throws  Exception
     */
    public function Delete($Path)
    {
        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if (!$ID) {
            throw new Exception("No ID defined", 401);
        }

        $this->API['Controller'] = 'User';
        $this->API['Method']     = 'Delete';
        $this->API['Arguments']  = array(
            'UserID'       => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }
}
