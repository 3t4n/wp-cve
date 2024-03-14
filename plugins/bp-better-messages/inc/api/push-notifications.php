<?php
if ( !class_exists( 'Better_Messages_Rest_Push_Notifications' ) ):

    class Better_Messages_Rest_Push_Notifications
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Rest_Push_Notifications();
            }

            return $instance;
        }

        public function __construct()
        {
            add_action('rest_api_init', array($this, 'rest_api_init'));
        }

        public function rest_api_init()
        {
            register_rest_route('better-messages/v1', '/pushNotifications/save', array(
                'methods' => 'POST',
                'callback' => array($this, 'save_user_push_subscription'),
                'permission_callback' => array(Better_Messages_Rest_Api(), 'is_user_authorized')
            ));

            register_rest_route('better-messages/v1', '/pushNotifications/delete', array(
                'methods' => 'POST',
                'callback' => array($this, 'delete_user_push_subscription'),
                'permission_callback' => array(Better_Messages_Rest_Api(), 'is_user_authorized')
            ));
        }

        public function save_user_push_subscription( WP_REST_Request $request ){
            $user_id      = Better_Messages()->functions->get_current_user_id();
            $subscription = json_decode( wp_unslash( $request->get_param('sub') ) );

            $user_push_subscriptions = Better_Messages()->functions->get_user_meta( $user_id, 'bpbm_messages_push_subscriptions', true );
            if( empty( $user_push_subscriptions ) || ! is_array( $user_push_subscriptions ) ) $user_push_subscriptions = array();
            $user_push_subscriptions[ $subscription->endpoint ] = (array) $subscription->keys;
            Better_Messages()->functions->update_user_meta( $user_id, 'bpbm_messages_push_subscriptions', $user_push_subscriptions );

            return true;
        }

        public function delete_user_push_subscription( WP_REST_Request $request ){
            $user_id      = Better_Messages()->functions->get_current_user_id();
            $subscription = json_decode( wp_unslash( $request->get_param('sub') ) );

            $user_push_subscriptions = Better_Messages()->functions->get_user_meta( $user_id, 'bpbm_messages_push_subscriptions', true );
            if( empty( $user_push_subscriptions ) || ! is_array( $user_push_subscriptions ) ) $user_push_subscriptions = array();

            if( isset( $user_push_subscriptions[ $subscription->endpoint ] ) ){
                unset( $user_push_subscriptions[ $subscription->endpoint ] );
            }

            Better_Messages()->functions->update_user_meta( $user_id, 'bpbm_messages_push_subscriptions', $user_push_subscriptions );

            return true;
        }
    }

    function Better_Messages_Rest_Push_Notifications(){
        return Better_Messages_Rest_Push_Notifications::instance();
    }
endif;
