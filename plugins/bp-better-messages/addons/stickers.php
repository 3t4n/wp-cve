<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Stickers' ) ):

    class Better_Messages_Stickers
    {
        public $api_key;

        public $lang;

        public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Stickers();
            }

            return $instance;
        }


        public function __construct()
        {
            if( ! empty(Better_Messages()->settings['stipopApiKey']) ) {
                $this->api_key = Better_Messages()->settings['stipopApiKey'];
                $this->lang = Better_Messages()->settings['stipopLanguage'];

                add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
            }

            add_filter('bp_better_messages_after_format_message', array($this, 'format_message'), 9, 4);
            add_action('bp_better_chat_settings_updated', array($this, 'check_if_api_key_valid'));
        }

        public function rest_api_init(){
            register_rest_route('better-messages/v1', '/stickers', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_stickers'),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ));

            register_rest_route('better-messages/v1', '/stickers/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => array( $this, 'get_sticker_pack' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ));

            register_rest_route('better-messages/v1', '/stickers/search', array(
                'methods' => 'POST',
                'callback' => array( $this, 'search_api' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ));

            register_rest_route('better-messages/v1', '/stickers/(?P<id>\d+)/send', array(
                'methods' => 'POST',
                'callback' => array( $this, 'send_sticker' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'can_reply' )
            ));
        }

        public function get_stickers(){
            $user_id = Better_Messages()->functions->get_current_user_id();

            $sticker_packs = Better_Messages_Stickers()->get_available_packs($user_id);
            $stickers = $this->get_user_latest_stickers($user_id);

            return [
                'stickers' => $stickers,
                'packs'    => $sticker_packs
            ];
        }

        public function get_sticker_pack( WP_REST_Request $request ){
            $used_id = Better_Messages()->functions->get_current_user_id();
            $pack_id = intval($request->get_param('id'));

            return $this->get_stickers_from_pack($used_id, $pack_id);
        }

        public function check_if_api_key_valid( $settings ){
            if( ! empty( $settings['stipopApiKey'] ) ){
                $this->api_key = $settings['stipopApiKey'];
                $this->check_api_key();
            }

            global $wpdb;
            $user_ids = $wpdb->get_col("SELECT * FROM {$wpdb->usermeta} WHERE `meta_key` IN ('bpbm_latest_stickers_cache','bpbm_available_packs_cache')");

            foreach( $user_ids as $user_id ){
                Better_Messages()->functions->delete_user_meta($user_id, 'bpbm_latest_stickers_cache');
                Better_Messages()->functions->delete_user_meta($user_id, 'bpbm_available_packs_cache');
            }

            delete_option('bpbm_packs_cache');
        }

        public function send_sticker( WP_REST_Request $request ){
            $thread_id  = intval($request->get_param('id'));
            $errors    = array();

            $sticker_id  = $request->get_param('sticker_id');
            $sticker_img = esc_url( strip_tags( $request->get_param('sticker_img') ) );

            if( strpos( $sticker_img, 'https://img.stipop.io/', 0 ) !== 0 ){
                return false;
            }

            $message = '<span class="bpbm-sticker"><img src="' . $sticker_img . '" alt=""></span>';

            $args = array(
                'content'    => $message,
                'thread_id'  => $thread_id,
                'return'     => 'message_id',
                'error_type' => 'wp_error'
            );

            if( ! Better_Messages()->functions->can_send_message_filter( Better_Messages()->functions->check_access( $thread_id ), Better_Messages()->functions->get_current_user_id(), $thread_id ) ) {
                $errors[] = __( 'You are not allowed to reply to this conversation.', 'bp-better-messages' );
            }

            Better_Messages()->functions->before_message_send_filter( $args, $errors );

            if( empty( $errors ) ){
                $message_id = Better_Messages()->functions->new_message( $args );
                Better_Messages()->functions->messages_mark_thread_read( $thread_id );

                if ( is_wp_error( $message_id ) ) {
                    $errors[] = $message_id->get_error_message();
                } else {
                    $this->register_usage( Better_Messages()->functions->get_current_user_id(), $sticker_id );
                }
            }

            if( ! empty($errors) ) {
                do_action( 'better_messages_on_message_not_sent', $thread_id, $errors );

                $redirect = 'redirect';

                if( count( $errors ) === 1 && isset( $errors['empty'] ) ){
                    $redirect = false;
                }

                wp_send_json( array(
                    'result'   => false,
                    'errors'   => $errors,
                    'redirect' => $redirect
                ) );
            } else {
                $messages = Better_Messages_Rest_Api()->get_messages( $thread_id, [ $message_id ] );

                wp_send_json( array(
                    'result'   => $message_id,
                    'update'   => $messages,
                    'redirect' => false
                ) );
            }
        }


        public function format_message( $message, $message_id, $context, $user_id ) {
            $is_sticker = strpos( $message, '<span class="bpbm-sticker">', 0 ) === 0;

            if( $is_sticker ){
                global $processedUrls;

                $regex = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
                preg_match_all( $regex, $message, $urls );

                if( ! empty( $urls[0] ) ){
                    $urls[0] = array_unique($urls[0]);
                }

                foreach ( $urls[ 0 ] as $_url ) {
                    $processedUrls[$message_id][] = $_url;
                }

                $desc = '<i class="fas fa-sticky-note"></i> ' . __('Sticker', 'bp-better-messages');
                if( $context !== 'stack' ) {
                    return $desc;
                } else {
                    //return str_replace('<span class="bpbm-sticker">', '<span class="bpbm-sticker" data-desc="' . base64_encode($desc) . '">',$message);
                }
            }

            return $message;
        }

        public function search_api( WP_REST_Request $request ){
            $used_id = Better_Messages()->functions->get_current_user_id();
            $search  = sanitize_text_field( $request->get_param('search') );
            $page    = intval( $request->get_param('page') );

            return $this->search( $used_id, $search, $page );
        }

        public function search( $user_id, $search, $page = 1 ){

            $endpoint = add_query_arg([
                'userId'     => $user_id,
                'pageNumber' => $page,
                'q'          => $search,
                'lang'       => $this->lang,
                'limit'      => 40
            ], 'https://messenger.stipop.io/v1/search');

            $args = array(
                'headers'     => array(
                    'apikey' => $this->api_key,
                ),
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [
                    'stickers' => [],
                    'pages'    => 0
                ];
            }

            $response = json_decode($request['body']);

            $stickers = [];

            if( $response->body->stickerList ) {
                foreach ($response->body->stickerList as $sticker) {
                    $stickers[$sticker->stickerId] = $sticker;
                }
            }

            return [
                'stickers' => $stickers,
                'pages'    => $response->body->pageMap->pageCount
            ];
        }

        public function register_usage( $user_id, $sticker_id ){
            $sticker_id = intval($sticker_id);

            $endpoint = add_query_arg([
                'userId'     => $user_id
            ], 'https://messenger.stipop.io/v1/analytics/send/' . $sticker_id);

            $args = array(
                'blocking' => false,
                'headers'     => array(
                    'apikey' => $this->api_key,
                ),
            );

            wp_remote_post($endpoint, $args);

            $latest_stickers = Better_Messages()->functions->get_user_meta($user_id, 'bpbm_latest_stickers_cache', true);
            if (!!$latest_stickers && isset($latest_stickers[$sticker_id])) {
                //probably sort here later
            } else {
                Better_Messages()->functions->delete_user_meta($user_id, 'bpbm_latest_stickers_cache');
            }

            return true;
        }

        public function check_api_key(){
            $user_id  = Better_Messages()->functions->get_current_user_id();
            $endpoint = add_query_arg([], 'https://messenger.stipop.io/v1/package/send/' . $user_id);

            $args = array(
                'headers'     => array(
                    'apikey' => $this->api_key,
                ),
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                update_option( 'bp_better_messages_stipop_error', 'Stipop Error:' . $request->get_error_message(), false );
            } else {
                $response = json_decode($request['body']);

                if (isset($response->status) && $response->status === 'fail') {
                    update_option('bp_better_messages_stipop_error', $response->message, false );
                } else {
                    delete_option('bp_better_messages_stipop_error');
                }
            }
        }

        public function get_user_latest_stickers( $user_id ){
            $latest_stickers = Better_Messages()->functions->get_user_meta($user_id, 'bpbm_latest_stickers_cache', true);
            if (is_array($latest_stickers)) return $latest_stickers;

            $endpoint = add_query_arg([], 'https://messenger.stipop.io/v1/package/send/' . $user_id);

            $args = array(
                'timeout'     => 2,
                'headers'     => array(
                    'apikey' => $this->api_key,
                ),
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [];
            }

            $response = json_decode($request['body']);

            $stickers = [];
            foreach( $response->body->stickerList as $sticker ){
                $stickers[ $sticker->stickerId ] = $sticker;
            }

            Better_Messages()->functions->update_user_meta($user_id, 'bpbm_latest_stickers_cache', $stickers);

            return $stickers;
        }

        public function get_available_packs( $user_id ){
            $available_packs = Better_Messages()->functions->get_user_meta($user_id, 'bpbm_available_packs_cache', true);
            if (!!$available_packs && count($available_packs) > 0) return $available_packs;

            $endpoint = add_query_arg([
                'userId'     => $user_id,
                'pageNumber' => 1
            ], 'https://messenger.stipop.io/v1/package');

            $args = array(
                'timeout'     => 2,
                'headers'     => array(
                    'apikey' => $this->api_key,
                ),
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [];
            }

            $response = json_decode($request['body']);

            $packages = [];

            foreach( $response->body->packageList as $package ){
                $packages[ $package->packageId ] = $package;
            }

            Better_Messages()->functions->update_user_meta($user_id, 'bpbm_available_packs_cache', $packages);

            return $packages;
        }

        public function get_stickers_from_pack( $user_id, $package_id ){
            $available_packs = get_option('bpbm_packs_cache', []);
            if( is_array( $available_packs ) && isset( $available_packs[ $package_id ]) ) {
                return $available_packs[ $package_id ];
            }

            if( ! is_array($available_packs) ){
                $available_packs = array();
            }

            $package_id = intval($package_id);

            $endpoint = add_query_arg([
                'userId' => $user_id
            ], 'https://messenger.stipop.io/v1/package/' . $package_id);

            $args = array(
                'headers'     => array(
                    'apikey' => $this->api_key,
                ),
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [];
            }

            $response = json_decode($request['body']);

            $stickers = [];

            foreach( $response->body->package->stickers as $sticker ){
                $stickers[ $sticker->stickerId ] = $sticker;
            }

            $available_packs[ $package_id ] = $stickers;

            update_option( 'bpbm_packs_cache', $available_packs, false );

            return $stickers;
        }
    }

endif;


function Better_Messages_Stickers()
{
    return Better_Messages_Stickers::instance();
}
