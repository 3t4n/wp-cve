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
class Woocommerce_Shopup_Venipak_Shipping_Public_Pickup_Checkout {

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
	private $googlemap_api_key;

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	private $is_map_enabled;


	/**
	 *
	 *
	 * @since    1.2.0
	 */
	private $pickup_type;

	/**
	 *
	 *
	 * @since    1.3.0
	 */
	private $is_clusters_enabled;

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
		$this->googlemap_api_key = $settings->get_option_by_key('shopup_venipak_shipping_field_googlemapapikey');
		$this->is_map_enabled = $settings->get_option_by_key('shopup_venipak_shipping_field_ismapenabled');
		$this->pickup_type = $settings->get_option_by_key('shopup_venipak_shipping_field_pickuptype');
		$this->is_clusters_enabled = $settings->get_option_by_key('shopup_venipak_shipping_field_isclustersenabled');
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_pickup_options() {
		if (in_array('shopup_venipak_shipping_pickup_method', wc_get_chosen_shipping_method_ids())) {
			wc_get_template(
				'woocommerce/checkout/venipak-shipping-terminals.php',
				array(),
				'',
				untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/' );
		}
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_pickup_checkout_process() {
		if ( isset( $_POST['venipak_pickup_point'] ) && empty( $_POST['venipak_pickup_point'] ) )
			wc_add_notice( ( __( 'Select pickup point', 'woocommerce-shopup-venipak-shipping' ) ), "error" );
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_pickup_update_order_meta( $order_id ) {
		if ( isset( $_POST['venipak_pickup_point'] )) {
			$order = wc_get_order($order_id);
			$order->update_meta_data('venipak_pickup_point', sanitize_text_field( $_POST['venipak_pickup_point'] ) );
			$order->save();
		}
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_pickup_points() {
		$cart_total_weight = WC()->cart->cart_contents_weight;
		if (get_option('woocommerce_weight_unit') == 'g') {
			$cart_total_weight /= 1000;
		}

		switch (get_option( 'woocommerce_dimension_unit' )) {
		case 'm':
			$unit_multiplayer = 0.01;
			break;
		case 'mm':
			$unit_multiplayer = 10;
			break;
		default:
			$unit_multiplayer = 1;
		}

		$maximum_weight_pickup = 10;

		$venipak_max_l = 61 * $unit_multiplayer;
		$venipak_max_w = 39.5 * $unit_multiplayer;
		$venipak_max_h = 41 * $unit_multiplayer;
		$venipak_max_volume = $venipak_max_l * $venipak_max_w * $venipak_max_h;
		$lp_max_l = 61 * $unit_multiplayer;
		$lp_max_w = 35 * $unit_multiplayer;
		$lp_max_h = 75 * $unit_multiplayer;
		$lp_max_volume = $lp_max_l * $lp_max_w * $lp_max_h;
		
		$is_valid_for_locker = true;
		$is_valid_for_pickup = true;
		$total_cart_volume = 0;
		
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product = $cart_item['data']; // Directly use the product data

			if ($product->is_type('variation')) {
				$product = new WC_Product_Variation($cart_item['variation_id']);
			}
			$product_l = (float)$product->get_length() ?: 0;
			$product_w = (float)$product->get_width() ?: 0;
			$product_h = (float)$product->get_height() ?: 0;
			$total_cart_volume += ($product_l * $product_w * $product_h) * $cart_item['quantity'];
			if (
				!($product_l <= $venipak_max_l && $product_w <= $venipak_max_w && $product_h <= $venipak_max_h) &&
				!($product_l <= $lp_max_l && $product_w <= $lp_max_w && $product_h <= $lp_max_h)
			) {
				$is_valid_for_locker = false;
			}
			$product_min_age = $product->get_meta('shopup_venipak_shipping_min_age');
			if ($product_min_age > 0) {
				$is_valid_for_locker = false;
				$is_valid_for_pickup = false;
			}
		}
		// if ($cart_total_weight > $maximum_weight_locker) {
		//   $is_valid_for_locker = false;
		// }
		if ($cart_total_weight > $maximum_weight_pickup) {
			$is_valid_for_pickup = false;
		}
		if ($total_cart_volume > $venipak_max_volume && $total_cart_volume > $lp_max_volume) {
			$is_valid_for_locker = false;
		}

		if ($this->pickup_type === 'all' && !$is_valid_for_locker && $is_valid_for_pickup) {
			$pickup_type = 1; // only pickup
		} elseif ($this->pickup_type === 'all' && $is_valid_for_locker && !$is_valid_for_pickup) {
			$pickup_type = 3; // only locker
		} else {
			$pickup_type = $this->pickup_type;
		}


		if (!$is_valid_for_locker && !$is_valid_for_pickup) {
			wp_die();
		}
		
		$collection = venipak_fetch_pickups();
		$result = [];
		$country = WC()->customer->get_shipping_country();
		foreach ($collection as $item) {
			if ($country !== $item['country']) continue;
			if ($pickup_type !== 'all' && $pickup_type != $item['type']) continue;

			$result[] = $item;
		}
		echo json_encode($result);
		wp_die();
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_checkout_settings() {
		echo json_encode(array(
			'googlemap_api_key' => $this->googlemap_api_key,
			'is_map_enabled' => $this->googlemap_api_key !== '' ? !!$this->is_map_enabled : false,
			'pickup_marker' => plugin_dir_url( __FILE__ ) . 'images/venipak-marker.svg',
			'locker_marker' => plugin_dir_url( __FILE__ ) . 'images/venipak-marker.svg'
		));
		wp_die();
	}

	/**
	 *
	 *
	 * @since    1.14.9
	 */
	public function validate_cod( $fields, $errors ) {
		$items = venipak_fetch_pickups();
		
		if ($fields['payment_method'] === 'cod' && strpos($fields['shipping_method'][0], 'shopup_venipak_shipping_pickup_method') !== false) {
			foreach($items as $point) {
				if ($point["id"] === +$_POST['venipak_pickup_point'] && $point["cod_enabled"] === 0) {
					$errors->add( 'validation', __( 'Cod incompatible', 'woocommerce-shopup-venipak-shipping' ) );
				}
			}
		}
	}
}
