<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Constant_Contact {
	public function __construct() {
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_action( 'wfacp_internal_css', [ $this, 'actions' ] );
	}

	public function actions() {
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
	}

	public function add_fields( $field ) {
		if ( $this->is_enable() ) {
			$field['wc_constant_contact_subscribe'] = [
				'type'          => 'checkbox',
				'default'       => false,
				'label'         => __( 'Constant Contact', 'woocommerce-constant-contact' ),
				'validate'      => [],
				'id'            => 'wc_constant_contact_subscribe',
				'required'      => false,
				'wrapper_class' => [],
			];

		}

		return $field;
	}

	public function is_enable() {
		if ( function_exists( 'wc_constant_contact' ) ) {
			if ( ! wc_constant_contact()->get_api() || wc_constant_contact()->get_api()->customer_has_already_subscribed() ) {
				return false;
			} else {
				return true;
			}
		}

		return false;
	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( $key == 'wc_constant_contact_subscribe' && $this->is_enable() ) {
			$optin_text = get_option( 'wc_constant_contact_subscribe_checkbox_label' );
			if ( '' !== $optin_text ) {
				$args['label'] = $optin_text;
			}
			if ( ! empty( $_POST['wc_constant_contact_subscribe'] ) ) {
				$value = ( 'yes' === $_POST['wc_constant_contact_subscribe'] ) ? 1 : 0;
			} else {
				$value = ( 'checked' === get_option( 'wc_constant_contact_subscribe_checkbox_default', 'unchecked' ) ) ? 1 : 0;
			}

			$args['default'] = $value;
		}

		return $args;
	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Constant_Contact(), 'constant_contact' );


