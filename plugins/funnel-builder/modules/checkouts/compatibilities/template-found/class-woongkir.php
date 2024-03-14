<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Woongkir
 * https://github.com/sofyansitorus/Woongkir
 * author Sofyan Sitorus
 * #[AllowDynamicProperties] 

  class WFACP_WoongKir_Indo
 */
#[AllowDynamicProperties] 

  class WFACP_Woongkir_Indo {
	public function __construct() {
		add_filter( 'wfacp_forms_field', [ $this, 'assign_class' ], 10, 2 );
	}

	public function assign_class( $fields, $key ) {
		if ( $key == 'wfacp_divider_shipping' ) {
			$fields['label_class'][] = 'woocommerce-shipping-fields';
		}
		if ( $key == 'wfacp_divider_billing' ) {
			$fields['label_class'][] = 'woocommerce-billing-fields';
		}

		return $fields;
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Woongkir_Indo(), 'woongkir_sof' );

