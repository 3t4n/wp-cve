<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_E_Aabi_Postoffice {

	public $object = null;

	public function __construct() {

		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'setup' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'setup' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ], 10 );
	}

	public function setup() {
		add_action( 'woocommerce_review_order_before_shipping', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {
		$this->object = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'WC_Eabi_Itella_Smartship_Smartpost', 'review_order_pickup_location' );

		if ( $this->object instanceof WC_Eabi_Itella_Smartship_Smartpost ) {
			add_action( 'wfacp_woocommerce_review_order_after_shipping', [ $this->object, 'review_order_pickup_location' ] );
		}
	}


	public function internal_css() {

		?>
        <style>
            tr#eabi_postoffice_pickup_location_div select {
                display: inline-block;
                margin: 10px 0 0;
            }

            @media (max-width: 767px) {
                tr#eabi_postoffice_pickup_location_div select {
                    width: 100% !important;
                }
            }
        </style>

		<?php
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_E_Aabi_Postoffice(), 'wfacp-wc-e-abi-postoffice' );
