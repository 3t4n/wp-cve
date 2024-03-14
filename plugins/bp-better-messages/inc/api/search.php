<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Search' ) ):

    class Better_Messages_Search
    {
        public static function instance()
        {
            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Search();
            }

            return $instance;
        }

        public function __construct(){
            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/search', array(
                'methods' => 'POST',
                'callback' => array( $this, 'search' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ) );
        }

        public function search( WP_REST_Request $request ){
            $search = sanitize_text_field($request->get_param('search'));
            $user_id = Better_Messages()->functions->get_current_user_id();

            $return = [];

            if( Better_Messages()->settings['disableUsersSearch'] !== '1' ) {
                $users = $this->get_users_results( $search, $user_id );

                if (count($users) > 0) {
                    $return['friends'] = [];
                    $return['users'] = [];
                    foreach ($users as $user) {
                        if (Better_Messages()->functions->is_friends($user_id, $user)) {
                            $return['friends'][] = Better_Messages()->functions->rest_user_item($user);
                        } else {
                            $return['users'][] = Better_Messages()->functions->rest_user_item($user);
                        }
                    }
                }
            }

            $messages = $this->get_messages_results( $search, $user_id );

            if( count( $messages ) > 0 ){
                $return['messages'] = $messages;

                $threads_ids = array_map( function( $item ) {
                    return $item['thread_id'];
                }, $messages );

                $updateData = Better_Messages_Rest_Api()->get_threads($threads_ids);

                $message_ids = array_map( function( $item ) {
                    return $item['message_id'];
                }, $messages );

                $messagesData = Better_Messages_Rest_Api()->get_messages(null, $message_ids, $updateData['users'] );

                $updateData['messages'] = array_merge( $updateData['messages'], $messagesData['messages'] );
                $updateData['users']    = $messagesData['users'];

                $return['updateData'] = $updateData;
            }

            if( count( $return ) ){
                foreach( $return as $key => $array ){
                    if( count( $array ) === 0 ) unset( $return[$key] );
                }
            }

            return apply_filters( 'better_messages_search_results', $return, $search, $user_id );
        }

        public function get_users_results( $search, $user_id, $excluded_ids = [] ){
            $all_users = [];

            global $wpdb;
            $only_friends = Better_Messages()->settings['searchAllUsers'] !== '1';

            if( Better_Messages()->functions->is_only_friends_mode() ){
                $only_friends = true;
            }

            if( Better_Messages()->functions->is_friends_active() ){
                $all_users = apply_filters( 'better_messages_search_friends', [], $search, $user_id );
            } else {
                $only_friends = false;
            }

            if( current_user_can('manage_options') ){
                $only_friends = false;
            }

            if( count($excluded_ids) > 0 ){
                foreach ( $excluded_ids as $key => $excluded_id ){
                    if( $user_id === $excluded_id ) unset( $excluded_ids[$key] );

                    $key = array_search($excluded_id, $all_users);
                    if( $key !== false ) unset( $all_users[$key] );
                }
            }

            if ( ! $only_friends ) {
                $exclude = '';

                if( count( $all_users ) > 0 ) {
                    $exclude = 'AND `ID` NOT IN (' . implode(',', $all_users) . ')';
                }

                if( count( $excluded_ids ) > 0 ){
                    $exclude .= " AND `ID` NOT IN (" . implode(',', $excluded_ids) . ") ";
                }

                $initial_sql = [];

                if( ! Better_Messages()->guests->guest_access_enabled() ){
                    $initial_sql[] = "AND `ID` NOT IN(SELECT DISTINCT(user_id) FROM `" . bm_get_table('roles')  . "` WHERE `role` = 'bm-guest' )";
                }

                $additional_sql_condition = apply_filters('better_messages_search_user_sql_condition', $initial_sql, array_map('intval', $all_users ), $search, $user_id );

                $search_sql = '%' . $search . '%';

                $sql = $wpdb->prepare("
                SELECT `ID`
                FROM `" . bm_get_table('users'). "` `users`
                WHERE ( `user_nicename` LIKE %s
                    OR `display_name` LIKE %s
                    OR `first_name` LIKE %s
                    OR `last_name` LIKE %s
                    OR `nickname` LIKE %s )
                    AND `ID` != %d
                    {$exclude}
                " . implode(' ', $additional_sql_condition ) . "
                ORDER BY `last_activity` DESC
                LIMIT 0, 10
                ", $search_sql, $search_sql, $search_sql, $search_sql, $search_sql, $user_id );

                $users = $wpdb->get_col($sql);

                if( count( $users ) > 0 ){
                    foreach ( $users as $user_id ) {
                        $all_users[] = intval($user_id);
                    }
                }
            }

            return apply_filters( 'better_messages_search_user_results', array_map('intval', $all_users ), $search, $user_id );
        }

        public function get_messages_results( $search, $user_id ){
            global $wpdb;

            $query = $wpdb->prepare( "
                SELECT " . bm_get_table('messages') . ".thread_id,
                COUNT(" . bm_get_table('messages') . ".thread_id) as count,
                " . bm_get_table('messages') . ".id as message_id
                FROM " . bm_get_table('messages') . "
                INNER JOIN " . bm_get_table('recipients') . "
                ON " . bm_get_table('recipients') . ".thread_id = " . bm_get_table('messages') . ".thread_id
                INNER JOIN " . bm_get_table('threads') . "
                ON " . bm_get_table('threads') . ".id = " . bm_get_table('messages') . ".thread_id
                WHERE
                " . bm_get_table('recipients') . ".is_deleted = 0 
                AND " . bm_get_table('recipients') . ".user_id = %d
                AND (" . bm_get_table('messages') . ".message LIKE %s OR " . bm_get_table('threads') . ".subject LIKE %s)
                GROUP BY " . bm_get_table('messages') . ".thread_id
                ORDER BY " . bm_get_table('messages') . ".id DESC
                LIMIT 0, 10
            ", $user_id, '%' . $search . '%', '%' . $search . '%' );

            $messages = $wpdb->get_results( $query, ARRAY_A );

            $messages = array_map( function( $item ) {
                $item['thread_id']  = intval( $item['thread_id'] );
                $item['message_id'] = intval( $item['message_id'] );
                $item['count']      = intval( $item['count'] );
                return $item; },
            $messages );

            return apply_filters( 'better_messages_search_messages_results', $messages, $search, $user_id );
        }

    }

    function Better_Messages_Search(){
        return Better_Messages_Search::instance();
    }

endif;
