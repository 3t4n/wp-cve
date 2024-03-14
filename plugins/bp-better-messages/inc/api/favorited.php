<?php
if ( !class_exists( 'Better_Messages_Rest_Api_Favorited' ) ):

    class Better_Messages_Rest_Api_Favorited
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Rest_Api_Favorited();
            }

            return $instance;
        }

        public function __construct()
        {
            add_action('rest_api_init', array($this, 'rest_api_init'));
        }

        public function rest_api_init(){
            if( Better_Messages()->settings['disableFavoriteMessages'] !== '1' ) {
                register_rest_route('better-messages/v1', '/getFavorited', array(
                    'methods' => 'GET',
                    'callback' => array($this, 'get_favorited'),
                    'permission_callback' => array(Better_Messages_Rest_Api(), 'is_user_authorized'),
                ));


                register_rest_route('better-messages/v1', '/thread/(?P<id>\d+)/favorite', array(
                    'methods' => 'POST',
                    'callback' => array($this, 'favorite'),
                    'permission_callback' => array(Better_Messages_Rest_Api(), 'check_thread_access'),
                    'args' => array(
                        'id' => array(
                            'validate_callback' => function ($param, $request, $key) {
                                return is_numeric($param);
                            }
                        ),
                    ),
                ));

            }
        }

        public function get_favorited( WP_REST_Request $request ){
            $current_user_id = Better_Messages()->functions->get_current_user_id();

            global $wpdb;

            $query = $wpdb->prepare( "
                SELECT
                  " . bm_get_table('messages') . ".id
                FROM " . bm_get_table('meta') . "
                  INNER JOIN " . bm_get_table('messages') . "
                    ON " . bm_get_table('meta') . ".bm_message_id = " . bm_get_table('messages') . ".id
                  INNER JOIN " . bm_get_table('recipients') . "
                    ON " . bm_get_table('recipients') . ".thread_id = " . bm_get_table('messages') . ".thread_id
                WHERE " . bm_get_table('meta') . ".meta_key = 'starred_by_user'
                AND " . bm_get_table('meta') . ".meta_value = %d
                AND " . bm_get_table('recipients') . ".is_deleted = 0
                AND " . bm_get_table('recipients') . ".user_id = %d
            ", $current_user_id, $current_user_id );

            $messages_ids = $wpdb->get_col( $query );

            $return = Better_Messages_Rest_Api()->get_messages( null, $messages_ids );

            return $return;
        }

        public function favorite( WP_REST_Request $request ){
            $message_id = absint( $request->get_param( 'messageId') );
            $type       = sanitize_text_field( $request->get_param('type') );

            $args = array(
                'action'     => $type,
                'message_id' => $message_id,
                'user_id'    => Better_Messages()->functions->get_current_user_id(),
            );

            $is_starred = Better_Messages()->functions->is_message_starred( $args['message_id'], $args['user_id'] );

            // Star.
            if ( 'star' == $args['action'] ) {
                if ( true === $is_starred ) {
                    return true;
                } else {
                    Better_Messages()->functions->add_message_meta( $args['message_id'], 'starred_by_user', $args['user_id'] );
                    return true;
                }
                // Unstar.
            } else {
                if ( false === $is_starred ) {
                    return true;
                } else {
                    Better_Messages()->functions->delete_message_meta( $args['message_id'], 'starred_by_user', $args['user_id'] );
                    return true;
                }
            }
        }
    }

    function Better_Messages_Rest_Api_Favorited(){
        return Better_Messages_Rest_Api_Favorited::instance();
    }

endif;
