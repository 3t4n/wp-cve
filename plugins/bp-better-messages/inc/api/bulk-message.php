<?php
if ( !class_exists( 'Better_Messages_Rest_Api_Bulk_Message' ) ):

    class Better_Messages_Rest_Api_Bulk_Message
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Rest_Api_Bulk_Message();
            }

            return $instance;
        }

        public function __construct(){
            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/bulkMessages', array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_reports' ),
                'permission_callback' => array( $this, 'has_access' )
            ) );

            register_rest_route( 'better-messages/v1', '/bulkMessages/preview', array(
                'methods' => 'POST',
                'callback' => array( $this, 'preview' ),
                'permission_callback' => array( $this, 'has_access' )
            ) );

            register_rest_route( 'better-messages/v1', '/bulkMessages/start', array(
                'methods' => 'POST',
                'callback' => array( $this, 'create_report' ),
                'permission_callback' => array( $this, 'has_access' )
            ) );

            register_rest_route( 'better-messages/v1', '/bulkMessages/send', array(
                'methods' => 'POST',
                'callback' => array( $this, 'send' ),
                'permission_callback' => array( $this, 'has_access' )
            ) );

            register_rest_route( 'better-messages/v1', '/bulkMessages/changeReport', array(
                'methods' => 'POST',
                'callback' => array( $this, 'change_report' ),
                'permission_callback' => array( $this, 'has_access' )
            ) );

            register_rest_route( 'better-messages/v1', '/bulkMessages/deleteReport', array(
                'methods' => 'POST',
                'callback' => array( $this, 'delete_report' ),
                'permission_callback' => array( $this, 'has_access' )
            ) );


            add_filter( 'better_messages_can_send_message', array( $this, 'disabled_thread_reply' ), 10, 3);
        }


        public function get_existing_bulk_thread_id( $user_id ){
            global $wpdb;

            $thread_id = (int) $wpdb->get_var( $wpdb->prepare("
            SELECT bm_thread_id
            FROM " . bm_get_table('threadsmeta') . " `meta`
                RIGHT JOIN " . bm_get_table('threads') . " `threads`
            ON `meta`.bm_thread_id = `threads`.id
                WHERE `meta_key` = 'bulk_thread_user'
                AND `meta_value` = %d;
            ", $user_id ) );

            if( ! $thread_id ){
                $thread_id = (int) $wpdb->get_var( $wpdb->prepare("
                SELECT `postmeta`.`meta_value`
                FROM `{$wpdb->postmeta}` as `postmeta`
                    RIGHT JOIN `{$wpdb->posts}` as `posts`
                        ON `posts`.`ID` = `postmeta`.`post_id`
                    WHERE `posts`.`post_type` = 'bpbm-bulk-report'
                        AND `postmeta`.`meta_key` = 'thread_ids'
                        AND `postmeta`.`meta_value` IN (
                            SELECT `thread_id`
                            FROM " . bm_get_table('recipients') . "
                            WHERE `user_id` = %d
                        )
                LIMIT 0, 1
                ", $user_id ) );

                if( $thread_id ){
                    Better_Messages()->functions->update_thread_meta( $thread_id, 'bulk_thread_user', $user_id );
                }
            }

            return $thread_id;
        }

        public function disabled_thread_reply( $allowed, $user_id, $thread_id ){
            global $wpdb;

            $reports = $wpdb->get_col( $wpdb->prepare("
            SELECT `posts`.`ID`
            FROM `{$wpdb->postmeta}` as `postmeta`
            RIGHT JOIN `$wpdb->posts` as `posts`
            ON `posts`.`ID` = `postmeta`.`post_id`
            WHERE `posts`.`post_type` = 'bpbm-bulk-report'
            AND `postmeta`.`meta_key` = 'thread_ids'
            AND `postmeta`.`meta_value` = %d", $thread_id) );

            if( isset( $reports[0] ) ){
                $disableReply = get_post_meta($reports[0], 'disableReply', true);
                if($disableReply === '1') {
                    $allowed = false;
                    global $bp_better_messages_restrict_send_message;
                    $bp_better_messages_restrict_send_message['disable_bulk_replies'] = __('Admin disabled replies to this conversation', 'bp-better-messages');
                }
            }

            return $allowed;
        }

        public function delete_report( WP_REST_Request $request ){
            global $wpdb;
            $report_id = intval( $request->get_param( 'report_id' ) );

            $report = get_post($report_id);

            if( ! $report ) return false;

            $threads = get_post_meta($report->ID, 'thread_ids');
            $messages = get_post_meta($report->ID, 'message_ids');

            if( count( $messages ) > 0 ){
                foreach ( $messages as $message_id ) {
                    Better_Messages()->functions->delete_message( $message_id );
                }
            } else {
                foreach ($threads as $thread_id) {
                    BP_Better_Messages()->functions->erase_thread($thread_id);
                }
            }

            // DELETE REPORT
            wp_delete_post( $report->ID, true);

            return true;
        }

        public function change_report( WP_REST_Request $request ){
            $report_id = intval( $request->get_param( 'report_id' ) );
            $report    = get_post($report_id);

            if( ! $report ) return false;

            $key   = sanitize_text_field( $request->get_param( 'property' ) );
            $value = sanitize_text_field( $request->get_param( 'value' ) );

            return update_post_meta($report->ID, $key, $value);
        }

        public function has_access(){
            if( current_user_can('manage_options') ){
                return true;
            }

            return false;
        }

        public function send( WP_REST_Request $request ){
            $report_id = intval( $request->get_param( 'report_id' ) );
            $page = intval( $request->get_param( 'page' ) );
            $per_page = intval( $request->get_param('per_page') );

            $report = get_post($report_id);

            if( ! $report ) return false;

            $current_user_id = get_current_user_id();

            $selectors = $report->selectors;
            $message   = $report->message;

            $subject   = $selectors['subject'];

            $user_query = $this->get_user_query( $selectors, true, $page, $per_page );

            $thread_ids = [];

            if ( ! empty( $user_query->get_results() ) ) {
                if( $selectors['singleThread'] ) {
                    $user_ids = [];
                    foreach ( $user_query->get_results() as $user ) { $user_ids[] = $user; }

                    $args = array(
                        'subject'       => sanitize_text_field( $subject ),
                        'content'       => $message,
                        'error_type'    => 'wp_error',
                        'bulk_hide'     => $selectors['hideThread'] == '1',
                        'recipients'    => $user_ids
                    );

                    $thread_id = (int) Better_Messages()->functions->new_message( $args );
                    add_post_meta($report_id, 'thread_ids', $thread_id, false);

                    if( $selectors['hideThread'] == '1' ) {
                        Better_Messages()->functions->archive_thread( $current_user_id, $thread_id );
                    }

                    $thread_ids[] = $thread_id;
                } else {
                    foreach ( $user_query->get_results() as $user ) {
                        $args = array(
                            'subject'       => sanitize_text_field( $subject ),
                            'content'       => $message,
                            'return'        => 'both',
                            'error_type'    => 'wp_error',
                            'bulk_hide'     => $selectors['hideThread'] == '1',
                            'recipients'    => array($user)
                        );

                        $thread_id = null;

                        if( $selectors['useExistingThread'] && $selectors['useExistingThread'] === '1' ) {
                            $thread_id = $this->get_existing_bulk_thread_id($user);

                            if( $thread_id ){
                                $args['thread_id'] = $thread_id;
                                $args['return']    = 'message_id';
                            }
                        }

                        if( $thread_id ){
                            $message_id = (int) Better_Messages()->functions->new_message($args);
                        } else {
                            $result = Better_Messages()->functions->new_message($args);
                            $thread_id  = $result['thread_id'];
                            $message_id = $result['message_id'];
                        }

                        add_post_meta( $report_id, 'message_ids', $message_id, false );
                        add_post_meta($report_id,  'thread_ids', $thread_id, false);

                        if( $selectors['hideThread'] == '1' ) {
                            Better_Messages()->functions->archive_thread( $current_user_id, $thread_id );
                        }

                        $thread_ids[] = $thread_id;
                    }
                }
            }

            return $thread_ids;
        }

        public function create_report( WP_REST_Request $request ){
            $selectors  = $request->get_param( 'selectors' );
            $user_query = $this->get_user_query( $selectors );

            $content = Better_Messages()->functions->filter_message_content($request->get_param('message'));

            $users_count = (int) $user_query->total_users;

            if( empty( trim( $content ) )){
                return new WP_Error(
                    'rest_forbidden',
                    _x('Message is empty', 'Bulk Messages Page', 'bp-better-messages'),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            if( $users_count == 0 ){
                return new WP_Error(
                    'rest_forbidden',
                    _x('No users was selected', 'Bulk Messages Page', 'bp-better-messages'),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $report_id = wp_insert_post(array(
                'post_type' => 'bpbm-bulk-report',
                'meta_input' => array(
                    'selectors'         => $selectors,
                    'message'           => $content,
                    'subject'           => sanitize_text_field( $selectors[ 'subject' ] ),
                    'disableReply'      => (isset($selectors['disableReply'])) ? '1' : '0',
                    'useExistingThread' => (isset($selectors['useExistingThread'])) ? '1' : '0',
                    'hideThread'        => (isset($selectors['hideThread'])) ? '1' : '0'
                )
            ) );

            if( $selectors['singleThread'] ){
                $users_count = 1;
            }

            return [ 'report_id' => (int) $report_id, 'total' => (int) $users_count ];
        }

        public function preview( WP_REST_Request $request ) {
            $selectors = $request->get_param( 'selectors' );

            $user_query = $this->get_user_query( $selectors );
            if( $user_query ) {
                return (int) $user_query->total_users;
            } else {
                return 0;
            }
        }

        public function get_user_query( $selectors, $real_query = false, $page = 1, $per_page = 20 ){
            $args = array(
                'number'      => 1,
                'count_total' => true,
                'fields'      => 'ID',
                'exclude'     => array( get_current_user_id() )
            );

            if( $real_query ){
                if( $selectors['singleThread'] ) {
                    $args['number'] = -1;
                } else {
                    $args['number'] = $per_page;
                    $args['paged'] = $page;
                }
            }

            $sentTo = $selectors['sent-to'];

            $users = false;

            switch ($sentTo){
                case 'all':
                    $users = new WP_User_Query($args);
                    break;
                case 'role';
                    $roles = $selectors['roles'];

                    if( ! $roles ){
                        $roles = [];
                    }
                    if( ! is_array( $roles ) ){
                        $roles = [ $roles ];
                    }

                    $args['role__in'] = $roles;

                    $users = new WP_User_Query($args);
                    break;
                case 'group':
                    $users = groups_get_group_members(array(
                        'group_id' => intval($selectors['group']),
                        'per_page' => -1
                    ));

                    $usersIds = array();

                    foreach($users['members'] as $user){
                        if( $user->ID == get_current_user_id() ) continue;
                        $usersIds[] = $user->ID;
                    }

                    unset($args['exclude']);

                    if( count( $usersIds ) === 0 ){
                        return (object) [
                            'total_users' => 0
                        ];
                    }

                    $args['include'] = $usersIds;

                    $users = new WP_User_Query($args);

                    break;
            }

            return $users;
        }

        public function get_reports( WP_REST_Request $request ){
            global $wpdb;
            $return = [
                'reports' => [],
                'roles'   => [],
                'groups'  => []
            ];

            $reports = get_posts([
                'post_type' => 'bpbm-bulk-report',
                'post_status' => 'any',
                'posts_per_page' => -1
            ]);

            if( count( $reports ) > 0 ) {
                foreach ($reports as $report) {
                    $thread_ids = get_post_meta($report->ID, 'thread_ids');
                    foreach ($thread_ids as $i => $thread_id) {
                        if (!is_string($thread_id) && !is_numeric($thread_id)) {
                            unset($thread_ids[$i]);
                        }
                    }

                    $thread_ids = array_unique($thread_ids);

                    $read_count = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT COUNT(*) 
                            FROM `" . bm_get_table('recipients') . "` 
                            WHERE `user_id` != %d 
                            AND `thread_id` IN (%s) 
                            AND `unread_count` = 0",
                            $report->post_author,
                            implode(',', $thread_ids)
                        )
                    );

                    $return['reports'][] = [
                        'id'           => (int) $report->ID,
                        'count'        => (int) count($thread_ids),
                        'subject'      => $report->subject,
                        'sender'       => (int) $report->post_author,
                        'read'         => (int) $read_count,
                        'date'         => $report->post_date,
                        'disableReply' => ( $report->disableReply === '1')
                    ];
                }

            }

            $roles = wp_roles()->roles;

            foreach( $roles as $slug => $role ){
                $return['roles'][] = [
                    'slug' => $slug,
                    'name' => $role['name']
                ];
            }

            if( function_exists('groups_get_groups') ) {
                $groups = groups_get_groups(array(
                    'per_page' => -1,
                    'show_hidden' => true
                ));


                foreach($groups['groups'] as $group){
                    $return['groups'][] = [ 'id' => intval( $group->id ), 'name' => esc_attr( $group->name ) ];
                }
            }

            return $return;
        }

    }


    function Better_Messages_Rest_Api_Bulk_Message(){
        return Better_Messages_Rest_Api_Bulk_Message::instance();
    }
endif;
