<?php

/**
 * Discussions API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright (c) 2013-2015 Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
final class DiscussionsAPI extends APIMapper
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
            "controller" => "Discussions",
            "arguments" => [
                "Page" => val("Page", $data)
            ]
        ]);

        static::get("/[i:DiscussionID]", [
            "controller" => "Discussion",
            "arguments" => [
                "Page" => val("Page", $data)
            ]
        ]);

        static::get("/bookmarks", [
            "controller" => "Discussions",
            "method" => "bookmarked",
            "authenticate" => true,
            "arguments" => [
                "Page" => val("Page", $data)
            ]
        ]);

        static::get("/mine", [
            "controller" => "Discussions",
            "method" => "mine",
            "authenticate" => true,
            "arguments" => [
                "Page" => val("Page", $data)
            ]
        ]);

        static::post("/", [
            "controller" => "Post",
            "method" => "discussion"
        ]);

        static::post("/[i:DiscussionID]/comments", [
            "controller" => "Post",
            "method" => "comment"
        ]);

        static::post("/[i:DiscussionID]/announce", [
            "controller" => "Discussion",
            "method" => "announce",
            "authenticate" => true,
            "arguments" => [
                "Announce" => 1
            ]
        ]);

        static::post("/[i:DiscussionID]/unannounce", [
            "controller" => "Discussion",
            "method" => "announce",
            "authenticate" => true,
            "arguments" => [
                "Announce" => 0
            ]
        ]);

        static::post("/[i:DiscussionID]/dismiss", [
            "controller" => "Discussion",
            "method" => "dismissAnnouncement",
            "authenticate" => true
        ]);

        static::post("/[i:DiscussionID]/sink", [
            "controller" => "Discussion",
            "method" => "sink",
            "authenticate" => true,
            "arguments" => [
                "Sink" => 1
            ]
        ]);

        static::post("/[i:DiscussionID]/unsink", [
            "controller" => "Discussion",
            "method" => "sink",
            "authenticate" => true,
            "arguments" => [
                "Sink" => 0
            ]
        ]);

        static::post("/[i:DiscussionID]/close", [
            "controller" => "Discussion",
            "method" => "close",
            "authenticate" => true,
            "arguments" => [
                "Close" => 1
            ]
        ]);

        static::post("/[i:DiscussionID]/open", [
            "controller" => "Discussion",
            "method" => "close",
            "authenticate" => true,
            "arguments" => [
                "Close" => 0
            ]
        ]);

        static::post("/[i:DiscussionID]/bookmark", [
            "controller" => "Discussion",
            "method" => "bookmark",
            "authenticate" => true,
            "arguments" => [
                "Bookmark" => 1
            ]
        ]);

        static::post("/[i:DiscussionID]/unbookmark", [
            "controller" => "Discussion",
            "method" => "bookmark",
            "authenticate" => true,
            "arguments" => [
                "Bookmark" => 0
            ]
        ]);

        static::put("/[i:DiscussionID]", [
            "controller" => "Post",
            "method" => "editDiscussion"
        ]);

        static::put("/comments/[i:CommentID]", [
            "controller" => "Post",
            "method" => "editComment"
        ]);

        static::delete("/[i:DiscussionID]", [
            "controller" => "Discussion"
        , "method" => "delete"
        ]);

        static::delete("/comments/[i:CommentID]", [
            "controller" => "Discussion",
            "method" => "deleteComment",
            "arguments" => [
                "TransientKey" => Gdn::session()->transientKey()
            ]
        ]);
    }
}
