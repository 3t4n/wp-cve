<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Facturare DHL for WooCommerce by DHL
 * Plugin URI: https://wordpress.org/plugins/dhl-for-woocommerce/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_DHL_WC {
	private $instance = null;

	public function __construct() {

		/* Remove DHL Actions on shipping method */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_dhl_actions' ], 10 );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'remove_dhl_actions' ], 10 );

		/* ADD DHL Actions on Aero shipping method */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'add_dhl_action' ], 12 );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'add_dhl_action' ], 12 );

		/* Custom CSS added of DHL WC */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ], 10 );


	}

	public function remove_dhl_actions() {


		$this->instance = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'PR_DHL_Front_End_Paket', 'add_preferred_fields' );

	}

	public function add_dhl_action() {
		if ( ! $this->instance instanceof PR_DHL_Front_End_Paket ) {
			return '';
		}

		add_action( 'wfacp_woocommerce_review_order_after_shipping', [ $this->instance, 'add_preferred_fields' ] );
	}


	public function internal_css() {


		?>
        <style>
            body .wfacp_main_form.woocommerce .dhl-preferred-location input[type=radio] {
                position: relative;
                top: auto;
                left: auto;
                margin: 0;
            }

            body .wfacp_main_form .wfacp_shipping_table tbody > tr td {
                line-height: 1.5;
            }

            body .wfacp_main_form.woocommerce .dhl-preferred-location input[type=radio] + label {
                display: inline-block;
                padding-left: 0;
                margin: 0;
            }

            body .wfacp_main_form.woocommerce table.wfacp_shipping_table table {
                width: 100%;
            }

            body .wfacp_main_form.woocommerce tr.dhl-co-tr.dhl-co-tr-fist img {
                margin: 10px 0 0;
            }

            body .wfacp_main_form.woocommerce .wfacp_shipping_table tr.dhl-co-tr td {
                padding: 0;
            }

            body .wfacp_main_form.woocommerce ul.dhl-preferred-location {
                margin: 0 0 15px;
            }
        </style>


		<?php
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_DHL_WC(), 'wfacp-dhl-wc' );

