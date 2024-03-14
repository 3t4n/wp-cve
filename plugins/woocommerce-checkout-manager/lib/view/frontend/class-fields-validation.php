<?php

namespace QuadLayers\WOOCCM\View\Frontend;

use QuadLayers\WOOCCM\Plugin as Plugin;

/**
 * Fields_Validation Class
 */
class Fields_Validation {

	protected static $_instance;

	public function __construct() {
		add_action( 'woocommerce_checkout_process', array( $this, 'validate_fields' ) );
	}

	public function validate_fields() {
		if ( ! isset( $_POST['woocommerce-process-checkout-nonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['woocommerce-process-checkout-nonce'] ) ), 'woocommerce-process_checkout' ) ) {
			return;
		}

		$billing_fields    = Plugin::instance()->billing->get_fields();
		$shipping_fields   = Plugin::instance()->shipping->get_fields();
		$additional_fields = Plugin::instance()->additional->get_fields();

		foreach ( $billing_fields as $key => $field ) {
			$this->validate_field( $key, $field );
		}

		// Check if field is shipping and if is enable ship to different address checkbox to validate it.
		if ( isset( $_POST['ship_to_different_address'] ) && wc_clean( wp_unslash( $_POST['ship_to_different_address'] ) ) ) {
			foreach ( $shipping_fields as $key => $field ) {
				$this->validate_field( $key, $field );
			}
		}

		foreach ( $additional_fields as $key => $field ) {
			$this->validate_field( $key, $field );
		}

	}

	private function validate_field( $key, $field ) {

		// phpcs:disable WordPress.Security.NonceVerification.Missing

		// Check if is type text or textarea.
		if ( ! in_array( $field['type'], array( 'textarea', 'text' ), true ) ) {
			return;
		}

		// Check if is not required and it is empty, it is valid. If it is not required but it doesn't have the minlength is not valid.
		if ( ! $field['required'] && isset( $_POST[ $field['key'] ] ) && 0 === strlen( wc_clean( wp_unslash( $_POST[ $field['key'] ] ) ) ) ) {
			return;
		}

		// Check if field is disable, if it is it won't ve verified.
		if ( $field['disabled'] ) {
			return;
		}

		// Check if field has a parent conditional.
		if ( ! empty( $field['conditional'] ) ) {

			$conditional_parent_key   = isset( $field['conditional_parent_key'] ) ? $field['conditional_parent_key'] : '';
			$conditional_parent_value = isset( $field['conditional_parent_value'] ) ? $field['conditional_parent_value'] : '';

			// Check if form conditional parent value is sended.
			$post_conditional_parent_value = isset( $_POST[ $conditional_parent_key ] ) ? wc_clean( wp_unslash( $_POST[ $conditional_parent_key ] ) ) : '';

			if ( $post_conditional_parent_value !== $conditional_parent_value ) {
				return;
			}
		}

		// Check if minlength is set, and check if field value length is greater than minlength.
		if ( isset( $field['minlength'] ) && isset( $_POST[ $key ] ) && intval( $field['minlength'] ) > strlen( trim( wc_clean( wp_unslash( $_POST[ $key ] ) ) ) ) ) {

			wc_add_notice( sprintf( esc_html__( '%1$s requires at least %2$s characters', 'woocommerce-checkout-manager' ), $field['label'], $field['minlength'] ), 'error' );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
