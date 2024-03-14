<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_SendCloud_Shipping {
	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'hook_sendcloud_shipping' ] );

	}

	public function hook_sendcloud_shipping() {

		add_action( 'wfacp_checkout_after_order_review', 'sendcloudshipping_add_service_point_to_checkout' );

		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
		add_action( 'wfacp_before_shipping_calculator_field', function () {
			echo '<div id=order_review>';

		} );
		add_action( 'wfacp_after_shipping_calculator_field', function () {
			echo '</div>';

		} );

	}

	public function internal_css( $selected_template_slug ) {

		$padd = '22px';
		if ( $selected_template_slug == 'layout_9' ) {
			$padd = '23px';
		}
		?>
        <style>
            body .wfacp_main_form .wfacp_shipping_table ul#shipping_method li button {
                display: inline-block;
                padding: 10px 14px;
                width: auto;
                margin: 6px 0 6px <?php echo $padd; ?>;
            }

            div#sendcloudshipping_service_point_selected_label {
                line-height: 1.5;
                padding-left: <?php echo $padd; ?>;
            }

        </style>
		<?php
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_SendCloud_Shipping(), 'send-cloud-shipping' );
