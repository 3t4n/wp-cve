<?php

namespace QuadLayers\WOOCCM\View\Frontend;

use PHP_CodeSniffer\Standards\PSR12\Sniffs\Classes\ClosingBraceSniff;
use QuadLayers\WOOCCM\Plugin as Plugin;

/**
 * Fields_I18n Class
 */
class Fields_I18n {

	protected static $_instance;

	public function __construct() {
		add_action( 'init', array( $this, 'init_polylang' ) );
		add_action( 'admin_init', array( $this, 'init_wpml' ) );
		add_filter( 'wooccm_checkout_field_filter', array( $this, 'translate_field' ) );
	}

	public function init_polylang() {

		if ( function_exists( 'pll_register_string' ) ) {

			$billing_fields = Plugin::instance()->billing->get_fields();
			$billing_label  = esc_html__( 'Billing', 'woocommerce-checkout-manager' );

			$shipping_fields = Plugin::instance()->shipping->get_fields();
			$shipping_label  = esc_html__( 'Shipping', 'woocommerce-checkout-manager' );

			$additional_fields = Plugin::instance()->additional->get_fields();
			$additional_label  = esc_html__( 'Additional', 'woocommerce-checkout-manager' );

			$this->register_polylang_fields( $billing_fields, $billing_label );
			$this->register_polylang_fields( $shipping_fields, $shipping_label );
			$this->register_polylang_fields( $additional_fields, $additional_label );
		}
	}

	public function init_wpml() {
		if ( function_exists( 'icl_register_string' ) ) {

			$billing_fields = Plugin::instance()->billing->get_fields();
			$billing_label  = esc_html__( 'Billing', 'woocommerce-checkout-manager' );

			$shipping_fields = Plugin::instance()->shipping->get_fields();
			$shipping_label  = esc_html__( 'Shipping', 'woocommerce-checkout-manager' );

			$additional_fields = Plugin::instance()->additional->get_fields();
			$additional_label  = esc_html__( 'Additional', 'woocommerce-checkout-manager' );

			$this->register_wpml_fields( $billing_fields, $billing_label );
			$this->register_wpml_fields( $shipping_fields, $shipping_label );
			$this->register_wpml_fields( $additional_fields, $additional_label );
		}
	}

	protected function register_polylang_fields( $fields, $label ) {

		if ( function_exists( 'pll_register_string' ) ) {

			if ( is_array( $fields ) ) {

				$name = sprintf( 'WCM / %s', $label );

				foreach ( $fields as $field ) {
					if ( isset( $field['label'] ) && '' != $field['label'] ) {
						pll_register_string( $field['label'], $field['label'], $name );
					}
					if ( isset( $field['placeholder'] ) && '' != $field['placeholder'] ) {
						pll_register_string( $field['placeholder'], $field['placeholder'], $name );
					}
					if ( isset( $field['description'] ) && '' != $field['description'] ) {
						pll_register_string( $field['description'], $field['description'], $name );
					}
					if ( isset( $field['conditional_parent_value'] ) && '' != $field['conditional_parent_value'] ) {
						pll_register_string( $field['conditional_parent_value'], $field['conditional_parent_value'], $name );
					}

					if ( isset( $field['options'] ) ) {
						foreach ( $field['options'] as $option_data ) {
							if ( isset( $option_data['label'] ) ) {
								pll_register_string( $option_data['label'], $option_data['label'], sprintf( '%s / %s', $name, $field['label'] ) );
							}
						}
					}
				}
			}
		}
	}

	protected function register_wpml_fields( $fields, $label ) {

		if ( function_exists( 'icl_register_string' ) ) {

			$icl_language_code = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : get_bloginfo( 'language' );

			if ( is_array( $fields ) ) {

				foreach ( $fields as $field ) {
					if ( isset( $field['label'] ) && '' != $field['label'] ) {
						icl_register_string( 'woocommerce-checkout-manager', $field['label'], $field['label'], false, $icl_language_code );
					}
					if ( isset( $field['placeholder'] ) && '' != $field['placeholder'] ) {
						icl_register_string( 'woocommerce-checkout-manager', $field['placeholder'], $field['placeholder'], false, $icl_language_code );
					}
					if ( isset( $field['description'] ) && '' != $field['description'] ) {
						icl_register_string( 'woocommerce-checkout-manager', $field['description'], $field['description'], false, $icl_language_code );
					}
					if ( isset( $field['conditional_parent_value'] ) && '' != $field['conditional_parent_value'] ) {
						icl_register_string( 'woocommerce-checkout-manager', $field['conditional_parent_value'], $field['conditional_parent_value'], false, $icl_language_code );
					}
					if ( isset( $field['options'] ) ) {
						foreach ( $field['options'] as $option_data ) {
							if ( isset( $option_data['label'] ) ) {
								icl_register_string( 'woocommerce-checkout-manager', $option_data['label'], $option_data['label'], false, $icl_language_code );
							}
						}
					}
				}
			}
		}
	}

	public function i18n( $string ) {

		if ( function_exists( 'icl_t' ) ) {
			return icl_t( 'woocommerce-checkout-manager', $string, $string );
		} elseif ( function_exists( 'pll__' ) ) {
			return pll__( $string );
		}

		// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
		return esc_html__( $string, 'woocommerce' );
	}

	public function translate( $value ) {
		if ( ! empty( $value ) ) {

			if ( is_string( $value ) ) {
				$value = $this->i18n( $value );
			}
		}

		return $value;
	}

	public function translate_field( $field ) {
		// ii18n
		// -----------------------------------------------------------------------

		if ( ! empty( $field['label'] ) ) {
			$field['label'] = $this->translate( $field['label'] );
		}

		if ( ! empty( $field['placeholder'] ) ) {
			$field['placeholder'] = $this->translate( $field['placeholder'] );
		}

		if ( ! empty( $field['description'] ) ) {
			$field['description'] = $this->translate( $field['description'] );
		}

		if ( ! empty( $field['conditional_parent_value'] ) ) {
			$field['conditional_parent_value'] = $this->translate( $field['conditional_parent_value'] );
		}

		if ( isset( $field['options'] ) ) {
			foreach ( $field['options'] as $key => $option_data ) {
				if ( isset( $option_data['label'] ) && ! is_numeric( $option_data['label'] ) && is_string( $option_data['label'] ) ) {
					$field['options'][ $key ]['label'] = $this->i18n( $option_data['label'] );
				}
			}
		}

		return $field;
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
