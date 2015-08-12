<?php

/**
 * Categories API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright (c) 2013-2015 Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
final class CategoriesAPI extends APIMapper
{
    /**
     * Register API endpoints
     *
     * @since  0.1.0
     * @access public
     * @param  array $data
     * @return void
     * @static
     */
    public static function register($data)
    {
        static::get("/", [
            "controller" => "Categories",
            "method" => "all"
        ]);

        static::get("/[i:CategoryIdentifier]", [
            "controller" => "Categories"
        ]);

        static::post("/", [
            "application" => "Vanilla",
            "controller" => "Settings",
            "method" => "addCategory"
        ]);

        static::post("/[i:CategoryID]/follow", [
            "controller" => "Category",
            "method" => "follow",
            "authenticate" => true,
            "arguments" => [
                "Value" => 1,
                "TKey" => Gdn::session()->transientKey()
            ]
        ]);

        static::post("/[i:CategoryID]/unfollow", [
            "controller" => "Category",
            "method" => "follow",
            "authenticate" => true,
            "arguments" => [
                "Value" => 0,
                "TKey" => Gdn::session()->transientKey()
            ]
        ]);

        static::put("/[i:CategoryID]", [
            "application" => "Vanilla",
            "controller" => "Settings",
            "method" => "editCategory"
        ]);

        static::delete("/[i:CategoryID]", [
            "application" => "Vanilla",
            "controller" => "Settings",
            "method" => "deleteCategory"
        ]);
    }
}
