<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Pinned_Message' ) ) {

    class Better_Messages_Pinned_Message
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Pinned_Message();
                $instance->setup_actions();
            }

            return $instance;
        }

        public function setup_actions(){
            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
            add_filter( 'better_messages_can_pin_messages', array( $this, 'can_pin_messages' ), 10, 3 );
            add_filter( 'better_messages_rest_thread_item', array( $this, 'rest_thread_item'), 10, 5 );
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/pinMessage', array(
                'methods' => 'POST',
                'callback' => array( $this, 'pin_message' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'check_thread_access' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
            ) );

            register_rest_route( 'better-messages/v1', '/thread/(?P<id>\d+)/unpinMessage', array(
                'methods' => 'POST',
                'callback' => array( $this, 'unpin_message' ),
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

        public function rest_thread_item( $thread_item, $thread_id, $thread_type, $include_personal, $user_id )
        {
            $pinned_messages = $this->get_pinned_messages( $thread_id );

            if( is_array($pinned_messages) && count( $pinned_messages ) > 0 ){
                $thread_item['pinned'] = $pinned_messages;
            }

            return $thread_item;
        }

        public function pin_message( WP_REST_Request $request ){
            $thread_id  = intval($request->get_param('id'));
            $message_id = intval($request->get_param('messageId'));

            $message = Better_Messages()->functions->get_message( $message_id );

            if( ! $message || ( int ) $message->thread_id !== $thread_id ) {
                return new WP_Error(
                    'rest_forbidden',
                    __( 'Message not found', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $can_pin_message = apply_filters('better_messages_can_pin_messages', false, Better_Messages()->functions->get_current_user_id(), $thread_id );

            if( ! $can_pin_message ){
                return new WP_Error(
                    'rest_forbidden',
                    _x('Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages'),
                    array('status' => rest_authorization_required_code())
                );
            }

            $current_pinned = $this->get_pinned_messages( $thread_id );

            // Only allow single pinned message for now
            if( count( $current_pinned ) > 0 ){
                foreach ( $current_pinned as $id ){
                    Better_Messages()->functions->delete_message_meta( $id, 'is_pinned', '' );
                    Better_Messages()->functions->delete_message_meta($id, 'pinned_by', '');
                }
            }

            Better_Messages()->functions->update_message_meta( $message_id, 'is_pinned', time() );
            Better_Messages()->functions->update_message_meta( $message_id, 'pinned_by', Better_Messages()->functions->get_current_user_id() );

            $value = [ $message_id ];

            do_action( 'better_messages_thread_updated', $thread_id );
            do_action('better_messages_info_changed', $thread_id);

            return $value;
        }

        public function unpin_message( WP_REST_Request $request ){
            $thread_id  = intval($request->get_param('id'));
            $message_id = intval($request->get_param('messageId'));

            $message = Better_Messages()->functions->get_message( $message_id );

            if( ! $message || ( int ) $message->thread_id !== $thread_id ) {
                return new WP_Error(
                    'rest_forbidden',
                    __( 'Message not found', 'bp-better-messages' ),
                    array( 'status' => rest_authorization_required_code() )
                );
            }

            $can_pin_message = apply_filters('better_messages_can_pin_messages', false, Better_Messages()->functions->get_current_user_id(), $thread_id );

            if( ! $can_pin_message ){
                return new WP_Error(
                    'rest_forbidden',
                    _x('Sorry, you are not allowed to do that', 'Rest API Error', 'bp-better-messages'),
                    array('status' => rest_authorization_required_code())
                );
            }

            $current_pinned = $this->get_pinned_messages( $thread_id );

            // Only allow single pinned message for now
            if( count( $current_pinned ) > 0 ){
                foreach ( $current_pinned as $id ){
                    if( $id === $message_id ) {
                        Better_Messages()->functions->delete_message_meta($id, 'is_pinned', '');
                        Better_Messages()->functions->delete_message_meta($id, 'pinned_by', '');
                    }
                }
            }

            do_action( 'better_messages_thread_updated', $thread_id );
            do_action('better_messages_info_changed', $thread_id);

            return true;
        }

        public function get_pinned_messages( $thread_id ){
            global $wpdb;

            $sql = $wpdb->prepare("
            SELECT `bm_message_id`
            FROM `" . bm_get_table('meta') . "`
            WHERE `meta_key` = 'is_pinned'
            AND `bm_message_id` IN (SELECT id FROM `" . bm_get_table('messages') . "` WHERE `thread_id` = %d)
            ", $thread_id);

            return array_map('intval', $wpdb->get_col( $sql ) );
        }

        public function can_pin_messages( $allowed, $user_id, $thread_id ){
            return Better_Messages()->functions->can_moderate_thread( $thread_id, $user_id );
        }
    }
}
