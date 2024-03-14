<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Giphy' ) ):

    class Better_Messages_Giphy
    {
        public $api_key;

        public $content_rating;

        public $lang;

        public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Giphy();
            }

            return $instance;
        }


        public function __construct()
        {
            if( ! empty(Better_Messages()->settings['giphyApiKey']) ) {
                $this->api_key        = Better_Messages()->settings['giphyApiKey'];
                $this->content_rating = Better_Messages()->settings['giphyContentRating'];
                $this->lang           = Better_Messages()->settings['giphyLanguage'];

                add_filter('bp_better_messages_pre_format_message', array($this, 'format_message'), 9, 4);
                add_filter('bp_better_messages_after_format_message', array($this, 'after_format_message'), 9, 4);
                add_action('bp_better_chat_settings_updated', array($this, 'check_if_api_key_valid'));

                add_action( 'rest_api_init',  array( $this, 'rest_api_init' ) );
            }
        }

        public function rest_api_init(){
            register_rest_route('better-messages/v1', '/gifs', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_gifs'),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'is_user_authorized' )
            ));

            /* register_rest_route('better-messages/v1', '/gifs/search', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_gifs'),
                'permission_callback' => 'is_user_logged_in'
            ));*/

            register_rest_route('better-messages/v1', '/gifs/(?P<id>\d+)/send', array(
                'methods' => 'POST',
                'callback' => array( $this, 'send_gif' ),
                'permission_callback' => array( Better_Messages_Rest_Api(), 'can_reply' )
            ));

        }

        public function get_gifs( WP_REST_Request $request ){
            $user_id = Better_Messages()->functions->get_current_user_id();

            $page    = intval( $request->get_param('page') );

            $search  = sanitize_text_field( $request->get_param('search') );

            if( ! empty( $search ) ) {
                $user_id = Better_Messages()->functions->get_current_user_id();
                $gifs = $this->search( $user_id, $search, $page );
            } else {
                $gifs    = $this->get_trending_gifs( $user_id, $page );
            }
            return $gifs;
        }

        public function send_gif( WP_REST_Request $request ){
            $thread_id  = intval($request->get_param('id'));
            $errors    = array();

            $gif_id  = sanitize_text_field($request->get_param('gif_id'));
            $gif = $this->get_gif( $gif_id, Better_Messages()->functions->get_current_user_id() );

            if( ! $gif ) return false;

            $gif_mp4 = esc_url($gif->images->original_mp4->mp4);
            $poster  = esc_url($gif->images->{"480w_still"}->url);

            $message  = '<span class="bpbm-gif">';
            $message .= '<video preload="auto" muted playsinline="playsinline" loop="loop" poster="' . $poster . '">';
            $message .= '<source src="' . $gif_mp4 . '" type="video/mp4">';
            $message .= '</video>';
            $message .= '</span>';

            $args = array(
                'content'    => $message,
                'thread_id'  => $thread_id,
                'error_type' => 'wp_error'
            );

            if( ! Better_Messages()->functions->can_send_message_filter( Better_Messages()->functions->check_access( $thread_id ), Better_Messages()->functions->get_current_user_id(), $thread_id ) ) {
                $errors[] = __( 'You are not allowed to reply to this conversation.', 'bp-better-messages' );
            }

            Better_Messages()->functions->before_message_send_filter( $args, $errors );

            if( empty( $errors ) ){
                remove_filter( 'better_messages_message_content_before_save', 'bp_messages_filter_kses', 1 );
                remove_action( 'better_messages_message_sent', 'messages_notification_new_message', 10 );
                $sent = Better_Messages()->functions->new_message( $args );
                add_action( 'better_messages_message_sent', 'messages_notification_new_message', 10 );
                Better_Messages()->functions->messages_mark_thread_read( $thread_id );
                add_filter( 'better_messages_message_content_before_save', 'bp_messages_filter_kses', 1 );

                if ( is_wp_error( $sent ) ) {
                    $errors[] = $sent->get_error_message();
                } else {
                    //$this->register_usage( get_current_user_id(), $gif_id );
                }
            }

            if( ! empty($errors) ) {
                do_action( 'better_messages_on_message_not_sent', $thread_id, $errors );

                $redirect = 'redirect';

                if( count( $errors ) === 1 && isset( $errors['empty'] ) ){
                    $redirect = false;
                }

                return array(
                    'result'   => false,
                    'errors'   => $errors,
                    'redirect' => $redirect
                );
            } else {
                return array(
                    'result'   => $sent,
                    'redirect' => false
                );
            }
        }

        public function get_gif( $gif_id, $user_id ){
            $user_id = $this->get_random_id( $user_id );

            $offset = 0;

            $endpoint = add_query_arg([
                'api_key'   => $this->api_key,
                'gif_id'    => $gif_id,
                'random_id' => $user_id,
            ], 'https://api.giphy.com/v1/gifs/' . $gif_id);

            $args = array(
                'timeout'     => 2,
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [];
            }

            $response = json_decode($request['body']);

            if( isset( $response->data->id ) ) {
                return $response->data;
            } else {
                return false;
            }
        }

        public function get_random_id($user_id){
            $random_id = Better_Messages()->functions->get_user_meta($user_id, 'bpbm_giphy_random_id', true);
            if( !! $random_id ) return $random_id;

            $endpoint = add_query_arg([
                'api_key' => $this->api_key
            ], 'https://api.giphy.com/v1/randomid');

            $args = array(
                'timeout'     => 2,
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [];
            }

            $response = json_decode($request['body']);

            $unique_id = $response->data->random_id;

            Better_Messages()->functions->update_user_meta($user_id, 'bpbm_giphy_random_id', $unique_id);

            return $unique_id;
        }

        public function get_trending_gifs( $user_id, $page = 1 ){
            $user_id = $this->get_random_id( $user_id );

            if( $page <= 1 ) {
                $offset = 0;
            } else {
                $offset = ($page * 20) - 20;
            }

            $endpoint = add_query_arg([
                'api_key' => $this->api_key,
                'limit'       => 20,
                'rating'      => $this->content_rating,
                'random_id'   => $user_id,
                'offset'      => $offset
            ], 'https://api.giphy.com/v1/gifs/trending');

            $args = array(
                'timeout'     => 2,
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [];
            }

            $response = json_decode($request['body']);

            $return = [
                'pagination' => $response->pagination,
                'gifs'       => []
            ];

            $gifs = $response->data;

            if( count($gifs ) > 0 ){
                foreach ( $gifs as $gif ){
                    $return['gifs'][] = [
                      'id'  => $gif->id,
                      'url' => $gif->images->fixed_width->url
                    ];
                }
            }

            #Better_Messages()->functions->update_user_meta($user_id, 'bpbm_latest_stickers_cache', $stickers);

            return $return;
        }

        public function check_if_api_key_valid( $settings ){
            if( ! empty( $settings['giphyApiKey'] ) ){
                $this->api_key = $settings['giphyApiKey'];
                $this->check_api_key();
            }
        }

        function render_gif( $gifs, $type ){
            if( count( $gifs ) > 0 ){
                foreach( $gifs as $gif ){
                    echo '<div class="bpbm-gifs-selector-gif" data-gif-id="' . $gif['id'] . '">';
                    echo '<img src="' . $gif['images']->fixed_width->url . '" alt="">';
                    echo '</div>';
                }
            }
        }

        public function format_message( $message, $message_id, $context, $user_id ) {
            $is_gif = strpos( $message, '<span class="bpbm-gif">', 0 ) === 0;

            if( $is_gif ){
                if( $context !== 'stack' ) {
                    return '%bpbmgif%';
                }
            }
            return $message;
        }

        public function after_format_message( $message, $message_id, $context, $user_id ){
            $is_gif = strpos( $message, '<span class="bpbm-gif">', 0 ) === 0 || $message === '%bpbmgif%';

            if( $is_gif ){
                $desc = '<i class="bpbm-gifs-icon" title="' . __('GIF', 'bp-better-messages') . '"></i>';
                if( $context !== 'stack' ) {
                    return $desc;
                } else {
                    #return str_replace('<span class="bpbm-gif">', '<span class="bpbm-gif" title="' . __('GIF', 'bp-better-messages') . '"><span class="bpbm-gif-play"></span>', $message);
                }
            }
            return $message;
        }

        public function get_search_gifs(){
            $user_id = Better_Messages()->functions->get_current_user_id();
            $search  = sanitize_text_field($_POST['search']);
            $page    = ( isset( $_POST['page'] ) ) ? intval( $_POST['page'] ) : false;

            if( ! $page ){;
                $results = $this->search( $user_id, $search );
                if($results['pagination']->total_count === 0){
                    echo '<div class="bpbm-gifs-selector-gif-list empty" data-pages="' . 0 . '" data-pages-loaded="0">';
                    echo '<div class="bpbm-gifs-selector-empty">' . __('Search results will display here', 'bp-better-messages') . '</div>';
                    echo '</div>';

                } else {
                    $pages = ceil($results['pagination']->total_count / $results['pagination']->count);
                    echo '<div class="bpbm-gifs-selector-gif-list" data-pages="' . $pages . '" data-pages-loaded="1">';
                    $this->render_gif($results['gifs'], 'search');
                    echo '</div>';
                }
            } else {
                $results = $this->search( $user_id, $search, $page );
                $this->render_gif( $results['gifs'], 'search' );
            }

            exit;
        }

        public function search( $user_id, $search, $page = 1 ){

            $user_id = $this->get_random_id( $user_id );

            if( $page <= 1 ) {
                $offset = 0;
            } else {
                $offset = ($page * 20) - 20;
            }

            $endpoint = add_query_arg([
                'api_key'     => $this->api_key,
                'q'           => $search,
                'limit'       => 20,
                'rating'      => $this->content_rating,
                'random_id'   => $user_id,
                'offset'      => $offset,
                'lang'        => $this->lang
            ], 'https://api.giphy.com/v1/gifs/search');

            $args = array(
                'timeout'     => 2,
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [];
            }

            $response = json_decode($request['body']);

            $return = [
                'pagination' => $response->pagination,
                'gifs'       => []
            ];

            $gifs = $response->data;

            if( count($gifs ) > 0 ){
                foreach ( $gifs as $gif ){
                    $return['gifs'][] = [
                        'id'  => $gif->id,
                        'url' => $gif->images->fixed_width->url
                    ];
                }
            }

            return $return;
            /* $return = [
                'pagination' => $response->pagination,
                'gifs'       => []
            ];

            $gifs = $response->data;

            if( count($gifs ) > 0 ){
                foreach ( $gifs as $gif ){
                    $return['gifs'][] = (array) $gif;
                }
            }

            return $return; */
        }

        public function check_api_key(){
            $endpoint = add_query_arg([
                'api_key' => $this->api_key,
                'limit'       => 20,
                'rating'      => $this->content_rating,
                'offset'      => 0
            ], 'https://api.giphy.com/v1/gifs/trending');

            $args = array(
                'timeout'     => 2,
            );

            $request = wp_remote_get($endpoint, $args);

            if( is_wp_error( $request ) ){
                return [];
            }

            $response = json_decode($request['body']);

            if( is_wp_error( $request ) ){
                update_option( 'bp_better_messages_giphy_error', 'GIPHY Error:' . $request->get_error_message(), false );
            } else {
                if (isset($request['response']) && $request['response']['code'] !== 200) {
                    update_option('bp_better_messages_giphy_error', $response->message, false );
                } else {
                    delete_option('bp_better_messages_giphy_error');
                }
            }
        }


    }

endif;


function Better_Messages_Giphy()
{
    return Better_Messages_Giphy::instance();
}
