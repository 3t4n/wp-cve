<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Billing_Field_RO {
	public function __construct() {
		/* checkout page */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_firma_fields' ] );
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );
		add_filter( 'wfacp_html_fields_romania_firma_field', '__return_false' );
		add_action( 'woocommerce_form_field_args', [ $this, 'register_ro_field_style' ], 10, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function register_ro_field_style( $args, $key ) {

		if ( $key == 'wbfr_cif' || $key == 'wbfr_regcom' || $key == 'wbfr_cont_banca' || $key == 'wbfr_banca' ) {
			$all_cls     = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$input_class = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$label_class = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );

			$args['class']       = $all_cls;
			$args['cssready']    = [ 'wfacp-col-full' ];
			$args['input_class'] = $input_class;
			$args['label_class'] = $label_class;
		}

		return $args;
	}

	public function internal_css() {


		if ( function_exists( 'wfacp_template' ) ) {
			$instance = wfacp_template();
		}

		if ( is_null( $instance ) ) {
			return;
		}
		$px = $instance->get_template_type_px();

		echo '<style>';
		echo '#woocommerce_billing_fields_ro {clear: both;position: relative;}';
		if ( ! isset( $px ) || $px == '' ) {
			echo "#woocommerce_billing_fields_ro h3{padding: 0 $px" . 'px !important;font-weight: normal;margin-bottom: 10px !important;}';
		}
		echo ".wbfr_company_details:after, .wbfr_company_details:before {content: '';display: block;}";
		echo '.wbfr_company_details:after, .wbfr_company_details:after {clear: both;}';
		echo '#woocommerce_billing_fields_ro h3 label{font-weight: normal;color: initial;}';
		echo '#woocommerce_billing_fields_ro input[type=checkbox]{max-width: 13px;float: left; margin-top: 4px; margin-right: 5px;}';
		echo '#woocommerce_billing_fields_ro > h3 + p{display: none;}';
		echo '</style>';


	}

	public function add_firma_fields( $field ) {

		$field['romania_firma_field'] = [
			'type'       => 'wfacp_html',
			'field_type' => 'advanced',
			'class'      => [ 'wfacp_order_coupon' ],
			'id'         => 'romania_firma_field',
			'label'      => __( 'Firma?', 'woocommerce' ),
		];

		return $field;
	}


	public function process_wfacp_html( $field, $key ) {
		if ( ! empty( $key ) && $key == 'romania_firma_field' ) {

			woocommerce_billing_fields_ro( WC()->checkout() );
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Billing_Field_RO(), 'wbfr' );
