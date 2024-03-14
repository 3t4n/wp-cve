<?php

/**
 * WooCommerce Taxamo By WooCommerce
 * Plugin URI: https://www.woocommerce.com/
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_WC_Taxamo {
	public $instance = null;

	public function __construct() {


		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wc_taxamo_vat_number', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 12, 2 );

		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		/* default classes */
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_styling' ], 13, 2 );

		/* Internal css  */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );


	}

	public function is_enable() {
		return class_exists( 'WC_TA_Vat_Number_Field' );
	}

	public function add_field( $fields ) {
		$fields['wc_taxamo_vat_number'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_wc_taxamo_vat_number' ],
			'id'         => 'wc_taxamo_vat_number',
			'field_type' => 'wc_taxamo_vat_number',
			'label'      => __( 'WC Taxamo Vat', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function action() {

		if ( ! $this->is_enable() ) {
			return;
		}

		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_checkout_billing_form', 'WC_TA_Vat_Number_Field', 'print_field' );


	}

	public function display_field( $field, $key ) {


		if ( ! $this->is_enable() || empty( $key ) || 'wc_taxamo_vat_number' !== $key || ! $this->instance instanceof WC_TA_Vat_Number_Field ) {
			return '';
		}


		?>
        <div class="wfacp_taxamo" id="wfacp_taxamo">
			<?php
			$this->instance->print_field();
			?>
        </div>
		<?php

	}


	public function add_default_styling( $args, $key ) {


		if ( ! $this->is_enable() || 'vat_number' !== $key ) {
			return $args;
		}


		if ( isset( $args['type'] ) && 'checkbox' !== $args['type'] ) {

			$args['input_class'] = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$args['label_class'] = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );
			$args['class']       = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full' ], $args['class'] );
			$args['cssready']    = [ 'wfacp-col-full' ];

		} else {
			$args['class']    = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$args['cssready'] = [ 'wfacp-col-full' ];
		}


		return $args;
	}

	public function internal_css() {

		if ( ! $this->is_enable() ) {
			return;

		}
		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";

		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
		}


		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_taxamo{clear: both;}";
		$cssHtml .= "#wfacp_taxamo label.wfacp-form-control-label {bottom: auto;margin: 0;top: 16px;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Taxamo(), 'wfacp-wc-taxamo' );

