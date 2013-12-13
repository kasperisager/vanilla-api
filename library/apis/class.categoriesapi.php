<?php if (!defined('APPLICATION')) exit();

/**
 * Categories API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class CategoriesAPI extends APIMapper
{
    /**
     * Retrieve categories
     *
     * GET /categories
     * GET /categories/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     */
    public function Get($Path)
    {
        $this->API['Controller'] = 'Categories';

        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if ($ID) {

            $this->API['Arguments'] = array(
                'CategoryID' => $ID
            );

        } else {

            $this->API['Method'] = 'All';

        }
    }

    /**
     * Create categories
     *
     * POST /categories
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     */
    public function Post($Path)
    {
        $this->API['Application'] = 'Vanilla';
        $this->API['Controller']  = 'Settings';
        $this->API['Method']      = 'AddCategory';
    }

    /**
     * Update categories
     *
     * PUT /categories/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     */
    public function Put($Path)
    {
        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if (!$ID) {
            throw new Exception("No ID defined", 401);
        }

        $this->API['Application'] = 'Vanilla';
        $this->API['Controller']  = 'Settings';
        $this->API['Method']      = 'EditCategory';
        $this->API['Arguments']   = array(
            'CategoryID'   => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }

    /**
     * Remove categories
     *
     * DELETE /categories/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @throws Exception
     */
    public function Delete($Path)
    {
        $ID = (isset($Path[2])) ? $Path[2] : FALSE;

        if (!$ID) {
            throw new Exception("No ID defined", 401);
        }

        $this->API['Application'] = 'Vanilla';
        $this->API['Controller']  = 'Settings';
        $this->API['Method']      = 'DeleteCategory';
        $this->API['Arguments']   = array(
            'CategoryID'   => $ID,
            'TransientKey' => Gdn::Session()->TransientKey()
        );
    }
}
