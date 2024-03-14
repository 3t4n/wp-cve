<?php
/**
 * Apaczka.pl Mapa Punktów
 *
 * @package Apaczka Mapa Punktów
 * @author  InspireLabs
 * @link    https://inspirelabs.pl/
 */

namespace Apaczka_Points_Map;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display delivery point map in the checkout.
 */
class Delivery_Point_Map {
	/**
	 * Supplier name.
	 *
	 * @var string.
	 */
	private $supplier;

	/**
	 * Only cod points.
	 *
	 * @var string.
	 */
	private $only_cod;

	/**
	 * Delivery_Point_Map constructor.
	 */
	public function __construct() {
		$this->init_hooks();
		$this->supplier = 'all';
	}

	/**
	 * Init Hooks.
	 */
	public function init_hooks() {
		add_action( 'woocommerce_review_order_before_payment', array( $this, 'delivery_map_button_init' ) );
		add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'update_map_button_display' ) );
		add_action( 'woocommerce_checkout_process', array( $this, 'select_delivery_point_validation' ) );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_delivery_point_in_order_meta' ), 10, 2 );
		add_filter( 'woocommerce_order_formatted_shipping_address', array( $this, 'delivery_point_as_shipping_address' ), 20, 2 );
	}

	/**
	 * Checks if the plugin is to be enabled.
	 *
	 * @return bool
	 */
	private function is_enable() {
		$is_active = true;

		if ( ! isset( WC()->integrations->integrations['woocommerce-maps-apaczka']->settings['correct_api_connection'] ) ||
			'no' === WC()->integrations->integrations['woocommerce-maps-apaczka']->settings['correct_api_connection']
		) {
			$is_active = false;
		} else {
			if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {
				$is_active = true;
			} else {
				$is_active = false;
			}
		}

		return $is_active;
	}

	/**
	 * Update map button display.
	 *
	 * @param array $data .
	 * @return mixed
	 */
	public function update_map_button_display( $data ) {
		if ( false === $this->is_enable() ) {
			return $data;
		}

		if ( true === $this->is_delivery_map_button_display() ) {
			$data['.amp-map-button']                 = '<span id="amp-map-button" data-supplier="' . $this->supplier . '" data-only-cod="' . $this->only_cod . '" class="button alt amp-map-button">' . __( 'Select a Delivery Point', 'apaczka-pl-mapa-punktow' ) . '</span>';
			$data['amp_delivery_point_desc_display'] = true;
		} else {
			$data['.amp-map-button']                 = '<span class="amp-map-button hidden"></span>';
			$data['amp_delivery_point_desc_display'] = false;
		}

		$customer = WC()->session->get( 'customer' );

		$data['data_supplier']         = $this->supplier;
		$data['data_only_cod']         = $this->only_cod;
		$data['data_shipping_address'] = isset( $customer['shipping_address'] ) ? $customer['shipping_address'] : '';
		$data['data_shipping_city']    = isset( $customer['shipping_city'] ) ? $customer['shipping_city'] : '';

		return $data;
	}

	/**
	 * Checks if the button should be displayed.
	 *
	 * @return bool
	 */
	private function is_delivery_map_button_display() {
		// Get all your existing shipping zones IDS.
		$zone_ids                = array_keys( array( '' ) + \WC_Shipping_Zones::get_zones() );
		$chosen_shipping_methods = WC()->session->chosen_shipping_methods;

		// Loop through shipping Zones IDs.
		foreach ( $zone_ids as $zone_id ) {
			// Get the shipping Zone object.
			$shipping_zone = new \WC_Shipping_Zone( $zone_id );

			// Get all shipping method values for the shipping zone.
			$shipping_methods = $shipping_zone->get_shipping_methods( true, 'values' );

			// Loop through each shipping methods set for the current shipping zone.
			foreach ( $shipping_methods as $instance_id => $shipping_method ) {
				if ( isset( $chosen_shipping_methods[0] ) && $shipping_method->id . ':' . $instance_id === $chosen_shipping_methods[0] ) {
					if ( isset( $shipping_method->instance_settings['display_apaczka_map'] ) && 'yes' === $shipping_method->instance_settings['display_apaczka_map'] ) {
						$this->supplier = $shipping_method->instance_settings['supplier_apaczka_map'];
						$this->only_cod = $shipping_method->instance_settings['only_cod_apaczka_map'];
						return true;
					}
				}

				if ( defined( 'FLEXIBLE_SHIPPING_VERSION' ) && 'flexible_shipping' === $shipping_method->id ) {
					$flexible_shipping      = new Flexible_Shipping_Integration();
					$flexible_shipping_data = $flexible_shipping->get_chosen_shipping_data( $chosen_shipping_methods[0], $instance_id );

					if ( isset( $flexible_shipping_data['display_apaczka_map_fxsp'] ) && 'yes' === $flexible_shipping_data['display_apaczka_map_fxsp'] ) {
						$this->supplier = $flexible_shipping_data['supplier_apaczka_map_fxsp'];
						$this->only_cod = $flexible_shipping_data['only_cod_apaczka_map_fxsp'];

						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Init delivery map button.
	 */
	public function delivery_map_button_init() {
		if ( false === $this->is_enable() ) {
			return null;
		}

		?>
		<input type="hidden" name="apm_supplier" id="apm_supplier" value="">
		<input type="hidden" name="apm_access_point_id" id="apm_access_point_id" value="">
		<input type="hidden" name="apm_name" id="apm_name" value="">
		<input type="hidden" name="apm_foreign_access_point_id" id="apm_foreign_access_point_id" value="">
		<input type="hidden" name="apm_street" id="apm_street" value="">
		<input type="hidden" name="apm_city" id="apm_city" value="">
		<input type="hidden" name="apm_postal_code" id="apm_postal_code" value="">
		<input type="hidden" name="apm_country_code" id="apm_country_code" value="">
		<span class="amp-map-button hidden"></span>
		<address id="amp-delivery-point-desc"></address>
		<?php
	}

	/**
	 * Checking if the delivery point has been selected.
	 */
	public function select_delivery_point_validation() {
		if ( false === $this->is_enable() ) {
			return null;
		}

		// phpcs:disable
		if ( true === $this->is_delivery_map_button_display() && empty( $_POST['apm_access_point_id'] ) ) {
			// phpcs:enable
			wc_add_notice( __( 'Select a delivery point.', 'apaczka-pl-mapa-punktow' ), 'error' );
		}
	}

	/**
	 * Adds to order meta selected delivery point.
	 *
	 * @param int   $order_id .
	 * @param array $data .
	 * @return null
	 */
	public function save_delivery_point_in_order_meta( $order_id, $data ) {
		// phpcs:disable
		if ( empty( $_POST['apm_access_point_id'] ) ) {
			return;
		}

		$delivery_point                        = array();
		$delivery_point['apm_access_point_id'] = sanitize_text_field( wp_unslash( $_POST['apm_access_point_id'] ) );

		! empty( $_POST['apm_name'] ) ? $delivery_point['apm_supplier']                                       =
			sanitize_text_field( wp_unslash( $_POST['apm_supplier'] ) ) : null;
		! empty( $_POST['apm_name'] ) ? $delivery_point['apm_name']                                       =
			sanitize_text_field( wp_unslash( $_POST['apm_name'] ) ) : null;
		! empty( $_POST['apm_foreign_access_point_id'] ) ? $delivery_point['apm_foreign_access_point_id'] =
			sanitize_text_field( wp_unslash( $_POST['apm_foreign_access_point_id'] ) ) : null;
		! empty( $_POST['apm_street'] ) ? $delivery_point['apm_street']                                   =
			sanitize_text_field( wp_unslash( $_POST['apm_street'] ) ) : null;
		! empty( $_POST['apm_city'] ) ? $delivery_point['apm_city']                                       =
			sanitize_text_field( wp_unslash( $_POST['apm_city'] ) ) : null;
		! empty( $_POST['apm_postal_code'] ) ? $delivery_point['apm_postal_code']                         =
			sanitize_text_field( wp_unslash( $_POST['apm_postal_code'] ) ) : null;
		! empty( $_POST['apm_country_code'] ) ? $delivery_point['apm_country_code']                       =
			sanitize_text_field( wp_unslash( $_POST['apm_country_code'] ) ) : null;
		// phpcs:enable

		add_post_meta( $order_id, 'apaczka_delivery_point', $delivery_point );
		
		if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
			$order = wc_get_order( $order_id );
			if ( $order && !is_wp_error($order) ) {
				$order->update_meta_data('apaczka_delivery_point', $delivery_point );
				$order->save();
			}
		}
	}

	/**
	 * Changes the shipping address to the delivery point.
	 *
	 * @param array  $raw_address .
	 * @param object $object .
	 * @return mixed
	 */
	public function delivery_point_as_shipping_address( $raw_address, $object ) {
		$apaczka_delivery_point = get_post_meta( $object->get_id(), 'apaczka_delivery_point', true );

		if ( ! empty( $apaczka_delivery_point ) && isset( $apaczka_delivery_point['apm_supplier'] ) ) {
			$raw_address['first_name'] = $apaczka_delivery_point['apm_name'];
			$raw_address['last_name']  = '';
			$raw_address['company']    = __( 'Delivery Point', 'apaczka-pl-mapa-punktow' ) . ': ' . $apaczka_delivery_point['apm_foreign_access_point_id'] . ' (' . $apaczka_delivery_point['apm_supplier'] . ')';
			$raw_address['address_1']  = $apaczka_delivery_point['apm_street'];
			$raw_address['address_2']  = '';
			$raw_address['city']       = $apaczka_delivery_point['apm_city'];
			$raw_address['state']      = '';
			$raw_address['postcode']   = $apaczka_delivery_point['apm_postal_code'];
			$raw_address['country']    = $apaczka_delivery_point['apm_country_code'];
		}

		return $raw_address;
	}
}

new Delivery_Point_Map();
