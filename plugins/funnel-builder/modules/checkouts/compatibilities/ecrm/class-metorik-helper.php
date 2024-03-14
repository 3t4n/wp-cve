<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Metorik_Helper {
	/**
	 * @var Metorik_Custom;
	 */
	public $custom_instance = null;

	/**
	 * @var Metorik_Helper_Carts;
	 */
	public $cart_instance = null;

	public function __construct() {
		/* checkout page */
		$this->remove_metorki_hook();
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );
	}

	public function remove_metorki_hook() {
		if ( class_exists( 'Metorik_Helper_Carts' ) ) {
			$this->custom_instance = WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'Metorik_Custom', 'source_form_fields' );

			if ( $this->custom_instance instanceof Metorik_Custom ) {
				add_action( 'woocommerce_checkout_after_customer_details', [ $this->custom_instance, 'source_form_fields' ] );
			}

		}
	}

	public function wfacp_internal_css( $slug ) {
		if ( ! class_exists( 'Metorik_Helper_Carts' ) || $slug != 'layout_9' ) {
			return;
		}


		?>
        <style>
            p#billing_email_field:not(.wfacp-anim-wrap) label {
                top: 17px;
                margin-top: 0;
                bottom: auto;
                line-height: 20px;
            }

            p#billing_email_field:not(.wfacp-anim-wrap) input {
                padding-top: 12px;
                padding-bottom: 10px;
            }


        </style>

		<?php


	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Metorik_Helper(), 'metorik-helper' );

