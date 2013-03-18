<?php if (!defined('APPLICATION')) exit();

/**
 * API to access categories
 *
 * @package API
 * @since 0.1.0
 * @author Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright © 2013
 * @license http://opensource.org/licenses/MIT MIT
 */
class CategoryController extends APIController {

    public $Resource = array();

    /**
     * Define resources used in controller
     * 
     * @since 0.1.0
     * @access public
     */
    public $Uses = array('CategoryModel');

    /**
     * Build the category API and render it
     * 
     * GET /category
     * GET /category/:id
     * 
     * @param int $CategoryID
     * @since 0.1.0
     * @access public
     */
    public function Index($CategoryID) {

        $Limit = GetIncomingValue('limit', null);
        $Offset = GetIncomingValue('offset', null);

        $CategoryModel = $this->CategoryModel;

        if ($CategoryID):

            $Categories = $CategoryModel->GetFull($CategoryID)->ResultArray();

        elseif (is_null($Limit) && is_null($Offset)):

            $Categories = $CategoryModel->GetFull()->ResultArray();

        else:

            $Categories = array_slice($CategoryModel->GetFull($CategoryID)->ResultArray(), $Offset, $Limit);
        
        endif;

        $Data = array();

        foreach ($Categories as $Category):
            $Data[] = $Category;
        endforeach;

        if ($CategoryID):
            $Data = array_shift($Categories);
        endif;

        $this->RenderData($Data);

    }

    /**
     * Define resource readable by Swagger
     * 
     * @return array
     * @since 0.1.0
     * @access public
     */
    public function Resource() {

        return array(
            'resourcePath' => '/category',
            'apis' => array(
                array(
                    'path' => '/category',
                    'description' => T('Operations related to categories'),
                    'operations' => array(
                        array(
                            'httpMethod' => 'GET',
                            'nickname' => 'categories',
                            'summary' => 'List all categories',
                            'notes' => T('Only categories that you have permission to access will be listed.'),
                            'parameters' => array(
                                array(
                                    'name' => 'limit',
                                    'description' => T('Limit the number of categories retrieved'),
                                    'paramType' => 'query',
                                    'required' => false,
                                    'allowMultiple' => false,
                                    'dataType' => 'integer'
                                ),
                                array(
                                    'name' => 'offset',
                                    'description' => T('Offset the categories relative to how you\'ve sorted them'),
                                    'paramType' => 'query',
                                    'required' => false,
                                    'allowMultiple' => false,
                                    'dataType' => 'integer'
                                )
                            )
                        )
                    )
                ),
                array(
                    'path' => '/category/{id}',
                    'description' => T('Operations related to categories'),
                    'operations' => array(
                        array(
                            'httpMethod' => 'GET',
                            'nickname' => 'category_id',
                            'summary' => T('Find a category by its unique ID'),
                            'notes' => T('Only categories that you have permission to access will be listed.'),
                            'parameters' => array(
                                array(
                                    'name' => 'id',
                                    'description' => T('The unique ID for the category you wish to retrieve'),
                                    'paramType' => 'path',
                                    'required' => true,
                                    'allowMultiple' => false,
                                    'dataType' => 'integer'
                                )
                            )
                        ),
                        array(
                            'httpMethod' => 'POST',
                            'nickname' => 'category_id',
                            'summary' => T('Find a category by its unique ID'),
                            'notes' => T('Only categories that you have permission to access will be listed.'),
                            'parameters' => array(
                                array(
                                    'name' => 'id',
                                    'description' => T('The unique ID for the category you wish to retrieve'),
                                    'paramType' => 'path',
                                    'required' => true,
                                    'allowMultiple' => false,
                                    'dataType' => 'integer'
                                )
                            )
                        )
                    )
                )
            )
        );

    }

}