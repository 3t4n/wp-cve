<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Rest_Api' ) ):

    class Better_Messages_Rest_Api
    {

        public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Rest_Api();
            }

            return $instance;
        }

        public function __construct()
        {
            require_once 'api/search.php';
            Better_Messages_Search();

            require_once('api/db-migrate.php');
            Better_Messages_Rest_Api_DB_Migrate();

            require_once('api/conversations.php');
            Better_Messages_Rest_Api_Conversations();

            require_once('api/push-notifications.php');
            Better_Messages_Rest_Push_Notifications();

            require_once('api/friends.php');
            Better_Messages_Rest_Friends();

            require_once('api/groups.php');
            Better_Messages_Rest_Groups();

            require_once('api/favorited.php');
            Better_Messages_Rest_Api_Favorited();

            require_once('api/admin.php');
            Better_Messages_Rest_Api_Admin();

            require_once('api/bulk-message.php');
            Better_Messages_Rest_Api_Bulk_Message();

            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );

            add_action( 'wp_ajax_better_messages_new_nonce_token', array( $this, 'rest_nonce' ) );
            add_action( 'wp_ajax_nopriv_better_messages_new_nonce_token', array( $this, 'rest_nonce' ) );

            add_filter('rest_post_dispatch', array( $this, 'catch_unauthorized'), 10 , 3 );
        }

        public function catch_unauthorized( $result, $server, WP_REST_Request $request ){
            $route = $request->get_route();

            if( str_starts_with( $route, '/better-messages/') && str_ends_with( $route, '/send' ) ){
                if( isset( $result->data ) ){
                    if( isset( $result->data['code'] ) && $result->data['code'] === 'rest_cookie_invalid_nonce' ){
                        $temp_id = $request->get_param('tmpId');

                        if( $temp_id ){
                            $temp_id_explode = explode('_', $temp_id);
                            $thread_id = (int) $temp_id_explode[1];

                            do_action( 'better_messages_on_message_not_sent', $thread_id, $temp_id, [] );
                        }
                    }
                }
            }

            return $result;
        }

        public function rest_nonce(){
            wp_send_json([
                'user_id' => Better_Messages()->functions->get_current_user_id(),
                'nonce'   => wp_create_nonce( 'wp_rest' )
            ]);
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/ping', array(
                'methods' => 'GET',
                'callback' => array( $this, 'ping' ),
                'permission_callback' => '__return_true'
            ) );

            register_rest_route( 'better-messages/v1', '/checkNew', array(
                'methods' => 'POST',
                'callback' => array( $this, 'checkNew' ),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ) );

            register_rest_route( 'better-messages/v1', '/openThreads', array(
                'methods' => 'POST',
                'callback' => array( $this, 'openThreads' ),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ) );

            register_rest_route( 'better-messages/v1', '/markAllRead', array(
                'methods' => 'GET',
                'callback' => array( $this, 'markAllRead' ),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ) );

            register_rest_route( 'better-messages/v1', '/threads', array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_threads' ),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ) );

            register_rest_route( 'better-messages/v1', '/threads', array(
                'methods' => 'POST',
                'callback' => array( $this, 'get_threads' ),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)', array(
                'methods' => 'POST',
                'callback' => array( $this, 'get_thread' ),
                'permission_callback' => array( $this, 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/searchParticipants', array(
                'methods' => 'POST',
                'callback' => array( $this, 'search_participants' ),
                'permission_callback' => array( $this, 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/loadMore', array(
                'methods' => 'POST',
                'callback' => array( $this, 'load_more' ),
                'permission_callback' => array( $this, 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/suggestions', array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_suggestions' ),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ) );

            register_rest_route( 'better-messages/v1', '/userSuggestions', array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_user_suggestions' ),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ) );

            if( Better_Messages()->settings['allowMuteThreads'] === '1' ) {
                register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/mute', array(
                    'methods' => 'POST',
                    'callback' => array($this, 'mute_thread'),
                    'permission_callback' => array($this, 'check_thread_access'),
                    'args' => array(
                        'id' => array(
                            'validate_callback' => function ($param, $request, $key) {
                                return is_numeric($param);
                            }
                        ),
                    ),
                ));

                register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/unmute', array(
                    'methods' => 'POST',
                    'callback' => array($this, 'unmute_thread'),
                    'permission_callback' => array($this, 'check_thread_access'),
                    'args' => array(
                        'id' => array(
                            'validate_callback' => function ($param, $request, $key) {
                                return is_numeric($param);
                            }
                        ),
                    ),
                ));
            }

            register_rest_route('better-messages/v1', '/thread/suggest', array(
                'methods' => 'POST',
                'callback' => array($this, 'suggest_thread'),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ));

            register_rest_route('better-messages/v1', '/getPrivateThread', array(
                'methods' => 'POST',
                'callback' => array($this, 'get_pm_thread'),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ));

            register_rest_route('better-messages/v1', '/thread/new', array(
                'methods' => 'POST',
                'callback' => array($this, 'start_new_thread'),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ));

            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/send', array(
                'methods' => 'POST',
                'callback' => array($this, 'send_message'),
                'permission_callback' => array($this, 'can_reply'),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                ),
            ));

            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/save', array(
                'methods' => 'POST',
                'callback' => array($this, 'save_message'),
                'permission_callback' => array($this, 'can_reply'),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                ),
            ));

            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/deleteMessages', array(
                'methods' => 'POST',
                'callback' => array($this, 'delete_messages'),
                'permission_callback' => array($this, 'check_thread_access'),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                ),
            ));

            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/delete', array(
                'methods' => 'POST',
                'callback' => array($this, 'delete_thread'),
                'permission_callback' => array($this, 'check_thread_access'),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                ),
            ));

            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/restore', array(
                'methods' => 'POST',
                'callback' => array($this, 'restore_thread'),
                'permission_callback' => array($this, 'is_user_authorized'),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                ),
            ));

            register_rest_route( 'better-messages/v1', '/lazyPool', array(
                'methods' => 'POST',
                'callback' => array( $this, 'lazy_pool' ),
                'permission_callback' => array( $this, 'is_user_authorized' )
            ) );

            if( ! empty( trim(Better_Messages()->settings['badWordsList']) ) ) {
                register_rest_route('better-messages/v1', '/getBlockList', array(
                    'methods' => 'GET',
                    'callback' => array($this, 'get_block_list'),
                    'permission_callback' => array($this, 'is_user_authorized')
                ));
            }

            register_rest_route( 'better-messages/v1', '/getUniqueConversation', array(
                'methods' => 'POST',
                'callback' => array( $this, 'get_unique_conversation' ),
                'permission_callback' => '__return_false'

                //'permission_callback' => array( $this, 'is_user_authorized' )
            ) );
        }

        public function get_unique_conversation( WP_REST_Request $request ){
            $thread_id = apply_filters('better_messages_get_unique_conversation', 0, sanitize_text_field( $request->get_param('key') ), Better_Messages()->functions->get_current_user_id() );

            if( $thread_id ){
                $return = $this->get_threads([ $thread_id ]);
                $return['thread_id'] = $thread_id;
                return $return;
            }

            return false;
        }

        public function get_block_list( WP_REST_Request $request ){
            return array_map('trim', explode("\n", Better_Messages()->settings['badWordsList']));
        }

        public function is_user_authorized( WP_REST_Request $request ){
            if( is_user_logged_in() ) {
                return true;
            }

            return apply_filters('better_messages_rest_is_user_authorized', false, $request );
        }

        public function lazy_pool(WP_REST_Request $request){
            $users    = (array) $request->get_param('users');
            $messages = (array) $request->get_param('messages');

            $current_user_id = Better_Messages()->functions->get_current_user_id();

            $return = [];

            if( count( $messages ) > 0 ) {
                $return['messages'] = [];
                foreach ( $messages as $message_id ){
                    $message = Better_Messages()->functions->get_message( $message_id );
                    if( $message ){
                        $has_access = Better_Messages()->functions->check_access( $message->thread_id, $current_user_id );
                        if( $has_access ){
                            $message->message_id = (int) $message->id;
                            unset( $message->id );
                            $_user_id       = (int) $message->sender_id;
                            $_thread_id     = (int) $message->thread_id;
                            $last_update    = (float) Better_Messages()->functions->get_message_meta( $message->message_id, 'bm_last_update', true );
                            $created_time   = (float) Better_Messages()->functions->get_message_meta( $message->message_id, 'bm_created_time', true );

                            Better_Messages()->functions->check_created_time( $message->message_id, $message->date_sent, $created_time );

                            $message->sender_id  = $_user_id;
                            $message->thread_id  = $_thread_id;

                            $meta                = apply_filters('better_messages_rest_message_meta', [], (int) $message->message_id, (int) $message->thread_id, $message->message );
                            $message->meta       = $meta;

                            $message->favorited = (Better_Messages()->functions->is_message_starred($message->message_id, $current_user_id)) ? 1 : 0;
                            $message->message   = Better_Messages()->functions->format_message($message->message, (int) $message->message_id, 'stack', $current_user_id);

                            $message->lastUpdate = $last_update;
                            $message->createdAt  = $created_time;
                            $message->tmpId      = Better_Messages()->functions->get_message_meta( $message->message_id, 'bm_tmp_id', true );

                            $return['messages'][] = $message;
                        }
                    }
                }
            }

            if( count( $users ) > 0 ) {
                $return['users'] = [];
                foreach ( $users as $user_id ){
                    $item = Better_Messages()->functions->rest_user_item( $user_id );

                    $return['users'][] = $item;
                }
            }

            return $return;
        }

        public function markAllRead(){
            global $wpdb;

            $user_id = Better_Messages()->functions->get_current_user_id();

            $time = Better_Messages()->functions->get_microtime();

            $wpdb->query($wpdb->prepare(
                "UPDATE " . bm_get_table('recipients') . " 
                SET unread_count = 0, last_update = %d
                WHERE user_id = %d AND unread_count > 0", $time, $user_id));

            do_action( 'better_messages_mark_all_read', $user_id );

            return true;
        }

        public function openThreads( WP_REST_Request $request ){
            $thread_ids = (array) $request->get_param('thread_ids');

            if( count( $thread_ids ) > 0 ){
                foreach ( $thread_ids as $thread_id ){
                    Better_Messages()->functions->messages_mark_thread_read( $thread_id, Better_Messages()->functions->get_current_user_id() );
                }
            }

            return true;
        }

        public function checkNew( WP_REST_Request $request ) {
            $lastClient = intval( $request->get_param( 'lastUpdate' ) );
            if( $lastClient < 0 ) $lastClient = 0;

            $user_id = Better_Messages()->functions->get_current_user_id();

            $time = Better_Messages()->functions->get_microtime();

            $visibleThreads = (array) $request->get_param('visibleThreads');

            if( count( $visibleThreads ) > 0 ){
                foreach ( $visibleThreads as $thread_id ){
                    Better_Messages()->functions->messages_mark_thread_read( $thread_id, Better_Messages()->functions->get_current_user_id() );
                }
            }

            if( $lastClient > 0 ) {
                $return = $this->get_messages(null, $lastClient);
            } else {
                $return = $this->get_threads( $request );
            }

            if( Better_Messages()->settings['mechanism'] === 'ajax' ) {
                $return['totalUnread'] = Better_Messages()->functions->get_total_threads_for_user( $user_id, 'unread' );
            }

            if( ! isset( $return['threads'] ) ) $return['threads'] = [];
            if( ! isset( $return['users'] ) ) $return['users'] = [];
            if( ! isset( $return['messages'] ) ) $return['messages'] = [];


            if( $lastClient > 0 ) {
                $changedThreads = $this->get_changed_threads($lastClient, $user_id);

                if (count($changedThreads) > 0) {
                    $thread_ids = array_column($return['threads'], 'thread_id');

                    foreach ($changedThreads as $index => $thread_id) {
                        if (in_array($thread_id, $thread_ids)) unset($changedThreads[$index]);
                    }

                    if (count($changedThreads) > 0) {
                        $deletedThreads = $changedThreads;

                        $threads = $this->get_threads($changedThreads, false);

                        $return['threads'] = array_merge($return['threads'], $threads['threads']);
                        $return['users'] = array_merge($return['users'], $threads['users']);

                        $thread_ids = array_column($return['threads'], 'thread_id');

                        foreach ($deletedThreads as $index => $thread_id) {
                            if (in_array($thread_id, $thread_ids)) unset($deletedThreads[$index]);
                        }

                        if (count($deletedThreads) > 0) {
                            $return['deletedThreads'] = array_values($deletedThreads);
                        }
                    }
                }
            }

            $clientThreads = (array) $request->get_param( 'threadIds' );

            if( $clientThreads ){
                /**
                 * Threads existing on client, but not available in database anymore
                 */
                $missedThreads = $this->check_missing_threads( $user_id, $clientThreads );
                if( count( $missedThreads ) > 0 ){
                    $return['missedThreads'] = $missedThreads;
                }

                $unloadedThreads = $this->check_unloaded_threads($user_id, $clientThreads);
            } else {
                $unloadedThreads = $this->check_unloaded_threads($user_id, []);
            }

            if( count( $unloadedThreads ) > 0 ){
                $getUnloadedThreads = $this->get_threads($unloadedThreads);

                $return['threads'] = array_merge($return['threads'], $getUnloadedThreads['threads']);
                $return['users'] = array_merge($return['users'], $getUnloadedThreads['users']);
                $return['messages'] = array_merge($return['messages'], $getUnloadedThreads['messages']);
            }

            if( $lastClient > 0 ) {
                $return['deletedMessages'] = $this->get_deleted_messages($lastClient);

                if (count($return['deletedMessages']) > 0) {
                    $message_ids = array_column($return['messages'], 'message_id');
                    foreach ($return['deletedMessages'] as $index => $message_id) {
                        if (in_array($message_id, $message_ids)) {
                            unset($return['deletedMessages'][$index]);
                        }
                    }
                }

                if (count($return['deletedMessages']) === 0) unset($return['deletedMessages']);

                /*$return['erasedThreads'] = $this->get_erased_threads($lastClient);
                if (count($return['erasedThreads']) === 0) unset($return['erasedThreads']);*/

                $changedUsers = $this->get_changed_users( $lastClient );

                if( count( $changedUsers ) > 0 ) {
                    foreach ($changedUsers as $user_id) {
                        $user_ids = array_column($return['users'], 'user_id');

                        if( ! in_array( $user_id, $user_ids ) ){
                            $return['users'][] = Better_Messages()->functions->rest_user_item( $user_id );
                        }
                    }
                }

            }

            $return['currentTime'] = $time;

            return $return;
        }

        public function start_new_thread( WP_REST_Request $request ){
            $recipients = (array) $request->get_param( 'recipients');
            $subject    = sanitize_text_field( $request->get_param( 'subject') );
            $content = Better_Messages()->functions->filter_message_content( $request->get_param('message') );
            $hasFiles = (boolean) $request->get_param('files');

            $args = array(
                'subject'       => $subject,
                'content'       => $content,
                'recipients'    => $recipients,
                'return'        => 'both',
                'new_thread'    => true,
                'error_type'    => 'wp_error',
                'append_thread' => false
            );

            if( trim($args['content']) == '') {
                if( $hasFiles ){
                    $args['content'] .= '<!-- BM-ONLY-FILES -->';
                }
            } else if ($args['content'] === '0') {
                $args['content'] .= ' ';
            }

            Better_Messages()->functions->before_new_thread_filter( $args, $errors );

            if( empty( $errors ) ){
                $sent = Better_Messages()->functions->new_message( $args );

                if ( is_wp_error( $sent ) ) {
                    $errors[] = $sent->get_error_message();
                }
            }

            if( ! empty( $errors ) ) {
                return array(
                    'result' => false,
                    'errors' => $errors
                );
            } else {
                do_action( 'bp_better_messages_new_thread_created', $sent['thread_id'], $sent['message_id'] );

                return array(
                    'result'     => true,
                    'thread_id'  => $sent['thread_id'],
                    'message_id' => $sent['message_id']
                );
            }
        }

        public function suggest_thread( WP_REST_Request $request ){
            $current_user_id = Better_Messages()->functions->get_current_user_id();
            $recipients = (array) $request->get_param( 'recipients');
            $forceNew   = (boolean) $request->get_param( 'forceNew');

            if( count( $recipients ) === 0 ) return false;

            if( ! $forceNew && count( $recipients ) === 1 ){
                return Better_Messages()->functions->get_pm_thread_id( (int) $recipients[0], Better_Messages()->functions->get_current_user_id(), false );
            }

            return Better_Messages()->functions->can_start_conversation($current_user_id, $recipients);
        }

        public function get_pm_thread( WP_REST_Request $request ){
            $user_id   = intval( $request->get_param('user_id') );
            $create    = boolval( $request->get_param('create') );
            $subject   = trim( sanitize_text_field( urldecode( $request->get_param('subject') ) ) );
            $uniqueKey = trim( sanitize_text_field( urldecode( $request->get_param('uniqueKey') ) ) );

            if( empty( $uniqueKey ) ){
                $result = Better_Messages()->functions->get_pm_thread_id( $user_id, Better_Messages()->functions->get_current_user_id(), $create, $subject );
            } else {
                $result = Better_Messages()->functions->get_unique_pm_thread_id( $uniqueKey, $user_id, Better_Messages()->functions->get_current_user_id(), $create, $subject );
            }

            if( $result['result'] === 'thread_found' || $result['result'] === 'thread_created' ){
                $thread_id = (int) $result['thread_id'];

                $thread_data = $this->get_threads( [ $thread_id ] );

                if( isset( $thread_data['threads'] ) )  $result['threads'] = $thread_data['threads'];
                if( isset( $thread_data['users'] ) )    $result['users'] = $thread_data['users'];
                if( isset( $thread_data['messages'] ) ) $result['messages'] = $thread_data['messages'];
            }

            return $result;
        }

        public function delete_messages( WP_REST_Request $request ){
            $messages_ids = array_map( 'absint', $request->get_param( 'messageIds') );

            $user_id = Better_Messages()->functions->get_current_user_id();

            $replaceMethod = Better_Messages()->settings['deleteMethod'] === 'replace';

            $deleted_messages = [];
            $errors = [];

            foreach( $messages_ids as $message_id ){
                $message = new BM_Messages_Message( $message_id );

                $canDelete = Better_Messages()->settings['allowDeleteMessages'] === '1' && $message->sender_id === $user_id;

                if( ! $canDelete ) {
                    if ( Better_Messages()->functions->is_thread_super_moderator($user_id, $message->thread_id) ) {
                        $canDelete = true;
                    } else if ( Better_Messages()->functions->is_thread_moderator($message->thread_id, $user_id) ) {
                        if ( ! Better_Messages()->functions->is_thread_super_moderator($message->sender_id, $message->thread_id) ) {
                            $canDelete = true;
                        } else {
                            $errors[] = _x('You are not allowed to delete message of super moderator.', 'Rest API Error', 'bp-better-messages');
                        }
                    }
                }

                if( $canDelete ){
                    Better_Messages()->functions->delete_message( $message_id, $message->thread_id );
                    $deleted_messages[] = $message_id;
                }
            }

            $return = [];

            if( $replaceMethod ){
                $return = Better_Messages()->api->get_messages(null, $deleted_messages);
            } else {
                $return['deleted'] = $deleted_messages;
            }

            $return['errors'] = $errors;

            return $return;
        }

        public function delete_thread( WP_REST_Request $request ){
            global $wpdb;

            $thread_id  = intval($request->get_param('id'));

            if( ! apply_filters( 'bp_better_messages_can_delete_thread', true, $thread_id, Better_Messages()->functions->get_current_user_id() ) ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to delete this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $delete_allowed = Better_Messages()->settings['restrictThreadsDeleting'] === '0';
            if( current_user_can('manage_options') ) {
                $delete_allowed = true;
            }

            if( ! $delete_allowed ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to delete this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $user_id = Better_Messages()->functions->get_current_user_id();

            Better_Messages()->functions->archive_thread( $user_id, $thread_id );
        }

        public function restore_thread( WP_REST_Request $request ){
            global $wpdb;

            $user_id = Better_Messages()->functions->get_current_user_id();
            $thread_id  = intval($request->get_param('id'));

            $has_access = (bool)$wpdb->get_var( $wpdb->prepare( "
                SELECT COUNT(*)
                FROM " . bm_get_table('recipients') . "
                WHERE `thread_id`  = %d
                AND   `user_id`    = %d
                AND   `is_deleted` = 1
            ", $thread_id, $user_id ) );

            if ( ! $has_access ) {
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to restore this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $delete_allowed = Better_Messages()->settings['restrictThreadsDeleting'] === '0';

            if( current_user_can('manage_options') ) {
                $delete_allowed = true;
            }

            if( ! $delete_allowed ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to restore this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $time = Better_Messages()->functions->get_microtime();

            $restored = $wpdb->update( bm_get_table('recipients'), array(
                'is_deleted'  => 0,
                'last_update' => $time
            ), array(
                'thread_id' => $thread_id,
                'user_id'   => $user_id
            ) );

            do_action( 'better_messages_thread_updated', $thread_id );

            return !! $restored;
        }

        public function send_message( WP_REST_Request $request ){
            $thread_id  = intval($request->get_param('id'));
            $temp_id    = sanitize_text_field($request->get_param('tmpId'));
            $temp_time  = sanitize_text_field($request->get_param('tmpTime'));

            $content = Better_Messages()->functions->filter_message_content($request->get_param('message'));
            $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');

            $group_thread = false;

            if( ! empty( $group_id ) ) {
                $group_thread = true;
            }

            $args = array(
                'sender_id'    => Better_Messages()->functions->get_current_user_id(),
                'content'      => $content,
                'thread_id'    => $thread_id,
                'return'       => 'message_id',
                'group_thread' => $group_thread,
                'error_type'   => 'wp_error'
            );

            $meta     = (array) $request->get_param('meta');

            if( ! empty( $temp_id ) ) {
                $args['temp_id'] = $temp_id;

                if ( ! empty( $temp_time ) ) {
                    $time_diff = Better_Messages()->functions->get_microtime() - (int) $temp_time;

                    if( $time_diff < 600000 && $time_diff > -600000 ) {
                        $args['date_sent'] = gmdate( 'Y-m-d H:i:s',  (int) $temp_time / 10000 );
                        $meta['bm_created_time'] = $temp_time;
                        $meta['bm_last_update'] = $temp_time;
                    }
                }
            }

            $hasFiles = (boolean) $request->get_param('files');

            if( count( $meta ) > 0 ){
                $args['meta_data'] = $meta;
            }


            if( trim($args['content']) == '') {
                if( $hasFiles ){
                    $args['content'] .= '<!-- BM-ONLY-FILES -->';
                } else {
                    $errors['empty'] = _x('The message you were trying to send was empty', 'User tried to send empty message error', 'bp-better-messages');
                }
            } else if ($args['content'] === '0') {
                $args['content'] .= ' ';
            }

            Better_Messages()->functions->before_message_send_filter( $args, $errors );

            if( empty( $errors ) ){
                $message_id = Better_Messages()->functions->new_message( $args );

                if ( is_wp_error( $message_id ) ) {
                    $errors[] = $message_id->get_error_message();
                } else {
                    Better_Messages()->functions->messages_mark_thread_read( $thread_id );
                }
            }

            if( ! empty($errors) ) {
                do_action( 'better_messages_on_message_not_sent', $thread_id, $temp_id, $errors );

                $redirect = 'refresh';

                if( count( $errors ) === 1 && ( isset( $errors['empty'] ) || isset( $errors['restrictBadWord'] ) ) ){
                    $redirect = false;
                }

                $thread = $this->get_threads( [$thread_id] );

                return array(
                    'result'   => false,
                    'errors'   => $errors,
                    'update'   => $thread,
                    'redirect' => $redirect
                );
            } else {
                $update = $this->get_messages( $thread_id, [ $message_id ] );
                $get_threads = Better_Messages()->api->get_threads( [ $thread_id ], false, false, true );

                if(isset($get_threads['threads'][0])) {
                    $thread = $get_threads['threads'][0];
                    $update['threads'][] = $thread;
                }

                return array(
                    'result'     => true,
                    'message_id' => $message_id,
                    'update'     => $update,
                    'redirect'   => false
                );
            }
        }

        public function save_message( WP_REST_Request $request ){
            if( Better_Messages()->settings['allowEditMessages'] !== '1' ) {
                return new WP_Error(
                    'rest_forbidden',
                    _x('Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages'),
                    array('status' => rest_authorization_required_code())
                );
            }

            global $wpdb;

            $user_id    = Better_Messages()->functions->get_current_user_id();
            $thread_id  = intval( $request->get_param('id') );
            $message_id = intval( $request->get_param('message_id') );
            $content    = Better_Messages()->functions->filter_message_content($request->get_param('message'));

            if( trim($content) == '') {
                return new WP_Error(
                    'rest_forbidden',
                    __( 'Message content was empty.', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $old_message_content = $wpdb->get_var( $wpdb->prepare( "SELECT message FROM " . bm_get_table('messages') ." WHERE id = %d AND sender_id = %d", $message_id, $user_id ) );
            $old_message = $old_message_content;

            $attachments = Better_Messages()->functions->get_message_meta( $message_id, 'attachments', true );
            if( is_array( $attachments ) && count( $attachments ) > 0 ) {
                foreach ($attachments as $attachment) {
                    $old_message_content = str_replace($attachment, '', $old_message_content);
                }
            }

            $old_message_content = trim($old_message_content);

            $update_message = str_replace( $old_message_content, $content, $old_message );

            $message = $wpdb->get_row($wpdb->prepare(
                "SELECT * 
                FROM `" . bm_get_table('messages') . "` 
                WHERE `thread_id` = %d 
                AND `id` = %d 
                AND `sender_id` = %d"
                , $thread_id, $message_id, $user_id)
            );

            if( ! $message ) {
                return new WP_Error(
                    'rest_forbidden',
                    __( 'Message not found', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $args = array(
                'sender_id'   => $user_id,
                'thread_id'   => $thread_id,
                'content'     => $update_message,
                'message_id'  => $message_id,
                'return'      => 'message_id',
                'send_push'   => false,
                'mobile_push' => true,
                'date_sent'   => bp_core_current_time()
            );

            Better_Messages()->functions->record_message_edit_history( $message_id, $old_message_content );

            Better_Messages()->functions->update_message( $args );

            return Better_Messages_Rest_Api()->get_messages( $thread_id, [ $message_id ] );
        }

        public function mute_thread(WP_REST_Request $request) {
            global $wpdb;

            $thread_id  = intval($request->get_param('id'));
            $user_id   = Better_Messages()->functions->get_current_user_id();

            $current_value = $wpdb->get_var($wpdb->prepare(
                "SELECT is_muted 
                 FROM " . bm_get_table('recipients') . "
                 WHERE `user_id` = %d
                 AND `thread_id` = %d
            ", $user_id, $thread_id ));

            if( is_null( $current_value ) ) return false;

            if( (int) $current_value === 0 ){
                $time = Better_Messages()->functions->get_microtime();

                $wpdb->get_var($wpdb->prepare(
                    "UPDATE " . bm_get_table('recipients') . "
                SET   `is_muted` = 1, `last_update` = %d
                WHERE `user_id` = %d
                AND   `thread_id` = %d
                ", $time, $user_id, $thread_id ));

                do_action( 'better_messages_thread_self_update', $thread_id, $user_id );
                do_action( 'better_messages_thread_updated', $thread_id, $user_id );
            }


            return true;
        }

        public function unmute_thread(WP_REST_Request $request) {
            global $wpdb;

            $thread_id  = intval($request->get_param('id'));

            $user_id   = Better_Messages()->functions->get_current_user_id();
            $time      = Better_Messages()->functions->get_microtime();

            $current_value = $wpdb->get_var($wpdb->prepare(
                "SELECT is_muted 
                 FROM " . bm_get_table('recipients') . "
                 WHERE `user_id` = %d
                 AND `thread_id` = %d
            ", $user_id, $thread_id ));

            if( is_null( $current_value ) ) return false;

            if( (int) $current_value === 1 ){
                $wpdb->get_var($wpdb->prepare(
                "UPDATE " . bm_get_table('recipients') . "
                SET   `is_muted` = 0, `last_update` = %d
                WHERE `user_id` = %d
                AND   `thread_id` = %d
                ", $time, $user_id, $thread_id ));

                do_action( 'better_messages_thread_self_update', $thread_id, $user_id );
                do_action( 'better_messages_thread_updated', $thread_id, $user_id );
            }

            return true;
        }

        public function get_user_suggestions(){
            $user_ids = apply_filters('better_messages_predefined_suggestions_user_ids', []);

            if( ! is_array( $user_ids ) || count( $user_ids ) === 0 ){
                $total_to_get = 12;
                $friends = Better_Messages()->functions->get_friends_sorted( Better_Messages()->functions->get_current_user_id(), $total_to_get );

                $user_ids = [];

                if( count( $friends ) > 0 ) {
                    foreach ( $friends as $user_id => $time ){
                        $user_ids[] = $user_id;
                        $total_to_get--;
                    }
                }

                $get_all_users = $total_to_get > 0;

                if( Better_Messages()->functions->is_only_friends_mode() ){
                    $get_all_users = false;
                }

                if( current_user_can('manage_options') ){
                    $get_all_users = true;
                }

                if( $get_all_users ) {
                    if ( Better_Messages()->settings['searchAllUsers'] === '1' ) {
                        $other_users = Better_Messages()->functions->get_users_sorted( Better_Messages()->functions->get_current_user_id(), array_keys($friends), $total_to_get );

                        if (count($other_users) > 0) {
                            foreach ($other_users as $user_id => $time) {
                                $user_ids[] = $user_id;
                            }
                        }
                    }
                }
            }

            return $user_ids;

        }

        public function get_suggestions( WP_REST_Request $request ){
            global $wpdb;

            $search     = sanitize_text_field($request->get_param('search'));
            $thread_id  = intval( $request->get_param('threadId') );
            $current_user_id = Better_Messages()->functions->get_current_user_id();

            $exclude_sql = '';
            $exclude = [ $current_user_id ];

            if( $thread_id > 0 ){
                $exclude = array_unique(array_merge($exclude, array_map('intval', array_keys(Better_Messages()->functions->get_recipients( $thread_id )))));

                if( count( $exclude ) > 0 ){
                    $exclude_sql = " AND `ID` NOT IN (" . implode(',', $exclude) . ") ";
                }
            }

            if( Better_Messages()->settings['disableUsersSearch'] === '1' ){
                $sql = $wpdb->prepare("
                    SELECT ID 
                    FROM `{$wpdb->users}`
                    WHERE `user_login` LIKE %s
                    OR `user_nicename` LIKE %s
                    OR `display_name`  LIKE %s 
                    {$exclude_sql}
                    LIMIT 0,1", $search, $search, $search);

                $user_id = $wpdb->get_var($sql);

                if( $user_id ) {
                    return [Better_Messages()->functions->rest_user_item($user_id)];
                } else {
                    return [];
                }
            }

            $suggestions = Better_Messages_Search()->get_users_results( $search, $current_user_id, $exclude );

            $users = [];

            if ( count( $suggestions ) > 0 ) {
                foreach ( $suggestions as $suggestion ) {
                    $item = Better_Messages()->functions->rest_user_item( $suggestion );
                    $users[] = $item;
                }
            }

            return $users;
        }

        public function get_messages( $thread_id = null, $message_ids = [], $added_users = [], $count = 80, $apply_filters = true ){
            global $wpdb;

            $current_user_id = Better_Messages()->functions->get_current_user_id();

            $return = [
                'users' => [],
                'messages' => []
            ];

            $mode = 'standard';

            if( is_int( $message_ids ) ) {
                $mode = 'changed_since';

                if( is_int( $thread_id ) ){
                    $mode = 'from_message';
                }
            }

            if( $mode === 'from_message' ){
                $messages = Better_Messages()->functions->get_messages( $thread_id, $message_ids, 'from_message', $count );
            } else if( $mode === 'changed_since' ) {
                $sql = $wpdb->prepare("SELECT messages.*
                FROM " . bm_get_table('messages') . " messages
                INNER JOIN " . bm_get_table('recipients') . " recipients
                    ON messages.`thread_id` = recipients.`thread_id`
                    AND recipients.user_id = %d
                LEFT JOIN " . bm_get_table('meta') . " messagesmeta ON
                ( messagesmeta.`bm_message_id` = messages.`id`  
                AND messagesmeta.meta_key = 'bm_last_update' )
                WHERE messagesmeta.meta_value > %d", $current_user_id, $message_ids);

                $messages = $wpdb->get_results($sql);

            } else {
                $message_ids = array_map('intval', $message_ids);

                if( !! $thread_id && count( $message_ids ) > 0 ) {
                    $query = $wpdb->prepare( "
                        SELECT id, thread_id, sender_id, message, date_sent
                        FROM  " . bm_get_table('messages') . "
                        WHERE `thread_id` = %d
                        AND `id` IN (" . implode(',', $message_ids) . ")
                        ORDER BY `date_sent` DESC
                        ", $thread_id );

                    $messages = $wpdb->get_results( $query );
                } elseif ( ! $thread_id && count( $message_ids ) > 0 ) {
                    $query = $wpdb->prepare( "
                        SELECT id, thread_id, sender_id, message, date_sent
                        FROM  " . bm_get_table('messages') . "
                        WHERE `id` IN (" . implode(',', $message_ids) . ")
                        ORDER BY `date_sent` DESC
                    ");

                    $messages = $wpdb->get_results( $query );
                } else {
                    $messages = Better_Messages()->functions->get_messages( $thread_id, false, 'last_messages', $count );
                }
            }

            if( count ( $messages ) === 0 ) {
                return $return;
            }

            $user_ids   = [];
            $thread_ids = [];

            foreach( $messages as $key => $message ){
                $messages[ $key ]->message_id = (int) $message->id;
                unset($messages[ $key ]->id);
                $user_id        = (int) $message->sender_id;
                $_thread_id     = (int) $message->thread_id;
                $user_ids[]     = $user_id;
                $thread_ids[]   = $_thread_id;
                $last_update    = (float) Better_Messages()->functions->get_message_meta( $message->message_id, 'bm_last_update', true );
                $created_time   = (float) Better_Messages()->functions->get_message_meta( $message->message_id, 'bm_created_time', true );

                Better_Messages()->functions->check_created_time( $message->message_id, $message->date_sent, $created_time );


                $messages[ $key ]->sender_id  = $user_id;
                $messages[ $key ]->thread_id  = $_thread_id;

                $meta = [];
                $last_edit_time = Better_Messages()->functions->get_message_last_edit( $message->message_id );

                if( $last_edit_time ){
                    $meta['lastEdit'] = $last_edit_time;
                }


                $meta = apply_filters('better_messages_rest_message_meta', $meta, (int) $message->message_id, (int) $message->thread_id, $message->message );
                $messages[ $key ]->meta       = $meta;

                if( $apply_filters ) {
                    $messages[$key]->favorited = (Better_Messages()->functions->is_message_starred($message->message_id, $current_user_id)) ? 1 : 0;
                    //$messages[$key]->onsite    = Better_Messages()->functions->format_message($message->message, (int) $message->message_id, 'site', $current_user_id);
                    $messages[$key]->message   = Better_Messages()->functions->format_message($message->message, (int) $message->message_id, 'stack', $current_user_id);
                } else {
                    $messages[$key]->favorited = 0;
                }

                $messages[ $key ]->lastUpdate = $last_update;
                $messages[ $key ]->createdAt  = $created_time;
                $messages[ $key ]->tmpId      = Better_Messages()->functions->get_message_meta( $message->message_id, 'bm_tmp_id', true );
            }

            if( is_null($thread_id) ) {
                $thread_ids = array_unique($thread_ids);
                $result = $this->get_threads( $thread_ids, false );
                $return['threads'] = $result['threads'];
                $return['users']   = $result['users'];
            } else {
                $user_ids = array_unique( $user_ids );

                foreach ( $user_ids as $user_id ){
                    if ( in_array($user_id, $added_users ) ) continue;

                    $return['users'][] = Better_Messages()->functions->rest_user_item( $user_id, $apply_filters );

                    $added_users[] = $user_id;
                }
            }

            $return['messages'] = $messages;

            return $return;
        }

        public function get_changed_threads( $since, $user_id ){
            global $wpdb;

            return array_map( 'intval', $wpdb->get_col( $wpdb->prepare(
                "SELECT thread_id
            FROM " . bm_get_table('recipients') . "
            WHERE `user_id` = %d
            AND `last_update` > %d
            ", $user_id, $since ) ) );
        }

        public function get_changed_users( $since ){
            global $wpdb;

            $user_id = Better_Messages()->functions->get_current_user_id();

            return array_map( 'intval', $wpdb->get_col(
                $wpdb->prepare(" SELECT ID
                FROM `" . bm_get_table('users') . "`
                WHERE `last_changed` > %d
                AND `ID` != %d
                ", $since, $user_id ) ) );
        }

        public function check_missing_threads( $user_id, $thread_ids = [] ){
            global $wpdb;

            $thread_ids = array_map( 'intval', $thread_ids );
            if( count( $thread_ids ) === 0 ) return [];

            $sql =  $wpdb->prepare(
                "SELECT threads.id
            FROM " . bm_get_table('threads') . " threads
            LEFT JOIN " . bm_get_table('recipients') . " recipients
            ON threads.id = recipients.thread_id
            WHERE threads.id IN (" . implode(',', $thread_ids) . ")
            AND ( ( recipients.`user_id` = %d AND recipients.`is_deleted` = 0 ) OR threads.type = 'chat-room' )
            ", $user_id );

            $existing = array_map( 'intval', $wpdb->get_col( $sql ) );

            return array_values(array_diff( $thread_ids, $existing ));
        }

        public function check_unloaded_threads( $user_id, $thread_ids = [] ){
            global $wpdb;

            $thread_ids = array_map( 'intval', $thread_ids );

            $exclude_sql = '';
            if( count( $thread_ids ) > 0 ) $exclude_sql = "`threads`.`id` NOT IN(" . implode(',', $thread_ids ) . ") AND ";

            /** Find all unloaded unread */
            $sql = $wpdb->prepare("
                SELECT
                `threads`.`id`    	        as `thread_id`,
                MAX(`messages`.`id`)        as `message_id`,
                MAX(`messages`.`date_sent`) as `date_sent`
                FROM " . bm_get_table('threads') . " threads
                INNER JOIN " . bm_get_table('recipients') . " recipients
                    ON threads.`id` = recipients.`thread_id`
                INNER JOIN " . bm_get_table('messages') . " messages 
                    ON recipients.`thread_id` = messages.`thread_id`
                LEFT JOIN " . bm_get_table('threadsmeta') . " threadsmeta ON
                    ( threadsmeta.`bm_thread_id` = messages.`thread_id`
                AND threadsmeta.meta_key = 'exclude_from_threads_list' )
                WHERE
                    {$exclude_sql}
                    recipients.`user_id` = %d 
                    AND recipients.`is_deleted` = 0
                    AND recipients.`unread_count` > 0
                    AND `threadsmeta`.`meta_value` IS NULL
                    " . Better_Messages()->functions->threads_order_sql() . "
                    ", $user_id);

            $results = $wpdb->get_results( $sql, ARRAY_A );

            $unloaded_unread_count = count( $results );

            if( $unloaded_unread_count < 20 ){
                $toLoad             = 20 - $unloaded_unread_count;
                $unread_thread_ids = array_map( 'intval', array_column( $results, 'thread_id' ));

                $exclude_sql = '';
                if( count( $unread_thread_ids ) > 0 ) {
                    $exclude_sql = "`threads`.`id` NOT IN(" . implode(',', $unread_thread_ids ) . ") AND ";
                }

                $sql = $wpdb->prepare("
                    SELECT
                    `threads`.`id`    	        as `thread_id`,
                    MAX(`messages`.`id`)        as `message_id`,
                    MAX(`messages`.`date_sent`) as `date_sent`
                    FROM " . bm_get_table('threads') . " threads
                    INNER JOIN " . bm_get_table('recipients') . " recipients
                        ON threads.`id` = recipients.`thread_id`
                    INNER JOIN " . bm_get_table('messages') . " messages 
                        ON recipients.`thread_id` = messages.`thread_id`
                    LEFT JOIN " . bm_get_table('threadsmeta') . " threadsmeta ON
                        ( threadsmeta.`bm_thread_id` = messages.`thread_id`
                    AND threadsmeta.meta_key = 'exclude_from_threads_list' )
                    WHERE
                        {$exclude_sql}
                        recipients.`user_id` = %d 
                        AND recipients.`is_deleted` = 0
                        AND `threadsmeta`.`meta_value` IS NULL
                    " . Better_Messages()->functions->threads_order_sql() . "
                        LIMIT 0, %d
                        ", $user_id, $toLoad);

                $results = array_merge( $results, $wpdb->get_results( $sql, ARRAY_A ) );
            }


            $results = array_column( $results, 'thread_id' );
            sort( $results );

            $unloadedThreads = [];
            foreach( $results as $thread_id ){
                if( ! in_array( $thread_id, $thread_ids ) ){
                    $unloadedThreads[] = (string) $thread_id;
                }
            }

            return $unloadedThreads;
        }

        public function get_deleted_messages( $since ){
            global $wpdb;

            return array_map( 'intval', $wpdb->get_col( $wpdb->prepare(
                "SELECT messagesmeta.bm_message_id
            FROM " . bm_get_table('messages') . " messages
            RIGHT JOIN " . bm_get_table('meta') . " messagesmeta ON
                `messagesmeta`.`bm_message_id` = `messages`.`id` 
            WHERE messagesmeta.meta_value > %d
            AND messagesmeta.meta_key = 'bm_deleted_time'
            AND `messages`.`id` IS NULL", $since ) ) );
        }

        public function get_erased_threads( $since ){
            global $wpdb;

            return array_map( 'intval', $wpdb->get_col( $wpdb->prepare(
                "SELECT threadsmeta.bm_thread_id, threadsmeta.meta_value
                FROM " . bm_get_table('threads') . " threads
                RIGHT JOIN " . bm_get_table('threadsmeta') . " threadsmeta ON
                    `threadsmeta`.`bm_thread_id` = `threads`.`id`
                WHERE threadsmeta.meta_value > %d
                AND threadsmeta.meta_key = 'bm_last_update'
                AND `threads`.`id` IS NULL", $since ) ) );
        }

        public function load_more( WP_REST_Request $request ){
            global $wpdb;

            $thread_id   = intval($request->get_param('id'));
            $message_ids = array_map('intval', $request->get_param('loaded'));
            $to          = intval($request->get_param('to'));
            $mode        = sanitize_text_field($request->get_param('mode'));

            $missed_messages = Better_Messages()->functions->get_missed_message_ids( $thread_id, $message_ids );

            $return = [
                'messages' => [],
                'users' => []
            ];

            if( count( $missed_messages ) > 0 ){
                $message_ids = array_unique( array_merge( $message_ids, $missed_messages ) );
                $get_missed_messages = $this->get_messages( $thread_id, $missed_messages );
                $return['messages'] = array_merge($return['messages'], $get_missed_messages['messages']);
                $return['users']    = array_merge($return['users'], $get_missed_messages['users']);
            }

            $lazyLoadCount = 80;

            $from = 0;
            if( count( $message_ids ) > 0 ){
                $from = min( $message_ids );
            }

            if( $mode === 'until' ){
                $sql = $wpdb->prepare("
                    SELECT MIN(id)
                    FROM `" . bm_get_table('messages') . "` 
                    WHERE `thread_id` = %d 
                    AND `id` <= %d
                    ORDER BY `id` ASC
                LIMIT 0, 20", $thread_id, $to );

                $first = (int) $wpdb->get_var( $sql );

                if( $first ) {
                    $sql = $wpdb->prepare("
                    SELECT id
                        FROM `" . bm_get_table('messages') . "` 
                        WHERE `thread_id` = %d 
                        AND `id` >= %d
                    ORDER BY `id` DESC", $thread_id, $first);

                    $from = array_map('intval', $wpdb->get_col($sql));
                } else {
                    $from = [];
                }
            } else if( $from > 0 && $to > 0 ){
                $sql = $wpdb->prepare("SELECT id
                FROM `" . bm_get_table('messages') . "` 
                WHERE `thread_id` = %d 
                AND `id` < %d
                ORDER BY `id` DESC
                LIMIT %d, %d", $thread_id, $from, 0, $lazyLoadCount );

                $from = array_map('intval', $wpdb->get_col( $sql ));
            }

            if( is_array( $from ) && count( $from ) === 0 ){
                return $return;
            }

            $get_messages = $this->get_messages( $thread_id, $from, array_column($return['users'], 'user_id') );

            if( ! empty($get_messages['messages']) ) $return['messages'] = array_merge($return['messages'], $get_messages['messages']);
            if( ! empty($get_messages['users']) )    $return['users']    = array_merge($return['users'], $get_messages['users']);

            return $return;
        }

        public function search_participants( WP_REST_Request $request ){
            global $wpdb;

            $thread_id   = intval($request->get_param('id'));
            $search      = '%' . sanitize_text_field( $request->get_param('search') ) . '%';

            $sql = $wpdb->prepare( "SELECT user_id
                FROM `" . bm_get_table('recipients') . "` `recipients`
            RIGHT JOIN `" . bm_get_table('users') . "` `user_index`
                ON `recipients`.`user_id` = `user_index`.`ID`
            WHERE `recipients`.`thread_id` = %d
            AND ( 
                `user_nicename` LIKE %s
                OR `display_name` LIKE %s
                OR `first_name` LIKE %s
                OR `last_name` LIKE %s
                OR `nickname` LIKE %s
            )
            ORDER BY `user_index`.`last_activity` DESC", $thread_id, $search, $search, $search, $search, $search );

            $results = $wpdb->get_col( $sql );

            $users = [];

            if( count( $results ) > 0 ){
                foreach( $results as $user_id ){
                    $users[] = Better_Messages()->functions->rest_user_item( $user_id );
                }
            }

            return $users;
        }

        public function get_thread( WP_REST_Request $request ) {
            global $wpdb;

            $thread_id   = intval($request->get_param('id'));
            // Loaded messages IDs
            $message_ids = (array) $request->get_param('messages');

            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );

            if( $thread_type === 'chat-room' ){
                $chat_id = Better_Messages_Chats()->get_chat_thread_id( $thread_id );
                Better_Messages_Chats()->sync_auto_add_users( $chat_id );
            }

            Better_Messages()->functions->messages_mark_thread_read( $thread_id, Better_Messages()->functions->get_current_user_id() );

            $return     = $this->get_threads( [ $thread_id ], false );

            if( Better_Messages()->settings['mechanism'] === 'ajax' ) {
                $return['totalUnread'] = Better_Messages()->functions->get_total_threads_for_user( Better_Messages()->functions->get_current_user_id(), 'unread' );
            }

            $added_user_ids     = array_column($return['users'], 'user_id');

            $get_messages       = $this->get_messages( $thread_id, [], $added_user_ids );

            $return['messages'] = $get_messages['messages'];
            $return['users']    = array_merge($return['users'], $get_messages['users']);

            if( count( $message_ids ) > 0 ){
                $sql = $wpdb->prepare("SELECT id
                FROM " . bm_get_table('messages') . " messages
                WHERE thread_id = %d
                AND id IN(" . implode( ",", array_map( 'intval', $message_ids ) ) . ")", $thread_id );

                $existing_messages = $wpdb->get_col($sql);
                $existing_messages = array_map( 'intval', $existing_messages );

                $deleted_messages = [];

                foreach( $message_ids as $message_id ){
                    if( ! in_array( $message_id, $existing_messages ) ){
                        $deleted_messages[] = $message_id;
                    }
                }

                if( count( $deleted_messages ) > 0 ){
                    $return['deleted_messages'] = $deleted_messages;
                }

                $missed_messages = Better_Messages()->functions->get_missed_message_ids( $thread_id, $message_ids );

                if( count( $missed_messages ) > 0 && count( $return['messages'] ) > 0 ){
                    foreach ( $return['messages'] as $message ){
                        $match = array_search( $message->message_id, $missed_messages );

                        if( $match !== false ){
                            unset( $missed_messages[ $match ] );
                        }
                    }
                }

                if( count( $missed_messages ) > 0 ){
                    $get_missed_messages = $this->get_messages( $thread_id, $missed_messages, array_column($return['users'], 'user_id') );
                    $return['messages'] = array_merge($return['messages'], $get_missed_messages['messages']);
                    $return['users']    = array_merge($return['users'], $get_missed_messages['users']);
                }
            }

            return $return;
        }

        public function get_threads( $thread_ids = [], $fetch_messages = true, $fetch_users = true, $personal_data = true, $cache = true, $user_id = false ){
            set_time_limit(0);

            $excluded = [];

            if( is_a($thread_ids, 'WP_REST_Request' ) ){
                $request = $thread_ids;
                $excluded = (array) $request->get_param('exclude');
                $thread_ids = [];
            }

            global $wpdb;

            $current_user_id = $user_id ? : Better_Messages()->functions->get_current_user_id();
            $server_time     = Better_Messages()->functions->get_microtime();

            $excluded_sql = '';
            if( count( $excluded ) > 0 ){
                $excluded_sql = " AND threads.id NOT IN(" . implode(',', array_map('intval', $excluded)) . ") ";
            }

            $accessChecked = true;

            $sql = $wpdb->prepare("
                SELECT
                `threads`.`id`    	        as `thread_id`,
                `threads`.`type`    	    as `type`,
                `threads`.`subject`    	    as `subject`,
                `recipients`.`unread_count` as `unread_count`,
                `recipients`.`is_muted`     as `is_muted`,
                `recipients`.`is_pinned`    as `is_pinned`,
                `recipients`.`last_update`  as `last_update`,
                MAX(`messages`.`id`)        as `last_message`,
                MAX(`messages`.`date_sent`) as `date_sent`
                FROM " . bm_get_table('threads') . " threads
                INNER JOIN " . bm_get_table('recipients') . " recipients
                    ON threads.`id` = recipients.`thread_id`
                INNER JOIN " . bm_get_table('messages') . " messages 
                    ON recipients.`thread_id` = messages.`thread_id`
                LEFT JOIN " . bm_get_table('threadsmeta') . " threadsmeta ON
                    ( threadsmeta.`bm_thread_id` = messages.`thread_id`
                AND threadsmeta.meta_key = 'exclude_from_threads_list' )
                WHERE
                    recipients.`user_id` = %d 
                    AND recipients.`is_deleted` = 0
                    AND `threadsmeta`.`meta_value` IS NULL
                    {$excluded_sql}
                    " . Better_Messages()->functions->threads_order_sql() . "
                    LIMIT 0, 20
                    ", $current_user_id);

            if( count($thread_ids) > 0 ) {
                $sql = $wpdb->prepare("
                SELECT
                `threads`.`id`    	        as `thread_id`,
                `threads`.`type`    	    as `type`,
                `threads`.`subject`    	    as `subject`,
                `recipients`.`unread_count` as `unread_count`,
                `recipients`.`is_muted`     as `is_muted`,
                `recipients`.`is_pinned`    as `is_pinned`,
                `recipients`.`last_update`  as `last_update`,
                `recipients`.`is_deleted`   as `is_deleted`,
                MAX(`messages`.`id`)        as `last_message`,
                MAX(`messages`.`date_sent`) as `date_sent`
                FROM " . bm_get_table('threads') . " threads
                LEFT JOIN " . bm_get_table('recipients') . " recipients
                    ON threads.`id` = recipients.`thread_id`
                    AND recipients.user_id = %d
                LEFT JOIN " . bm_get_table('messages') . " messages 
                    ON recipients.`thread_id` = messages.`thread_id`
                LEFT JOIN " . bm_get_table('threadsmeta') . " threadsmeta ON
                    ( threadsmeta.`bm_thread_id` = messages.`thread_id`
                    AND threadsmeta.meta_key = 'exclude_from_threads_list' )
                WHERE 
                    `threads`.`id` IN (" . implode(',', array_map( 'intval', $thread_ids) ) . ")
                    " . Better_Messages()->functions->threads_order_sql() . "
                ", $current_user_id);

                $accessChecked = false;

                if( ! $personal_data ) {
                    $sql = "
                    SELECT
                    `threads`.`id`    	        as `thread_id`,
                    `threads`.`type`    	    as `type`,
                    `threads`.`subject`    	    as `subject`,
                    MAX(`messages`.`id`)        as `last_message`,
                    MAX(`messages`.`date_sent`) as `date_sent`
                    FROM " . bm_get_table('threads') . " threads
                    LEFT JOIN " . bm_get_table('messages') . " messages 
                        ON threads.`id` = messages.`thread_id`
                    WHERE  `threads`.`id` IN (" . implode(',', array_map('intval', $thread_ids)) . ")
                    GROUP BY `threads`.`id`
                    ORDER BY `messages`.`date_sent` DESC";
                }
            }

            $get_threads = $wpdb->get_results( $sql );

            $users = [];

            $threads  = [];
            $messages = [];
            $user_ids = [ $current_user_id ];
            $added_users = [];

            foreach ( $get_threads as $thread ){
                /**
                 * Cache thread so child functions do not access database again
                 */
                wp_cache_set('thread_' . $thread->thread_id, (object) [
                    'id'      => $thread->thread_id,
                    'subject' => $thread->subject,
                    'type'    => $thread->type
                ], 'bm_messages');

                $admin_access = false;

                if( $personal_data && ! $accessChecked ){
                    $has_access = Better_Messages()->functions->check_access( $thread->thread_id, $current_user_id );

                    if( ! $has_access && current_user_can('manage_options') ){
                        $has_access   = true;
                        $admin_access = true;
                    }

                    if( ! $has_access ) continue;
                }

                $is_participant = false;
                $recipients = Better_Messages()->functions->get_recipients( $thread->thread_id, $cache );

                if( isset($recipients[$current_user_id]) ){
                    unset($recipients[$current_user_id]);
                    $is_participant = true;
                }

                $_all_user_ids = array_map( 'intval', array_keys($recipients) );
                foreach ( array_slice($_all_user_ids, 0, 10) as $user_id ){ $user_ids[] = $user_id; }

                if( $is_participant ){
                    $_all_user_ids[] = intval($current_user_id);
                }

                $thread_type = Better_Messages()->functions->get_thread_type( $thread->thread_id );

                $title    = Better_Messages()->functions->get_thread_title( $thread->thread_id );
                $image    = Better_Messages()->functions->get_thread_image( $thread->thread_id );
                $url      = Better_Messages()->functions->get_thread_url( $thread->thread_id );

                $delete_allowed = Better_Messages()->settings['restrictThreadsDeleting'] === '0';

                if( current_user_can('manage_options') ) {
                    $delete_allowed = true;
                }

                if( $thread_type !== 'thread' ){
                    $delete_allowed = false;
                }

                global $bp_better_messages_restrict_send_message;
                $bp_better_messages_restrict_send_message = [];
                $can_reply = Better_Messages()->functions->can_send_message_filter( Better_Messages()->functions->check_access( $thread->thread_id, $current_user_id ), $current_user_id, $thread->thread_id );


                $thread_id  = (int) $thread->thread_id;
                $is_hidden  = ( Better_Messages()->functions->get_thread_meta($thread_id, 'exclude_from_threads_list') === '1' );
                $is_deleted = ( isset( $thread->is_deleted ) ? ($thread->is_deleted === '1') : false );

                $thread_item =  [
                    'thread_id'          => $thread_id,
                    'lastMessage'        => (int) $thread->last_message,
                    'isHidden'           => (int) $is_hidden,
                    'isDeleted'          => (int) $is_deleted,
                    'type'               => $thread_type,
                    'title'              => html_entity_decode($title),
                    'subject'            => (string) html_entity_decode($thread->subject),
                    'image'              => $image,
                    'lastTime'           => (string) $thread->date_sent,
                    'participants'       => array_map('intval', array_values($_all_user_ids)),
                    'participantsCount'  => (int) count( $_all_user_ids ),
                    //'totalMessages'      => (int) Better_Messages()->functions->get_thread_message_count( $thread->thread_id ),
                    'url'                => $url,
                    'meta'              => [
                        'allowInvite' => Better_Messages()->functions->get_thread_meta($thread_id, 'allow_invite') === 'yes'
                    ]
                ];

                if( $admin_access ){
                    $thread_item['adminAccess'] = true;
                }

                if( $personal_data ){
                    $unread = (int) $thread->unread_count;

                    $thread_item['isPinned'] = ( Better_Messages()->settings['pinnedThreads'] == '1' ) ? (int) $thread->is_pinned : 0;
                    $thread_item['isMuted'] = (bool) $thread->is_muted;
                    $thread_item['permissions'] = [
                        'isModerator'          => Better_Messages()->functions->is_thread_super_moderator( $current_user_id, $thread->thread_id ),
                        'deleteAllowed'        => $delete_allowed,
                        'canDeleteOwnMessages' => Better_Messages()->settings['allowDeleteMessages'] === '1',
                        'canDeleteAllMessages' => current_user_can('manage_options'),
                        'canEditOwnMessages'   => Better_Messages()->settings['allowEditMessages'] === '1',
                        'canFavorite'          => Better_Messages()->settings['disableFavoriteMessages'] !== '1',
                        'canMuteThread'        => ( Better_Messages()->settings['allowMuteThreads'] === '1' && ! $admin_access ),
                        'canEraseThread'       => Better_Messages()->functions->can_erase_thread( $current_user_id, $thread->thread_id ),
                        'canClearThread'       => Better_Messages()->functions->can_clear_thread( $current_user_id, $thread->thread_id ),
                        'canInvite'            => Better_Messages()->functions->can_invite( $current_user_id, $thread->thread_id ),
                        'canLeave'             => Better_Messages()->functions->can_leave( $current_user_id, $thread->thread_id ),
                        'canUpload'            => Better_Messages()->files->user_can_upload( $current_user_id, $thread->thread_id ),
                        'canVideoCall'         => false,
                        'canAudioCall'         => false,
                        'canMaximize'          => true,
                        'canPinMessages'       => apply_filters('better_messages_can_pin_messages', false, $current_user_id, $thread->thread_id ),
                        'canMinimize'          => Better_Messages()->functions->is_user_authorized() && Better_Messages()->settings['miniChatsEnable'] === '1',
                        'canReply'             => (bool) $can_reply,
                        'canReplyMsg'          => $bp_better_messages_restrict_send_message
                    ];

                    $mentions = [];

                    if( $unread > 0 ){
                        $first_unread = Better_Messages()->functions->get_message_by_order( $thread->thread_id, $thread->unread_count );
                        $mentions     = Better_Messages()->mentions->get_mentions_since( $current_user_id, $thread->thread_id, $first_unread );
                    }

                    $thread_item['mentions'] = $mentions;
                    $thread_item['unread'] = $unread;
                }

                $thread_item = apply_filters('better_messages_rest_thread_item', $thread_item, $thread_item['thread_id'], $thread_item['type'], $personal_data, $current_user_id );

                $threads[] = $thread_item;

                if( $fetch_messages ) {
                    $get_messages = $this->get_messages($thread->thread_id, [], $added_users, 20);

                    $messages = array_merge( $messages, $get_messages['messages'] );
                    $users    = array_merge( $users, $get_messages['users'] );
                    $added_users = array_merge( $added_users, array_column($users, 'user_id') );
                }
            }

            if( $fetch_users ) {
                $user_ids = array_unique($user_ids);

                foreach ($user_ids as $user_id) {
                    if (in_array($user_id, $added_users)) continue;

                    if( $user_id > 0 ){
                        $user = get_userdata($user_id);
                        if ( ! $user ) continue;
                    }

                    $users[] = Better_Messages()->functions->rest_user_item($user_id);

                    $added_users[] = $user_id;
                }
            }

            return [
                'threads'    => $threads,
                'users'      => $users,
                'messages'   => $messages,
                'serverTime' => $server_time
            ];
        }

        public function can_reply(WP_REST_Request $request) {
            if( ! $this->is_user_authorized( $request ) ){
                return false;
            }

            $user_id    = Better_Messages()->functions->get_current_user_id();

            $thread_id  = intval($request->get_param('id'));
            $has_access = Better_Messages()->functions->check_access( $thread_id, $user_id, 'reply' );

            if( ! $has_access ){
                $temp_id = sanitize_text_field( $request->get_param('tmpId') );
                do_action('better_messages_on_message_not_sent', $thread_id, $temp_id, [] );

                $thread = $this->get_threads([$thread_id]);

                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to reply into this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array(
                        'status' => rest_authorization_required_code(),
                        'update' => $thread
                    )
                );
            }

            $errors = [];

            $type = Better_Messages()->functions->get_thread_type( $thread_id );

            if( $type === 'group' ){
                if( class_exists('BP_Groups_Member') ) {
                    $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'group_id');

                    if (!!$group_id) {
                        if ( ! Better_Messages()->functions->can_send_message_filter( BP_Groups_Member::check_is_member($user_id, $group_id), $user_id, $thread_id ) ) {
                            $errors[] = _x( 'Sorry, you are not allowed to reply into this conversation', 'Rest API Error', 'bp-better-messages' );
                        }
                    }
                }

                if( class_exists('PeepSoGroupsPlugin') ) {
                    $group_id = Better_Messages()->functions->get_thread_meta($thread_id, 'peepso_group_id');

                    if ( !! $group_id ) {
                        $has_access  = Better_Messages_Peepso_Groups::instance()->user_has_access( $group_id, $user_id );

                        if ( ! Better_Messages()->functions->can_send_message_filter( $has_access, $user_id, $thread_id ) ) {
                            $errors[] = _x( 'Sorry, you are not allowed to reply into this conversation', 'Rest API Error', 'bp-better-messages' );
                        }
                    }
                }
            } else {
                if ( ! Better_Messages()->functions->can_send_message_filter( Better_Messages()->functions->check_access($thread_id, $user_id), $user_id, $thread_id ) ) {
                    global $bp_better_messages_restrict_send_message;

                    if( count( $bp_better_messages_restrict_send_message ) > 0 ){
                        $errors = array_merge($errors, array_values($bp_better_messages_restrict_send_message));
                    } else {
                        $errors[] = _x( 'Sorry, you are not allowed to reply into this conversation', 'Rest API Error', 'bp-better-messages' );
                    }

                }
            }

            if( count( $errors ) === 0 ) {
                return true;
            } else {
                $error = new WP_Error();

                $temp_id = sanitize_text_field( $request->get_param('tmpId') );
                do_action('better_messages_on_message_not_sent', $thread_id, $temp_id, $errors );

                $thread = $this->get_threads( [$thread_id] );

                foreach ( $errors as $_error ) {
                    $error->add(
                        'rest_forbidden',
                        $_error,
                        array('status' => rest_authorization_required_code())
                    );
                }

                $error->add_data(['update' => $thread]);

                return $error;
            }
        }

        public function check_thread_access(WP_REST_Request $request) {
            $this->is_user_authorized( $request );

            $user_id    = Better_Messages()->functions->get_current_user_id();
            $thread_id  = intval($request->get_param('id'));
            $thread     = Better_Messages()->functions->get_thread( $thread_id );

            if( ! $thread ){
                return new WP_Error(
                    'rest_thread_not_exists',
                    _x( 'Sorry, the conversation which you tried to access does not exists', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $has_access = current_user_can('manage_options');

            if( ! $has_access ) {
                $has_access = Better_Messages()->functions->check_access($thread_id, $user_id);
            }

            if( ! $has_access ){
                return new WP_Error(
                    'rest_thread_forbidden',
                    _x( 'Sorry, you are not allowed to access this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            return true;
        }

        public function ping(){
            return 'pong';
        }

    }
endif;

function Better_Messages_Rest_Api()
{
    return Better_Messages_Rest_Api::instance();
}
