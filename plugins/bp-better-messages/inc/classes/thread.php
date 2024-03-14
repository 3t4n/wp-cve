<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class BM_Thread{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $subject;
    /**
     * @var string
     */
    public $type;
}
/**
 * BuddyPress Message Thread class.
 *
 * @since 1.0.0
 */
class BM_Messages_Thread {

    /**
     * The message thread ID.
     *
     * @since 1.0.0
     * @var int
     */
    public $thread_id;

    /**
     * The current messages.
     *
     * @since 1.0.0
     * @var array
     */
    public $messages;

    /**
     * The current recipients in the message thread.
     *
     * @since 1.0.0
     * @var array
     */
    public $recipients;

    /**
     * The user IDs of all messages in the message thread.
     *
     * @since 1.2.0
     * @var array
     */
    public $sender_ids;

    /**
     * The unread count for the logged-in user.
     *
     * @since 1.2.0
     * @var int
     */
    public $unread_count;

    /**
     * The content of the last message in this thread.
     *
     * @since 1.2.0
     * @var string
     */
    public $last_message_content;

    /**
     * The date of the last message in this thread.
     *
     * @since 1.2.0
     * @var string
     */
    public $last_message_date;

    /**
     * The ID of the last message in this thread.
     *
     * @since 1.2.0
     * @var int
     */
    public $last_message_id;

    /**
     * The subject of the last message in this thread.
     *
     * @since 1.2.0
     * @var string
     */
    public $last_message_subject;

    /**
     * The user ID of the author of the last message in this thread.
     *
     * @since 1.2.0
     * @var int
     */
    public $last_sender_id;

    /**
     * Sort order of the messages in this thread (ASC or DESC).
     *
     * @since 1.5.0
     * @var string
     */
    public $messages_order;

    /**
     * Constructor.
     *
     * @since 1.0.0
     * @since 10.0.0 Updated the `$args` with new paremeters.
     *
     * @param int    $thread_id          The message thread ID.
     * @param string $order              The order to sort the messages. Either 'ASC' or 'DESC'.
     *                                   Defaults to 'ASC'.
     * @param array  $args               {
     *     Array of arguments.
     *     @type int         $user_id             ID of the user to get the unread count.
     *     @type bool        $update_meta_cache   Whether to pre-fetch metadata for
     *                                            queried message items. Default: true.
     *     @type int|null    $page                Page of messages being requested. Default to null, meaning all.
     *     @type int|null    $per_page            Messages to return per page. Default to null, meaning all.
     *     @type string      $order               Optional. The order to sort the messages. Either 'ASC' or 'DESC'.
     *                                            Defaults to 'ASC'.
     *     @type int|null    $recipients_page     Page of recipients being requested. Default to null, meaning all.
     *     @type int|null    $recipients_per_page Recipients to return per page. Defaults to null, meaning all.
     * }
     */
    public function __construct( $thread_id = 0, $order = 'ASC', $args = array() ) {
        if ( ! empty( $thread_id ) ) {
            $this->populate( $thread_id, $order, $args );
        }
    }

    /**
     * Populate method.
     *
     * Used in the constructor.
     *
     * @since 1.0.0
     * @since 10.0.0 Updated the `$args` with new paremeters.
     *
     * @param int    $thread_id                   The message thread ID.
     * @param string $order                       The order to sort the messages. Either 'ASC' or 'DESC'.
     *                                            Defaults to 'ASC'.
     * @param array  $args                        {
     *     Array of arguments.
     *     @type int         $user_id             ID of the user to get the unread count.
     *     @type bool        $update_meta_cache   Whether to pre-fetch metadata for
     *                                            queried message items. Default: true.
     *     @type int|null    $page                Page of messages being requested. Default to null, meaning all.
     *     @type int|null    $per_page            Messages to return per page. Default to null, meaning all.
     *     @type string      $order               The order to sort the messages. Either 'ASC' or 'DESC'.
     *                                            Defaults to 'ASC'.
     *     @type int|null    $recipients_page     Page of recipients being requested. Default to null, meaning all.
     *     @type int|null    $recipients_per_page Recipients to return per page. Defaults to null, meaning all.
     * }
     * @return bool False if there are no messages.
     */
    public function populate( $thread_id = 0, $order = 'ASC', $args = array() ) {
        $this->thread_id      = (int) $thread_id;
    }

    /**
     * Mark a thread initialized in this class as read.
     *
     * @since 1.0.0
     *
     * @see BM_Messages_Thread::mark_as_read()
     */
    public function mark_read() {
        self::mark_as_read( $this->thread_id );
    }

    /**
     * Mark a thread initialized in this class as unread.
     *
     * @since 1.0.0
     *
     * @see BM_Messages_Thread::mark_as_unread()
     */
    public function mark_unread() {
        self::mark_as_unread( $this->thread_id );
    }

    /**
     * Returns recipients for a message thread.
     *
     * @since 1.0.0
     * @since 2.3.0  Added `$thread_id` as a parameter.
     * @since 10.0.0 Added `$args` as a parameter.
     *
     * @global BuddyPress $bp The one true BuddyPress instance.
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int   $thread_id Message thread ID.
     * @param array $args      {
     *     Array of arguments.
     *     @type int|null $recipients_page     Page of recipients being requested. Default to all.
     *     @type int|null $recipients_per_page Recipients to return per page. Defaults to all.
     * }
     * @return array
     */
    public function get_recipients( $thread_id = 0, $args = array() ) {
        global $wpdb;

        if ( empty( $thread_id ) ) {
            $thread_id = $this->thread_id;
        }

        $thread_id = (int) $thread_id;

        if ( empty( $thread_id ) ) {
            return array();
        }

        $bp = buddypress();
        $r  = bp_parse_args(
            $args,
            array(
                'recipients_page'     => null,
                'recipients_per_page' => null,
            )
        );

        // Get recipients from cache if available.
        $recipients = wp_cache_get( 'thread_recipients_' . $thread_id, 'bm_messages' );

        // Get recipients and cache it.
        if ( empty( $recipients ) ) {

            // Query recipients.
            $results = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$bp->messages->table_name_recipients} WHERE thread_id = %d",
                    $thread_id
                )
            );

            $recipients = array();
            foreach ( (array) $results as $recipient ) {
                $recipient_properties              = get_object_vars( $recipient );
                $recipients[ $recipient->user_id ] = (object) array_map( 'intval', $recipient_properties );
            }

            // Cache recipients.
            wp_cache_set( 'thread_recipients_' . $thread_id, (array) $recipients, 'bm_messages' );
        }

        // Paginate the results.
        if ( ! empty( $recipients ) && $r['recipients_per_page'] && $r['recipients_page'] ) {
            $start      = ( $r['recipients_page'] - 1 ) * ( $r['recipients_per_page'] );
            $recipients = array_slice( $recipients, $start, $r['recipients_per_page'] );
        }

        /**
         * Filters the recipients of a message thread.
         *
         * @since 2.2.0
         * @since 10.0.0 Added `$r` as a parameter.
         *
         * @param array $recipients Array of recipient objects.
         * @param int   $thread_id  ID of the thread.
         * @param array $r          An array of parameters.
         */
        return apply_filters( 'bp_messages_thread_get_recipients', (array) $recipients, (int) $thread_id, (array) $r );
    }

    /**
     * Static method to get message recipients by thread ID.
     *
     * @since 2.3.0
     *
     * @param int $thread_id The thread ID.
     * @return array
     */
    public static function get_recipients_for_thread( $thread_id = 0 ) {
        return Better_Messages()->functions->get_recipients( $thread_id );
    }

    /**
     * Mark messages in a thread as deleted or delete all messages in a thread.
     *
     * Note: All messages in a thread are deleted once every recipient in a thread
     * has marked the thread as deleted.
     *
     * @since 1.0.0
     * @since 2.7.0 The $user_id parameter was added. Previously the current user
     *              was always assumed.
     *
     * @global BuddyPress $bp The one true BuddyPress instance.
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $thread_id The message thread ID.
     * @param int $user_id The ID of the user in the thread to mark messages as
     *                     deleted for. Defaults to the current logged-in user.
     *
     * @return bool
     */
    public static function delete( $thread_id = 0, $user_id = 0 ) {
        global $wpdb;

        $thread_id = (int) $thread_id;
        $user_id = (int) $user_id;

        if ( empty( $user_id ) ) {
            $user_id = Better_Messages()->functions->get_current_user_id();
        }

        /**
         * Fires before a message thread is marked as deleted.
         *
         * @since 2.2.0
         * @since 2.7.0 The $user_id parameter was added.
         *
         * @param int $thread_id ID of the thread being deleted.
         * @param int $user_id   ID of the user that the thread is being deleted for.
         */
        do_action( 'bp_messages_thread_before_mark_delete', $thread_id, $user_id );

        $bp = buddypress();

        // Mark messages as deleted
        $wpdb->query( $wpdb->prepare( "UPDATE {$bp->messages->table_name_recipients} SET is_deleted = 1 WHERE thread_id = %d AND user_id = %d", $thread_id, $user_id ) );

        // Get the message ids in order to pass to the action.
        $message_ids = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM {$bp->messages->table_name_messages} WHERE thread_id = %d", $thread_id ) );

        // Check to see if any more recipients remain for this message.
        $recipients = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$bp->messages->table_name_recipients} WHERE thread_id = %d AND is_deleted = 0", $thread_id ) );

        // No more recipients so delete all messages associated with the thread.
        if ( empty( $recipients ) ) {

            /**
             * Fires before an entire message thread is deleted.
             *
             * @since 2.2.0
             *
             * @param int   $thread_id   ID of the thread being deleted.
             * @param array $message_ids IDs of messages being deleted.
             */
            do_action( 'bp_messages_thread_before_delete', $thread_id, $message_ids );

            // Delete all the messages.
            $wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->messages->table_name_messages} WHERE thread_id = %d", $thread_id ) );

            // Do something for each message ID.
            foreach ( $message_ids as $message_id ) {

                // Delete message meta.
                //bp_messages_delete_meta( $message_id );

                /**
                 * Fires after a message is deleted. This hook is poorly named.
                 *
                 * @since 1.0.0
                 *
                 * @param int $message_id ID of the message.
                 */
                do_action( 'messages_thread_deleted_thread', $message_id );
            }

            // Delete all the recipients.
            $wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->messages->table_name_recipients} WHERE thread_id = %d", $thread_id ) );
        }

        /**
         * Fires after a message thread is either marked as deleted or deleted.
         *
         * @since 2.2.0
         * @since 2.7.0 The $user_id parameter was added.
         *
         * @param int   $thread_id   ID of the thread being deleted.
         * @param array $message_ids IDs of messages being deleted.
         * @param int   $user_id     ID of the user the threads were deleted for.
         */
        do_action( 'bp_messages_thread_after_delete', $thread_id, $message_ids, $user_id );

        return true;
    }

    /**
     * Exit a user from a thread.
     *
     * @since 10.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $thread_id The message thread ID.
     * @param int $user_id The ID of the user in the thread.
     *                     Defaults to the current logged-in user.
     *
     * @return bool
     */
    public static function exit_thread( $thread_id = 0, $user_id = 0 ) {
        global $wpdb;

        $thread_id = (int) $thread_id;
        $user_id   = (int) $user_id;

        if ( empty( $user_id ) ) {
            $user_id = Better_Messages()->functions->get_current_user_id();
        }

        // Check the user is a recipient of the thread and recipients count > 2.
        $recipients    = self::get_recipients_for_thread( $thread_id );
        $recipient_ids = wp_list_pluck( $recipients, 'user_id' );

        if ( 2 >= count( $recipient_ids ) || ! in_array( $user_id, $recipient_ids, true ) ) {
            return false;
        }

        /**
         * Fires before a user exits a thread.
         *
         * @since 10.0.0
         *
         * @param int $thread_id ID of the thread being deleted.
         * @param int $user_id   ID of the user that the thread is being deleted for.
         */
        do_action( 'bp_messages_thread_before_exit', $thread_id, $user_id );

        $bp = buddypress();

        // Delete the user from messages recipients
        $exited = $wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->messages->table_name_recipients} WHERE thread_id = %d AND user_id = %d", $thread_id, $user_id ) );

        // Bail if the user wasn't removed from the recipients list.
        if ( empty( $exited ) ) {
            return false;
        }

        /**
         * Fires after a user exits a thread.
         *
         * @since 10.0.0
         *
         * @param int   $thread_id ID of the thread being deleted.
         * @param int   $user_id   ID of the user the threads were deleted for.
         */
        do_action( 'bp_messages_thread_after_exit', $thread_id, $user_id );

        return true;
    }

    /**
     * Mark a thread as read.
     *
     * @since 1.0.0
     * @since 9.0.0 Added the `user_id` parameter.
     *
     * @global BuddyPress $bp The one true BuddyPress instance.
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $thread_id The message thread ID.
     * @param int $user_id   The user the thread will be marked as read.
     *
     * @return bool|int Number of threads marked as read or false on error.
     */
    public static function mark_as_read( $thread_id = 0, $user_id = 0 ) {
        global $wpdb;

        if ( empty( $user_id ) ) {
            $user_id =
                bp_displayed_user_id() ?
                    bp_displayed_user_id() :
                    Better_Messages()->functions->get_current_user_id();
        }

        $bp       = buddypress();
        $num_rows = $wpdb->query( $wpdb->prepare( "UPDATE {$bp->messages->table_name_recipients} SET unread_count = 0 WHERE user_id = %d AND thread_id = %d", $user_id, $thread_id ) );

        wp_cache_delete( 'thread_recipients_' . $thread_id, 'bm_messages' );
        wp_cache_delete( $user_id, 'bm_messages_unread_count' );

        /**
         * Fires when messages thread was marked as read.
         *
         * @since 2.8.0
         * @since 9.0.0 Added the `user_id` parameter.
         * @since 10.0.0 Added the `$num_rows` parameter.
         *
         * @param int $thread_id The message thread ID.
         * @param int $user_id   The user the thread will be marked as read.
         * @param bool|int $num_rows    Number of threads marked as unread or false on error.
         */
        do_action( 'messages_thread_mark_as_read', $thread_id, $user_id, $num_rows );

        return $num_rows;
    }

    /**
     * Mark a thread as unread.
     *
     * @since 1.0.0
     * @since 9.0.0 Added the `user_id` parameter.
     *
     * @global BuddyPress $bp The one true BuddyPress instance.
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $thread_id The message thread ID.
     * @param int $user_id   The user the thread will be marked as unread.
     *
     * @return bool|int Number of threads marked as unread or false on error.
     */
    public static function mark_as_unread( $thread_id = 0, $user_id = 0 ) {
        global $wpdb;

        if ( empty( $user_id ) ) {
            $user_id =
                bp_displayed_user_id() ?
                    bp_displayed_user_id() :
                    Better_Messages()->functions->get_current_user_id();
        }

        $bp       = buddypress();
        $num_rows = $wpdb->query( $wpdb->prepare( "UPDATE {$bp->messages->table_name_recipients} SET unread_count = 1 WHERE user_id = %d AND thread_id = %d", $user_id, $thread_id ) );

        wp_cache_delete( 'thread_recipients_' . $thread_id, 'bm_messages' );
        wp_cache_delete( $user_id, 'bm_messages_unread_count' );

        /**
         * Fires when messages thread was marked as unread.
         *
         * @since 2.8.0
         * @since 9.0.0  Added the `$user_id` parameter.
         * @since 10.0.0 Added the `$num_rows` parameter.
         *
         * @param int      $thread_id The message thread ID.
         * @param int      $user_id   The user the thread will be marked as unread.
         * @param bool|int $num_rows  Number of threads marked as unread or false on error.
         */
        do_action( 'messages_thread_mark_as_unread', $thread_id, $user_id, $num_rows );

        return $num_rows;
    }

    /**
     * Returns the total number of message threads for a user.
     *
     * @since 1.0.0
     *
     * @param int    $user_id The user ID.
     * @param string $box     The type of mailbox to get. Either 'inbox' or 'sentbox'.
     *                        Defaults to 'inbox'.
     * @param string $type    The type of messages to get. Either 'all' or 'unread'.
     *                        or 'read'. Defaults to 'all'.
     * @return int Total thread count for the provided user.
     */
    public static function get_total_threads_for_user( $user_id, $box = 'inbox', $type = 'all' ) {
        global $wpdb;

        $exclude_sender = $type_sql = '';
        if ( $box !== 'sentbox' ) {
            $exclude_sender = 'AND sender_only != 1';
        }

        if ( $type === 'unread' ) {
            $type_sql = 'AND unread_count != 0';
        } elseif ( $type === 'read' ) {
            $type_sql = 'AND unread_count = 0';
        }

        return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(thread_id) FROM " . bm_get_table('recipients') . " WHERE user_id = %d AND is_deleted = 0 {$exclude_sender} {$type_sql}", $user_id ) );
    }

}
