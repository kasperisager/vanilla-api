<?php if (!defined('APPLICATION')) exit();

/**
 * Categories API
 *
 * @package     API
 * @version     0.1.0
 * @author      Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright   Copyright © 2013
 * @license     http://opensource.org/licenses/MIT MIT
 */
class Categories extends Mapper
{
    /**
     * Retrieve categories
     *
     * GET /categories
     * GET /categories/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Get($Params)
    {
        $CategoryID = $Params['URI'][2];

        if ($CategoryID) {
            $Data = array('Map' => 'vanilla/categories' . DS . $CategoryID);
        } else {
            $Data = array('Map' => 'vanilla/categories/all');
        }

        return $Data;
    }

    /**
     * Creat categories
     *
     * POST /categories
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Post($Params)
    {
        $Data = array('Map' => 'vanilla/settings/addcategory');
        return $Data;
    }

    /**
     * Update categories
     *
     * PUT /categories/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Put($Params)
    {
        $CategoryID = $Params['URI'][2];

        if ($CategoryID) {
            $Map = 'vanilla/settings/editcategory' . DS . $CategoryID;
            $Args = array(
                'CategoryID' => $CategoryID,
                'TransientKey'  => Gdn::Session()->TransientKey()
            );
        }

        $Data = array('Map' => $Map, 'Args' => $Args);
        return $Data;
    }

    /**
     * Remove categories
     *
     * DELETE /categories/:id
     *
     * @package API
     * @since   0.1.0
     * @access  public
     */
    public function Delete($Params)
    {
        $CategoryID = $Params['URI'][2];

        if ($CategoryID) {
            $Map = 'vanilla/settings/deletecategory' . DS . $CategoryID;
            $Args = array(
                'CategoryID'    => $CategoryID,
                'TransientKey'  => Gdn::Session()->TransientKey()
            );
        }

        $Data = array('Map' => $Map, 'Args' => $Args);
        return $Data;
    }
}