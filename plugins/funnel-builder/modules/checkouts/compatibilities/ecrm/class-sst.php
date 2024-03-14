<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Simple Sales Tax
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_With_SST
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_SST {
	/**
	 * @var SST_Checkout
	 */
	private $sst_obj = null;

	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'remove_sst_hook' ] );
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_sst_field' ] );
		add_filter( 'wfacp_html_fields_sst_tax', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_sst_hook' ], 10, 3 );
		add_action( 'wfacp_internal_css', [ $this, 'add_css' ] );
	}

	public function remove_sst_hook() {

		$obj = WFACP_Common::remove_actions( 'woocommerce_checkout_shipping', 'SST_Checkout', 'output_exemption_form' );
		if ( ! is_null( $obj ) ) {
			$this->sst_obj = $obj;

			return;
		}
		$obj = WFACP_Common::remove_actions( 'woocommerce_checkout_after_customer_details', 'SST_Checkout', 'output_exemption_form' );
		if ( ! is_null( $obj ) ) {
			$this->sst_obj = $obj;
		}
	}

	public function add_sst_field( $field ) {
		$field['sst_tax'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'sst_tax' ],
			'id'         => 'sst_tax',
			'field_type' => 'advanced',
			'label'      => __( 'Simple Sales Tax', 'woocommerce' ),
		];

		return $field;
	}


	public function call_sst_hook( $field, $key, $args ) {
		if ( ! empty( $key ) && $key == 'sst_tax' && ! is_null( $this->sst_obj ) ) {
			echo '<div class="wfacp_sst_field">';
			$this->sst_obj->output_exemption_form();
			echo '</div>';
		}
	}

	public function add_css() {
		?>
        <style>
            .form-row-wide {
                clear: none;
            }

            body .wfacp_main_form.woocommerce .wfacp_sst_field h3 input[type=checkbox] {
                position: relative;
            }

            body .wfacp_main_form.woocommerce .wfacp_sst_field {
                padding-left: 15px !important;
                clear: both;
            }

            #sst-certificates {
                width: 100%;
            }

            .sst-certificate-modal-content p.form-row.sst-input.validate-required.woocommerce-invalid-required-field.woocommerce-invalid input {
                border: 1px solid red;
            }

            .sst-certificate-modal-content p.form-row.sst-input.validate-required.woocommerce-invalid-required-field.woocommerce-invalid select {
                border: 1px solid red;
            }

            span.screen-reader-text {
                display: none;
            }
        </style>
		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_SST(), 'sst_tax' );

