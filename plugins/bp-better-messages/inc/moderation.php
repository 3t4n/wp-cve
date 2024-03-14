<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Moderation' ) ):

    class Better_Messages_Moderation
    {
        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Moderation();
            }

            return $instance;
        }

        public function __construct()
        {
            add_action('better_messages_cleaner_job', array( $this, 'clean_expired_bans') );
            add_filter('better_messages_can_send_message', array( $this, 'can_send_reply' ), 20, 3 );
            add_filter('better_messages_chat_user_can_join', array( $this, 'restrict_join' ), 10, 4 );
            add_action('rest_api_init',  array( $this, 'rest_api_init' ) );
            add_action('better_messages_clean_expired_ban', array( $this, 'clean_expired_ban'), 10, 2 );
        }

        public function restrict_join( $has_access, $user_id, $chat_id, $thread_id ){
            $restrictions = $this->is_user_restricted( $thread_id, $user_id );

            if( isset( $restrictions['ban'] ) ){
                $has_access = false;
            }

            return $has_access;
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/muteUser', array(
                'methods' => 'POST',
                'callback' => array( $this, 'mute_user_api' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/unmuteUser', array(
                'methods' => 'POST',
                'callback' => array( $this, 'unmute_user_api' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );


            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/banUser', array(
                'methods' => 'POST',
                'callback' => array( $this, 'ban_user_api' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/unbanUser', array(
                'methods' => 'POST',
                'callback' => array( $this, 'un_ban_user_api' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );
        }

        public function ban_user_api( WP_REST_Request $request ){
            $thread_id       = intval( $request->get_param('id') );
            $user_id         = intval( $request->get_param('user_id') );
            $duration        = intval( $request->get_param('duration') );
            $current_user_id = Better_Messages()->functions->get_current_user_id();

            $can_mute = Better_Messages()->functions->can_moderate_thread( $thread_id, $current_user_id );
            $is_participant = Better_Messages()->functions->is_thread_participant( $user_id, $thread_id, true );

            if( ! $can_mute || ! $is_participant ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $result = $this->restrict_user( 'ban', $user_id, $thread_id, $duration );

            if( $result ){
                Better_Messages()->functions->remove_participant_from_thread( $thread_id, $user_id );
                return Better_Messages()->api->get_threads( [ $thread_id ], false, false );
            }

            return false;
        }

        public function un_ban_user_api( WP_REST_Request $request ){
            $thread_id       = intval( $request->get_param('id') );
            $user_id         = intval( $request->get_param('user_id') );
            $current_user_id = Better_Messages()->functions->get_current_user_id();

            $can_mute = Better_Messages()->functions->can_moderate_thread( $thread_id, $current_user_id );

            if( ! $can_mute ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $result = $this->un_restrict_user( 'ban', $user_id, $thread_id );

            if( $result ){
                return Better_Messages()->api->get_threads( [ $thread_id ], false, false );
            }

            return false;
        }

        public function mute_user_api( WP_REST_Request $request ){
            $thread_id       = intval( $request->get_param('id') );
            $user_id         = intval( $request->get_param('user_id') );
            $duration        = intval( $request->get_param('duration') );
            $current_user_id = Better_Messages()->functions->get_current_user_id();

            $can_mute = Better_Messages()->functions->can_moderate_thread( $thread_id, $current_user_id );
            $is_participant = Better_Messages()->functions->is_thread_participant( $user_id, $thread_id, true );

            if( ! $can_mute || ! $is_participant ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $result = $this->restrict_user( 'mute', $user_id, $thread_id, $duration );

            if( $result ){
                return Better_Messages()->api->get_threads( [ $thread_id ], false, false );
            }

            return false;
        }

        public function unmute_user_api( WP_REST_Request $request ){
            $current_user_id = Better_Messages()->functions->get_current_user_id();
            $thread_id  = intval( $request->get_param('id') );
            $user_id    = intval( $request->get_param('user_id') );

            $can_mute = Better_Messages()->functions->can_moderate_thread( $thread_id, $current_user_id );
            $is_participant = Better_Messages()->functions->is_thread_participant( $user_id, $thread_id, true );

            if( ! $can_mute || ! $is_participant ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $result = $this->un_restrict_user( 'mute', $user_id, $thread_id );

            if( $result ){
                return Better_Messages()->api->get_threads( [ $thread_id ], false, false );
            }

            return false;
        }

        public function can_send_reply( $allowed, $user_id, $thread_id ){
            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );

            if( $thread_type === 'chat-room' ){
                $restrictions = $this->is_user_restricted( $thread_id, $user_id );

                if( isset( $restrictions['mute'] ) ){
                    $allowed = false;

                    $expiration = $restrictions['mute'];

                    $time = $this->format_time($expiration);

                    /**
                     * With this global variable you can add extra message which will replace editor field
                     */
                    global $bp_better_messages_restrict_send_message;
                    $bp_better_messages_restrict_send_message['bm_user_muted'] = sprintf(_x('You were muted in this conversation until %s', 'Message when user was muted in conversation', 'bp-better-messages'), $time);

                }
            }

            return $allowed;
        }

        public function format_time( $expiration ){
            return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime( $expiration ) );
        }

        private function user_thread_cache_key( int $thread_id, int $user_id ): string
        {
            return 'bm_restriction_' . $thread_id . '_' . $user_id;
        }

        private function thread_cache_key( int $thread_id ): string
        {
            return 'bm_restriction_' . $thread_id;
        }


        public function clean_expired_bans(){
            global $wpdb;

            $table = bm_get_table('moderation');

            $results = $wpdb->get_results("
            SELECT id, thread_id, user_id
            FROM  {$table} 
            WHERE `expiration` <= NOW()");

            if( ! empty( $results ) ) {
                foreach ($results as $result) {
                    $wpdb->query($wpdb->prepare("
                DELETE
                FROM  {$table} 
                WHERE `id` = %d", $result->id));

                    $this->cache_delete($result->thread_id, $result->user_id);
                    Better_Messages()->functions->thread_updated_for_user($result->thread_id, $result->user_id);
                }
            }
        }

        public function clean_expired_ban( $thread_id, $user_id ){
            global $wpdb;

            $table = bm_get_table('moderation');

            $results = $wpdb->get_results( $wpdb->prepare("
            SELECT id, thread_id, user_id
            FROM  {$table} 
            WHERE `thread_id` = %d
            AND `user_id` = %d
            AND `expiration` <= NOW()", $thread_id, $user_id) );

            if( ! empty( $results ) ) {
                foreach ($results as $result) {
                    $wpdb->query($wpdb->prepare("
                    DELETE
                    FROM  {$table} 
                    WHERE `id` = %d", $result->id));

                    $this->cache_delete($result->thread_id, $result->user_id);
                    Better_Messages()->functions->thread_updated_for_user($result->thread_id, $result->user_id);
                }
            }
        }

        public function get_restricted_users( int $thread_id ){
            $key = $this->thread_cache_key( $thread_id );

            $restricted_users = wp_cache_get( $key, 'bm_messages' );

            if( $restricted_users ){
                return $restricted_users;
            }

            global $wpdb;

            $table = bm_get_table('moderation');

            $query = $wpdb->prepare("
            SELECT user_id, type, CONVERT_TZ(expiration, @@session.time_zone, '+0:00') as expiration 
            FROM  {$table} 
            WHERE `thread_id` = %d
            AND `type` = 'ban' OR ( `type` = 'mute' AND `user_id` IN ( SELECT user_id FROM " . bm_get_table('recipients') . " WHERE thread_id = %d AND is_deleted = 0 ) )
            AND `expiration` > NOW()", $thread_id, $thread_id);

            $results = $wpdb->get_results( $query );

            $result = [];

            if( count( $results ) > 0 ){
                foreach( $results as $item ){
                    $type = $item->type;
                    if( ! isset( $result[$type] ) ) $result[$type] = [];
                    $result[$type][ $item->user_id ] = strtotime( $item->expiration );
                }
            }

            if( isset( $result['ban'] ) && isset( $result['mute'] ) ){
                foreach( $result['ban'] as $key => $value ){
                    if( isset( $result['mute'][$key] ) ) unset(  $result['mute'][$key] );
                }

                if( empty( $result['mute'] ) ) unset($result['mute']);
            }

            wp_cache_set( $key, $result, 'bm_messages' );

            return $result;
        }

        public function is_user_restricted( int $thread_id, int $user_id ){
            $key = $this->user_thread_cache_key( $thread_id, $user_id );

            $restrictions = wp_cache_get( $key, 'bm_messages' );

            if( $restrictions ){
                return $restrictions;
            }

            global $wpdb;

            $table = bm_get_table('moderation');
            $query = $wpdb->prepare("SELECT type, expiration FROM {$table} WHERE `thread_id` = %d AND `user_id` = %d AND `expiration` > NOW()", $thread_id, $user_id);

            $array = [];
            $results = $wpdb->get_results( $query );

            if( count( $results ) > 0 ){
                foreach ( $results as $result ){
                    $array[$result->type] = $result->expiration;
                }
            }

            wp_cache_set($key, $array, 'bm_messages');

            return $array;
        }

        public function restrict_user( string $type, int $user_id, int $thread_id, int $time = 1 ){
            global $wpdb;

            $admin_id = Better_Messages()->functions->get_current_user_id();
            if( $time < 1 ) $time = 1;

            $seconds = $time * 60;

            $table = bm_get_table('moderation');

            $query = $wpdb->prepare("INSERT INTO {$table} 
            (user_id, thread_id, type, expiration, admin_id)
            VALUES (%d, %d, %s, DATE_ADD(NOW(), INTERVAL %d SECOND), %d)
            ON DUPLICATE KEY 
            UPDATE expiration = DATE_ADD(NOW(), INTERVAL %d SECOND), admin_id = %d", $user_id, $thread_id, $type, $seconds, $admin_id, $seconds, $admin_id);

            $result = $wpdb->query( $query );

            $this->cache_delete( $thread_id, $user_id );

            if( $result ){
                Better_Messages()->functions->thread_updated_for_user( $thread_id, $user_id );

                if( ! wp_get_scheduled_event( 'better_messages_clean_expired_ban', [ $thread_id, $user_id ] ) ){
                    wp_schedule_single_event( time() + $seconds + 1, 'better_messages_clean_expired_ban', [ $thread_id, $user_id ] );
                }
            }

            return $result;
        }

        public function un_restrict_user( string $type, int $user_id, int $thread_id ){
            global $wpdb;
            $table = bm_get_table('moderation');
            $query = $wpdb->prepare("DELETE FROM {$table} WHERE `type` = %s AND `thread_id` = %d AND `user_id` = %d", $type, $thread_id, $user_id);
            $result = $wpdb->query( $query );

            $this->cache_delete( $thread_id, $user_id );

            if( $result ){
                Better_Messages()->functions->thread_updated_for_user( $thread_id, $user_id );

                if( wp_get_scheduled_event( 'better_messages_clean_expired_ban', [ $thread_id, $user_id ] ) ){
                    wp_clear_scheduled_hook( 'better_messages_clean_expired_ban', [ $thread_id, $user_id ] );
                }
            }

            return $result;
        }

        private function cache_delete( $thread_id, $user_id ){
            wp_cache_delete( $this->user_thread_cache_key( $thread_id, $user_id ), 'bm_messages' );
            wp_cache_delete( $this->thread_cache_key( $thread_id ), 'bm_messages' );
        }

    }

endif;

function Better_Messages_Moderation(){
    return Better_Messages_Moderation::instance();
}
