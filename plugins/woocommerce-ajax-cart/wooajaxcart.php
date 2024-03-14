<?php
/*
Plugin Name: WooCommerce AJAX Cart
Plugin URI: https://wordpress.org/plugins/woocommerce-ajax-cart/
Description: Change the default behavior of WooCommerce Cart page, making AJAX requests when quantity field changes
Version: 1.3.25
Author: Moises Heberle
Author URI: https://pluggablesoft.com/contact/
Text Domain: woocommerce-ajax-cart
Domain Path: /i18n/languages/
WC requires at least: 3.2
WC tested up to: 6.7.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

defined('WAC_BASE_FILE') || define('WAC_BASE_FILE', __FILE__);

if ( !function_exists('wac_init') ) {
    add_action('init', 'wac_init');
    add_filter('mh_wac_settings', 'wac_settings');
    add_filter('mh_wac_premium_url', 'wac_premium_url');
    add_action('woocommerce_before_cart_table', 'wac_enqueue_scripts');
    add_action('wp_enqueue_scripts', 'wac_enqueue_scripts' );
    add_filter('woocommerce_cart_item_quantity', 'wac_filter_woocommerce_cart_item_quantity', 10, 3);
    add_filter('wc_get_template', 'wac_get_template', 10, 5 );
    add_action('plugins_loaded', 'wac_load_plugin_textdomain');

    add_action( 'before_woocommerce_init', function() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );

    function wac_init() {
        // common lib init
        include_once( 'common/MHCommon.php' );
        MHCommon::initializeV2(
            'woocommerce-ajax-cart',
            'wac',
            WAC_BASE_FILE,
            __('WooCommerce Ajax Cart', 'woocommerce-ajax-cart')
        );

        // plugin checks
        if ( !empty($_POST['is_wac_ajax']) && !defined( 'WOOCOMMERCE_CART' ) ) {
            define( 'WOOCOMMERCE_CART', true );
        }

        do_action('wac_init');
    }
    
    function wac_settings($arr) {
        $arr['show_qty_buttons'] = array(
            'label' => __('Display -/+ buttons around product quantity input', 'woocommerce-ajax-cart'),
            'tab' => __('General', 'woocommerce-ajax-cart'),
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $arr['qty_as_select'] = array(
            'label' => __('Show item quantity as select instead numeric field', 'woocommerce-ajax-cart'),
            'tab' => __('General', 'woocommerce-ajax-cart'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['select_items'] = array(
            'label' => __('Items to show on select', 'woocommerce-ajax-cart'),
            'tab' => __('General', 'woocommerce-ajax-cart'),
            'type' => 'number',
            'default' => 5,
            'depends_on' => 'qty_as_select',
            'min' => 1,
            'max' => 1000,
        );
        $arr['confirmation_zero_qty'] = array(
            'label' => __('Show user confirmation when change item quantity to zero or empty', 'woocommerce-ajax-cart'),
            'tab' => __('General', 'woocommerce-ajax-cart'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['ajax_timeout'] = array(
            'label' => __('AJAX timeout in milliseconds to refresh the cart on change', 'woocommerce-ajax-cart'),
            'tab' => __('General', 'woocommerce-ajax-cart'),
            'type' => 'number',
            'default' => 800,
            'min' => 1,
            'max' => 5000,
        );
    
        return $arr;
    }
    
    function wac_premium_url() {
        return 'http://gum.co/wajaxcart';
    }
    
    function wac_option($name) {
        return apply_filters('mh_wac_setting_value', $name);
    }
    
    function wac_load_plugin_textdomain() {
        load_plugin_textdomain(
            'woocommerce-ajax-cart',
            FALSE,
            dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages'
        );
    }

    function wac_enqueue_scripts() {
        // check if WooCommerce was enabled
        if ( !class_exists('WooCommerce') ) {
            return;
        }

        $deps = array('jquery');

        if ( is_cart() ) {
            $deps[] = 'wc-cart';
        }

        wp_enqueue_style('wooajaxcart', plugins_url('assets/wooajaxcart.css', plugin_basename(WAC_BASE_FILE)));
        wp_enqueue_script('wooajaxcart', plugins_url('assets/wooajaxcart.js', plugin_basename(WAC_BASE_FILE)), $deps);
    
        $frontendData = wac_frontend_vars();
        wp_localize_script( 'wooajaxcart', 'wooajaxcart', $frontendData );
    }

    function wac_frontend_vars() {
        return apply_filters('wac_frontend_vars', array(
            'updating_text' => __( 'Updating...', 'woocommerce-ajax-cart' ),
            'warn_remove_text' => __( 'Are you sure you want to remove this item from cart?', 'woocommerce-ajax-cart' ),
            'ajax_timeout' => wac_option('ajax_timeout'),
            'confirm_zero_qty' => wac_option('confirmation_zero_qty'),
        ));
    }
    
    // define the woocommerce_cart_item_quantity callback
    // add the + and - buttons
    function wac_filter_woocommerce_cart_item_quantity( $inputDiv, $cart_item_key, $cart_item = null ) {
        // check config
        if ( ( wac_option('show_qty_buttons') == 'no' ) || preg_match('/type=\"hidden\"/', $inputDiv) ) {
            return $inputDiv;
        }
    
        // avoid duplication issue some users related
        if ( preg_match('/wac-qty-button/', $inputDiv) ) {
            return $inputDiv;
        }
    
        // add plus and minus buttons
        $minus = wac_button_link('-', 'sub');
        $plus = wac_button_link('+', 'inc');
    
        $input = str_replace(array('<div class="quantity">', '</div>'), array('', ''), $inputDiv);
        $newDiv = '<div class="quantity wac-quantity">' . $minus . $input . $plus . '</div>';
    
        return apply_filters('wac_quantity_div', $newDiv);
    };
    
    function wac_button_link($label, $identifier) {
        $html  = '<a href="" class="wac-qty-button wac-btn-'.$identifier.'">';
        $html .= $label;
        $html .= '</a>';

        return apply_filters('wac_button_link', $html);
    }
    
    function wac_get_template( $located, $template_name, $args, $template_path, $default_path ) {
    
        // ignore if select disabled
        if ( wac_option('qty_as_select') != 'yes' ) {
            return $located;
        }
    
        // modify input template to use select
        if ( 'global/quantity-input.php' == $template_name ) {
            // make the template behavior same as WooCommerce
            $ignoreDisplaySelect = ( !empty($args['min_value']) && !empty($args['max_value']) && ( $args['min_value'] == $args['max_value'] ) );

            if ( !$ignoreDisplaySelect ) {
                $located = plugin_dir_path(WAC_BASE_FILE) . '/templates/wac-qty-select.php';
            }
        }
    
        return apply_filters('wac_template_file', $located, $template_name, $args);
    }
}
