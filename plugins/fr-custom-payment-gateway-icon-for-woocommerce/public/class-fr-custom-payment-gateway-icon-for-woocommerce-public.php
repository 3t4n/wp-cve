<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Fr_Custom_Payment_Gateway_Icon_For_WooCommerce
 * @subpackage Fr_Custom_Payment_Gateway_Icon_For_WooCommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fr_Custom_Payment_Gateway_Icon_For_WooCommerce
 * @subpackage Fr_Custom_Payment_Gateway_Icon_For_WooCommerce/public
 * @author     Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Custom_Payment_Gateway_Icon_For_WooCommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
        
    /**
     * Change the icon.
     * 
     * Hooked on `woocommerce_gateway_icon` filter.
     * 
     * @since 1.0.0
     * 
     * @param string $icon  Payment gateway icon image.
     * @param string $id    Payment gateway ID.
     * @return string
     */
    public function modify_icon($icon = '', $id = '') {
        if (!$id) {
            return $icon;
        }
        
        $payment_gateways = WC()->payment_gateways()->payment_gateways();
        
        if (!isset($payment_gateways[$id])) {
            return $icon;
        }
        
        /* @var $payment_gateway WC_Payment_Gateway */
        $payment_gateway    = $payment_gateways[$id]; 
        $custom_icon        = $payment_gateway->get_option('fcpgifw_icon');
        $custom_icon_2x     = $payment_gateway->get_option('fcpgifw_icon_2x');
        
        if (!$custom_icon) {
            return $icon;
        }

        $img_src = WC_HTTPS::force_https_url(esc_url($custom_icon));
        $img_src_2x = $custom_icon_2x ? WC_HTTPS::force_https_url(esc_url($custom_icon_2x)) : '';
        $img_alt = esc_attr($payment_gateway->get_title());
        $img_srcset = $img_src_2x ? "srcset=\"$img_src, $img_src_2x 2x\"" : '';
        
        return "<img src=\"$img_src\" alt=\"$img_alt\" $img_srcset />";
    }

}
