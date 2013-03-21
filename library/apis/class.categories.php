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
    private $Data;
    private $CategoryID;

    /**
     * GET
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    public function Get($Params)
    {
        $CategoryID = DS . $Params['URI'][2];

        $Map = 'vanilla/categories' . $CategoryID;

        $Data = array(
            'Map'   => $Map
        );

        return $Data;
    }

    /**
     * POST
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    public function Post($Params)
    {
        $CategoryID = DS . $Params['URI'][2];

        if ($CategoryID) {

            if ($Params['Request']['Delete']) {
                $Map = 'vanilla/settings/deletecategory' . $CategoryID;
            } else {
                $Map = 'vanilla/settings/editcategory' . $CategoryID;
            }

            $Args = array(
                'CategoryID' => ltrim($CategoryID, DS)
            );

        } else {
            $Map = 'vanilla/settings/addcategory';
        }

        $Data = array(
            'Map'   => $Map,
            'Args'  => $Args
        );

        return $Data;
    }

    /**
     * PUT
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    public function Put($Params)
    {
        $CategoryID = DS . $Params['URI'][2];

        if ($CategoryID) {

            $Map = 'vanilla/settings/editcategory' . $CategoryID;

            $Args = array(
                'CategoryID' => ltrim($CategoryID, DS)
            );

        }

        $Data = array(
            'Map'   => $Map,
            'Args'  => $Args
        );

        return $Data;
    }

    /**
     * DELETE
     *
     * @package API
     * @since 0.1.0
     * @access public
     */
    public function Delete($Params)
    {
        $CategoryID = DS . $Params['URI'][2];

        $Map = 'vanilla/settings/deletecategory' . $CategoryID;

        if ($CategoryID) {
            $Args = array(
                'CategoryID' => ltrim($CategoryID, DS)
            );
        }

        $Data = array(
            'Map'   => $Map,
            'Args'  => $Args
        );

        return $Data;
    }
}