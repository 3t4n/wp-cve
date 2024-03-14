<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BP_Core_Notification_Abstract' ) ) {
    return;
}

/**
 * Set up the Custom notification class.
 */
class BetterMessagesNewMessageNotification extends BP_Core_Notification_Abstract {

    /**
     * Instance of this class.
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Get the instance of this class.
     *
     * @return null|BetterMessagesNewMessageNotification|Controller|object
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor method.
     */
    public function __construct() {
        $this->start();

        add_filter( 'bb_is_better_messages_new_message_email_preference_type_render', array( $this, 'force_enable_email' ), 10, 3 );
        add_filter( 'better_messages_is_user_emails_enabled', array( $this, 'overwrite_user_email_enabled' ), 10, 2 );

        add_action( 'better_messages_user_emails_enabled_update', array( $this, 'user_emails_enabled_update' ), 10, 2 );
    }

    public function user_emails_enabled_update( $user_id, $value ){
        Better_Messages()->functions->update_user_meta( $user_id, 'better_messages_new_message', $value );
    }

    public function overwrite_user_email_enabled($enabled, $user_id){
        $enabled = Better_Messages()->functions->get_user_meta( $user_id, 'better_messages_new_message', true ) != 'no';
        return $enabled;
    }

    public function force_enable_email( $is_render, $key, $_key ){
        return true;
    }

    /**
     * Initialize all methods inside it.
     *
     * @return mixed|void
     */
    public function load() {

        /**
         * Register Notification Group.
         *
         * @param string $group_key         Group key.
         * @param string $group_label       Group label.
         * @param string $group_admin_label Group admin label.
         * @param int    $priority          Priority of the group.
         */
        $this->register_notification_group(
            'better_messages',
            esc_html__( 'Private Messages', 'bp-better-messages' ), // For the frontend.
            esc_html__( 'Better Messages', 'bp-better-messages' ) // For the backend.
        );

        $this->register_custom_notification();


    }

    /**
     * Register notification for user mention.
     */
    public function register_custom_notification() {
        /**
         * Register Notification Type.
         *
         * @param string $notification_type        Notification Type key.
         * @param string $notification_label       Notification label.
         * @param string $notification_admin_label Notification admin label.
         * @param string $notification_group       Notification group.
         * @param bool   $default                  Default status for enabled/disabled.
         */
        $this->register_notification_type(
            'better_messages_new_message',
            esc_html__( 'You receive a new private message', 'bp-better-messages' ),
            esc_html__( 'A member receives a new private message ', 'bp-better-messages' ),
            'better_messages'
        );

        /**
         * Register notification.
         *
         * @param string $component         Component name.
         * @param string $component_action  Component action.
         * @param string $notification_type Notification Type key.
         * @param string $icon_class        Notification Small Icon.
         */
        $this->register_notification(
            'better_messages',
            'better_messages_new_message',
            'better_messages_new_message'
        );
    }

    /**
     * Format the notifications.
     *
     * @param string $content               Notification content.
     * @param int    $item_id               Notification item ID.
     * @param int    $secondary_item_id     Notification secondary item ID.
     * @param int    $action_item_count     Number of notifications with the same action.
     * @param string $component_action_name Canonical notification action.
     * @param string $component_name        Notification component ID.
     * @param int    $notification_id       Notification ID.
     * @param string $screen                Notification Screen type.
     *
     * @return array
     */
    public function format_notification( $content, $item_id, $secondary_item_id, $action_item_count, $component_action_name, $component_name, $notification_id, $screen ) {
        return $content;
    }
}
