<?php if (!defined('APPLICATION')) exit;

/**
 * Conversations API
 *
 * @package   API
 * @since     0.1.0
 * @author    Kasper Kronborg Isager <kasperisager@gmail.com>
 * @copyright Copyright 2013 © Kasper Kronborg Isager
 * @license   http://opensource.org/licenses/MIT MIT
 */
class ConversationsAPI extends APIMapper
{
    /**
     * Retrieve messages
     *
     * GET /messages
     * GET /messages/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Get($Path)
    {
        static::$Controller   = 'Messages';
        static::$Authenticate = TRUE;

        $Arg = val(2, $Path);

        if (is_numeric($Arg)) {
            static::$Arguments = array(
                'ConversationID' => $Arg
            );
        } else {
            static::$Method = 'All';
        }
    }

    /**
     * Create conversations and messages
     *
     * POST /conversations
     * POST /conversation/:id/messages
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Post($Path)
    {
        static::$Controller = 'Messages';
        static::$Arguments  = array(
            'TransientKey' => Gdn::Session()->TransientKey()
        );

        $Arg = val(2, $Path);

        if (is_numeric($Arg) && val(3, $Path) == 'messages') {
            static::$Method = 'AddMessage';
            static::$Arguments['ConversationID'] = $Arg;
        } else {
            static::$Method = 'Add';
        }
    }

    /**
     * Delete (clear) the message history of a conversation
     *
     * DELETE /conversations/:id
     *
     * @since  0.1.0
     * @access public
     * @param  array $Path
     * @static
     */
    public static function Delete($Path)
    {
        $Arg = val(2, $Path);

        if (!$Arg) throw new Exception("No ID defined", 401);

        static::$Controller = 'Messages';
        static::$Method     = 'Clear';
        static::$Arguments  = array(
            'ConversationID' => $Arg,
            'TransientKey'   => Gdn::Session()->TransientKey()
        );
    }
}
