<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_GR_WC {
	public function __construct() {

		/* checkout page */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_action( 'wfacp_internal_css', [ $this, 'gr_field_register' ] );

	}

	public function gr_field_register() {

		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );

	}

	public function add_fields( $field ) {
		$field['gr_checkout_checkbox'] = [
			'type'          => 'checkbox',
			'default'       => true,
			'label'         => 'GetResponse',
			'validate'      => [],
			'id'            => 'gr_checkout_checkbox',
			'required'      => false,
			'wrapper_class' => [],
			'class'         => [ 'gr-wc-checkbox' ],
		];

		return $field;
	}

	public function add_default_wfacp_styling( $args, $key ) {

		$default        = false;
		$checkout_label = __( 'Sign up to our newsletter!', 'Gr_Integration' );

		if ( $key == 'gr_checkout_checkbox' ) {
			if ( function_exists( 'gr_get_option' ) ) {
				$checked = gr_get_option( 'checkout_checked' );
				if ( $checked ) {
					$default = true;
				}

				$checkout_label_text = gr_get_option( 'checkout_label' );

				if ( isset( $checkout_label_text ) && $checkout_label_text != '' ) {
					$checkout_label = $checkout_label_text;
				}
			}

			$args['label']       = $checkout_label;
			$args['default']     = $default;
			$args['input_class'] = [ 'GR_checkoutbox' ];

		}

		return $args;
	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_GR_WC(), 'gr-wc' );

