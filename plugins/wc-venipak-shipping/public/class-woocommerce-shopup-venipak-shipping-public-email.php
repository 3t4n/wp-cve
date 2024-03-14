<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://shopup.lt/
 * @since      1.0.0
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/public
 * @author     ShopUp <info@shopup.lt>
 */
class Woocommerce_Shopup_Venipak_Shipping_Public_Email {

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
	 *
	 *
	 * @since    1.0.0
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings = $settings;

	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_tracking_number( $order, $sent_to_admin, $plain_text, $email ) {
		$country = $order->get_shipping_country() ? $order->get_shipping_country() : $order->get_billing_country();

		switch ($country) {
			case "LT":
				$domain = 'lt';
				break;
			case "EE":
				$domain = 'ee';
				break;
			case "LV":
				$domain = 'lv';
				break;
			default:
				$domain = 'lt/en/';
		}
		$gls_tracking_number = $order->get_meta('venipak_gls');
		if ($gls_tracking_number) {
    		echo '<p>' . __( 'Your tracking order code number:', 'woocommerce-shopup-venipak-shipping' ) . ' ' . $gls_tracking_number . '</p>';
		} else {
			$venipak_shipping_order_data = json_decode($order->get_meta('venipak_shipping_order_data'), true);
      if ($venipak_shipping_order_data) {
        $pack_numbers = $venipak_shipping_order_data['pack_numbers'];
        echo '<p>' . __( 'Your tracking order code ', 'woocommerce-shopup-venipak-shipping' ) . ' <a href="https://venipak.' . $domain . '/tracking/track/' . $pack_numbers[0] . '/">' . $pack_numbers[0] . '</p>';
      } else {
        $tracking = $order->get_meta('venipak_shipping_tracking');
        if ($tracking) {
          $tracking_code = $this->settings->format_pack_number($tracking);
          echo '<p>' . __( 'Your tracking order code ', 'woocommerce-shopup-venipak-shipping' ) . ' <a href="https://venipak.' . $domain . '/tracking/track/' . $tracking_code . '/">' . $tracking_code . '</p>';
        }
      }
		}
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_selected_pickup_info( $order, $sent_to_admin, $plain_text, $email ) {
		$venipak_pickup_point = $order->get_meta('venipak_pickup_point');
		$venipak_pickup = false;
		if (is_numeric($venipak_pickup_point)) {
      $response = wp_remote_get( "https://go.venipak.lt/ws/get_pickup_points?country=" . $order->get_shipping_country() );
      $response_body = wp_remote_retrieve_body( $response );
      $collection = json_decode($response_body, true);
      $pickup_options[] = '';
      foreach ($collection as $key => $value) {
        if ($value['id'] == $venipak_pickup_point) {
          $venipak_pickup = $value;
          break;
        }
      }
    } elseif (is_string($venipak_pickup_point)) {
      $venipak_pickup = json_decode($venipak_pickup_point, true);
    }
		if ($venipak_pickup) {
			echo '<h2>' . __( 'Your selected pickup location', 'woocommerce-shopup-venipak-shipping' ) . ':</h2>';
			echo '<p><b>' . $venipak_pickup['display_name'] . '</b>, ' . $venipak_pickup['address'] . ', ' . $venipak_pickup['city'] . '</p>';
		}
	}
}
