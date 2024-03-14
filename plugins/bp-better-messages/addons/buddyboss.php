<?php

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_BuddyBoss' ) ) {

    class Better_Messages_BuddyBoss
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_BuddyBoss();
            }

            return $instance;
        }

        public function __construct()
        {
            add_filter( 'bp_better_messages_after_format_message', array($this, 'buddyboss_group_messages'), 10, 4);
            add_filter( 'heartbeat_received', array($this, 'heartbeat_unread_notifications'), 12);
            add_filter( 'bb_pusher_enabled_features', array( $this, 'disable_bb_pusher') );

            add_action('wp_ajax_buddyboss_theme_get_header_unread_messages', array($this, 'buddyboss_theme_get_header_unread_messages'), 9);

            /**
             * BuddyBoss moderation
             */
            if( function_exists('bp_is_moderation_member_blocking_enable') ){
                $bb_blocking_enabled = bp_is_moderation_member_blocking_enable();
                if( $bb_blocking_enabled ){
                    add_filter( 'better_messages_can_send_message', array($this, 'buddyboss_disable_message_to_blocked'), 10, 3);
                }
            }

            if( function_exists('bb_access_control_member_can_send_message') ) {
                add_filter( 'better_messages_can_send_message', array($this, 'buddyboss_blocked_message'), 10, 3);
            }

            add_filter('bp_messages_thread_current_threads', array( $this, 'buddyboss_notifications_fix' ), 10, 1 );

            add_filter('bb_exclude_endpoints_from_restriction', array( $this, 'buddyboss_disable_rest_api_block' ), 10, 2 );

            add_filter( 'bp_has_message_threads', array( $this, 'has_message_threads' ), 10, 3 );

            if ( class_exists( 'BP_Core_Notification_Abstract' ) ) {
                add_action('bb_register_notification_preferences', array( $this, 'remove_bb_notification_settings') );

                require_once trailingslashit( dirname( __FILE__ ) ) . 'buddyboss/notifications/new-message.php';
                BetterMessagesNewMessageNotification::instance();

                add_filter( 'better_messages_is_user_web_push_enabled', array( $this, 'overwrite_user_web_push_enabled' ), 10, 2 );

                if( Better_Messages()->settings['bpAppPush'] === '1' && function_exists('bbapp_send_push_notification') && function_exists('bbapp_is_active') && bbapp_is_active( 'push_notification' ) ){
                    add_action('better_messages_message_sent', array( $this, 'send_bb_app_push' ), 10, 1 );
                }
            }

            if( Better_Messages()->settings['enableGroups'] === '1' ) {
                add_filter('better_messages_can_send_message', array($this, 'group_restrictions'), 20, 3);
            }

            /**
             * BuddyBoss Pushs
             */
            if (function_exists('bb_onesingnal_send_notification') && $this->bb_pushs_active() ) {
                add_action( 'better_messages_send_pushs', array( $this, 'send_pushs' ), 10, 2 );

                add_filter( 'better_messages_3rd_party_push_active', '__return_true' );
                add_filter( 'better_messages_push_active', '__return_false' );
                add_filter( 'better_messages_push_message_in_settings', array( $this, 'push_message_in_settings' ) );
            }
        }

        public function group_restrictions( $allowed, $user_id, $thread_id ){
            if( function_exists('bp_disable_group_messages') && ! function_exists('groups_can_user_manage_messages') || ! function_exists('bp_group_get_message_status') ){
                return $allowed;
            }

            if( ! bp_disable_group_messages() ){
                return $allowed;
            }

            $type = Better_Messages()->functions->get_thread_type( $thread_id );

            if( $type !== 'group' ){
                return $allowed;
            }

            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');

            if( ! groups_can_user_manage_messages( $user_id, $group_id ) ){
                $status = bp_group_get_message_status( $group_id ) ?? '';
                global $bp_better_messages_restrict_send_message;
                if ( 'admins' === $status ) {
                    $bp_better_messages_restrict_send_message['bb_restrict_group'] = __( 'Only group organizers can send messages to this group.', 'buddyboss' );
                    $allowed = false;
                } elseif ( 'mods' === $status ) {
                    $bp_better_messages_restrict_send_message['bb_restrict_group'] = __( 'Only group organizers and moderators can send messages to this group.', 'buddyboss' );
                    $allowed = false;
                }
            }

            return $allowed;
        }

        public function push_message_in_settings( $message ){
            $message = '<p style="color: #0c5460;background-color: #d1ecf1;border: 1px solid #d1ecf1;padding: 15px;line-height: 24px;max-width: 550px;">';
            $message .= _x('The BuddyBoss OneSignal integration is active and will be used, this option do not need to be enabled', 'Settings page', 'bp-better-messages');
            $message .= '</p>';

            return $message;
        }

        public function send_pushs( array $user_ids, array $notification ){
            if( ! function_exists('bb_onesingnal_send_notification') || count($user_ids) === 0 ) return;

            foreach ($user_ids as $index => $___user_id) {
                if (!Better_Messages()->notifications->user_web_push_enabled($___user_id)) {
                    unset($user_ids[$___user_id]);
                }
            }

            if( count( $user_ids ) > 0 ) {
                bb_onesingnal_send_notification(array(
                    'user_id' => implode(',', $user_ids),
                    'title'   => $notification['title'],
                    'content' => $notification['body'],
                    'link'    => $notification['data']['url'],
                    'image'   => $notification['icon']
                ));
            }

        }

        public function has_message_threads( $bool, $messages_template, $r ){
            return true;
        }

        public function send_bb_app_push( $message ){
            $thread_id  = $message->thread_id;
            $send_push  = $message->send_push ?? false;

            if( ! $send_push ) return;

            $online = [];

            if( Better_Messages()->websocket ) {
                $online = Better_Messages()->websocket->get_online_users();
            }

            $recipients = array_keys( $message->recipients );

            foreach ($recipients as $user_id) {
                if( isset( $online[ $user_id ] ) ) continue;

                if( Better_Messages()->functions->get_user_meta( $user_id, 'better_messages_new_message_app', true ) == 'no' ) {
                    continue;
                }

                // Check if user not muted the thread
                $muted_threads = Better_Messages()->functions->get_user_muted_threads( $user_id );

                if( isset($muted_threads[ $thread_id ]) ){
                    continue;
                }

                // Conversation URL
                $url = Better_Messages()->functions->get_user_thread_url( $thread_id, $user_id );
                $subject = sprintf( __('New message from %s', 'bp-better-messages'), Better_Messages()->functions->get_name( $message->sender_id ) );
                $content = sprintf( __('You have new message from %s', 'bp-better-messages'), Better_Messages()->functions->get_name( $message->sender_id ) );

                $args = [
                    'primary_text' => $subject,
                    'secondary_text' => $content,
                    'sent_as' => $message->sender_id,
                    'user_ids'  => [$user_id],
                    'data' => [
                        'link' => $url
                    ],
                    'type' => 'better_messages_better_messages_new_message',
                    'filter_users_by_subscription' => false
                ];

                bbapp_send_push_notification($args);
            }
        }

        public function overwrite_user_web_push_enabled($enabled, $user_id){
            return Better_Messages()->functions->get_user_meta( $user_id, 'better_messages_new_message_web', true ) != 'no';
        }

        public function remove_bb_notification_settings( $settings ){
            if( ! wp_doing_ajax() ) {
                if (isset($settings['messages'])) {
                    unset($settings['messages']);
                }
            }

            return $settings;
        }

        public function buddyboss_disable_rest_api_block( $default_exclude_endpoint, $current_endpoint ){
            if( strpos( $current_endpoint, 'better-messages/v1/' ) !== false ){
                $default_exclude_endpoint[] = $current_endpoint;
            }
            return $default_exclude_endpoint;
        }

        public function disable_bb_pusher( $options ){
            if( ! is_admin() ) {
                if (isset($options['live-messaging'])) {
                    $options['live-messaging'] = '0';
                }
            }
            return $options;
        }

        public function buddyboss_notifications_fix( $array ){
            if ( function_exists( 'buddyboss_theme_register_required_plugins' ) || class_exists('BuddyBoss_Theme') ) {
                if( count( $array['threads'] ) > 0 && isset( $array['total'] ) ) {
                    $new_threads = [];

                    foreach ($array['threads'] as $i => $thread) {
                        if ( ! isset($thread->last_message_date) || strtotime($thread->last_message_date) <= 0 ) {
                            unset($array['threads'][$i]);
                            $array['total']--;
                        } else {
                            $new_threads[] = $thread;
                        }
                    }


                    $array['threads'] = $new_threads;
                }
                if( $array['total'] < 0 ) $array['total'] = 0;
            }

            return $array;
        }

        public function heartbeat_unread_notifications( $response = array() ){
            if( Better_Messages()->settings['mechanism'] === 'websocket') {
                if (isset($response['total_unread_messages'])) {
                    unset($response['total_unread_messages']);
                }
            }

            return $response;
        }

        public function buddyboss_theme_get_header_unread_messages(){
            $response = array();
            ob_start();

            echo Better_Messages()->functions->get_conversations_layout();
            ?>
            <script type="text/javascript">
                var notification_list = jQuery('.site-header .messages-wrap .notification-list');
                notification_list.removeClass('notification-list').addClass('bm-notification-list');

                notification_list.css({'margin' : 0, 'padding' : 0});

                jQuery(document).trigger("bp-better-messages-init-scrollers");
            </script>
            <?php
            $response['contents'] = ob_get_clean();

            wp_send_json_success( $response );
        }

        public function buddyboss_group_messages( $message, $message_id, $context, $user_id ){
            global $wpdb;
            $group_id         = Better_Messages()->functions->get_message_meta( $message_id, 'group_id', true );
            $message_deleted  = Better_Messages()->functions->get_message_meta( $message_id, 'bp_messages_deleted', true );

            if( $group_id ) {
                if ( function_exists('bp_get_group_name') ) {
                    $group_name = bp_get_group_name(groups_get_group($group_id));
                } else {
                    $bp_prefix = bp_core_get_table_prefix();
                    $table = $bp_prefix . 'bp_groups';
                    $group_name = $wpdb->get_var( "SELECT `name` FROM `{$table}` WHERE `id` = '{$group_id}';" );
                }

                $message_left     = Better_Messages()->functions->get_message_meta( $message_id, 'group_message_group_left', true );
                $message_joined   = Better_Messages()->functions->get_message_meta( $message_id, 'group_message_group_joined', true );

                if ($message_left && 'yes' === $message_left) {
                    $message = '<i>' . sprintf(__('Left "%s"', 'bp-better-messages'), ucwords($group_name)) . '</i>';
                } else if ($message_joined && 'yes' === $message_joined) {
                    $message = '<i>' . sprintf(__('Joined "%s"', 'bp-better-messages'), ucwords($group_name)) . '</i>';
                }
            }

            if ( $message_deleted && 'yes' === $message_deleted ) {
                $message =  '<i>' . __( 'This message was deleted.', 'bp-better-messages' ) . '</i>';
            }

            return $message;
        }

        public function buddyboss_disable_message_to_blocked( $allowed, $user_id, $thread_id ){
            if ( ! bm_bp_is_active( 'moderation' ) ) return $allowed;
            if( ! class_exists( 'BP_Moderation' ) ) return $allowed;
            if( ! function_exists( 'bp_moderation_is_user_blocked' ) ) return $allowed;

            $participants = Better_Messages()->functions->get_participants($thread_id);

            if( ! isset( $participants['recipients'] ) ) {
                return $allowed;
            }

            /**
             * Not block in group thread
             */
            if( count($participants['recipients']) > 1 ){
                return $allowed;
            }

            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );
            if( $thread_type !== 'thread') return $allowed;

            foreach( $participants['recipients'] as $recipient_user_id ){
                if( bp_moderation_is_user_blocked( $recipient_user_id ) ){
                    global $bp_better_messages_restrict_send_message;
                    $bp_better_messages_restrict_send_message['bb_blocked_user'] = __( "You can't message a blocked member.", 'bp-better-messages' );
                    $allowed = false;

                    continue;
                }

                $moderation            = new BP_Moderation();
                $moderation->user_id   = $recipient_user_id;
                $moderation->item_id   = $user_id;
                $moderation->item_type = 'user';

                $id = BP_Moderation::check_moderation_exist( $user_id, 'user' );

                if ( ! empty( $id ) ) {
                    $moderation->id = (int) $id;
                    $moderation->populate();
                }

                $is_blocked = ( ! empty( $moderation->id ) && ! empty( $moderation->report_id ) );

                if( $is_blocked ){
                    global $bp_better_messages_restrict_send_message;
                    $bp_better_messages_restrict_send_message['bb_blocked_by_user'] = __("You can't message this member.", 'bp-better-messages');
                    $allowed = false;
                }
            }

            return $allowed;
        }

        public function buddyboss_blocked_message( $allowed, $user_id, $thread_id ){
            if( ! isset( $recipients ) ) return $allowed;
            if( ! is_array( $recipients ) ) return $allowed;
            if( count( $recipients ) === 0 ) return $allowed;

            $thread = new BP_Messages_Thread( $thread_id );

            $check_buddyboss_access = bb_access_control_member_can_send_message( $thread, $thread->recipients, 'wp_error' );

            if( is_wp_error($check_buddyboss_access) ){
                $allowed = false;
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['buddyboss_restricted'] = $check_buddyboss_access->get_error_message();
            }

            return $allowed;
        }

        public function bb_pushs_active(){
            if( function_exists('bb_onesignal_app_is_connected') ) {
                return bb_onesignal_app_is_connected();
            }

            return false;
        }
    }
}

