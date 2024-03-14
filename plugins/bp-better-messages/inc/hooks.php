<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Hooks' ) ):

    class Better_Messages_Hooks
    {

        public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Hooks();
            }

            return $instance;
        }

        public function __construct()
        {


            add_action( 'admin_init',     array( $this, 'update_db_if_needed' ) );

            add_filter( 'cron_schedules', array( $this, 'cron_intervals' ) );

            add_action('pre_get_posts', array($this, 'exclude_attachments_global'));

            if( Better_Messages()->settings['attachmentsHide'] === '1' ) {
                add_action('ajax_query_attachments_args', array($this, 'exclude_attachments'));
            }

            add_action( 'wp_head', array( $this, 'themes_adaptation' ) );

            if( Better_Messages()->settings['chatPage'] !== '0' ){
                add_filter( 'the_content', array( $this, 'chat_page' ), 12 );
            }

            add_action( 'admin_notices', array( $this, 'admin_notice') );

            //if( Better_Messages()->settings['fastStart'] == '1' ) {
            add_action('template_redirect', array($this, 'catch_fast_thread'));
            //}

            if( Better_Messages()->settings['friendsMode'] == '1' && function_exists('friends_check_friendship') ) {
                add_filter( 'better_messages_can_send_message', array( $this, 'disable_non_friends_reply' ), 10, 3);
                add_action( 'better_messages_before_new_thread', array( $this, 'disable_start_thread_for_non_friends' ), 10, 2 );
            }

            if( Better_Messages()->settings['allowUsersRestictNewThreads'] === '1' ){
                add_filter( 'better_messages_before_new_thread', array( $this, 'disable_start_thread_if_user_restricted' ), 9, 2);
            }

            add_filter( 'better_messages_can_send_message', array( $this, 'disable_archived_reply' ), 10, 3);

            /*
             * Block, Suspend, Report for BuddyPress integration
             */
            add_filter('better_messages_can_send_message',         array($this, 'disable_message_for_blocked_user'), 10, 3);

            if( isset(Better_Messages()->settings['restrictNewReplies'])
                && is_array(Better_Messages()->settings['restrictNewReplies'])
                && count(Better_Messages()->settings['restrictNewReplies']) > 0
            ) {
                add_filter( 'better_messages_can_send_message', array( $this, 'disable_message_for_blocked_restricted_role' ), 10, 3);
            }

            if( isset(Better_Messages()->settings['restrictNewThreads'])
                && is_array(Better_Messages()->settings['restrictNewThreads'])
                && count(Better_Messages()->settings['restrictNewThreads']) > 0
            ) {
                add_action( 'better_messages_before_new_thread', array( $this, 'disable_thread_for_blocked_restricted_role' ), 10, 2);

                if( Better_Messages()->settings['restrictNewThreadsRemoveNewThreadButton'] === '1' ){
                    $this->disable_new_thread_button_if_needed();
                }
            }

            if( isset(Better_Messages()->settings['restrictViewMessages'])
                && is_array(Better_Messages()->settings['restrictViewMessages'])
                && count(Better_Messages()->settings['restrictViewMessages']) > 0
            ) {
                add_filter( 'bp_better_messages_after_format_message', array( $this, 'hide_message_for_restricted_role' ), 10, 4 );
                add_filter( 'bp_better_messages_fast', array( $this, 'disable_fast_mode' ), 10, 1 );
            }

            if(
                Better_Messages()->settings['restrictRoleType'] === 'allow' &&
                isset(Better_Messages()->settings['restrictRoleBlock'])
                && is_array(Better_Messages()->settings['restrictRoleBlock'])
                && count(Better_Messages()->settings['restrictRoleBlock']) > 0
                && ! current_user_can('manage_options')
            ) {
                add_filter( 'better_messages_can_send_message',          array( $this, 'disable_replied_role_to_role_block' ), 10, 3 );
                add_filter( 'better_messages_before_new_thread',         array( $this, 'disable_start_thread_role_to_role_block' ), 10, 2 );
                add_filter( 'better_messages_search_user_sql_condition', array( $this, 'role_to_role_block_search_user_sql_condition'), 10, 4 );
            }


            if(  Better_Messages()->settings['restrictRoleType'] === 'disallow' && ! current_user_can('manage_options') ){
                add_filter( 'better_messages_can_send_message',          array( $this, 'disable_replied_role_to_role_allow' ), 10, 3 );
                add_filter( 'better_messages_before_new_thread',         array( $this, 'disable_start_thread_role_to_role_allow' ), 10, 2 );
                add_filter( 'better_messages_search_user_sql_condition', array( $this, 'role_to_role_allow_search_user_sql_condition'), 10, 4 );

                if( Better_Messages()->settings['restrictNewThreadsRemoveNewThreadButton'] === '1' ){
                    $this->disable_new_thread_button_if_disallowed();
                }
            }

            if( Better_Messages()->settings['singleThreadMode'] == '1' ) {
                add_action( 'better_messages_before_new_thread', array( $this, 'disable_start_thread_if_thread_exist' ), 10, 2 );
            }

            if( Better_Messages()->settings['disableGroupThreads'] == '1' ) {
                add_action( 'better_messages_before_new_thread', array( $this, 'disable_group_threads' ), 10, 2 );
            }

            if( Better_Messages()->settings['mechanism'] == 'websocket' && Better_Messages()->settings['messagesStatus'] == '0'){
                add_action( 'wp_head', array( $this, 'disableStatuses'), 1 );
                add_action( 'bm_app_head', array( $this, 'disableStatuses' ) );
            }

            add_action( 'template_redirect', array( $this, 'update_last_activity' ) );

            /*
             * BuddyBoss trying to fix
             */
            add_action('wp_enqueue_scripts', array( $this, 'inbox_counter_javascript' ) );
            add_filter('messages_thread_get_inbox_count', array( $this, 'replace_unread_count' ), 10, 2 );

            add_action( 'wp_footer', array( $this, 'mobile_popup_button') );

            /*
             * BeeHive premium theme integration
             * https://www.wordplus.org/beehive
            */
            add_action('wp_head', array( $this, 'beehive_theme_integration' ), 100 );
            add_action('wp_head', array( $this, 'max_height_css' ), 100 );
            add_filter( 'update_user_metadata', array( $this, 'override_last_activity' ), 1, 5 );

            /**
             * PMPRO Access
             */
            if( defined('PMPROBP_DIR') && function_exists('pmpro_bp_user_can') ){
                add_action( 'better_messages_before_new_thread', array( $this, 'disable_thread_for_pmpro_restricted_role' ), 10, 2);
                add_filter( 'better_messages_can_send_message', array( $this, 'disable_messages_for_pmpro_restricted_role' ), 10, 3);
            }


            add_action('admin_head', array($this, 'hide_admin_counter' ) );

            add_filter( 'bp_messages_allowed_tags', array($this, 'allow_additional_tags'), 10, 1 );

            add_filter( 'better_messages_can_send_message', array( $this, 'disable_message_to_deleted_users' ), 10, 3);

            /**
             * https://wordpress.org/plugins/asgaros-forum/
             */
            if( class_exists('AsgarosForum') ){
                add_action('asgarosforum_custom_profile_menu',        array( $this, 'asragaros_profile_link'));
                if( apply_filters('asgarosforum_filter_show_header', true) ){
                    add_action('asgarosforum_content_header', array( $this, 'asragaros_profile_messages'));
                } else {
                    add_action('asgarosforum_profile_custom_content_top', array( $this, 'asragaros_profile_messages'));
                }

                add_action('asgarosforum_after_post_author', array( $this, 'asragaros_thread_view'), 10, 2);
            }

            /**
             * BBPress
             */
            if( class_exists('bbPress') ){
                if( Better_Messages()->settings['bbPressAuthorDetailsLink'] === '1' ) {
                    add_action('bbp_theme_after_reply_author_details', array($this, 'pm_link_bbpress'));
                }
            }

            if( Better_Messages()->settings['allowMuteThreads'] === '1' ) {
                /**
                 * Remove standard Email & Notifications
                 */
                add_filter('bp_email_validate', array($this, 'mute_thread_remove_standard_email'), 10, 2);
                add_action('bp_notification_after_save', array( $this, 'mute_thread_delete_notification'), 10, 1 );
            }

            if( Better_Messages()->settings['rateLimitNewThread'] > 0 ){
                add_action( 'better_messages_before_new_thread',  array( $this, 'new_thread_rate_limit' ), 10, 2 );
                add_action( 'bp_better_messages_new_thread_created', array( $this, 'new_thread_record_last_thread_time' ), 10, 2 );
            }

            add_action( 'better_messages_before_message_send',  array( $this, 'new_replies_rate_limit' ), 10, 2 );
            add_filter( 'better_messages_can_send_message', array( $this, 'disable_message_for_new_replies_rate_limited' ), 10, 3 );

            if( Better_Messages()->settings['enableReplies'] === '1' ) {
                add_filter('bp_better_messages_after_format_message', array($this, 'reply_message_formatting'), 101, 4);
            }

            if( ! empty( Better_Messages()->settings['badWordsList'] ) ){
                add_action( 'better_messages_before_new_thread',  array( $this, 'disable_new_thread_with_bad_word' ), 10, 2 );
                add_action( 'better_messages_before_message_send', array( $this, 'disable_reply_with_bad_word' ), 10, 2 );
            }

            if( class_exists('WooCommerce') && Better_Messages()->settings['chatPage'] === 'woocommerce' ) {
                add_action( 'init', array( $this, 'woocommerce_add_messages_endpoint' ) );
                add_filter( 'query_vars', array( $this, 'woocommerce_messages_query_vars' ), 0 );

                $slug = Better_Messages()->settings['bpProfileSlug'];
                add_filter( 'woocommerce_account_menu_items', array( $this, 'woocommerce_add_messages_link_my_account' ) );
                add_action( 'woocommerce_account_' . $slug . '_endpoint', array( $this, 'woocommerce_messages_content' ) );
            }

            /**
             * Smart caching hooks
             */
            add_action( 'better_messages_message_sent', array( $this, 'on_message_sent' ), 1000 );
            add_action( 'profile_update', array( $this, 'on_user_update' ), 1000, 2 );
            add_action( 'bp_better_chat_settings_updated', array( $this, 'settings_updated' ), 1000, 1 );

            if( defined('ultimatemember_version') ){
                require_once Better_Messages()->path . 'addons/ultimate-member.php';
                Better_Messages_Ultimate_Member::instance();
            }

            if( Better_Messages()->settings['allowUsersBlock'] === '1' ) {
                require_once Better_Messages()->path . 'addons/users-block.php';
                Better_Messages_Block_Users::instance();
            }

            if( Better_Messages()->settings['enableReactions'] === '1' ) {
                Better_Messages_Reactions::instance();
            }

            if( Better_Messages()->settings['pinnedThreads'] === '1' ) {
                require_once Better_Messages()->path . 'addons/pinned-conversations.php';
                Better_Messages_Pinned_Conversations::instance();
            }


            if ( class_exists( 'myCRED_Core' ) ){
                require_once Better_Messages()->path . 'addons/mycred.php';
                Better_Messages_MyCred::instance();
            }

            if ( class_exists( 'GamiPress' ) ) {
                require_once Better_Messages()->path . 'addons/gamipress.php';
                Better_Messages_GamiPress::instance();
            }

            if( Better_Messages()->settings['pinnedMessages'] === '1' ) {
                require_once Better_Messages()->path . 'addons/pinned-message.php';
                Better_Messages_Pinned_Message::instance();
            }

            if( class_exists( 'PeepSo' ) ) {
                require_once Better_Messages()->path . 'addons/peepso.php';
                Better_Messages_Peepso::instance();
            }

            if( class_exists('WP_User_Manager') ){
                require_once Better_Messages()->path . 'addons/wp-user-manager.php';
                Better_Messages_WP_User_Manager::instance();
            }

            if( class_exists('WP_Job_Manager') ){
                require_once Better_Messages()->path . 'addons/wp-job-manager.php';
                Better_Messages_WP_Job_Manager::instance();
            }

            if( defined('PROGRID_PLUGIN_VERSION') ){
                require_once Better_Messages()->path . 'addons/profile-grid.php';
                Better_Messages_Profile_Grid::instance();
            }

            if( defined('USERSWP_VERSION') ){
                require_once Better_Messages()->path . 'addons/userswp.php';
                Better_Messages_UsersWP::instance();
            }

            if( defined('WPFORO_VERSION') ){
                require_once Better_Messages()->path . 'addons/wpforo.php';
                Better_Messages_wpForo::instance();
            }

            if( class_exists( 'WooCommerce' ) ){
                require_once Better_Messages()->path . 'addons/woocommerce.php';
                Better_Messages_WooCommerce::instance();
            }

            if( class_exists( 'WooCommerce' ) && class_exists('WeDevs_Dokan') ){
                require_once Better_Messages()->path . 'addons/dokan.php';
                Better_Messages_Dokan::instance();
            }

            if( defined('MVX_PLUGIN_VERSION') ){
                require_once Better_Messages()->path . 'addons/multi-vendor-x.php';
                Better_Messages_MultiVendorX::instance();
            }

            if( function_exists('hivepress') ){
                require_once Better_Messages()->path . 'addons/hivepress.php';
                Better_Messages_HivePress::instance();
            }

            add_action('init', array( $this, 'flush_rewrite_rules' ) );

            add_action( 'better_messages_thread_updated', array( $this, 'thread_updated' ), 10, 1 );

            if( function_exists('buddypress') && apply_filters( 'better_messages_replace_buddypress', true ) ) {
                require_once Better_Messages()->path . 'addons/buddypress.php';
                Better_Messages_BuddyPress::instance();
            }

            if( defined('BP_PLATFORM_VERSION') && apply_filters( 'better_messages_replace_buddyboss', true ) ) {
                require_once Better_Messages()->path . 'addons/buddyboss.php';
                Better_Messages_BuddyBoss::instance();
            }

            if( defined('ONESIGNAL_PLUGIN_URL') ) {
                require_once Better_Messages()->path . 'addons/onesignal.php';
                Better_Messages_OneSignal::instance();
            }

            add_action('template_redirect', array( $this, 'redirect_to_messages') );

            if( Better_Messages()->settings['enableReplies'] === '1' ){
                add_filter( 'better_messages_rest_message_meta', array( $this, 'reply_message_meta'), 10, 4 );
            }

            if( function_exists('jet_engine') ){
                require_once Better_Messages()->path . 'addons/jet-engine.php';
                Better_Messages_Jet_Engine::instance();
            }

            add_filter('bp_better_messages_script_variable', array( $this, 'script_variables'), 10, 1 );

            add_filter( 'better_messages_message_content_before_save', 'bp_messages_filter_kses', 1 );

            add_action( 'deleted_user', array( $this, 'wp_on_deleted_user' ), 10, 3 );
            add_action( 'better_messages_on_deleted_user', array( $this, 'bm_on_deleted_user'), 10, 1 );
        }

        public function wp_on_deleted_user( $user_id, $reassign, $user ){
            if( Better_Messages()->settings['deleteMessagesOnUserDelete'] === '1' && ! wp_get_scheduled_event( 'better_messages_on_deleted_user', [ $user_id ] ) ){
                wp_schedule_single_event( time(), 'better_messages_on_deleted_user', [ $user_id ] );
            }
        }

        public function bm_on_deleted_user( $user_id ){
            global $wpdb;
            ignore_user_abort(true);
            set_time_limit(0);

            $messages = $wpdb->get_results( $wpdb->prepare("SELECT id, thread_id FROM `" . bm_get_table('messages') . "` WHERE `sender_id` = %d", $user_id) );

            foreach ( $messages as $message ){
                Better_Messages()->functions->delete_message( $message->id, $message->thread_id, true, 'replace' );
            }
        }

        public function update_db_if_needed(){
            if( current_user_can('manage_options') ) {
                Better_Messages_Rest_Api_DB_Migrate()->install_tables();
                Better_Messages_Rest_Api_DB_Migrate()->migrations();
            }
        }

        public function css_customizations(){
            $rules = [];

            if( ! empty( BP_Better_Messages()->settings['mobilePopupLocationBottom'] ) && BP_Better_Messages()->settings['mobilePopupLocationBottom'] !== 20 ){
                $bottom  = (int) BP_Better_Messages()->settings['mobilePopupLocationBottom'];
                $rules[] = '#bp-better-messages-mini-mobile-open{bottom:' . $bottom . 'px!important}';
            }

            ob_start();

            if( count( $rules ) > 0 ) {
                echo implode('', $rules);
            }

            return ob_get_clean();
        }

        public function script_variables( $script_variables ){
            $user_id = Better_Messages()->functions->get_current_user_id();
            $roles = Better_Messages()->functions->get_user_roles( $user_id );

            if( isset($script_variables['miniMessages']) && $script_variables['miniMessages'] === '1' ){
                $restricted_roles = Better_Messages()->settings['restrictViewMiniThreads'];
                $is_restricted = false;

                if( count( $restricted_roles ) > 0 ) {
                    foreach( $restricted_roles as $restricted_role ){
                        if( in_array( $restricted_role, $roles ) ){
                            $is_restricted = true;
                        }
                    }
                }

                if( $is_restricted ) {
                    $script_variables['miniMessages'] = '0';
                }
            }

            if( isset($script_variables['miniFriends']) && $script_variables['miniFriends'] === '1' ){
                $restricted_roles = Better_Messages()->settings['restrictViewMiniFriends'];
                $is_restricted = false;

                if( count( $restricted_roles ) > 0 ) {
                    foreach( $restricted_roles as $restricted_role ){
                        if( in_array( $restricted_role, $roles ) ){
                            $is_restricted = true;
                        }
                    }
                }

                if( $is_restricted ) {
                    $script_variables['miniFriends'] = '0';
                }
            }

            if( isset($script_variables['miniGroups']) && $script_variables['miniGroups'] === '1' ){
                $restricted_roles = Better_Messages()->settings['restrictViewMiniGroups'];
                $is_restricted = false;

                if( count( $restricted_roles ) > 0 ) {
                    foreach( $restricted_roles as $restricted_role ){
                        if( in_array( $restricted_role, $roles ) ){
                            $is_restricted = true;
                        }
                    }
                }

                if( $is_restricted ) {
                    $script_variables['miniGroups'] = '0';
                }
            }

            return $script_variables;
        }

        public function reply_message_meta( $meta, $message_id, $thread_id, $content ){
            $reply_to = Better_Messages()->functions->get_message_meta( $message_id, 'reply_to', true );

            if( $reply_to ){
                $meta['replyTo'] = (int) $reply_to;
            }

            return $meta;
        }

        public function redirect_to_messages(){
            if( isset( $_GET['bm-redirect-to-messages'] ) ) {
                if(  is_user_logged_in() ) {
                    $link = Better_Messages()->functions->get_user_messages_url(get_current_user_id());

                    if (isset($_GET['thread-id'])) {
                        $link = Better_Messages()->functions->get_user_messages_url(get_current_user_id(), intval($_GET['thread-id']));
                    }

                    wp_redirect($link);

                    exit;
                }
            }
        }

        public function thread_updated( $thread_id ){
            global $wpdb;

            $wpdb->update( bm_get_table('recipients'),
                [ 'last_update' => Better_Messages()->functions->get_microtime() ],
                [ 'thread_id' => $thread_id ],
                ['%d'], ['%d']
            );

            Better_Messages()->hooks->clean_thread_cache( $thread_id );
        }

        public function flush_rewrite_rules(){
            if( ! is_admin() ) return false;
            global $wp_rewrite;

            $is_updated = get_option( 'bp-better-chat-settings-updated', false );

            if( $is_updated ) {
                flush_rewrite_rules();
                $wp_rewrite->init();
                delete_option( 'bp-better-chat-settings-updated' );
            }
        }

        public function role_to_role_allow_search_user_sql_condition( $sql_array, $user_ids, $search, $user_id )
        {
            global $wpdb;

            $restrict_to = Better_Messages()->functions->get_restrict_to_roles( $user_id );

            if( count( $restrict_to ) > 0 ){
                $roles = array_keys( $restrict_to );

                $arguments = [];

                foreach( $roles as $role ){
                    $arguments[] = $wpdb->prepare('%s', $role );
                }

                $sql = "AND `ID` IN(SELECT DISTINCT(user_id) FROM `" . bm_get_table('roles')  . "` WHERE role IN (". implode(',', $arguments ) .") )";

                $sql_array[] = $sql;
            } else {
                $sql_array[] = "AND `ID` IS NULL";
            }

            return $sql_array;
        }

        public function role_to_role_block_search_user_sql_condition( $sql_array, $user_ids, $search, $user_id ){
            global $wpdb;

            $restrict_to = Better_Messages()->functions->get_restrict_to_roles( $user_id );

            if( count( $restrict_to ) > 0 ){
                $roles = array_keys( $restrict_to );

                $arguments = [];

                foreach( $roles as $role ){
                    $arguments[] = $wpdb->prepare('%s', $role );
                }

                $sql = "AND `ID` NOT IN(SELECT DISTINCT(user_id) FROM `" . bm_get_table('roles')  . "` WHERE role IN (". implode(',', $arguments ) .") )";

                $sql_array[] = $sql;
            }

            return $sql_array;
        }

        public function disable_start_thread_role_to_role_allow(&$args, &$errors){
            $recipients = $args['recipients'];
            if( ! is_array( $recipients ) ) return false;
            if( count($recipients) === 0 ) return false;

            $restrict_to = Better_Messages()->functions->get_restrict_to_roles( get_current_user_id() );

            $is_restricted = true;
            $restricted_message = Better_Messages()->settings['restrictRoleMessage'];

            if( count( $restrict_to ) > 0 ) {
                foreach ($recipients as $recipient) {
                    $recipient_roles = Better_Messages()->functions->get_user_roles( $recipient );

                    foreach( $recipient_roles as $recipient_role ){
                        if( isset( $restrict_to[$recipient_role] ) ){
                            $is_restricted = false;
                            $restricted_message = $restrict_to[$recipient_role];
                        }
                    }
                }
            }

            if( $is_restricted ) {
                $errors[] = $restricted_message;
            }

        }

        public function disable_start_thread_role_to_role_block(&$args, &$errors){
            $recipients = $args['recipients'];
            if( ! is_array( $recipients ) ) return false;
            if( count($recipients) === 0 ) return false;

            $restrict_to = Better_Messages()->functions->get_restrict_to_roles( get_current_user_id() );

            $is_restricted = false;
            $restricted_message = false;

            if( count( $restrict_to ) > 0 ) {
                foreach ($recipients as $recipient) {
                    $recipient_roles = Better_Messages()->functions->get_user_roles( $recipient );

                    foreach( $recipient_roles as $recipient_role ){
                        if( isset( $restrict_to[$recipient_role] ) ){
                            $is_restricted = true;
                            $restricted_message = $restrict_to[$recipient_role];
                        }
                    }
                }
            }

            if( $is_restricted ) {
                $errors[] = $restricted_message;
            }

        }

        public function disable_replied_role_to_role_block( $allowed, $user_id, $thread_id ){
            $user_id = Better_Messages()->functions->get_current_user_id();

            $restrict_to = Better_Messages()->functions->get_restrict_to_roles( $user_id );

            $is_restricted = false;
            $restricted_message = false;

            if( count( $restrict_to ) > 0 ){
                $group_id = Better_Messages()->functions->get_thread_meta( $thread_id, 'group_id');

                /**
                 * Disable restrictions for group threads
                 */
                if( ! empty( $group_id ) ) {
                    return $allowed;
                }

                /**
                 * Disable restrictions for chat threads
                 */
                $chat_id = Better_Messages()->functions->get_thread_meta( $thread_id, 'chat_id' );
                if( ! empty( $chat_id ) ) {
                    return $allowed;
                }

                $recipients = Better_Messages()->functions->get_recipients( $thread_id );

                if( count( $recipients ) > 2 ){
                    return $allowed;
                }

                foreach( $recipients as $recipient ){
                    if( $recipient->user_id === $user_id ) continue;

                    if( ! Better_Messages()->functions->is_valid_user_id( $recipient->user_id ) ) continue;

                    $recipient_roles = Better_Messages()->functions->get_user_roles( $recipient->user_id );

                    foreach( $recipient_roles as $recipient_role ){
                        if( isset( $restrict_to[$recipient_role] ) ){
                            $is_restricted = true;
                            $restricted_message = $restrict_to[$recipient_role];
                        }
                    }

                }
            }

            if( $is_restricted ) {
                $allowed = false;
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['role_block_restricted'] = $restricted_message;
            }

            return $allowed;
        }

        public function disable_replied_role_to_role_allow( $allowed, $user_id, $thread_id ){
            $user_id = Better_Messages()->functions->get_current_user_id();

            $restrict_to = Better_Messages()->functions->get_restrict_to_roles( $user_id );

            $is_restricted = true;
            $restricted_message = Better_Messages()->settings['restrictRoleMessage'];

            $group_id = Better_Messages()->functions->get_thread_meta( $thread_id, 'group_id');

            /**
             * Disable restrictions for group threads
             */
            if( ! empty( $group_id ) ) {
                return $allowed;
            }

            /**
             * Disable restrictions for chat threads
             */
            $chat_id = Better_Messages()->functions->get_thread_meta( $thread_id, 'chat_id' );
            if( ! empty( $chat_id ) ) {
                return $allowed;
            }

            $recipients = Better_Messages()->functions->get_recipients( $thread_id );

            if( count( $recipients ) > 2 ){
                return $allowed;
            }

            if( count( $restrict_to ) > 0 ){

                foreach( $recipients as $recipient ){
                    if( $recipient->user_id === $user_id ) continue;

                    if( ! Better_Messages()->functions->is_valid_user_id( $recipient->user_id ) ) continue;

                    $recipient_roles = Better_Messages()->functions->get_user_roles( $recipient->user_id );

                    foreach( $recipient_roles as $recipient_role ){
                        if( isset( $restrict_to[$recipient_role] ) ){
                            $is_restricted = false;
                        }
                    }

                }
            }

            if( $is_restricted ) {
                $allowed = false;
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['role_block_restricted'] = $restricted_message;
            }

            return $allowed;
        }

        public function clean_thread_cache( $thread_id ){
            wp_cache_delete('thread_' . $thread_id, 'bm_messages');
            wp_cache_delete('thread_' . $thread_id . '_type', 'bm_messages');
            wp_cache_delete('bm_thread_recipients_' . $thread_id, 'bm_messages');
        }

        public function settings_updated( $settings ){
        }

        public function on_user_update( $user_id, $old_user_data = false ){
        }

        public function on_message_sent( $message ){
        }

        public function woocommerce_add_messages_endpoint(){
            $page = get_option('woocommerce_myaccount_page_id');
            $page = get_post($page);

            $slug = Better_Messages()->settings['bpProfileSlug'];

            if( !! $page ) {
                add_rewrite_endpoint($slug, EP_ROOT | EP_PAGES);
            }
        }

        public function woocommerce_messages_query_vars( $vars ){
            $slug = Better_Messages()->settings['bpProfileSlug'];
            $vars[] = $slug;
            return $vars;
        }

        public function woocommerce_add_messages_link_my_account( $items ){
            $slug = Better_Messages()->settings['bpProfileSlug'];
            $label = __('Messages', 'bp-better-messages');

            if( isset( $items['customer-logout']) ) {
                $items = Better_Messages()->functions->array_insert_before('customer-logout', $items, $slug, $label);
            } else {
                $items['bp-messages'] = $label;
            }

            return $items;
        }

        public function woocommerce_messages_content(){
            echo Better_Messages()->functions->get_page( true );
        }

        public function reply_message_formatting( $message, $message_id, $context, $user_id ){
            if( $context !== 'stack' ) return $message;
            $is_reply = strpos( $message, '<!-- BPBM REPLY -->' ) !== false;
            if( ! $is_reply ) return $message;

            $reply_message_id = (int) Better_Messages()->functions->get_message_meta( $message_id, 'reply_to_message_id', true );

            if( ! $reply_message_id ) return $message;

            global $wpdb;
            $reply_message = $wpdb->get_row($wpdb->prepare("SELECT sender_id, message FROM " . bm_get_table('messages') . " WHERE `id` = %d", $reply_message_id));
            if( ! $reply_message ) return $message;

            $sender_id       = $reply_message->sender_id;
            $message_content = str_replace('<!-- BPBM REPLY -->', '', $reply_message->message);

            #remove_filter( 'bp_better_messages_after_format_message', array( Better_Messages()->urls, 'nice_links' ), 100 );
            $message_content = Better_Messages()->functions->format_message($message_content, $reply_message_id, 'stack', $user_id);
            #add_filter( 'bp_better_messages_after_format_message', array( Better_Messages()->urls, 'nice_links' ), 100, 4 );

            $sender = get_userdata( $sender_id );
            if( ! $sender ) {
                $display_name = __('Deleted User', 'bp-better-messages');
            } else {
                $display_name = esc_attr( $sender->display_name );
            }

            $newMessage  = '<span class="bpbm-replied-message" data-reply-message-id="' . $reply_message_id . '">';
            $newMessage .= '<span class="bpbm-replied-message-name">' . $display_name . '</span>';
            $newMessage .= '<span class="bpbm-replied-message-text">' . $message_content . '</span>';
            $newMessage .= '</span>';
            $newMessage .= '<span class="bpbm-replied-message-reply">' . $message . '</span>';

            return $newMessage;
        }

        public function new_thread_record_last_thread_time($sent, $bpbm_last_message_id){
            $user_id = Better_Messages()->functions->get_current_user_id();
            Better_Messages()->functions->update_user_meta( $user_id, 'bpbm_last_thread_created', time() );
        }

        public function new_replies_rate_limit( &$args, &$errors ){
            global $wpdb;
            $sender_id = (isset($args['sender_id'])) ? $args['sender_id'] : Better_Messages()->functions->get_current_user_id();
            $rate_limit_reply = (array) Better_Messages()->settings['rateLimitReply'];

            $user_roles = Better_Messages()->functions->get_user_roles( $sender_id );

            if( count( $user_roles ) === 0 ){
                return;
            }

            foreach( $user_roles as $user_role ){

                if( isset($rate_limit_reply[$user_role]) ) {
                    $value = (int) $rate_limit_reply[$user_role]['value'];
                    if( $value === 0 ) continue;

                    $type  = $rate_limit_reply[$user_role]['type'];

                    $timeAgo = 0;
                    switch ( $type ){
                        case 'hour':
                            $timeAgo = 3600;
                            break;
                        case 'day':
                            $timeAgo = 3600 * 24;
                            break;
                    }

                    $since = time() - $timeAgo;
                    $datetime = date('Y-m-d H:i:s', $since);

                    $count = $wpdb->get_var($wpdb->prepare( "
                        SELECT COUNT(*)
                        FROM " . bm_get_table('messages') . "
                        WHERE `sender_id`  = %d
                        AND   `date_sent`  > %s
                    ", $sender_id, $datetime ));

                    if( $count >= $value ){
                        $errors['restrictRateLimitReply'] = Better_Messages()->settings['rateLimitReplyMessage'];
                        break;
                    }
                }
            }

        }

        public function disable_new_thread_with_bad_word( &$args, &$errors ){
            $words_list = array_map('trim', explode("\n", Better_Messages()->settings['badWordsList']));

            $contains_word = false;

            if( isset( $args['content'] ) ) {
                $message = strip_tags($args['content']);

                foreach ($words_list as $word) {
                    if ( preg_match('/\b' . $word . '\b/i', $message ) ) {
                        $contains_word = true;
                        break;
                    }
                }
            }

            if( $contains_word ) {
                $errors['restrictBadWord'] = Better_Messages()->settings['restrictBadWordsList'];
            }
        }

        public function disable_reply_with_bad_word( &$args, &$errors ){
            $words_list = array_map('trim', explode("\n", Better_Messages()->settings['badWordsList']));

            $contains_word = false;

            $message = strip_tags($args['content']);

            foreach ($words_list as $word) {
                if ( preg_match('/\b' . $word . '\b/i', $message ) ) {
                    $contains_word = true;
                    break;
                }
            }

            if( $contains_word ) {
                $errors['restrictBadWord'] = Better_Messages()->settings['restrictBadWordsList'];
            }
        }

        public function disable_message_for_new_replies_rate_limited( $allowed, $user_id, $thread_id ){
            global $wpdb;

            if( ! Better_Messages()->functions->is_valid_user_id($user_id) ) {
                return $allowed;
            }

            $rate_limit_reply = (array) Better_Messages()->settings['rateLimitReply'];

            $user_roles       = Better_Messages()->functions->get_user_roles( $user_id );

            $is_restricted = false;
            foreach( $user_roles as $user_role ){

                if( isset($rate_limit_reply[$user_role]) ) {
                    $value = (int) $rate_limit_reply[$user_role]['value'];
                    if( $value === 0 ) continue;

                    $type  = $rate_limit_reply[$user_role]['type'];

                    $timeAgo = 0;
                    switch ( $type ){
                        case 'hour':
                            $timeAgo = 3600;
                            break;
                        case 'day':
                            $timeAgo = 3600 * 24;
                            break;
                    }

                    $since = time() - $timeAgo;
                    $datetime = date('Y-m-d H:i:s', $since);

                    $count = $wpdb->get_var($wpdb->prepare( "
                        SELECT COUNT(*)
                        FROM " . bm_get_table('messages') . "
                        WHERE `sender_id`  = %d
                        AND   `date_sent`  > %s
                    ", $user_id, $datetime ));

                    if( $count >= $value ){
                        $is_restricted = true;
                        break;
                    }
                }
            }

            if( $is_restricted ) {
                $allowed = false;
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['restrictRateLimitReply'] = Better_Messages()->settings['rateLimitReplyMessage'];
            }
            return $allowed;
        }

        public function new_thread_rate_limit( &$args, &$errors ){
            if( current_user_can('manage_options' ) ) return false;

            $user_id = Better_Messages()->functions->get_current_user_id();

            $last_thread_created = (int) Better_Messages()->functions->get_user_meta( $user_id, 'bpbm_last_thread_created', true );

            if( $last_thread_created !== 0 ){
                $rateLimit = Better_Messages()->settings['rateLimitNewThread'];
                $last_thread_created_ago = time() - $last_thread_created;

                if( $last_thread_created_ago < $rateLimit ){
                    $errors['restrictNewThreadsRateLimit'] = sprintf(__('You cant start new conversation now. Please wait %s seconds.', 'bp-better-messages'), $rateLimit - $last_thread_created_ago);
                }
            }
        }

        public function mute_thread_delete_notification( $notification ){
            if( $notification->component_name !== 'messages') return false;
            if( $notification->component_action !== 'new_message') return false;
            if( ! isset( $_REQUEST['thread_id'] ) ) return false;

            $thread_id = intval($_REQUEST['thread_id']);
            $user_id   = $notification->user_id;

            $muted_threads = Better_Messages()->functions->get_user_muted_threads( $user_id );

            if( isset( $muted_threads[ $thread_id ] ) ){
                BP_Notifications_Notification::delete( array( 'id' => $notification->id ) );
            }

            return true;
        }

        public function mute_thread_remove_standard_email($retval, $email){
            if($email->get('type') !== 'messages-unread') return $retval;
            if( ! isset( $_REQUEST['thread_id'] ) ) return $retval;

            $user_id = $email->get_to()[0]->get_user()->ID;
            $thread_id = intval($_REQUEST['thread_id']);
            $muted_threads = Better_Messages()->functions->get_user_muted_threads( $user_id );

            if( isset( $muted_threads[ $thread_id ] ) ){
                $error_code = 'messages_user_muted_thread';
                $feedback   =  'Your message was not sent. User muted this thread.';
                return new WP_Error( $error_code, $feedback );
            }

            return $retval;
        }

        public function pm_link_bbpress(){
            if( ! is_user_logged_in() ) return false;
            $reply_id = bbp_get_reply_id();
            if ( bbp_is_reply_anonymous( $reply_id ) ) return false;

            $user_id = bbp_get_reply_author_id( $reply_id );

            if( Better_Messages()->functions->get_current_user_id() === $user_id ) return false;
            $user = get_userdata($user_id);
            if( ! $user ) return false;

            $link = Better_Messages()->functions->create_conversation_link( $user->ID, '', '', Better_Messages()->settings['fastStart'] === '1' );

            echo '<a href="' . $link . '" class="bpbm-private-message-link-buddypress">' . __('Private Message', 'bp-better-messages') . '</a>';
        }

        public function asragaros_thread_view($author_id, $author_posts){
            if( ! is_user_logged_in() ) return false;
            if( Better_Messages()->functions->get_current_user_id() === intval($author_id) ) return false;

            $view_user = get_userdata( $author_id );

            $link = Better_Messages()->functions->add_hash_arg('new-conversation', [
                'to' => $view_user->ID
            ], Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));

            echo '<a href="' . $link .'" class="bpbm-asragaros-messages-link">' . __('Private Message', 'bp-better-messages') . ' </a>';
        }

        public function asragaros_profile_messages(){
            if( ! is_user_logged_in() ) return false;
            $url_parts = explode('/', $_SERVER['REQUEST_URI']);
            if( ! in_array('profile', $url_parts) || ! in_array('messages', $url_parts) ) return false;

            global $asgarosforum;

            $asgarosforum->current_view = 'messages';
            $asgarosforum->error        = 'No error';

            echo '<style type="text/css">#af-wrapper .error{display:none}</style>';
            $user_id = $asgarosforum->current_element;
            $userData = get_user_by('id', $user_id);

            if ($userData) {
                if ($asgarosforum->profile->hideProfileLink()) {
                    _e('You need to login to have access to profiles.', 'asgaros-forum');
                } else {
                    $asgarosforum->profile->show_profile_header($userData);
                    $asgarosforum->profile->show_profile_navigation($userData);

                    echo '<div id="profile-content" style="padding: 0">';
                    echo Better_Messages()->functions->get_page( true );
                    echo '</div>';
                }
            } else {
                _e('This user does not exist.', 'asgaros-forum');
            }
        }

        public function asragaros_profile_link(){
            if( ! is_user_logged_in() ) return false;

            global $asgarosforum;
            $user_id = Better_Messages()->functions->get_current_user_id();
            $view_id = $asgarosforum->current_element;

            if( $user_id !== $view_id ) {
                $view_user = get_userdata( $view_id );

                $link = Better_Messages()->functions->add_hash_arg('new-conversation', [
                    'to' => $view_user->ID
                ], Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));

                echo '<a href="' . $link .'">' . __('Private Message', 'bp-better-messages') . ' </a>';
            } else {
                $messages_total = Better_Messages()->functions->get_total_threads_for_user( $user_id,  'unread' );
                $class = ( 0 === $messages_total ) ? 'no-count' : 'count';

                $title = sprintf( _x( 'Messages <span class="%s bp-better-messages-unread">%s</span>', 'Messages list sub nav', 'bp-better-messages' ), esc_attr( $class ), bp_core_number_format( $messages_total ) );

                $link = $asgarosforum->get_link('profile', $user_id) . 'messages/';

                if( $asgarosforum->current_view === 'messages' ) {
                    echo '<a class="active" href="' . $link .'">' . $title . ' </a>';
                } else {
                    echo '<a href="' . $link .'">' . $title . ' </a>';
                }
            }
        }

        public function disable_message_to_deleted_users( $allowed, $user_id, $thread_id ){
            $type = Better_Messages()->functions->get_thread_type( $thread_id );
            if( $type !== 'thread' ) return $allowed;;

            $recipients = Better_Messages()->functions->get_recipients( $thread_id );
            unset( $recipients[$user_id] );

            if( count( $recipients ) === 1 ){
                $user_id = array_keys($recipients)[0];
                if( $user_id > 0 ){
                    $userdata = get_userdata(array_keys($recipients)[0]);
                    if( ! $userdata ) return false;
                } else {
                    $guest_user = Better_Messages()->guests->get_guest_user( $user_id );
                    if( ! $guest_user ){
                        return false;
                    }
                }
            }

            return $allowed;
        }

        public function allow_additional_tags( $tags ){
            $tags['u'] = [];
            $tags['sub'] = [];
            $tags['sup'] = [];

            return $tags;
        }

        public function hide_admin_counter(){
            echo '<style type="text/css">.no-count.bp-better-messages-unread{display:none!important}</style>';
        }

        public function disable_thread_for_pmpro_restricted_role( &$args, &$errors ){
            if( ! pmpro_bp_user_can( 'private_messaging', Better_Messages()->functions->get_current_user_id() ) ) {
                $errors['pmpro_restricted'] = __('Your membership does not allow to use messages', 'bp-better-messages');
            }
        }

        public function disable_messages_for_pmpro_restricted_role( $allowed, $user_id, $thread_id ){
            if( ! pmpro_bp_user_can( 'private_messaging', $user_id ) ) {
                $allowed = false;
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['pmpro_restricted'] = __('Your membership does not allow to use messages', 'bp-better-messages');
            }

            return $allowed;
        }

        public function override_last_activity($null, $object_id, $meta_key, $meta_value, $prev_value){
            if( $meta_key === 'last_activity' ) {
                Better_Messages()->users->update_last_activity( $object_id, $meta_value );
            }

            return $null;
        }

        public function max_height_css(){
            if( ! is_user_logged_in() ) return false;
            $max_height = (int) apply_filters('bp_better_messages_max_height', Better_Messages()->settings['messagesHeight']);
            $fixed_header_height = (int) Better_Messages()->settings['fixedHeaderHeight'];

            if( $fixed_header_height > 0 ){
                $max_height = $max_height - $fixed_header_height;
            }

            #echo '<style type="text/css">body:not(.bp-messages-mobile) .bp-messages-wrap.bp-messages-wrap-main > .scroller,body:not(.bp-messages-mobile) .bp-messages-wrap.bp-messages-wrap-main > .bp-messages-side-threads-wrapper > .bp-messages-column > .scroller,body:not(.bp-messages-mobile) .bp-messages-wrap.bp-messages-wrap-main > .bp-messages-side-threads-wrapper > .bp-messages-column > .scroller > .scroller,body:not(.bp-messages-mobile) .bp-messages-wrap.bp-messages-wrap-main > .scroller > .scroller{max-height:'. $max_height .'px;}body:not(.bp-messages-mobile) .bp-messages-threads-wrapper{max-height:' . ($max_height) .'px!important;}</style>';
            echo '<style type="text/css">.bp-messages-threads-wrapper{height:' . $max_height . 'px}</style>';
        }

        public function beehive_theme_integration(){
            if( ! class_exists('Beehive') ) return false;

            $options = get_option('beehive_opts', [
                'primary' => '#21759b'
            ]);

            if( is_array($options) && isset($options['primary'] ) ) {
                $main_color = $options['primary'];
                $rgba_color_075 = Better_Messages()->functions->hex2rgba($main_color, 0.075);
                $rgba_color_06  = Better_Messages()->functions->hex2rgba($main_color, 0.6);
                ?><style type="text/css">
                    body.bp-messages-mobile header{
                        display: none;
                    }

                    .bp-messages-wrap.bp-messages-mobile .reply .send button[type=submit]{
                        background: #f7f7f7 !important;
                    }

                    .bp-better-messages-list .tabs>div[data-tab=messages] .unread-count, .bp-better-messages-mini .chats .chat .head .unread-count{
                        background: <?php echo $main_color; ?> !important;
                    }

                    .bp-messages-wrap .chat-header .fas,
                    .bp-messages-wrap .chat-header>a,
                    .bp-messages-wrap .reply .send button[type=submit],
                    .uppy-Dashboard-browse,
                    .bp-messages-wrap.mobile-ready:not(.bp-messages-mobile) .bp-messages-mobile-tap{
                        color: <?php echo $main_color; ?> !important;
                    }

                    .uppy-Dashboard-close .UppyIcon{
                        fill: <?php echo $main_color; ?> !important;
                    }

                    .bp-messages-wrap .bp-emojionearea.focused,
                    .bp-messages-wrap .new-message form>div input:focus,
                    .bp-messages-wrap .active .taggle_list,
                    .bp-messages-wrap .chat-header .bpbm-search form input:focus{
                        border-color: <?php echo $main_color; ?>!important;
                        -moz-box-shadow: inset 0 1px 1px <?php echo $rgba_color_075; ?>, 0 0 8px <?php echo $rgba_color_06; ?>;
                        -webkit-box-shadow: inset 0 1px 1px <?php echo $rgba_color_075; ?>, 0 0 8px <?php echo $rgba_color_06; ?>;
                        box-shadow: inset 0 1px 1px <?php echo $rgba_color_075; ?>, 0 0 8px <?php echo $rgba_color_06; ?>;
                    }

                    .bp-messages-wrap #send-to .ui-autocomplete{
                        border-color: <?php echo $main_color; ?>;
                        -moz-box-shadow: inset 0 0 0 <?php echo $rgba_color_075; ?>, 0 3px 3px <?php echo $rgba_color_06; ?>;
                        -webkit-box-shadow: inset 0 0 0 <?php echo $rgba_color_075; ?>, 0 3px 3px <?php echo $rgba_color_06; ?>;
                        box-shadow: inset 0 0 0 <?php echo $rgba_color_075; ?>, 0 3px 3px <?php echo $rgba_color_06; ?>;
                    }
                </style>
                <script type="text/javascript">
                    jQuery(document).on('bp-better-messages-update-unread', function( event ) {
                        var unread = parseInt(event.detail.unread);
                        var private_messages = jQuery('#nav_private_messages');

                        if( unread > 0 ){
                            var count = private_messages.find('span.count');
                            if( count.length === 0 ){
                                private_messages.append('<span class="count">' + unread + '</span>');
                            } else {
                                private_messages.find('.count').text(unread);
                            }
                        } else {
                            private_messages.find('span.count').remove();
                        }
                    });
                </script>
                <?php
            }
        }

        public function update_last_activity(){
            $user_id = Better_Messages()->functions->get_current_user_id();
            if( is_user_logged_in() ) {
                bp_update_user_last_activity($user_id);
            } else {

            }
        }

        public function mobile_popup_button(){
            if( ! is_user_logged_in() && ! Better_Messages()->guests->guest_access_enabled() ) return '';

            echo '<div id="bp-better-messages-mini-mobile-container"></div>';

            if(  Better_Messages()->settings['mobilePopup'] == '0' ) return '';

            $user_id = Better_Messages()->functions->get_current_user_id();
            $restricted_roles = Better_Messages()->settings['restrictMobilePopup'];

            if( count($restricted_roles) > 0 ) {
                $roles = Better_Messages()->functions->get_user_roles( $user_id );

                $is_restricted = false;

                foreach ($restricted_roles as $restricted_role) {
                    if (in_array($restricted_role, $roles)) {
                        $is_restricted = true;
                    }
                }

                if( $is_restricted ) return '';
            }


            if( Better_Messages()->settings['mechanism'] === 'websocket') {
                $count = 0;
            } else {
                $count = Better_Messages()->functions->get_total_threads_for_user( $user_id,  'unread' );
            }

            $class = ($count === 0) ? 'no-count' : '';

            $positionClass = ( Better_Messages()->settings['mobilePopupLocation'] === 'left' ) ? ' bpbm-mobile-open-left' : '';

            echo '<div id="bp-better-messages-mini-mobile-open" class="' . $positionClass . '">';
            echo '<span class="bp-better-messages-mini-mobile-open-icon"></span>';
            echo '<span class="count ' . $class . ' bp-better-messages-unread">' . $count . '</span></div>';
        }

        public function heartbeat_unread_notifications( $response = array() ){
            if( Better_Messages()->settings['mechanism'] === 'websocket') {
                if (isset($response['total_unread_messages'])) {
                    unset($response['total_unread_messages']);
                }
            }

            return $response;
        }

        public function replace_unread_count( $unread_count, $user_id ){
            /** Replacing BuddyBoss Messages Count */
            if( class_exists('BuddyBoss_Theme') || function_exists( 'buddyboss_theme_register_required_plugins' ) ) {
                return 0;
            }

            if( Better_Messages()->settings['mechanism'] === 'websocket'){
                /** BuddyX Counter */
                if( defined('BUDDYX_MINIMUM_WP_VERSION') ){
                    return 0;
                }

                /** BuddyXPro Counter */
                if( defined('BUDDYXPRO_MINIMUM_WP_VERSION') ){
                    return 0;
                }

                /** CERA Counter */
                if( defined('GRIMLOCK_BUDDYPRESS_VERSION')){
                    return 0;
                }
            }

            return Better_Messages()->functions->get_total_threads_for_user( $user_id,  'unread' );
        }

        public function inbox_counter_javascript(){
            if( ! is_user_logged_in() ) return false;
            ob_start();

            if(class_exists('WooCommerce')){ ?>
                jQuery(document).on('bp-better-messages-update-unread', function( event ) {
                    var unread = parseInt(event.detail.unread);
                    var element = jQuery('.woocommerce-MyAccount-navigation-link--bp-messages a .bp-better-messages-unread');
                    if( element.length === 0 ){
                        jQuery('<span class="bp-better-messages-unread bpbmuc bpbmuc-preserve-space bpbmuc-hide-when-null" data-count="' + unread + '">' + unread + '</span>').appendTo('.woocommerce-MyAccount-navigation-link--bp-messages a');
                    }
                });
            <?php
                //
            }

            if( class_exists('BuddyBoss_Theme') ){ ?>
                    jQuery(document).on('bp-better-messages-update-unread', function( event ) {
                        var unread = parseInt(event.detail.unread);
                        var messages_count = jQuery('.header-notifications.user-messages span');
                        if( unread > 0 ){
                            messages_count.text(unread).attr('class', 'count');
                        } else {
                            messages_count.text(unread).attr('class', 'no-alert');
                        }
                    });
            <?php } else if( function_exists( 'buddyboss_theme_register_required_plugins' ) ){  ?>
                    jQuery(document).on('bp-better-messages-update-unread', function( event ) {
                        var unread = parseInt(event.detail.unread);

                        var iconSelector = '.bb-icon-inbox-small';

                        if( jQuery('body').hasClass('bb-template-v2') || jQuery('body').hasClass('bb-template-v1') ){
                            iconSelector = '.bb-icon-inbox';
                        }

                        var messages_count = jQuery('.notification-wrap.messages-wrap .count');
                        if( unread > 0 ){
                            if( messages_count.length === 0 ){
                                jQuery('.notification-wrap.messages-wrap').find(iconSelector).parent().append( '<span class="count">' + unread + '</span>' );
                            } else {
                                messages_count.text(unread).show();
                            }
                        } else {
                            messages_count.text(unread).hide();
                        }

                        var buddypanel = jQuery('.buddypanel-menu .bp-messages-nav > a');
                        if( buddypanel.length > 0 ){
                            var messages_count = buddypanel.find('.count');
                            if( unread > 0 ){
                                if( messages_count.length === 0 ){
                                    buddypanel.append( '<span class="count">' + unread + '</span>' );
                                } else {
                                    messages_count.text(unread).show();
                                }
                            } else {
                                messages_count.text(unread).hide();
                            }
                        }
                    });
            <?php } else if( defined('BUDDYX_MINIMUM_WP_VERSION') || defined('BUDDYXPRO_MINIMUM_WP_VERSION') ){ ?>
                    jQuery(document).on('bp-better-messages-update-unread', function( event ) {
                        var unread = parseInt(event.detail.unread);
                        var messages_count = jQuery('.buddypress-icons-wrapper .bp-msg .bp-icon-wrap sup');

                        if( unread === 0 ){
                            messages_count.hide();
                        } else {
                            if( messages_count.length === 0 ){
                                jQuery('.buddypress-icons-wrapper .bp-msg .bp-icon-wrap').append('<sup>' + unread + '</sup>');
                            } else {
                                messages_count.text(unread).show();
                            }
                        }
                    });
                <?php
            } else if( defined('GRIMLOCK_BUDDYPRESS_VERSION') ){ ?>
                    jQuery(document).on('bp-better-messages-update-unread', function( event ) {
                        var unread = parseInt(event.detail.unread);
                        var container = jQuery('.menu-item--messages');

                        var bubbleSpan = container.find('.bubble-count');

                        if( unread > 0 ){
                            if( bubbleSpan.length > 0 ){
                                bubbleSpan.text(unread);
                            } else {
                                var bubble = '<span class="bubble-count messages-count">' + unread + '</span>';
                                $(bubble).prependTo( container );
                            }
                        } else {
                            bubbleSpan.remove();
                        }

                        if( jQuery('body').hasClass('my-account') ) {
                            var grimlock_counter = jQuery('#profile-content__nav ul.settings-nav #user-bp_better_messages_tab');
                            var count = grimlock_counter.find('span.count');

                            if (unread > 0) {
                                if (count.length > 0) {
                                    count.text(unread);
                                } else {
                                    jQuery('<span class="count">' + unread + '</span>').appendTo(grimlock_counter);
                                }
                            } else {
                                count.remove();
                            }
                        }
                    });
                <?php
            }

            $js = ob_get_clean();
            if( trim( $js ) !== '' ){
                wp_add_inline_script( 'better-messages', Better_Messages()->functions->minify_js( $js ), 'before' );
            }
        }

        public function themes_adaptation(){
            $theme = wp_get_theme();
            $theme_name = $theme->get_template();

            switch ($theme_name){
                case 'boss':
                    echo '<style type="text/css">';
                    echo 'body.bp-messages-mobile #mobile-header{display:none}';
                    echo 'body.bp-messages-mobile #inner-wrap{margin-top:0}';
                    echo 'body.bp-messages-mobile .site{min-height:auto}';
                    echo '</style>';
                    break;
            }
        }

        public function disableStatuses(){
            ?><style type="text/css">.bp-messages-wrap .list .messages-stack .content .messages-list li .status{display: none !important;}.bp-messages-wrap .list .messages-stack .content .messages-list li .favorite{right: 5px !important;}</style><?php
        }

        public function disable_group_threads(&$args, &$errors){
            if( ! is_array($args['recipients']) ) return false;
            $recipients = $args['recipients'];
            if(count($recipients) > 1) {
                $message = _x('You can start conversation only with 1 user per time', 'Error message when group threads are disabled', 'bp-better-messages');
                $errors[] = $message;
            }
        }

        public function disable_message_for_blocked_user( $allowed, $user_id, $thread_id ){
            if( ! class_exists('BPTK_Block') ) return $allowed;

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

            foreach( $participants['recipients'] as $recipient_user_id ){
                $list = Better_Messages()->functions->get_user_meta( $recipient_user_id, 'bptk_block', true );
                if ( empty($list) ) {
                    $list = array();
                }
                $_list = apply_filters( 'get_blocked_users', $list, $recipient_user_id );
                $recipient_blocked = array_filter( $_list );

                if( in_array( $user_id, $recipient_blocked ) ){
                    global $bp_better_messages_restrict_send_message;
                    $bp_better_messages_restrict_send_message['blocked_by_user'] = __('You were blocked by recipient', 'bp-better-messages');
                    $allowed = false;
                }
            }


            return $allowed;
        }

        public function disable_message_for_blocked_restricted_role( $allowed, $user_id, $thread_id ){
            $user_id = Better_Messages()->functions->get_current_user_id();
            $restricted_roles = (array)  Better_Messages()->settings['restrictNewReplies'];
            $user_roles       = Better_Messages()->functions->get_user_roles( $user_id );

            $is_restricted = false;
            foreach( $user_roles as $user_role ){
                if( in_array( $user_role, $restricted_roles ) ){
                    $is_restricted = true;
                }
            }

            if( $is_restricted ) {
                $allowed = false;
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['role_reply_restricted'] = Better_Messages()->settings['restrictNewRepliesMessage'];
            }

            return $allowed;
        }

        public function disable_fast_mode( $enabled ){
            $user_id          = Better_Messages()->functions->get_current_user_id();
            $restricted_roles = (array) Better_Messages()->settings['restrictViewMessages'];
            $user_roles       = Better_Messages()->functions->get_user_roles( $user_id );

            $is_restricted = false;
            foreach( $user_roles as $user_role ){
                if( in_array( $user_role, $restricted_roles ) ){
                    $is_restricted = true;
                }
            }

            if( $is_restricted ){
                return '0';
            }

            return $enabled;
        }

        public function hide_message_for_restricted_role( $message, $message_id, $context, $user_id ){
            if( ! Better_Messages()->functions->is_valid_user_id( $user_id ) ){
                return $message;
            }

            $restricted_roles = (array) Better_Messages()->settings['restrictViewMessages'];

            $user_roles       = Better_Messages()->functions->get_user_roles( $user_id );

            $is_restricted = false;
            foreach( $user_roles as $user_role ){
                if( in_array( $user_role, $restricted_roles ) ){
                    $is_restricted = true;
                }
            }

            if( $is_restricted ){
                return Better_Messages()->settings['restrictViewMessagesMessage'];
            }

            return $message;
        }

        public function disable_new_thread_button_if_needed(){
            $user_id          = Better_Messages()->functions->get_current_user_id();
            $restricted_roles = (array)  Better_Messages()->settings['restrictNewThreads'];
            $user_roles       = Better_Messages()->functions->get_user_roles( $user_id );

            $is_restricted = false;
            foreach( $user_roles as $user_role ){
                if( in_array( $user_role, $restricted_roles ) ){
                    $is_restricted = true;
                }
            }

            if( $is_restricted ) {
                Better_Messages()->settings['disableNewThread'] = '1';
            }
        }

        public function disable_new_thread_button_if_disallowed(){
            $user_id          = Better_Messages()->functions->get_current_user_id();
            $roles = Better_Messages()->functions->get_restrict_to_roles( $user_id );

            $is_restricted = count( $roles ) === 0;

            if( $is_restricted ) {
                Better_Messages()->settings['disableNewThread'] = '1';
            }
        }

        public function disable_thread_for_blocked_restricted_role( &$args, &$errors ){
            $user_id          = Better_Messages()->functions->get_current_user_id();
            $restricted_roles = (array)  Better_Messages()->settings['restrictNewThreads'];
            $user_roles       = Better_Messages()->functions->get_user_roles( $user_id );

            $is_restricted = false;
            foreach( $user_roles as $user_role ){
                if( in_array( $user_role, $restricted_roles ) ){
                    $is_restricted = true;
                }
            }

            if( $is_restricted ) {
                $errors['restrictNewThreadsMessage'] = Better_Messages()->settings['restrictNewThreadsMessage'];
            }
        }

        public function disable_archived_reply($allowed, $user_id, $thread_id){
            $participants = Better_Messages()->functions->get_recipients($thread_id);

            if( isset( $participants[$user_id] ) ){
                if( (int) $participants[$user_id]->is_deleted === 1 ){
                    global $bp_better_messages_restrict_send_message;
                    $bp_better_messages_restrict_send_message['thread_archived_1'] = __("This conversation is deleted.", 'bp-better-messages');
                    $bp_better_messages_restrict_send_message['thread_archived'] = __("You need to recover this conversation to continue.", 'bp-better-messages');

                    return false;
                }
            }

            return $allowed;
        }

        public function disable_non_friends_reply( $allowed, $user_id, $thread_id ){
            $participants = Better_Messages()->functions->get_participants($thread_id);
            if( count($participants['recipients']) !== 1) return $allowed;
            reset($participants['recipients']);


            $friend_id = key($participants['recipients']);

            /**
             * Allow users reply to admins even if not friends
             */
            if( current_user_can('manage_options') || user_can( $friend_id, 'manage_options' ) ) {
                return $allowed;
            }

            $allowed = Better_Messages()->functions->is_friends($user_id, $friend_id);

            if( ! $allowed ){
                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message['friendship_needed'] = __('You must become friends to send messages', 'bp-better-messages');
            }

            return $allowed;
        }

        public function disable_start_thread_if_thread_exist(&$args, &$errors){
            $recipients = $args['recipients'];
            if( ! is_array( $recipients ) ) return false;
            if(count($recipients) > 1) return false;
            $threadExists = array();


            foreach( $recipients as $user_id ){
                $from = Better_Messages()->functions->get_current_user_id();
                $user = Better_Messages()->functions->rest_user_item( $user_id );
                $threads = Better_Messages()->functions->find_existing_threads($from, $user_id);

                if( count($threads) > 0) {
                    $threadExists[] = $user['name'];
                }
            }

            if(count($threadExists) > 0){
                $message = sprintf(__('You already have conversations with %s', 'bp-better-messages'), implode(', ', $threadExists));
                $errors[] = $message;
            }
        }

        public function disable_start_thread_if_user_restricted(&$args, &$errors){
            if( current_user_can('manage_options' ) ) {
                return null;
            }

            $current_user_id = Better_Messages()->functions->get_current_user_id();

            $recipients = $args['recipients'];
            if( ! is_array( $recipients ) ) $recipients = [ $recipients ];

            foreach($recipients as $recipient){
                $user = get_userdata($recipient);

                $who_can_start = Better_Messages_User_Config()->get_who_can_start_value( $user->ID );

                if( in_array( 'everyone', $who_can_start ) ) {
                    continue;
                }

                if( in_array('nobody', $who_can_start) ){
                    $errors[] = sprintf(__('%s not allow to message him', 'bp-better-messages'), Better_Messages()->functions->get_name($user->ID) );
                    continue;
                }

                $friends_allowed = in_array('only_friends', $who_can_start);
                $followers_allowed = in_array('only_followers', $who_can_start);

                if( $friends_allowed && $followers_allowed ){
                    if( ! Better_Messages()->functions->is_friends( $current_user_id, $user->ID) ){
                        $errors['not_friends'] = sprintf( __('<strong>%s</strong> allows only friends to message him', 'bp-better-messages'), Better_Messages()->functions->get_name($user->ID) );
                    } else if( ! Better_Messages()->functions->is_followers( $current_user_id, $user->ID) ){
                        $errors['not_friends'] = sprintf(_x('<strong>%s</strong> allows only followers to message him', 'Ultimate Member User Restriction', 'bp-better-messages'), Better_Messages()->functions->get_name($user->ID));
                    }
                } else if( $friends_allowed ){
                    if( ! Better_Messages()->functions->is_friends( $current_user_id, $user->ID) ){
                        $errors['not_friends'] = sprintf( __('<strong>%s</strong> allows only friends to message him', 'bp-better-messages'), Better_Messages()->functions->get_name($user->ID) );
                    }
                } else if( $followers_allowed ){
                    if( ! Better_Messages()->functions->is_friends( $current_user_id, $user->ID) ){
                        $errors['not_friends'] = sprintf( __('<strong>%s</strong> allows only friends to message him', 'bp-better-messages'), Better_Messages()->functions->get_name($user->ID) );
                    }
                }
            }
        }

        public function disable_start_thread_for_non_friends(&$args, &$errors){
            if( current_user_can('manage_options' ) ) {
                return null;
            }

            $recipients = $args['recipients'];
            if( ! is_array( $recipients ) ) $recipients = [ $recipients ];

            $notFriends = array();

            foreach( $recipients as $recipient ){
                if( ! friends_check_friendship( Better_Messages()->functions->get_current_user_id(), $recipient ) ) {
                    $notFriends[] = Better_Messages()->functions->get_name($recipient);
                }
            }

            if(count($notFriends) > 0){
                $message = sprintf(__('You must become friends with <strong>%s</strong> to start conversation', 'bp-better-messages'), implode(', ', $notFriends));
                $errors['not_friends'] = $message;
            }
        }

        public function catch_fast_thread(){
            if( ! is_user_logged_in() ) return;
            if( ! isset($_SERVER['QUERY_STRING']) ) return;

            $requested_page_slug = array_reverse(explode( "/", trim(rtrim(str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']), '?'), '/')));

            $settings_page_slug = array_reverse(explode( "?", str_replace(site_url('').'/', '', Better_Messages()->functions->get_link())));
            $settings_page_slug = array_reverse(array_filter(explode( "/", end($settings_page_slug))));
            $is_bp_profile_page = (function_exists ('bp_is_my_profile')) ? bp_is_my_profile() : false;

            $slug_match = true;

            foreach( $settings_page_slug as $index => $val ){
                if( ! isset($requested_page_slug[$index]) || $requested_page_slug[$index] !== $val ){
                    $slug_match = false;
                    break;
                }
            }

            if(
                isset($_GET['bm-fast-start'])
                && isset($_GET['to'])
                && ! empty($_GET['bm-fast-start'])
                && ! empty($_GET['to'])
                && ( $slug_match || $is_bp_profile_page )
            ){
                $getTo = sanitize_text_field($_GET['to']);

                $to = false;

                if( is_numeric($getTo) ){
                    $to = get_userdata( $getTo );
                }

                if( ! $to ) {
                    $to = get_user_by('slug', $getTo );
                }

                if( ! $to ) return false;

                if( Better_Messages()->settings['singleThreadMode'] == '1' ) {
                    $threads = Better_Messages()->functions->find_existing_threads(Better_Messages()->functions->get_current_user_id(), $to->ID);
                    if( count($threads) > 0) {
                        $url = Better_Messages()->functions->get_link();
                        $url = Better_Messages()->functions->add_hash_arg('conversation/' . $threads[0], ['scrollToContainer' => '' ], $url );

                        wp_redirect($url);
                        exit;
                    }
                }


                $result = Better_Messages()->functions->get_pm_thread_id($to->ID);

                $url = Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ) ;

                if( isset($result['errors']) ){
                    $url = Better_Messages()->functions->add_hash_arg('', ['bmErrors' => urlencode( json_encode( $result['errors'] ) ) . 'end' ], $url );
                    wp_redirect($url);
                    exit;
                }

                $url = Better_Messages()->functions->get_link();
                $url = Better_Messages()->functions->add_hash_arg('conversation/' . $result['thread_id'], ['scrollToContainer' => '' ], $url );

                wp_redirect($url);
                exit;
            }
        }

        public function admin_notice(){
            if( ! class_exists('BuddyPress') && ! defined('ultimatemember_version') ){
                if( Better_Messages()->settings['chatPage'] == '0' ){
                    echo '<div class="notice notice-error">';
                    echo '<p><b>Better Messages</b> require <b><a href="'. admin_url('options-general.php?page=bp-better-messages#general').'">installing Messages Location</a></b>.</p>';
                    echo '</div>';
                }
            } else {
                if( ! bm_bp_is_active('messages') && ! defined('ultimatemember_version') ) {
                    $url = admin_url('options-general.php?page=bp-components');
                    if( defined('BP_PLATFORM_VERSION') ){
                        $url = admin_url('admin.php?page=bp-components');
                    }
                    echo '<div class="notice notice-error">';
                    echo '<p><b>Better Messages</b> require <b>BuddyPress</b> <b>Messages Component</b> to be active. <a href="'.$url.'">Activate</a></p>';
                    echo '</div>';
                }
            }
        }

        public function chat_page($content){
            $page_id = get_the_ID();
            $chat_page_id = Better_Messages()->settings['chatPage'];

            if( ! is_numeric( $chat_page_id ) ) return $content;

            if( defined('ICL_LANGUAGE_CODE') ){
                $chat_page_id = apply_filters( 'wpml_object_id', $chat_page_id, 'page', true, ICL_LANGUAGE_CODE );
            }

            if( $chat_page_id != $page_id ) return $content;

            if( ! is_user_logged_in() && ! Better_Messages()->guests->guest_access_enabled() ){
                $messages_content = Better_Messages()->functions->render_login_form();

                if( strpos($content, '[bp-better-messages]') !== FALSE ){
                    return str_replace( '[bp-better-messages]', $messages_content, $content );
                } else {
                    return $messages_content;
                }
            }

            if( function_exists('pmpro_has_membership_access') ) {
                // PM PRO PLUGIN ACTIVE
                $hasaccess = pmpro_has_membership_access(NULL, NULL, false);

                if( ! $hasaccess ) return $content;
            }

            $messages_content = Better_Messages()->functions->get_page( true );

            if( strpos($content, '[bp-better-messages]') !== FALSE ){
                $content = str_replace( '[bp-better-messages]', $messages_content, $content );
            } else {
                $content = $messages_content;
            }

            return $content;
        }

        public function exclude_attachments_global( $query ){

            if( Better_Messages()->settings['attachmentsHide'] === '1' ) {
                if( is_admin() && $query->is_main_query()  && ( isset( $query->query['post_type'] )  && $query->query['post_type'] === 'attachment' ) ) {
                    $meta_query = $query->get('meta_query');
                    if( ! is_array($meta_query) ) $meta_query = array();

                    $meta_query[] = array(
                        'key'     => 'bp-better-messages-attachment',
                        'value'   => '1',
                        'compare' => 'NOT EXISTS'
                    );

                    $query->set('meta_query', $meta_query);
                }
            }

            if( ! is_admin() && $query->is_main_query() && isset( $query->query['pagename'] ) ){
                $meta_query = $query->get('meta_query');
                if( ! is_array($meta_query) ) $meta_query = array();

                $meta_query[] = array(
                    'key'     => 'bp-better-messages-attachment',
                    'value'   => '1',
                    'compare' => 'NOT EXISTS'
                );

                $query->set('meta_query', $meta_query);
            }

        }

        function exclude_attachments($query){
            if( isset( $query['meta_query'] ) ) {
                $meta_query = $query['meta_query'];
            } else {
                $meta_query = array();
            }

            if( ! is_array($meta_query) ) $meta_query = array();

            $meta_query[] = array(
                'key'     => 'bp-better-messages-attachment',
                'value'   => '1',
                'compare' => 'NOT EXISTS'
            );

            $query['meta_query'] = $meta_query;

            return $query;
        }

        function cron_intervals( $schedules )
        {
            /*
             * Cron for our new mailer!
             */
            $schedules[ 'fifteen_minutes' ] = array(
                'interval' => 60 * 15,
                'display'  => 'Every Fifteen Minutes',
            );

            $schedules[ 'one_minute' ] = array(
                'interval' => 60,
                'display'  => 'Every Minute' ,
            );

            $notifications_interval = (int) Better_Messages()->settings['notificationsInterval'];
            if( $notifications_interval < 1 ) $notifications_interval = 1;

            $schedules['bp_better_messages_notifications'] = array(
                'interval' => 60 * $notifications_interval,
                'display' => 'Better Messages Notifications Interval',
            );

            $schedules['better_messages_cleaner_job'] = array(
                'interval' => 60 * 5,
                'display' => 'Better Messages Cleaner Job Interval',
            );

            return $schedules;
        }

    }

    // Fallback
    if( ! class_exists('BP_Better_Messages_Hooks') ){
        class BP_Better_Messages_Hooks extends Better_Messages_Hooks {}
    }
endif;

function Better_Messages_Hooks()
{
    return Better_Messages_Hooks::instance();
}

if( ! function_exists('BP_Better_Messages_Hooks') ) {
    function BP_Better_Messages_Hooks()
    {
        return Better_Messages_Hooks();
    }
}
