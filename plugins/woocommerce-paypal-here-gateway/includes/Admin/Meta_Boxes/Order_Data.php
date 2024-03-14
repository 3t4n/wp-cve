<?php
/**
 * WooCommerce PayPal Here Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce PayPal Here Gateway to newer
 * versions in the future. If you wish to customize WooCommerce PayPal Here Gateway for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-gateway-paypal-here/
 *
 * @author    WooCommerce
 * @copyright Copyright (c) 2018-2020, Automattic, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace Automattic\WooCommerce\PayPal_Here\Admin\Meta_Boxes;

defined( 'ABSPATH' ) or exit;

/**
 * Order Data Meta Box.
 *
 * @since 1.0.0
 */
class Order_Data extends Meta_Box {


	/** @var \WC_Order the order being displayed in this meta box */
	protected $order;

	/** @var array billing fields to display */
	protected $billing_fields;

	/** @var array shipping fields to display */
	protected $shipping_fields;


	/**
	 * Constructs the meta box.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->title = __( 'Order Details', 'woocommerce-gateway-paypal-here' );

		// show at the bottom
		$this->priority = 'high';
	}


	/**
	 * Outputs the meta box markup.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post the post object.
	 */
	public function output( $post ) {

		$this->order = $order = wc_get_order( $post->ID );
		$meta_box    = $this;

		wp_nonce_field( 'woocommerce_save_data', 'woocommerce_meta_nonce' );

		include __DIR__ . '/Views/html-order-data.php';
	}


	/**
	 * Gets the order object being displayed in this meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return \WC_Order
	 */
	public function get_order() {

		return $this->order;
	}


	/**
	 * Saves the data inside this meta box.
	 *
	 * @since 1.0.0
	 */
	public function save() {}


	/**
	 * Outputs billing data for placed orders.
	 *
	 * @since 1.0.0
	 */
	public function output_placed_order_billing_data() {

		// show the formatted billing address
		if ( $this->order->get_formatted_billing_address() ) {

			echo '<p>' . wp_kses( $this->order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
		}

		// go through the billing fields and display their values if they have values and aren't set to hide
		foreach ( $this->get_billing_fields() as $key => $field ) {

			if ( isset( $field['show'] ) && false === $field['show'] ) {
				continue;
			}

			if ( $field_value = $this->get_billing_field_value( $key, $field, 'display' ) ) {

				echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . wp_kses_post( $field_value ) . '</p>';
			}
		}
	}


	/**
	 * Outputs shipping data for placed orders.
	 *
	 * @since 1.0.0
	 */
	public function output_placed_order_shipping_data() {

		// show the formatted shipping address or a message if none is set yet
		if ( $formatted_shipping_address = $this->order->get_formatted_shipping_address() ) {

			echo '<p>' . wp_kses( $formatted_shipping_address, array( 'br' => array() ) ) . '</p>';

		} else {

			echo '<p class="none_set"><strong>' . __( 'Optional:', 'woocommerce-gateway-paypal-here' ) . '</strong> ' . __( 'No shipping address set.', 'woocommerce-gateway-paypal-here' ) . '</p>';
		}

		// go through the shipping fields and display their values if they have values and aren't set to hide
		foreach ( $this->get_shipping_fields() as $key => $field ) {

			if ( isset( $field['show'] ) && false === $field['show'] ) {
				continue;
			}

			if ( $field_value = $this->get_shipping_field_value( $key, $field, 'display' ) ) {

				echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . wp_kses_post( $field_value ) . '</p>';
			}
		}
	}


	/**
	 * Outputs the edit fields for new order billing data that should display above the 'Add more details' button.
	 *
	 * @since 1.0.0
	 */
	public function output_edit_order_billing_fields_above() {

		$this->output_edit_order_billing_fields( 'above' );
	}


	/**
	 * Outputs the edit fields for billing data that should display below the 'Add more details' button.
	 *
	 * @since 1.0.0
	 */
	public function output_edit_order_billing_fields_below() {

		$this->output_edit_order_billing_fields();
	}


	/**
	 * Outputs all the edit order billing fields for the given location.
	 *
	 * @since 1.0.0
	 *
	 * @param string $location either `above` or `below`, meaning above or below the 'Add more details' button
	 */
	public function output_edit_order_billing_fields( $location = 'below' ) {

		foreach ( $this->get_billing_fields() as $key => $field ) {

			$field_value = $this->get_billing_field_value( $key, $field );

			$should_show_field_above = $this->should_show_field_above_button( $field, $field_value );

			if (    ( 'above' === $location && $should_show_field_above )
			     || ( 'below' === $location && ! $should_show_field_above ) ) {

				$this->output_billing_edit_field( $key, $field, $field_value );
			}
		}

		if ( 'below' === $location ) {
			woocommerce_wp_text_input(
				array(
					'id'    => '_transaction_id',
					'label' => __( 'Transaction ID', 'woocommerce-gateway-paypal-here' ),
					'value' => $this->order->get_transaction_id( 'edit' ),
				)
			);
		}
	}


	/**
	 * Outputs all the edit order shipping fields.
	 *
	 * @since 1.0.0
	 */
	public function output_edit_order_shipping_fields() {

		foreach( $this->get_shipping_fields() as $key => $field ) {

			$this->output_shipping_edit_field( $key, $field );
		}
	}


	/**
	 * Checks whether we should show an edit field above the 'Add more details' button.
	 *
	 * Edit fields should display above the button if they are set to via the `show_above` attribute
	 * in their field data, and if they have an empty value. If they contain a value, it means this
	 * is an already-placed order, and we should not display the edit field by default.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field the field data
	 * @param string $field_value the field value -- needs to be empty to display
	 * @return bool
	 */
	protected function should_show_field_above_button( $field, $field_value ) {

		return '' === $field_value && isset( $field['show_above'] ) && true === $field['show_above'];
	}


	/**
	 * Outputs a billing edit field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key the field key
	 * @param array $field the field data
	 * @param string|null $field_value the field value
	 */
	protected function output_billing_edit_field( $key, $field, $field_value = null ) {

		if ( ! isset( $field['id'] ) ) {
			$field['id'] = '_billing_' . $key;
		}

		$field['value'] = null === $field_value ? $this->get_billing_field_value( $key, $field ) : $field_value;

		$this->output_edit_field( $field );
	}


	/**
	 * Outputs a shipping edit field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key the field key
	 * @param array $field the field data
	 * @param string|null $field_value the field value
	 */
	protected function output_shipping_edit_field( $key, $field, $field_value = null ) {

		if ( ! isset( $field['id'] ) ) {
			$field['id'] = '_shipping_' . $key;
		}

		$field['value'] = null === $field_value ? $this->get_shipping_field_value( $key, $field ) : $field_value;

		$this->output_edit_field( $field );
	}


	/**
	 * Outputs an edit field.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field the field data
	 */
	protected function output_edit_field( $field ) {

		if ( ! isset( $field['type'] ) ) {
			$field['type'] = 'text';
		}

		'select' === $field['type'] ? woocommerce_wp_select( $field ) : woocommerce_wp_text_input( $field );
	}


	/**
	 * Gets the value for a billing field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $field_name the field name
	 * @param array $field the field data
	 * @param string $context the context for this value, either `value` or `display`
	 * @return string the field value
	 */
	protected function get_billing_field_value( $field_name, $field, $context = 'value' ) {

		return $this->get_field_value( 'billing_' . $field_name, $field, $context );
	}


	/**
	 * Gets the value for a shipping field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $field_name the field name
	 * @param array $field the field data
	 * @param string $context the context for this value, either `value` or `display`
	 * @return string the field value
	 */
	protected function get_shipping_field_value( $field_name, $field, $context = 'value' ) {

		return $this->get_field_value( 'shipping_' . $field_name, $field, $context );
	}


	/**
	 * Gets the value for a field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $field_name the field name
	 * @param array $field the field data
	 * @param string $context the context for this value, either `value` or `display`
	 * @return string the field value
	 */
	protected function get_field_value( $field_name, $field, $context = 'value' ) {

		if ( isset( $field['value'] ) ) {

			$field_value = $field['value'];

		} elseif ( is_callable( array( $this->order, 'get_' . $field_name ) ) ) {

			$field_value = $this->order->{"get_$field_name"}( 'edit' );

		} else {

			$field_value = $this->order->get_meta( '_' . $field_name );
		}

		if ( 'display' === $context ) {

			if ( 'billing_phone' === $field_name || 'shipping_phone' === $field_name ) {

				$field_value = wc_make_phone_clickable( $field_value );

			} else {

				$field_value = make_clickable( esc_html( $field_value ) );
			}
		}

		return $field_value;
	}


	/**
	 * Gets the billing fields to display in the admin.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_billing_fields() {

		if ( null === $this->billing_fields ) {

			/**
			 * Filters the admin billing fields.
			 *
			 * This is a core WC hook, repeated here for plugin compatibility.
			 *
			 * @see \WC_Meta_Box_Order_Data::init_address_fields()
			 *
			 * @since 1.0.0
			 *
			 * @param mixed[] billing fields {
			 *     @type bool $show_above whether to show the edit field above the 'Add more details' button
			 *     @type mixed see woocommerce_wp_select() and woocommerce_wp_text_input() for other options
			 * }
			 */
			$this->billing_fields = apply_filters( 'woocommerce_admin_billing_fields', array(
				'first_name' => array(
					'label'      => __( 'First name', 'woocommerce-gateway-paypal-here' ),
					'show'       => false,
					'show_above' => true,
				),
				'last_name'  => array(
					'label'      => __( 'Last name', 'woocommerce-gateway-paypal-here' ),
					'show'       => false,
					'show_above' => true,
				),
				'email'      => array(
					'label'      => __( 'Email address', 'woocommerce-gateway-paypal-here' ),
					'show_above' => true,
				),
				'phone'      => array(
					'label'      => __( 'Phone', 'woocommerce-gateway-paypal-here' ),
					'show_above' => true,
				),
				'company'    => array(
					'label' => __( 'Company', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'address_1'  => array(
					'label' => __( 'Address line 1', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'address_2'  => array(
					'label' => __( 'Address line 2', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'city'       => array(
					'label' => __( 'City', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'postcode'   => array(
					'label' => __( 'Postcode / ZIP', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'country'    => array(
					'label'   => __( 'Country', 'woocommerce-gateway-paypal-here' ),
					'show'    => false,
					'class'   => 'js_field-country select short',
					'type'    => 'select',
					'options' => array( '' => __( 'Select a country&hellip;', 'woocommerce-gateway-paypal-here' ) ) + wc()->countries->get_allowed_countries(),
				),
				'state'      => array(
					'label' => __( 'State / County', 'woocommerce-gateway-paypal-here' ),
					'class' => 'js_field-state select short',
					'show'  => false,
				),
			) );
		}

		return $this->billing_fields;
	}


	/**
	 * Gets the shipping fields to display in the admin.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_shipping_fields() {

		if ( null === $this->shipping_fields ) {

			/**
			 * Filters the admin shipping fields.
			 *
			 * This is a core WC hook, repeated here for plugin compatibility.
			 *
			 * @see \WC_Meta_Box_Order_Data::init_address_fields()
			 *
			 * @since 1.0.0
			 *
			 * @param mixed[] shipping fields {
			 *     @type mixed see woocommerce_wp_select() and woocommerce_wp_text_input() for other options
			 * }
			 */
			$this->shipping_fields = apply_filters( 'woocommerce_admin_shipping_fields', array(
				'first_name' => array(
					'label' => __( 'First name', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'last_name'  => array(
					'label' => __( 'Last name', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'company'    => array(
					'label' => __( 'Company', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'address_1'  => array(
					'label' => __( 'Address line 1', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'address_2'  => array(
					'label' => __( 'Address line 2', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'city'       => array(
					'label' => __( 'City', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'postcode'   => array(
					'label' => __( 'Postcode / ZIP', 'woocommerce-gateway-paypal-here' ),
					'show'  => false,
				),
				'country'    => array(
					'label'   => __( 'Country', 'woocommerce-gateway-paypal-here' ),
					'show'    => false,
					'type'    => 'select',
					'class'   => 'js_field-country select short',
					'options' => array( '' => __( 'Select a country&hellip;', 'woocommerce-gateway-paypal-here' ) ) + wc()->countries->get_shipping_countries(),
				),
				'state'      => array(
					'label' => __( 'State / County', 'woocommerce-gateway-paypal-here' ),
					'class' => 'js_field-state select short',
					'show'  => false,
				),
			) );
		}

		return $this->shipping_fields;
	}


}
