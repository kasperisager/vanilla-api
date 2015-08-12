<?php

/**
 * Activities API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright (c) 2013-2015 Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
final class ActivitiesAPI extends APIMapper
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
            "controller" => "Activity"
        ]);

        static::get("/[i:ActivityID]", [
            "controller" => "Activity",
            "method" => "item"
        ]);

        static::post("/", [
            "controller" => "Activity",
            "method" => "post",
            "arguments" => [
                "Notify" => val("Notify", $data)
            ]
        ]);

        static::post("/[i:ActivityID]/comments", [
            "controller" => "Activity",
            "method" => "comment"
        ]);

        static::delete("/[i:ActivityID]", [
            "controller" => "Activity",
            "method" => "delete",
            "arguments" => [
                "TransientKey" => Gdn::session()->transientKey()
            ]
        ]);

        static::delete("/comments/[i:ID]", [
            "controller" => "Activity",
            "method" => "deleteComment",
            "arguments" => [
                "TK" => Gdn::session()->transientKey()
            ]
        ]);
    }
}
