<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_WP_Job_Manager' ) ){

    class Better_Messages_WP_Job_Manager
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_WP_Job_Manager();
            }

            return $instance;
        }

        public function __construct()
        {
            if( Better_Messages()->settings['wpJobManagerIntegration'] !== '1' ) return;

            add_action( 'single_job_listing_end', array( $this, 'single_job_listing_button') );
            add_filter( 'better_messages_rest_thread_item', array( $this, 'thread_item' ), 10, 5 );
            add_filter('better_messages_can_send_message', array( $this, 'disable_filled_conversations' ), 20, 3 );

            add_shortcode( 'better_messages_wp_job_manager_listing_button',   array( &$this, 'listing_page_contact_button_shortcode' ) );
        }

        public function listing_page_contact_button_shortcode()
        {
            return $this->single_job_listing_button( true );
        }

        public function disable_filled_conversations( $allowed, $user_id, $thread_id ){
            $wp_job_manager_post_id = $this->is_wp_job_manager_thread( $thread_id );

            if( $wp_job_manager_post_id ) {
                $post = get_post( $wp_job_manager_post_id );
                global $bp_better_messages_restrict_send_message;

                if ( function_exists('is_position_filled') && is_position_filled($wp_job_manager_post_id)) {
                    $allowed = false;
                    $bp_better_messages_restrict_send_message['wp_job_manager_filled'] = __( 'This position has been filled', 'wp-job-manager' );
                } else if( function_exists('candidates_can_apply') && ! candidates_can_apply( $post ) && 'preview' !== $post->post_status ) {
                    $allowed = false;
                    $bp_better_messages_restrict_send_message['wp_job_manager_closed'] = __( 'Applications have closed', 'wp-job-manager' );
                }
            }

            return $allowed;
        }

        public function is_wp_job_manager_thread( $thread_id )
        {
            $unique_tag = Better_Messages()->functions->get_thread_meta( $thread_id, 'unique_tag' );

            if( ! empty( $unique_tag ) ) {
                if ( str_starts_with( $unique_tag, 'wp_job_manager_chat_') ) {
                    $parts = explode( '|', $unique_tag );
                    if ( isset( $parts[0] ) ) {
                        return (int) str_replace( 'wp_job_manager_chat_', '', $parts[0] );
                    }
                }
            }

            return false;
        }

        public function thread_item( $thread_item, $thread_id, $thread_type, $include_personal, $user_id ){
            if( $thread_type !== 'thread'){
                return $thread_item;
            }

            $wp_job_manager_post_id = $this->is_wp_job_manager_thread( $thread_id );

            if( $wp_job_manager_post_id ){
                $thread_info = '';
                if( isset( $thread_item['threadInfo'] ) ) $thread_info = $thread_item['threadInfo'];
                $thread_info .= $this->thread_info( $wp_job_manager_post_id );
                $thread_item['threadInfo'] = $thread_info;
            }

            return $thread_item;
        }

        public function thread_info( $post_id ){

            $post = get_post( $post_id );
            if( ! $post || $post->post_type !== 'job_listing' ) return '';

            $title = get_the_title( $post_id );
            $url   = get_permalink( $post_id );
            $image = get_the_company_logo( $post_id );

            $company_name = get_post_meta( $post_id, '_company_name', true );
            $company_tagline = get_post_meta( $post_id, '_company_tagline', true );

            $html = '<div class="bm-product-info">';

            if( $image ){
                $html .= '<div class="bm-product-image">';
                $html .= '<a href="' . $url . '" target="_blank"><img src="' . $image . '" alt="' . $title . '" /></a>';
                $html .= '</div>';
            }

            $html .= '<div class="bm-product-details">';
            $html .= '<div class="bm-product-title"><a href="' . $url . '" target="_blank">' . $title . '</a></div>';
            if( $company_name ) $html .= '<div class="bm-product-subtitle">' . $company_name . '</div>';
            if( $company_tagline ) $html .= '<div class="bm-product-subtitle">' . $company_tagline . '</div>';
            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }

        public function single_job_listing_button($return = false )
        {
            global $post;

            if( is_singular('job_listing') ) {
                $vendor_id = $post->post_author;

                $livechat_enabled = ! is_position_filled( $post->ID );

                if( $livechat_enabled ){
                        $subject = esc_attr( sprintf( _x('Question about your listing %s', 'WP Job Manager Integration (Listing page)', 'bp-better-messages'), get_the_title() ) );

                        $shortcode = do_shortcode('[better_messages_live_chat_button
                        type="button"
                        class=""
                        text="' . Better_Messages()->shortcodes->esc_brackets( esc_attr_x('Send Message', 'WP Job Manager Integration (Listing page)', 'bp-better-messages') ) . '"
                        user_id="' . $vendor_id . '"
                        subject="' . Better_Messages()->shortcodes->esc_brackets( $subject ) . '"
                        unique_tag="wp_job_manager_chat_' . get_the_ID() . '"
                        ]');

                        if( $return ){
                            return $shortcode;
                        } else {
                            echo $shortcode;
                        }
                    }
            }

        }
    }
}

