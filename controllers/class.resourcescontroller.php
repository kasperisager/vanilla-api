<?php if (!defined('APPLICATION')) exit();

/**
 * Resource controller used to get information about each information about
 * each controller and output this as JSON for Swagger UI to read
 *
 * @package API
 * @since 0.1.0
 * @author Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright © 2013
 * @license http://opensource.org/licenses/MIT MIT
 */
class ResourcesController extends APIController {

    /**
     * Render the JSON resource listing
     *
     * @param string $Resource
     * @since 0.1.0
     * @package API
     */
    public function Index($Resource = NULL) {

        $Apis = array(
            'apis' => array(
                array(
                    'path' => '/resources/category',
                    'description' => 'Operations related to categories'
                ),
                array(
                    'path' => '/resources/session',
                    'description' => 'Operations related to sessions'
                )
            )
        );

        if ($Resource):

            $Resource = $Resource.'Controller';

            if (is_subclass_of($Resource, get_parent_class($this))):
                $Resource = new $Resource;
                $Resource = $Resource->Resource();
                $Data = array_merge(parent::Meta(), $Apis, $Resource);
            else:

                http_response_code(404);

                $Data = array(
                    'errorResponses' => array(
                        array(
                            'code' => 404,
                            'reason' => T('Not Found')
                        )
                    )
                );

            endif;
        else:
            $Data = array_merge(parent::Meta(), $Apis);
        endif;

        $this->RenderData($Data);

    }

}