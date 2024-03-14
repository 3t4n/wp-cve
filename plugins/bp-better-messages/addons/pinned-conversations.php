<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Pinned_Conversations' ) ) {

    class Better_Messages_Pinned_Conversations
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Pinned_Conversations();
                $instance->setup_actions();
            }

            return $instance;
        }

        public function setup_actions(){
            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
        }

        public function rest_api_init(){
            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/makePinned', array(
                'methods' => 'POST',
                'callback' => array( $this, 'make_pinned' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ));

            register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/unmakePinned', array(
                'methods' => 'POST',
                'callback' => array( $this, 'unmake_pinned' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ));
        }

        public function make_pinned( WP_REST_Request $request ){
            $user_id = Better_Messages()->functions->get_current_user_id();
            $thread_id = intval( $request->get_param('id') );

            $is_participant = Better_Messages()->functions->is_thread_participant( $user_id, $thread_id );

            if( ! $is_participant ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $result = (bool) $this->update_pinned( $thread_id, $user_id, 1 );

            if( $result ){
                do_action( 'better_messages_thread_self_update', $thread_id, $user_id );
            }

            return $result;
        }



        public function unmake_pinned( WP_REST_Request $request ){
            $user_id = Better_Messages()->functions->get_current_user_id();
            $thread_id = intval( $request->get_param('id') );

            $is_participant = Better_Messages()->functions->is_thread_participant( $user_id, $thread_id );

            if( ! $is_participant ){
                return new WP_Error(
                    'rest_forbidden',
                    _x( 'Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $result = (bool) $this->update_pinned( $thread_id, $user_id, 0 );

            if( $result ){
                do_action( 'better_messages_thread_self_update', $thread_id, $user_id );
            }

            return $result;
        }

        public function update_pinned( $thread_id, $user_id, $is_pinned ){
            global $wpdb;

            $time = Better_Messages()->functions->get_microtime();

            return $wpdb->update( bm_get_table('recipients' ), [
                'is_pinned' => (int) $is_pinned,
                'last_update' => $time
            ], [
                'user_id'   => $user_id,
                'thread_id' => $thread_id
            ], [ '%d', '%d' ], [ '%d', '%d' ] );
        }

    }
}

