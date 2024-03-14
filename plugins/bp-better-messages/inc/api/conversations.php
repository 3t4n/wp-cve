<?php
if ( !class_exists( 'Better_Messages_Rest_Api_Conversations' ) ):

    class Better_Messages_Rest_Api_Conversations
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Rest_Api_Conversations();
            }

            return $instance;
        }

        public function __construct(){
            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/changeSubject', array(
                'methods' => 'POST',
                'callback' => array( $this, 'change_subject' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );
            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/changeMeta', array(
                'methods' => 'POST',
                'callback' => array( $this, 'change_meta' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/mentionsSuggestions', array(
                'methods' => 'POST',
                'callback' => array( $this, 'get_mentions_suggestions' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/clearMessages', array(
                'methods' => 'POST',
                'callback' => array( $this, 'clear_messages' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/erase', array(
                'methods' => 'POST',
                'callback' => array( $this, 'erase_thread' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/removeParticipant', array(
                'methods' => 'POST',
                'callback' => array( $this, 'remove_participant' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/addParticipant', array(
                'methods' => 'POST',
                'callback' => array( $this, 'add_participant' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/makeModerator', array(
                'methods' => 'POST',
                'callback' => array( $this, 'make_moderator' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/unmakeModerator', array(
                'methods' => 'POST',
                'callback' => array( $this, 'unmake_moderator' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/leaveThread', array(
                'methods' => 'POST',
                'callback' => array( $this, 'leave_thread' ),
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

        public function leave_thread( WP_REST_Request $request ){
            $user_id = Better_Messages()->functions->get_current_user_id();
            $thread_id = intval($request->get_param('id'));

            $can_leave = Better_Messages()->functions->can_leave( $user_id, $thread_id );

            if( ! $can_leave ) {
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            return Better_Messages()->functions->remove_participant_from_thread( $thread_id, $user_id );
        }

        public function unmake_moderator( WP_REST_Request $request ){
            $thread_id = intval($request->get_param('id'));
            $user_id   = intval($request->get_param('user_id'));

            if( ! current_user_can('manage_options') ) {
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );

            if( $thread_type !== 'chat-room' ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            if( ! Better_Messages()->functions->is_thread_participant( $user_id, $thread_id, true ) ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'You can not unmake user moderator if he is not participant of the conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            Better_Messages()->functions->remove_moderator( $thread_id, $user_id );

            return true;
        }

        public function make_moderator( WP_REST_Request $request ){
            $thread_id = intval($request->get_param('id'));
            $user_id   = intval($request->get_param('user_id'));

            if( ! current_user_can('manage_options') ) {
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $thread_type = Better_Messages()->functions->get_thread_type( $thread_id );

            if( $thread_type !== 'chat-room' ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            if( ! Better_Messages()->functions->is_thread_participant( $user_id, $thread_id, true ) ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'You can not make user moderator if he is not participant of the conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            Better_Messages()->functions->add_moderator( $thread_id, $user_id );

            return true;
        }

        public function add_participant( WP_REST_Request $request ){
            $thread_id = intval( $request->get_param('id') );
            $user_ids = $request->get_param('user_id');

            if( ! $user_ids ) return new WP_Error(
                'rest_forbidden',
                _x( 'Something went wrong', 'Rest API Error', 'bp-better-messages' ),
                array( 'status' => rest_authorization_required_code() )
            );

            if( is_array( $user_ids ) ){
                $user_ids = array_map('intval', $user_ids );
            } else {
                $user_ids = [ intval( $request->get_param('user_id') ) ];
            }

            $can_invite = Better_Messages()->functions->can_invite(Better_Messages()->functions->get_current_user_id(), $thread_id);

            if( ! $can_invite ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to add participants to this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $added = false;

            foreach( $user_ids as $user_id ){
                $is_participant = Better_Messages()->functions->is_thread_participant( $user_id, $thread_id, true );

                if( ! $is_participant ) {
                    $add = Better_Messages()->functions->add_participant_to_thread( $thread_id, $user_id );
                    if( $add ) $added = true;
                } else if( count( $user_ids ) === 1 ){
                    return new WP_Error(
                        'rest_forbidden',
                        _x( 'Sorry, this user already participating in this conversation', 'Rest API Error', 'bp-better-messages' ),
                        array( 'status' => rest_authorization_required_code() )
                    );
                }
            }

            return $added;
        }

        public function remove_participant( WP_REST_Request $request ){
            $thread_id = intval( $request->get_param('id') );
            $user_id   = intval( $request->get_param('user_id') );

            $isSuperModerator  = Better_Messages_Functions()->is_thread_super_moderator( Better_Messages()->functions->get_current_user_id(), $thread_id );
            $isThreadModerator = Better_Messages()->functions->is_thread_moderator( $thread_id, Better_Messages()->functions->get_current_user_id() );

            if( ! $isSuperModerator && ! $isThreadModerator ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to remove participants from this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $is_participant = Better_Messages()->functions->is_thread_participant( $user_id, $thread_id, true );

            if( ! $is_participant ) {
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, this user is not participant of this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            if( ! $isSuperModerator && Better_Messages_Functions()->is_thread_super_moderator( $user_id, $thread_id ) ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to remove this user from conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            return Better_Messages()->functions->remove_participant_from_thread( $thread_id, $user_id );
        }

        public function erase_thread( WP_REST_Request $request ){
            $thread_id      = intval($request->get_param('id'));
            $can_erase = BP_Better_Messages()->functions->can_erase_thread( Better_Messages()->functions->get_current_user_id(), $thread_id );

            if( ! $can_erase ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to delete this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            Better_Messages()->functions->erase_thread( $thread_id );
            BP_Better_Messages()->hooks->clean_thread_cache( $thread_id );

            return true;
        }

        public function clear_messages( WP_REST_Request $request ){
            $thread_id      = intval($request->get_param('id'));
            $can_erase = BP_Better_Messages()->functions->can_clear_thread( Better_Messages()->functions->get_current_user_id(), $thread_id );

            if( ! $can_erase ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to delete this conversation', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            BP_Better_Messages()->functions->clear_thread( $thread_id );
            BP_Better_Messages()->hooks->clean_thread_cache( $thread_id );

            return true;
        }

        public function get_mentions_suggestions( WP_REST_Request $request ){
            global $wpdb;
            $thread_id      = intval($request->get_param('id'));
            $search_request = sanitize_text_field( $request->get_param('search' ) );
            $search         = '%' . $search_request . '%';

            $query = $wpdb->prepare("
                SELECT `users`.`ID`
                FROM `{$wpdb->users}` as users
                RIGHT JOIN " . bm_get_table('recipients') . " recipients 
                ON ( `users`.`ID` = `recipients`.`user_id`
                    AND `recipients`.`thread_id` = %d
                    AND `recipients`.`user_id` != %d
                    AND `recipients`.`is_deleted` = 0)
                WHERE `user_login` LIKE %s 
                   OR `user_nicename` LIKE %s 
                   OR `display_name` LIKE %s 
                ORDER BY `display_name` ASC
                LIMIT 0, 50
            ", $thread_id, Better_Messages()->functions->get_current_user_id(), $search, $search, $search );

            $user_ids = $wpdb->get_col( $query );

            $response = [];

            $user_ids_index = [];
            foreach( $user_ids as $user_id ){
                if( in_array( $user_id, $user_ids_index ) ) continue;

                $user_ids_index[] = $user_id;

                $user = get_userdata( $user_id );

                $response[] = [
                    'user_id' => (int) $user_id,
                    'label'    => (!empty($user->display_name)) ? $user->display_name : $user->user_login
                ];
            }

            return $response;
        }

        public function change_subject( WP_REST_Request $request ){
            $thread_id  = intval($request->get_param('id'));
            $subject    = sanitize_text_field($request->get_param('subject'));

            $can_change = Better_Messages()->functions->is_thread_super_moderator( Better_Messages()->functions->get_current_user_id(), $thread_id );

            if( ! $can_change ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            Better_Messages()->functions->change_thread_subject( $thread_id, $subject );

            return Better_Messages()->api->get_threads( [ $thread_id ], false, false );
        }

        public function change_meta( WP_REST_Request $request ){
            $thread_id  = intval($request->get_param('id'));
            $key   = sanitize_text_field( $request->get_param('key') );
            $value = sanitize_text_field( $request->get_param('value') );

            $can_change = Better_Messages()->functions->is_thread_super_moderator( Better_Messages()->functions->get_current_user_id(), $thread_id );

            if( ! $can_change ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            switch( $key ){
                case 'allow_invite':
                    Better_Messages()->functions->update_thread_meta( $thread_id, 'allow_invite', $value );
                    do_action( 'better_messages_thread_updated', $thread_id );
                    do_action( 'better_messages_info_changed', $thread_id );
                    break;
            }

            return true;
        }
    }


    function Better_Messages_Rest_Api_Conversations(){
        return Better_Messages_Rest_Api_Conversations::instance();
    }
endif;
