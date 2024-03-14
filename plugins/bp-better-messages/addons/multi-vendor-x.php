<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Better_Messages_MultiVendorX' ) ) {

    class Better_Messages_MultiVendorX
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_MultiVendorX();
            }

            return $instance;
        }

        public function __construct(){
            if( Better_Messages()->settings['MultiVendorXIntegration'] !== '1' ) return;

            add_filter( 'mvx_vendor_dashboard_nav', array( $this, 'vendor_dashboard_nav' ), 20, 1 );
            add_filter( 'mvx_endpoints_query_vars', array( $this, 'endpoints_query_vars' ), 20, 1 );

            add_filter( 'better_messages_rest_thread_item', array( $this, 'thread_item' ), 10, 5 );
            add_filter( 'better_messages_rest_user_item', array( $this, 'vendor_user_meta' ), 20, 3 );
            add_filter( 'bp_better_messages_page', array( $this, 'vendor_messages_page' ), 20, 2 );

            add_action( 'mvx_vendor_add_store', array( $this, 'store_settings_output' ), 10, 1 );
            add_action( 'mvx_save_custom_store', array( $this, 'store_settings_save'), 10, 2 );

            add_action( 'mvx_vendor_dashboard_bm-messages_endpoint', array( $this, 'vendor_messages' ) );
            add_action( 'mvx_frontend_enqueue_scripts', array( $this, 'dashboard_scripts'), 20, 1 );

            add_action( 'woocommerce_single_product_summary',   array( &$this, 'product_page_contact_button' ), 35 );
            add_shortcode( 'better_messages_multivendorx_product_button',   array( &$this, 'product_page_contact_button_shortcode' ) );
            add_shortcode( 'better_messages_multivendorx_store_button',   array( &$this, 'store_page_contact_button_shortcode' ) );

            add_action( 'mvx_additional_button_at_banner', array( $this, 'display_store_button' ), 10, 1 );

        }

        public function vendor_messages_page( $url, $user_id ){

            if( is_user_mvx_vendor( $user_id ) ) {
                $livechat_enabled = $this->is_livechat_enabled($user_id);
                if( $livechat_enabled ){
                    return mvx_get_vendor_dashboard_endpoint_url('messages');
                }
            }
            return $url;
        }

        public function store_page_contact_button_shortcode(){
            return $this->display_store_button( true );
        }

        public function display_store_button( $return = false ){
            $user_id = mvx_find_shop_page_vendor();

            $livechat_enabled = $this->is_livechat_enabled( $user_id );

            if( $livechat_enabled ){
                $shortcode = do_shortcode('[better_messages_live_chat_button
                type="button"
                class="mvx-butn"
                text="' . Better_Messages()->shortcodes->esc_brackets( esc_attr_x( 'Live Chat', 'MultiVendorX Integration (Store page)', 'bp-better-messages' ) ) . '"
                user_id="' . $user_id . '"
                unique_tag="multivendorx_store_chat_' . $user_id . '"
                ]');

                if( $return ) {
                    return $shortcode;
                } else {
                    echo $shortcode;
                }

            }
        }

        public function is_livechat_enabled( $store_id ){
            if( is_user_mvx_vendor( $store_id ) ){
                $meta = get_user_meta($store_id, '_vendor_bm_livechat', true);
                if( $meta === 'disable' ) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return apply_filters('better_messages_multivendorx_store_default', true, $store_id );
            }
        }

        public function store_settings_save( $user_id, $post ){
            if ( $post && isset( $post['vendor_bm_livechat'] ) && !empty( $post['vendor_bm_livechat'] )) {
                update_user_meta($user_id, '_vendor_bm_livechat', wc_clean($post['vendor_bm_livechat']));
            } else {
                update_user_meta($user_id, '_vendor_bm_livechat', 'disable');
            }
        }

        public function store_settings_output( $vendor ){
            $enable_livechat = $this->is_livechat_enabled( $vendor->id );
            ?>
            <div class="form-group">
                <label class="control-label col-sm-3 col-md-3"><?php _ex( 'Live Chats', 'Marketplace Integrations', 'bp-better-messages' ); ?></label>
                <div class="col-md-6 col-sm-9">
                    <ul class="select-store-details">
                        <li>
                            <label>
                                <input type="checkbox" name="vendor_bm_livechat" value="enable" <?php if ($enable_livechat) echo 'checked'; ?>><?php echo esc_html_x( 'Enable live chat in store', 'Marketplace Integrations', 'bp-better-messages'  ); ?>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
        }

        function vendor_user_meta( $item, $user_id, $include_personal ){
            if( is_user_mvx_vendor( $user_id ) && $this->is_livechat_enabled( $user_id ) ){
                $vendor_data = get_mvx_vendor($user_id);

                if( $vendor_data ) {
                    $item['url'] = esc_url( $vendor_data->get_permalink() );
                    $item['avatar'] = esc_url( $vendor_data->get_image() );
                    $item['name'] = esc_attr( $vendor_data->get_page_title() );
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
                if( str_starts_with( $unique_tag, 'multivendorx_product_chat_' ) ){
                    $parts = explode('|', $unique_tag);
                    if( isset( $parts[0] ) ){
                        $product_id = str_replace( 'multivendorx_product_chat_', '', $parts[0]);
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

        public function product_page_contact_button_shortcode(){
            return $this->product_page_contact_button( true );
        }

        public function product_page_contact_button( $return = false )
        {
            global $post;

            if (is_product() && $post && is_object($post)) {
                $seller_id = (int) $post->post_author;

                if( is_user_mvx_vendor( $seller_id ) ){
                    $livechat_enabled = $this->is_livechat_enabled( $seller_id );

                    if( $livechat_enabled ){
                        $product = wc_get_product( get_the_ID() );

                        $subject = esc_attr(sprintf( _x('Question about your product %s', 'MultiVendorX Integration (Product page)', 'bp-better-messages'), $product->get_title() ) );

                        $shortcode = do_shortcode('[better_messages_live_chat_button
                        class="bm-style-btn"
                        text="' . Better_Messages()->shortcodes->esc_brackets(esc_attr_x('Live Chat', 'MultiVendorX Integration (Product page)', 'bp-better-messages') ) . '"
                        user_id="' . $seller_id . '"
                        subject="' . Better_Messages()->shortcodes->esc_brackets($subject ) . '"
                        unique_tag="multivendorx_product_chat_' . get_the_ID() . '"
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

        public function dashboard_scripts( $is_vendor_dashboard ){
            if( $is_vendor_dashboard ){
                if( $this->is_livechat_enabled( get_current_user_id() ) ) {
                    Better_Messages()->enqueue_css(true);


                    wp_add_inline_script('better-messages', 'jQuery(document).ready(function($){
                        var link = jQuery(".mvx-venrod-dashboard-nav-link--bm-messages");
                        link.append( \'' . do_shortcode('[better_messages_unread_counter hide_when_no_messages="1" preserve_space="0"]') . '\' );
                    });' );
                }
            }
        }

        public function vendor_messages(){
            echo '<div class="col-md-12">';
            echo do_shortcode('[better_messages]');
            echo '</div>';
        }
        public function endpoints_query_vars( $endpoints ){
            $endpoints['bm-messages'] = [
                'label' => __('Messages', 'bp-better-messages'),
                'endpoint' => 'messages'
            ];

            return $endpoints;
        }

        public function vendor_dashboard_nav( $nav_items ){
            if( $this->is_livechat_enabled( get_current_user_id() ) ) {
                $nav_items['bm-messages'] = [
                    'label' => 'Messages',
                    'url' => mvx_get_vendor_dashboard_endpoint_url('messages'),
                    'capability' => true,
                    'position' => 29,
                    'submenu' => [],
                    'link_target' => '_self',
                    'nav_icon' => 'mvx-font ico-message'
                ];
            }

            return $nav_items;
        }
    }
}

