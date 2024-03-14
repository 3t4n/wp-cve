<?php

namespace QuadLayers\WOOCCM\View\Frontend;

use QuadLayers\WOOCCM\Plugin as Plugin;

/**
 * Fields_Register Class
 */
class Fields_Register {

	protected static $_instance;

	public function __construct() {
		// Add keys
		// -----------------------------------------------------------------------
		add_filter( 'wooccm_additional_fields', array( $this, 'add_keys' ) );
		add_filter( 'wooccm_billing_fields', array( $this, 'add_keys' ) );
		add_filter( 'wooccm_shipping_fields', array( $this, 'add_keys' ) );

		// Billing fields
		// -----------------------------------------------------------------------
		add_filter( 'woocommerce_checkout_fields', array( $this, 'add_billing_fields_beta' ), 999 );

		// Shipping fields
		// -----------------------------------------------------------------------
		add_filter( 'woocommerce_checkout_fields', array( $this, 'add_shipping_fields_beta' ), 999 );

		// Additional fields
		// -----------------------------------------------------------------------
		add_filter( 'woocommerce_checkout_fields', array( $this, 'add_additional_fields' ), 999 );

		// Account beta
		// -----------------------------------------------------------------------
		add_filter( 'woocommerce_billing_fields', array( $this, 'add_account_billing_fields_beta' ), 999 );
		add_filter( 'woocommerce_shipping_fields', array( $this, 'add_account_shipping_fields_beta' ), 999 );

		// My account

		/*
		// woocommerce 4.2 issue, the shipping and billing fields not working on my account when required field is empty
		// temporary fix excluding required fields in my account
		add_filter('woocommerce_address_to_edit', array($this, 'add_my_account_fields'), 10, 2);
		*/
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function add_billing_fields_beta( $fields ) {
		if ( ! isset( $fields['billing'] ) ) {
			return $fields;
		}
		$wooccm_fields = Plugin::instance()->billing->get_fields();
		if ( empty( $wooccm_fields ) ) {
			return $fields;
		}
		$fields['billing'] = array_merge( $fields['billing'], $wooccm_fields );

		$fields['billing'] = array_filter(
			$fields['billing'],
			function ( $field ) {
				return ( empty( $field['disabled'] ) );
			}
		);

		if ( isset( $fields['billing']['billing_address_2']['label_class'] ) && in_array( 'screen-reader-text', $fields['billing']['billing_address_2']['label_class'], true ) ) {
			$fields['billing']['billing_address_2']['label_class'] = array_filter(
				$fields['billing']['billing_address_2']['label_class'],
				static function ( $class ) {
				return 'screen-reader-text' !== $class;
				}
			);
		}

		return $fields;
	}

	public function add_shipping_fields_beta( $fields ) {
		if ( ! isset( $fields['shipping'] ) ) {
			return $fields;
		}
		$wooccm_fields = Plugin::instance()->shipping->get_fields();
		if ( empty( $wooccm_fields ) ) {
			return $fields;
		}
		$fields['shipping'] = array_merge( $fields['shipping'], $wooccm_fields );

		$fields['shipping'] = array_filter(
			$fields['shipping'],
			function ( $field ) {
				return ( empty( $field['disabled'] ) );
			}
		);
		if ( isset( $fields['shipping']['shipping_address_2']['label_class'] ) && in_array( 'screen-reader-text', $fields['shipping']['shipping_address_2']['label_class'], true ) ) {
			$fields['shipping']['shipping_address_2']['label_class'] = array_filter(
				$fields['shipping']['shipping_address_2']['label_class'],
				static function ( $class ) {
				return 'screen-reader-text' !== $class;
				}
			);
		}

		return $fields;
	}

	public function add_additional_fields( $fields ) {
		$fields['additional'] = Plugin::instance()->additional->get_fields();
		$fields['additional'] = array_filter(
			$fields['additional'],
			function ( $field ) {
				return ( empty( $field['disabled'] ) );
			}
		);

		return $fields;
	}

	/*
	 public function add_my_account_fields( $defaults, $load_address ) {

		if ( isset( WOOCCM()->$load_address ) ) {

			$fields = WOOCCM()->$load_address->get_fields();

			$keys = array_column( WOOCCM()->$load_address->get_fields(), 'key' );

			foreach ( $fields as $field_id => $field ) {
				if ( ! isset( $field['value'] ) ) {

					// when country field is visible default state is set via javascript
					if ( in_array( "{$load_address}_country", $keys ) ) {
						unset( $fields[ $field_id ]['country'] );
					}
					$fields[ $field_id ]['value'] = user_meta( get_current_user_id(), $field['key'], true );
				}
			}

			return array_filter(
				$fields,
				function ( $field ) {
					return ( empty( $field['disabled'] ) );
				}
			);
		}

		return $defaults;
	}
	*/

	public function add_account_billing_fields_beta( $fields ) {
		if ( ! is_account_page() ) {
			return $fields;
		}
		$wooccm_fields = Plugin::instance()->billing->get_fields();
		return array_filter(
			array_merge( $fields, $wooccm_fields ),
			function ( $field ) {
				return ( empty( $field['disabled'] ) );
			}
		);
	}

	public function add_account_shipping_fields_beta( $fields ) {
		if ( ! is_account_page() ) {
			return $fields;
		}
		$wooccm_fields = Plugin::instance()->shipping->get_fields();
		return array_filter(
			array_merge( $fields, $wooccm_fields ),
			function ( $field ) {
				return ( empty( $field['disabled'] ) );
			}
		);
	}

	public function add_keys( $fields ) {
		$frontend_fields = array();

		foreach ( $fields as $field_id => $field ) {
			if ( ! empty( $field['key'] )/*  && empty( $field['disabled'] ) */ ) {
				$frontend_fields[ $field['key'] ] = $field;
			}
		}

		return $frontend_fields;
	}
}

