<?php
if ( !class_exists( 'Better_Messages_Rest_Friends' ) ):

    class Better_Messages_Rest_Friends
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Rest_Friends();
            }

            return $instance;
        }

        public function __construct(){
            add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
        }

        public function rest_api_init(){
            register_rest_route( 'better-messages/v1', '/getFriends', array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_friends' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ) );
        }

        public function get_friends( WP_REST_Request $request ){
            $current_user_id = Better_Messages()->functions->get_current_user_id();
            return apply_filters('better_messages_get_friends', [], $current_user_id);
        }
    }


    function Better_Messages_Rest_Friends(){
        return Better_Messages_Rest_Friends::instance();
    }
endif;
