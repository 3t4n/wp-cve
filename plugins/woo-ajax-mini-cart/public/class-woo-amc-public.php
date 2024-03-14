<?php

class WooAmcPublic {

    /**
     * The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {

        $options = get_option('woo_amc_options');
        $enabled = isset( $options['enabled']) ? $options['enabled'] : 1;
        if( ( is_cart() || is_checkout() ) && $enabled != 1 ){return;}

        wp_enqueue_style( 'perfect-scrollbar', plugin_dir_url( __FILE__ ) . 'css/perfect-scrollbar.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-amc-public.css', array(), $this->version, 'all' );

        $inline_css = $this->get_inline_css();
        wp_add_inline_style( $this->plugin_name, $inline_css );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueue_scripts() {

        $options = get_option('woo_amc_options');
        $enabled = isset( $options['enabled']) ? $options['enabled'] : 1;
        if( ( is_cart() || is_checkout() ) && $enabled != 1 ){return;}
        $options = get_option('woo_amc_options');

        wp_enqueue_script( 'perfect-scrollbar', plugin_dir_url( __FILE__ ) . 'js/perfect-scrollbar.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-amc-public.js', array( 'jquery' ), $this->version, false );

        wp_localize_script( $this->plugin_name, 'wooAmcVars', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'woo-amc-security' ),
                'cart_type' => $options['cart_type'],
            )
        );

    }

    private function get_inline_css(){
        $css = get_option('woo_amc_options');

        //print_r($css);

        $button_icon_color  = isset( $css['button_icon_color'] ) ? $css['button_icon_color'] : 'red';
        $button_bg_color    = isset( $css['button_bg_color'] ) ? $css['button_bg_color'] : 'red';
        $button_border_radius   = isset( $css['button_border_radius'] ) ? $css['button_border_radius'] : 2;
        $button_count_bg    = isset( $css['button_count_bg'] ) ? $css['button_count_bg'] : 'red';
        $button_count_color     = isset( $css['button_count_color'] ) ? $css['button_count_color'] : 'red';
        $bg_color = isset( $css['bg_color'] ) ? $css['bg_color'] : 'red';
        $bg_opacity = isset( $css['bg_opacity'] ) ? $css['bg_opacity'] : 60;
        $bg_opacity = $bg_opacity/100;
        $cart_bg    = isset( $css['cart_bg'] ) ? $css['cart_bg'] : 'red';
        $cart_loader_color  = isset( $css['cart_loader_color'] ) ? $css['cart_loader_color'] : 'red';
        $cart_header_bg     = isset( $css['cart_header_bg'] ) ? $css['cart_header_bg'] : 'red';
        $cart_header_title_size     = isset( $css['cart_header_title_size'] ) ? $css['cart_header_title_size'] : 36;
        $cart_header_title_color    = isset( $css['cart_header_title_color'] ) ? $css['cart_header_title_color'] : 'red';
        $cart_header_close_color    = isset( $css['cart_header_close_color'] ) ? $css['cart_header_close_color'] : 'red';
        $cart_item_bg   = isset( $css['cart_item_bg'] ) ? $css['cart_item_bg'] : 'red';
        $cart_item_border_width     = isset( $css['cart_item_border_width'] ) ? $css['cart_item_border_width'] : 2;
        $cart_item_border_color     = isset( $css['cart_item_border_color'] ) ? $css['cart_item_border_color'] : 'red';
        $cart_item_border_radius    = isset( $css['cart_item_border_radius'] ) ? $css['cart_item_border_radius'] : 2;
        $cart_item_padding  = isset( $css['cart_item_padding'] ) ? $css['cart_item_padding'] : 2;
        $cart_item_close_color  = isset( $css['cart_item_close_color'] ) ? $css['cart_item_close_color'] : 'red';
        $cart_item_title_color  = isset( $css['cart_item_title_color'] ) ? $css['cart_item_title_color'] : 'red';
        $cart_item_title_size   = isset( $css['cart_item_title_size'] ) ? $css['cart_item_title_size'] : 36;
        $cart_item_text_color   = isset( $css['cart_item_text_color'] ) ? $css['cart_item_text_color'] : 'red';
        $cart_item_text_size    = isset( $css['cart_item_text_size'] ) ? $css['cart_item_text_size'] : 36;
        $cart_item_old_price_color  = isset( $css['cart_item_old_price_color'] ) ? $css['cart_item_old_price_color'] : 'red';
        $cart_item_price_color  = isset( $css['cart_item_price_color'] ) ? $css['cart_item_price_color'] : 'red';
        $cart_item_quantity_buttons_color   = isset( $css['cart_item_quantity_buttons_color'] ) ? $css['cart_item_quantity_buttons_color'] : 'red';
        $cart_item_quantity_color   = isset( $css['cart_item_quantity_color'] ) ? $css['cart_item_quantity_color'] : 'red';
        $cart_item_quantity_bg  = isset( $css['cart_item_quantity_bg'] ) ? $css['cart_item_quantity_bg'] : 'red';
        $cart_item_quantity_border_radius   = isset( $css['cart_item_quantity_border_radius'] ) ? $css['cart_item_quantity_border_radius'] : 2;
        $cart_item_big_price_size   = isset( $css['cart_item_big_price_size'] ) ? $css['cart_item_big_price_size'] : 36;
        $cart_item_big_price_color  = isset( $css['cart_item_big_price_color'] ) ? $css['cart_item_big_price_color'] : 'red';
        $cart_footer_bg     = isset( $css['cart_footer_bg'] ) ? $css['cart_footer_bg'] : 'red';
        $cart_footer_products_size  = isset( $css['cart_footer_products_size'] ) ? $css['cart_footer_products_size'] : 36;
        $cart_footer_products_label_color   = isset( $css['cart_footer_products_label_color'] ) ? $css['cart_footer_products_label_color'] : 'red';
        $cart_footer_products_count_color   = isset( $css['cart_footer_products_count_color'] ) ? $css['cart_footer_products_count_color'] : 'red';
        $cart_footer_total_size     = isset( $css['cart_footer_total_size'] ) ? $css['cart_footer_total_size'] : 36;
        $cart_footer_total_label_color  = isset( $css['cart_footer_total_label_color'] ) ? $css['cart_footer_total_label_color'] : 'red';
        $cart_footer_total_price_color  = isset( $css['cart_footer_total_price_color'] ) ? $css['cart_footer_total_price_color'] : 'red';
        $cart_footer_link_size  = isset( $css['cart_footer_link_size'] ) ? $css['cart_footer_link_size'] : 36;
        $cart_footer_link_color     = isset( $css['cart_footer_link_color'] ) ? $css['cart_footer_link_color'] : 'red';

        $css = "
            .woo_amc_open{
                background: {$button_bg_color};
                border-radius: {$button_border_radius}px;
            }
            .woo_amc_open path{
                fill: {$button_icon_color};
            }
            .woo_amc_open_count{
                background: {$button_count_bg};
                color: {$button_count_color};
            }
            .woo_amc_bg:after{
                background: {$bg_color};
                opacity: {$bg_opacity};
            }
            .woo_amc_container{
                background: {$cart_bg};
            }
            .lds-spinner div:after{
                background: {$cart_loader_color};
            }
            .woo_amc_head{
                background: {$cart_header_bg};
            }
            .woo_amc_head_title{
                font-size: {$cart_header_title_size}px;
                color: {$cart_header_title_color};
            }
            .woo_amc_close line{
                stroke: $cart_header_close_color;
            }
            .woo_amc_item_delete line{
                stroke: {$cart_item_close_color};
            }
            .woo_amc_item_wrap{
                color: {$cart_item_text_color};
                font-size: {$cart_item_text_size}px;
                background: {$cart_item_bg};
                border: {$cart_item_border_width}px solid {$cart_item_border_color};
                border-radius: {$cart_item_border_radius}px;
                padding: {$cart_item_padding}px;
            }
            .woo_amc_item_title a{
                color: {$cart_item_title_color};
                font-size: {$cart_item_title_size}px;
            }
            .woo_amc_item_price_wrap del .woocommerce-Price-amount.amount{
                color: {$cart_item_old_price_color};
            }
            .woo_amc_item_price_wrap .woocommerce-Price-amount.amount{
                color: {$cart_item_price_color};
            }
            .woo_amc_item_quanity_minus line, .woo_amc_item_quanity_plus line{
                stroke: {$cart_item_quantity_buttons_color};
                fill: none;
            }
            input.woo_amc_item_quanity, input.woo_amc_item_quanity:focus{
                color: {$cart_item_quantity_color};
                background: {$cart_item_quantity_bg};
                border-radius: {$cart_item_quantity_border_radius}px;
                font-size: {$cart_item_text_size}px;
            }
            .woo_amc_item_total_price{
                color: {$cart_item_big_price_color};
                font-size: {$cart_item_big_price_size}px;
            }
            .woo_amc_footer{
                background: {$cart_footer_bg};
            }
            .woo_amc_footer_products{
                font-size: {$cart_footer_products_size}px;
            }
            .woo_amc_footer_products .woo_amc_label{
                color: {$cart_footer_products_label_color};
            }
            .woo_amc_footer_products .woo_amc_value{
                color: {$cart_footer_products_count_color};
            }
            .woo_amc_footer_total{
                font-size: {$cart_footer_total_size}px;
            }
            .woo_amc_footer_total .woo_amc_label{
                color: {$cart_footer_total_label_color};
            }
            .woo_amc_footer_total .woo_amc_value{
                color: {$cart_footer_total_price_color};
            }
            .woo_amc_footer_link{
                font-size: {$cart_footer_link_size}px;
                color: {$cart_footer_link_color};
            }
        ";


        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // backup values within single or double quotes
        preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
        for ($i=0; $i < count($hit[1]); $i++) {
            $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
        }
        // remove traling semicolon of selector's last property
        $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);
        // remove any whitespace between semicolon and property-name
        $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);
        // remove any whitespace surrounding property-colon
        $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);
        // remove any whitespace surrounding selector-comma
        $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);
        // remove any whitespace surrounding opening parenthesis
        $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);
        // remove any whitespace between numbers and units
        $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);
        // shorten zero-values
        $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
        // constrain multiple whitespaces
        $css = preg_replace('/\p{Zs}+/ims',' ', $css);
        // remove newlines
        $css = str_replace(array("\r\n", "\r", "\n"), '', $css);
        // Restore backupped values within single or double quotes
        for ($i=0; $i < count($hit[1]); $i++) {
            $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
        }


        return $css;
    }


    /**
     * Get Cart HTML
     */

    public function get_cart_templates(){
        $options = get_option('woo_amc_options');
        $enabled = isset( $options['enabled']) ? $options['enabled'] : 1;
        if( ( is_cart() || is_checkout() ) && $enabled != 1 ){return;}
        if($options['cart_type']=='center'){
            $template_type = 'center';
            $template_type_items = 'items_center';
        } else {
            $template_type = 'side';
            $template_type_items = 'items_side';
        }

        $custom_templates_path = get_stylesheet_directory().'/woocommerce-ajax-mini-cart';

        if ( file_exists( $custom_templates_path.'/'.$template_type_items.'.php' ) ) {
            $template_items_path = $custom_templates_path.'/'.$template_type_items.'.php';
        } else {
            $template_items_path = plugin_dir_path( dirname( __FILE__ ) ).'templates/'.$template_type_items.'.php';
        }
        
        $cart_count = WC()->cart->cart_contents_count;
        $items = WC()->cart->get_cart();
        $cart_total = WC()->cart->get_cart_total();

        if ( file_exists( $custom_templates_path.'/'.$template_type.'.php' ) ) {
            require_once $custom_templates_path.'/'.$template_type.'.php';
        } else {
            require_once plugin_dir_path( dirname( __FILE__ ) ).'templates/'.$template_type.'.php';
        }

        if ( file_exists( $custom_templates_path.'/button.php' ) ) {
            require_once $custom_templates_path.'/button.php';
        } else {
            require_once plugin_dir_path( dirname( __FILE__ ) ).'templates/button.php';
        }
            
    }

    /**
     * Show Cart Items HTML
     */
    public function show_cart_items_html(){
        $type = sanitize_text_field($_POST['type']);
        $cart = array(
            'html' => 0,
            'count' => 0,
            'total' => 0,
        );

        if ($type) {
            $items = WC()->cart->get_cart();
            if ($type == 'center') {
                $template_type_items = 'items_center';
            } else {
                $template_type_items = 'items_side';
            }
            ob_start();
            include(plugin_dir_path(dirname(__FILE__)) . 'templates/' . $template_type_items . '.php');
            $output = ob_get_contents();
            ob_end_clean();
            $cart['html'] = $output;
            $cart['count'] = WC()->cart->cart_contents_count;
            $cart['total'] = WC()->cart->get_cart_total();
            $cart['nonce'] = wp_create_nonce( 'woo-amc-security' );
        }
        
        echo json_encode($cart);
        wp_die();
    }

    /**
     * Delete Cart Item
     */
    public function delete_cart_item(){
        $key = sanitize_text_field($_POST['key']);
        $cart = array(
            'count' => 0,
            'total' => 0,
        );
        if ($key && wp_verify_nonce( $_POST['security'], 'woo-amc-security' )){
            WC()->cart->remove_cart_item($key);
            $cart = array();
            $cart['count'] = WC()->cart->cart_contents_count;
            $cart['total'] = WC()->cart->get_cart_total();
        }
        echo json_encode( $cart );
        wp_die();
    }

    /**
     * Quanity update
     */
    public function quanity_update(){
        $key = sanitize_text_field($_POST['key']);
        $number = intval(sanitize_text_field($_POST['number']));
        $cart = array(
            'count' => 0,
            'total' => 0,
            'item_price' => 0,
        );
        if($key && $number>0 && wp_verify_nonce( $_POST['security'], 'woo-amc-security' )){
            WC()->cart->set_quantity( $key, $number );
            $items = WC()->cart->get_cart();
            $cart = array();
            $cart['count'] = WC()->cart->cart_contents_count;
            $cart['total'] = WC()->cart->get_cart_total();
            $cart['item_price'] = wc_price($items[$key]['line_total']);
        }
        echo json_encode( $cart );
        wp_die();
    }

    /**
     * Add To Cart
     */
    public function add_to_cart(){
        WC_AJAX::get_refreshed_fragments();
        wp_die();
    }

    /**
     * Remove Added to Cart Notice
     */
    public function remove_added_to_cart_notice(){
        return false;
    }

}
