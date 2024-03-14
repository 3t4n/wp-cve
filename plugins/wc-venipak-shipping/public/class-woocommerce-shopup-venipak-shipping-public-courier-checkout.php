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
class Woocommerce_Shopup_Venipak_Shipping_Public_Courier_Checkout {

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
	public $is_door_code_enabled;

	/**
	 *
	 *
	 * @since    1.1.0
	 */
	public $is_office_no_enabled;

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public $is_delivery_time_enabled;

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
		$this->is_door_code_enabled = $settings->get_option_by_key('shopup_venipak_shipping_field_isdoorcodeenabled');
		$this->is_delivery_time_enabled = $settings->get_option_by_key('shopup_venipak_shipping_field_isdeliverytimenabled');
		$this->is_office_no_enabled = $settings->get_option_by_key('shopup_venipak_shipping_field_isofficenoenabled');

	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_courier_options( $method, $index ) {
		if ( ! is_checkout()) return; // Only on checkout page

		  $chosen_method_id = WC()->session->chosen_shipping_methods[ $index ];

		  if ($chosen_method_id !== $method->id || $method->method_id !== 'shopup_venipak_shipping_courier_method') {
		    return;
		  }

		  $WC_Checkout = new WC_Checkout();
			$city = $WC_Checkout->get_value( 'billing_city' );

			$items = WC()->cart->get_cart();
			$min_age = 0;
			foreach($items as $item => $values) {
				$product = $values['data'];

				$product_min_age = $product->get_meta('shopup_venipak_shipping_min_age');
				if ($product_min_age && $product_min_age > $min_age) {
					$min_age = $product_min_age;
				}
			}
		  $is_delivery_time = $min_age === 0 && $this->is_delivery_time_enabled && in_array(strtolower($city), array('vilnius', 'kaunas', 'šiauliai', 'siauliai', 'Šiauliai', 'klaipėda', 'klaipeda', 'panevėžys', 'panevezys', 'alytus', 'riga', 'tallin'));

		  if ($this->is_door_code_enabled || $this->is_office_no_enabled || $is_delivery_time) {
			?>
			<div class="venipak-shipping-options">
			<?php
				if ($this->is_door_code_enabled) {
					woocommerce_form_field( 'venipak_door_code' , array(
					'type'          => 'text',
					'class'         => array('form-row-wide venipak_door_code'),
					'label'         =>  __( 'Door code', 'woocommerce-shopup-venipak-shipping' ),
					'required'      => false
					), WC()->checkout->get_value( 'venipak_door_code' ));
				}

				if ($this->is_office_no_enabled) {
					woocommerce_form_field( 'venipak_office_no' , array(
					'type'          => 'text',
					'class'         => array('form-row-wide venipak_office_no'),
					'label'         =>  __( 'Office no', 'woocommerce-shopup-venipak-shipping' ),
					'required'      => false
					), WC()->checkout->get_value( 'venipak_office_no' ));
				}

				if ($is_delivery_time) {
					woocommerce_form_field( 'venipak_delivery_time' , array(
					'type'          => 'select',
					'class'         => array('form-row-wide venipak_delivery_tile'),
					'label'         =>  __( 'Delivery time', 'woocommerce-shopup-venipak-shipping' ),
					'required'      => false,
					'options'       => array(
						'nwd' => __( 'Any time', 'woocommerce-shopup-venipak-shipping' ),
						// 'nwd10' => __( 'Until 10:00', 'woocommerce-shopup-venipak-shipping' ),
						// 'nwd12' => __( 'Until 12:00', 'woocommerce-shopup-venipak-shipping' ),
						'nwd8_14' => __( '8:00-14:00', 'woocommerce-shopup-venipak-shipping' ),
						'nwd14_17' => __( '14:00-17:00', 'woocommerce-shopup-venipak-shipping' ),
						'nwd18_22' => __( '18:00-22:00', 'woocommerce-shopup-venipak-shipping' ),
						'nwd18a' => __( 'After 18:00', 'woocommerce-shopup-venipak-shipping' )
					)
					), WC()->checkout->get_value( 'venipak_delivery_time' ));
				}
			?>
			</div>
			<?php
		  }
	}

	/**
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_courier_update_order_meta( $order_id ) {
		$order = wc_get_order($order_id);
		if ( isset( $_POST['venipak_door_code'] )) {
			$order->update_meta_data('venipak_door_code', sanitize_text_field( $_POST['venipak_door_code'] ) );
			$order->save();
		}
		if ( isset( $_POST['venipak_office_no'] )) {
			$order->update_meta_data('venipak_office_no', sanitize_text_field( $_POST['venipak_office_no'] ) );
			$order->save();
		}
		if ( isset( $_POST['venipak_delivery_time'] )) {
			$order->update_meta_data('venipak_delivery_time', sanitize_text_field( $_POST['venipak_delivery_time'] ) );
			$order->save();
		}
	}
}
