<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Better_Messages_HivePress' ) ) {

    class Better_Messages_HivePress
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_HivePress();
            }

            return $instance;
        }

        public function __construct()
        {
            if( Better_Messages()->settings['hivepressIntegration'] !== '1' ) return;

            if ( ! is_admin() ) {
                // Listing page
                add_filter('hivepress/v1/templates/listing_view_page', [$this, 'alter_listing_view_page']);
                add_filter( 'hivepress/v1/templates/vendor_view_block', [ $this, 'alter_vendor_view_block' ] );

                // Vendor Page
                add_filter( 'hivepress/v1/templates/vendor_view_page', [ $this, 'alter_vendor_view_page' ] );
                add_filter( 'hivepress/v1/templates/listing_view_block', [ $this, 'alter_listing_view_block' ] );

                // Booking extension pages
                if ( hivepress()->get_version( 'bookings' ) ) {
                    add_filter( 'hivepress/v1/templates/booking_view_block', [ $this, 'alter_booking_view_block' ] );
                    add_filter( 'hivepress/v1/templates/booking_view_page', [ $this, 'alter_booking_view_page' ] );
                }

                if ( hivepress()->get_version( 'marketplace' ) ) {
                    add_filter( 'hivepress/v1/templates/order_footer_block', [ $this, 'alter_order_footer_block' ] );
                }
            }

            add_action( 'better_messages_render_big_listing_button', [ $this, 'render_big_listing_button' ] );
            add_action( 'better_messages_render_small_listing_button', [ $this, 'render_small_listing_button' ] );
            add_action( 'better_messages_render_big_pm_button', [ $this, 'render_big_pm_button' ] );
            add_action( 'better_messages_render_small_pm_button', [ $this, 'render_small_pm_button' ] );

            add_action( 'better_messages_render_order_button', [ $this, 'render_order_button' ] );

            add_filter('better_messages_rest_thread_item', array( $this, 'thread_item' ), 10, 5 );
            add_filter('better_messages_rest_user_item', array( $this, 'vendor_user_meta' ), 20, 3 );
        }

        function vendor_user_meta( $item, $user_id, $include_personal ){
            if( $user_id <= 0 ) return $item;

            $display_user = get_option( 'hp_user_enable_display' );
            $display_vendor = get_option( 'hp_vendor_enable_display' );

            if( $display_vendor || $display_user ){
                $user = \HivePress\Models\User::query()->get_by_id( $user_id );

                if( $user ){
                    if ( $user->get_image__url( 'thumbnail' ) ) {
                        $item['avatar'] = esc_url($user->get_image__url( 'thumbnail' ));
                    }

                    $vendor = \HivePress\Models\Vendor::query()->filter(
                        [
                            'status' => 'publish',
                            'user' => $user_id,
                        ]
                    )->get_first();

                    if( $vendor && $display_vendor ) {

                        if ($vendor) {
                            $item['url'] = esc_url(hivepress()->router->get_url('vendor_view_page', ['vendor_id' => $vendor->get_id()]));
                            return $item;
                        }
                    }

                    if( ! $vendor && $display_user ){
                        $item['url'] = esc_url( hivepress()->router->get_url( 'user_view_page', [ 'username' => $user->get_username() ] ) );
                        return $item;
                    }
                }
            }

            return $item;
        }

        public function thread_item( $thread_item, $thread_id, $thread_type, $include_personal, $user_id ){
            if( $thread_type !== 'thread'){
                return $thread_item;
            }

            $unique_tag = Better_Messages()->functions->get_thread_meta( $thread_id, 'unique_tag' );

            if( ! empty( $unique_tag ) ){
                if( str_starts_with( $unique_tag, 'hivepress_listing_chat_' ) ){
                    $parts = explode('|', $unique_tag);
                    if( isset( $parts[0] ) ){
                        $listing_id = str_replace( 'hivepress_listing_chat_', '', $parts[0]);
                        $thread_info = '';
                        if( isset( $thread_item['threadInfo'] ) ) $thread_info = $thread_item['threadInfo'];
                        $thread_info .= $this->hivepress_listing_chat_html( $listing_id );
                        $thread_item['threadInfo'] = $thread_info;
                    }
                } else if( hivepress()->get_version( 'bookings' ) && str_starts_with( $unique_tag, 'hivepress_booking_chat_' ) ){
                    $parts = explode('|', $unique_tag);
                    if( isset( $parts[0] ) ){
                        $booking_id = str_replace( 'hivepress_booking_chat_', '', $parts[0]);
                        $thread_info = '';
                        if( isset( $thread_item['threadInfo'] ) ) $thread_info = $thread_item['threadInfo'];
                        $thread_info .=  $this->hivepress_booking_chat_html( $booking_id );
                        $thread_item['threadInfo'] = $thread_info;
                    }
                }
            }

            return $thread_item;
        }

        public function hivepress_booking_chat_html( $booking_id ){
            $booking = \HivePress\Models\Booking::query()->get_by_id( $booking_id );
            if( ! $booking ) return '';

            $listing = $booking->get_listing();
            if( ! $listing ) return '';

            $title    = esc_html( $listing->get_title() );
            $url      = get_permalink( $listing->get_id() );
            $image_id = get_post_thumbnail_id( $listing->get_id() );

            $html = '<div class="bm-product-info">';

            if( $image_id ){
                $image_src = wp_get_attachment_image_src( $image_id, [100, 100] );
                if( $image_src ){
                    $image = $image_src[0];
                    $html .= '<div class="bm-product-image">';
                    $html .= '<a href="' . $url . '" target="_blank"><img src="' . $image . '" alt="' . $title . '" /></a>';
                    $html .= '</div>';
                }
            }

            $html .= '<div class="bm-product-details">';
            $html .= '<div class="bm-product-title"><a href="' . $url . '" target="_blank">' . $title . '</a></div>';

            $booking_url =  esc_url(hivepress()->router->get_url('booking_view_page', ['booking_id' => $booking->get_id()])); ;
            $html .= '<div class="bm-product-subtitle"><a href="' . $booking_url . '" target="_blank">' . sprintf( esc_html__( 'Booking %s', 'hivepress-bookings' ), $booking->get_title() ) . ' (' . $booking->display_dates() . ')</a></div>';

            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }

        public function hivepress_listing_chat_html( $listing_id ){
            $listing = \HivePress\Models\Listing::query()->get_by_id( $listing_id );
            if( ! $listing ) return '';

            $title    = esc_html($listing->get_title());
            $url      = get_permalink( $listing_id );
            $image_id = get_post_thumbnail_id( $listing_id );
            $price = false;

            $html = '<div class="bm-product-info">';

            if( $image_id ){
                $image_src = wp_get_attachment_image_src( $image_id, [100, 100] );
                if( $image_src ){
                    $image = $image_src[0];
                    $html .= '<div class="bm-product-image">';
                    $html .= '<a href="' . $url . '" target="_blank"><img src="' . $image . '" alt="' . $title . '" /></a>';
                    $html .= '</div>';
                }
            }

            $html .= '<div class="bm-product-details">';
            $html .= '<div class="bm-product-title"><a href="' . $url . '" target="_blank">' . $title . '</a></div>';

            $listing_fields = $listing->_get_fields();

            if ( isset( $listing_fields['price'] ) ) {
                $price = $listing_fields['price']->display();
            }
            if( $price ){
                $html .= '<div class="bm-product-price">' . $price . '</div>';
            }

            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }

        public function alter_booking_view_block( $template ) {
            return hivepress()->helper->merge_trees(
                $template,
                [
                    'blocks' => [
                        'booking_actions_primary' => [
                            'blocks' => [
                                'bm_message_send_link'  => [
                                    'type'     => 'callback',
                                    'callback' => 'do_action',
                                    'params'   => [ 'better_messages_render_small_listing_button' ],
                                    '_order' => 10,
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        public function alter_booking_view_page( $template ) {
            return hivepress()->helper->merge_trees(
                $template,
                [
                    'blocks' => [
                        'booking_actions_primary' => [
                            'blocks' => [
                                'bm_message_send_link'  => [
                                    'type'     => 'callback',
                                    'callback' => 'do_action',
                                    'params'   => [ 'better_messages_render_big_listing_button' ],
                                    '_order' => 10,
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        public function alter_order_footer_block( $template ) {
            return hivepress()->helper->merge_trees(
                $template,
                [
                    'blocks' => [
                        'order_actions_primary' => [
                            'blocks' => [
                                'bm_message_send_link'  => [
                                    'type'   => 'callback',
                                    'callback' => 'do_action',
                                    'params'   => [ 'better_messages_render_order_button' ],
                                    '_order' => 10,
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        public function alter_listing_view_page( $template ) {
            return hivepress()->helper->merge_trees(
                $template,
                [
                    'blocks' => [
                        'listing_actions_primary' => [
                            'blocks' => [
                                'bm_message_send_link'  => [
                                    'type'     => 'callback',
                                    'callback' => 'do_action',
                                    'params'   => [ 'better_messages_render_big_listing_button' ],
                                    '_order' => 10,
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        public function alter_vendor_view_page( $template ) {
            return hivepress()->helper->merge_trees(
                $template,
                [
                    'blocks' => [
                        'vendor_actions_primary' => [
                            'blocks' => [
                                'bm_message_send_link'  => [
                                    'type'     => 'callback',
                                    'callback' => 'do_action',
                                    'params'   => [ 'better_messages_render_big_pm_button' ],
                                    '_order' => 10,
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        public function alter_vendor_view_block( $template ) {
            return hivepress()->helper->merge_trees(
                $template,
                [
                    'blocks' => [
                        'vendor_actions_primary' => [
                            'blocks' => [
                                'bm_message_send_link'  => [
                                    'type'     => 'callback',
                                    'callback' => 'do_action',
                                    'params'   => [ 'better_messages_render_small_pm_button' ],
                                    '_order' => 10,
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        public function alter_listing_view_block( $template ){
            return hivepress()->helper->merge_trees(
                $template,
                [
                    'blocks' => [
                        'listing_actions_primary' => [
                            'blocks' => [
                                'bm_message_send_link'  => [
                                    'type'     => 'callback',
                                    'callback' => 'do_action',
                                    'params'   => [ 'better_messages_render_small_listing_button' ],
                                    '_order' => 10,
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }

        public function render_small_listing_button(){

            $btn_label = esc_attr_x('Send Message', 'HivePress Integration (Private Message)', 'bp-better-messages');

            if( ! is_user_logged_in() && ! Better_Messages()->guests->guest_access_enabled() ){
                echo '<a href="#user_login_modal" class="hp-listing__action hp-listing__action--bm-message" title="' . $btn_label . '"><span class="bm-button-text"><i class="hp-icon fas fa-comment"></i></span></a>';
            } else {
                $listing_id = get_the_ID();
                $current_user_id = Better_Messages()->functions->get_current_user_id();
                $user_id = (int) get_the_author_meta('ID');
                $post_type = get_post_type();
                $subject = esc_attr( sprintf( _x('Question about listing "%s"', 'HivePress Integration (Listing page)', 'bp-better-messages'), get_the_title()));

                $unique_tag = 'hivepress_listing_chat_' . $listing_id;

                if( $post_type === 'hp_booking' ){
                    $booking_id = get_the_ID();
                    $listing = get_post_parent();
                    if( $listing ) {
                        $subject = esc_attr(sprintf( _x('Question about booking "%s" of "%s"', 'HivePress Integration (Booking item)', 'bp-better-messages'), get_the_title($booking_id), get_the_title( $listing )));

                        $unique_tag = 'hivepress_booking_chat_' . $booking_id;
                        if( Better_Messages()->functions->get_current_user_id() === $user_id ){
                            $user_id = (int) $listing->post_author;
                        }
                    }
                }

                echo do_shortcode('[better_messages_live_chat_button
                type="link"
                alt="' . Better_Messages()->shortcodes->esc_brackets( $btn_label ) . '"
                class="hp-listing__action hp-listing__action--bm-message"
                subject="' . Better_Messages()->shortcodes->esc_brackets( $subject ) . '"
                text="<i class=\'hp-icon fas fa-comment\'></i>"
                user_id="' . $user_id . '"
                unique_tag="' . $unique_tag . '"
                ]');
            }
        }

        public function render_order_button()
        {
            $hp_order = hivepress()->request->get_context( 'order' );

            if( $hp_order ){
                $order_id = $hp_order->get_id();
                $order = wc_get_order( $order_id );

                if( $order ){
                    if ( $order->get_status() === 'processing' ) :
                        $buyer_id = $hp_order->get_buyer__id();

                        $subject = esc_attr( sprintf( _x('Question about order #%d', 'HivePress Integration (Order Page)', 'bp-better-messages'), $order_id) );

                        if ( get_current_user_id() === $buyer_id ) {
                            $btn_label = esc_attr(hivepress()->translator->get_string('contact_seller'));
                            $user_id = $hp_order->get_seller__id();
                        } else {
                            $btn_label = esc_attr(hivepress()->translator->get_string('contact_buyer'));
                            $user_id = $buyer_id;
                        }

                        $unique_tag = 'hivepress_order_chat_' . $order_id;

                        echo do_shortcode('[better_messages_live_chat_button
                        type="button"
                        class="hp-order__action hp-order__action--bm-message button button--primary alt"
                        text="' . Better_Messages()->shortcodes->esc_brackets( $btn_label ) . '"
                        user_id="' . $user_id . '"
                        subject="' . Better_Messages()->shortcodes->esc_brackets( $subject ) . '"
                        unique_tag="' . $unique_tag . '"
                        ]');
                    endif;
                }
            }

        }

        public function render_big_listing_button(){
            $btn_label = esc_attr_x('Send Message', 'HivePress Integration (Reply To Listing)', 'bp-better-messages');

            if( ! is_user_logged_in() && ! Better_Messages()->guests->guest_access_enabled() ){
                echo '<button type="button" class="hp-listing__action hp-listing__action--bm-message button button--large button--primary alt" data-component="link" data-url="#user_login_modal">' . $btn_label . '</button>';
            } else {
                $listing_id = get_the_ID();
                $current_user_id = Better_Messages()->functions->get_current_user_id();
                $user_id = (int) get_the_author_meta('ID');
                $subject = esc_attr( sprintf( _x('Question about your listing "%s"', 'HivePress Integration (Product page)', 'bp-better-messages'), get_the_title() ) );

                $unique_tag = 'hivepress_listing_chat_' . $listing_id;

                $booking = hivepress()->request->get_context( 'booking' );
                if( $booking ){
                    $booking_id = $booking->get_id();
                    $user = $booking->get_user();
                    $user_id = $user->get_id();

                    $listing = get_post_parent($booking_id);
                    if( $listing ) {
                        $subject = esc_attr( sprintf( _x('Question about booking "%s" of "%s"', 'HivePress Integration (Booking item)', 'bp-better-messages'), get_the_title($booking_id), get_the_title( $listing ) ) );

                        $unique_tag = 'hivepress_booking_chat_' . $booking_id;
                        if( Better_Messages()->functions->get_current_user_id() === $user_id ){
                            $user_id = (int) $listing->post_author;
                        }
                    }
                }


                echo do_shortcode('[better_messages_live_chat_button
                type="button"
                class="hp-listing__action hp-listing__action--bm-message button button--large button--primary alt"
                text="' . Better_Messages()->shortcodes->esc_brackets( $btn_label ) . '"
                user_id="' . $user_id . '"
                subject="' . Better_Messages()->shortcodes->esc_brackets( $subject ) . '"
                unique_tag="' . $unique_tag . '"
                ]');
            }
        }

        public function render_small_pm_button(){

            $btn_label = esc_attr_x('Send Message', 'HivePress Integration (Private Message)', 'bp-better-messages');

            if( ! is_user_logged_in() && ! Better_Messages()->guests->guest_access_enabled() ){
                echo '<a href="#user_login_modal" class="bm-lc-button hp-vendor__action hp-vendor__action--bm-message" title="' . $btn_label . '"><span class="bm-button-text"><i class="hp-icon fas fa-comment"></i></span></a>';
            } else {

                echo do_shortcode('[better_messages_live_chat_button
                type="link"
                alt="' . Better_Messages()->shortcodes->esc_brackets( $btn_label ) . '"
                class="hp-vendor__action hp-vendor__action--bm-message"
                text="<i class=\'hp-icon fas fa-comment\'></i>"
                user_id="' . get_the_author_meta('ID') . '"
                unique_tag="hivepress_vendor_chat_' . get_the_author_meta('ID') . '"
                ]');
            }
        }

        public function render_big_pm_button(){
            $btn_label = esc_attr_x('Send Message', 'HivePress Integration (Private Message)', 'bp-better-messages');

            if( ! is_user_logged_in() && ! Better_Messages()->guests->guest_access_enabled() ){
                echo '<button type="button" class="hp-vendor__action hp-vendor__action--bm-message button button--large button--primary alt" data-component="link" data-url="#user_login_modal">' . $btn_label . '</button>';
            } else {
                echo do_shortcode('[better_messages_live_chat_button
                type="button"
                class="hp-vendor__action hp-vendor__action--bm-message button button--large button--primary alt"
                text="' . Better_Messages()->shortcodes->esc_brackets( $btn_label ) . '"
                user_id="' . get_the_author_meta('ID') . '"
                unique_tag="hivepress_vendor_chat_' . get_the_author_meta('ID') . '"
                ]');
            }
        }
    }
}
