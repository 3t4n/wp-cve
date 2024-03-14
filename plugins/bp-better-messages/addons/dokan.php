<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Better_Messages_Dokan' ) ) {

    class Better_Messages_Dokan
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_Dokan();
            }

            return $instance;
        }

        public function __construct(){
            if( Better_Messages()->settings['dokanIntegration'] !== '1' ) return;

            add_action( 'woocommerce_single_product_summary',   array( &$this, 'product_page_contact_button' ), 35 );
            add_shortcode( 'better_messages_dokan_product_button',   array( &$this, 'product_page_contact_button_shortcode' ) );

            add_action( 'dokan_settings_before_store_email', array( $this, 'store_settings_output' ), 10, 2 );
            add_filter( 'dokan_store_profile_settings_args', array( $this, 'store_settings_save' ), 10, 2 );

            add_action( 'dokan_after_store_tabs', array( $this, 'display_store_button' ), 10, 1 );

            add_filter('better_messages_rest_thread_item', array( $this, 'thread_item' ), 10, 5 );
            add_filter('better_messages_rest_user_item', array( $this, 'vendor_user_meta' ), 20, 3 );
            add_filter('bp_better_messages_page', array( $this, 'vendor_messages_page' ), 20, 2 );

            add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokan_get_dashboard_nav' ), 10, 1 );
            add_filter( 'dokan_query_var_filter', array( $this, 'dokan_add_endpoint' ) );
            add_action( 'dokan_load_custom_template', array( $this, 'dokan_load_inbox_template' ), 22 );
        }

        public function vendor_messages_page( $url, $user_id ){
            if( dokan_is_user_seller( $user_id ) ) {
                $livechat_enabled = $this->is_livechat_enabled($user_id);
                if( $livechat_enabled ){
                    return dokan_get_navigation_url('messages');
                }
            }
            return $url;
        }

        public function dokan_load_inbox_template( $query_vars ) {
            if ( ! isset( $query_vars['messages'] ) ) {
                return;
            }

            do_action( 'dokan_dashboard_wrap_start' ); ?>
            <style type="text/css">
                .dokan-dashboard-content .bp-messages-wrap{
                    border-radius: 0 !important;
                }

                @media (min-width: 768px) {
                    .dokan-dashboard-content .bp-messages-wrap{
                        border-left: 0;
                    }
                }
                @media (max-width: 767px) {
                    .dokan-dashboard-content .bp-messages-wrap{
                        border-top: 0;
                    }
                }
            </style>
            <div class="dokan-dashboard-wrap">
                <?php

                /**
                 *  dokan_dashboard_content_before hook
                 *
                 *  @hooked get_dashboard_side_navigation
                 *
                 *  @since 2.4
                 */
                do_action( 'dokan_dashboard_content_before' );
                ?>

                <div class="dokan-dashboard-content" style="padding: 0;border: 0">

                    <?php

                    /**
                     *  dokan_chat_content_inside_before hook
                     *
                     *  @hooked show_seller_dashboard_notice
                     *
                     *  @since 2.4
                     */
                    do_action( 'dokan_chat_content_inside_before' );
                    ?>

                    <?php echo do_shortcode( '[better_messages]' ); ?>

                    <?php

                    /**
                     *  dokan_chat_content_inside_after hook
                     *
                     *  @since 2.4
                     */
                    do_action( 'dokan_chat_content_inside_after' );
                    ?>


                </div><!-- .dokan-dashboard-content -->

                <?php

                /**
                 *  dokan_dashboard_content_after hook
                 *
                 *  @since 2.4
                 */
                do_action( 'dokan_dashboard_content_after' );
                ?>

            </div><!-- .dokan-dashboard-wrap -->

            <?php do_action( 'dokan_dashboard_wrap_end' );
        }

        public function dokan_get_dashboard_nav( $nav_menus ){
            if( $this->is_livechat_enabled( get_current_user_id() ) ){
                $nav_menus['messages'] = array(
                    'title' => _x('Live Chat', 'Marketplace Integrations', 'bp-better-messages') . ' ' . do_shortcode('[better_messages_unread_counter hide_when_no_messages="1" preserve_space="0"]'),
                    'icon' => '<i class="fas fa-comment"></i>',
                    'url' => dokan_get_navigation_url('messages'),
                    'pos' => 100,
                );
            }

            return $nav_menus;
        }

        public function dokan_add_endpoint( $query_var ) {
            $query_var['messages'] = 'messages';

            return $query_var;
        }

        function vendor_user_meta( $item, $user_id, $include_personal ){
            if( dokan_is_user_seller( $user_id ) && dokan_is_seller_enabled( $user_id ) ){
                $store_user    = dokan()->vendor->get( $user_id );

                $item['url'] = esc_url( $store_user->get_shop_url() );
                $item['avatar'] = esc_url( $store_user->get_avatar() );
                $item['name'] = esc_attr( $store_user->get_shop_name() );
            }

            return $item;
        }

        public function thread_item( $thread_item, $thread_id, $thread_type, $include_personal, $user_id ){
            if( $thread_type !== 'thread'){
                return $thread_item;
            }

            $unique_tag = Better_Messages()->functions->get_thread_meta( $thread_id, 'unique_tag' );

            if( ! empty( $unique_tag ) ){
                if( str_starts_with( $unique_tag, 'dokan_product_chat_' ) ){
                    $parts = explode('|', $unique_tag);
                    if( isset( $parts[0] ) ){
                        $product_id = str_replace( 'dokan_product_chat_', '', $parts[0]);
                        $thread_info = '';
                        if( isset( $thread_item['threadInfo'] ) ) $thread_info = $thread_item['threadInfo'];
                        $thread_info .= $this->thread_info( $product_id );
                        $thread_item['threadInfo'] = $thread_info;
                    }
                }
            }

            return $thread_item;
        }

        public function thread_info( $product_id ){
            if( ! function_exists('wc_get_product') ) return '';

            $product = wc_get_product( $product_id );
            if( ! $product ) return '';

            $image_id = $product->get_image_id();
            $image_src = wp_get_attachment_image_src( $image_id, [100, 100] );

            $image         = false;
            $title         = $product->get_title();
            $url           = $product->get_permalink();
            $price         = $product->get_price_html();

            if( $image_src ){
                $image = $image_src[0];
            }


            $html = '<div class="bm-product-info">';

            if( $image ){
                $html .= '<div class="bm-product-image">';
                $html .= '<a href="' . $url . '" target="_blank"><img src="' . $image . '" alt="' . $title . '" /></a>';
                $html .= '</div>';
            }

            $html .= '<div class="bm-product-details">';
            $html .= '<div class="bm-product-title"><a href="' . $url . '" target="_blank">' . $title . '</a></div>';
            $html .= '<div class="bm-product-price">' . $price . '</div>';
            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }

        public function display_store_button( $user_id ){
            $livechat_enabled = $this->is_livechat_enabled( $user_id );

            if( $livechat_enabled ){
                ?>
                <li class="dokan-store-support-btn-wrap dokan-right">
                    <?php echo do_shortcode('[better_messages_live_chat_button
                    type="button"
                    class="dokan-btn dokan-btn-theme dokan-btn-sm"
                    text="' . Better_Messages()->shortcodes->esc_brackets( esc_attr_x( 'Live Chat', 'Dokan Integration (Store page)', 'bp-better-messages' ) ). '"
                    user_id="' . $user_id . '"
                    unique_tag="dokan_store_chat_' . $user_id . '"
                    ]'); ?>
                </li>
                <?php
            }
        }

        public function product_page_contact_button_shortcode(){
            return $this->product_page_contact_button( true );
        }

        public function product_page_contact_button( $return = false ){
            global $post;

            if( is_product() && $post && is_object( $post ) ) {
                $seller_id = $post->post_author;

                if( dokan_is_user_seller( $seller_id ) ){
                    $livechat_enabled = $this->is_livechat_enabled( $seller_id );
                    if( $livechat_enabled ){
                        $product = wc_get_product( get_the_ID() );

                        $subject = esc_attr( sprintf( _x('Question about your product %s', 'Dokan Integration (Product page)', 'bp-better-messages'), $product->get_title() ) );

                        $shortcode = do_shortcode('[better_messages_live_chat_button
                        type="button"
                        class="dokan-btn dokan-btn-theme dokan-btn-sm"
                        text="' . esc_attr_x('Live Chat', 'Dokan Integration (Product page)', 'bp-better-messages') . '"
                        user_id="' . $seller_id . '"
                        subject="' . Better_Messages()->shortcodes->esc_brackets( $subject ) . '"
                        unique_tag="dokan_product_chat_' . get_the_ID() . '"
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

        public function is_livechat_enabled( $store_id ){
            $store_info = dokan_get_store_info( $store_id );

            if( isset($store_info['bm_livechat']) ){
                if( $store_info['bm_livechat'] === 'no' ) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return apply_filters('better_messages_dokan_store_default', true, $store_id );
            }
        }

        public function store_settings_save( $dokan_settings, $store_id ){
            if( isset( $_POST['setting_bm_livechat'] ) && $_POST['setting_bm_livechat'] === 'no' ){
                $dokan_settings['bm_livechat'] = 'no';
            } else {
                $dokan_settings['bm_livechat'] = 'yes';
            }

            return $dokan_settings;
        }

        public function store_settings_output( $user_id, $profile_info ){
            $enable_livechat = $this->is_livechat_enabled( $user_id );
            ?>
            <div class="dokan-form-group">
                <label class="dokan-w3 dokan-control-label"><?php _ex( 'Live Chats', 'Marketplace Integrations', 'bp-better-messages' ); ?></label>
                <div class="dokan-w5 dokan-text-left">
                    <div class="checkbox">
                        <label>
                            <input type="hidden" name="setting_bm_livechat" value="no">
                            <input type="checkbox" name="setting_bm_livechat" value="yes" <?php checked( $enable_livechat ); ?>> <?php echo esc_html_x( 'Enable live chat in store', 'Marketplace Integrations', 'bp-better-messages'  ); ?>
                        </label>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
