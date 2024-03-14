<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Courier Guy Shipping for WooCommerce by The Courier Guy (v.4.4.9)
 *
 */
#[AllowDynamicProperties]

  class The_Courier_Guy_Shipping_For_WC {

	public function __construct() {
		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_billing' ] );
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_shipping' ] );
		} else {
			add_action( 'init', [ $this, 'setup_billing' ], 20 );
			add_action( 'init', [ $this, 'setup_shipping' ], 20 );

		}
		/* Internal css  */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function is_enable() {
		return class_exists( 'TCG_Plugin' );
	}


	public function setup_billing() {
		if ( ! $this->is_enable() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'tcg_place_lookup', [
			'type'        => 'tcg_place_lookup',
			'label'       => __( 'Area/Suburb', 'woofunnels-aero-checkout' ),
			'placeholder' => 'Area/Suburb',
			'options'     => [
				'Search Suburb...',
			],
			'cssready'    => [ 'wfacp-col-left-third' ],
			'class'       => [
				'form-row-wide',
				'tcg-suburb-field',
				'form-row-third first',
				'address-field',
				'wfacp-col-full'
			],
			'required'    => false,
			'priority'    => 60,
		] );

		new WFACP_Add_Address_Field( 'tcg_quoteno', [
			'type'        => 'text',
			'label'       => __( 'TCG Quote Number', 'woofunnels-aero-checkout' ),
			'placeholder' => 'TCG Quote Number',
			'class'       => [
				'form-row-wide',
				'tcg-quoteno',
				'wfacp-col-full'
			],
			'required'    => false,
			'priority'    => 90,
		] );


	}

	public function setup_shipping() {
		if ( ! $this->is_enable() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'tcg_place_lookup', [
			'type'        => 'tcg_place_lookup',
			'label'       => __( 'Area/Suburb', 'woofunnels-aero-checkout' ),
			'placeholder' => 'Area/Suburb',
			'options'     => [
				'Search Suburb...',
			],
			'cssready'    => [ 'wfacp-col-left-third' ],
			'class'       => [
				'tcg-suburb-field',
				'address-field',
				'wfacp-col-full'
			],
			'required'    => false,
			'priority'    => 60,
		], 'shipping' );

		new WFACP_Add_Address_Field( 'tcg_quoteno', [
			'type'        => 'text',
			'label'       => __( 'TCG Quote Number', 'woofunnels-aero-checkout' ),
			'placeholder' => 'TCG Quote Number',
			'class'       => [
				'form-row-wide',
				'tcg-quoteno',
				'wfacp-col-full'
			],
			'required'    => false,
			'priority'    => 90,
		], 'shipping' );


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
		$cssHtml .= $bodyClass . "#billing_sg_checkout_location_picker{width:100%;}";
		$cssHtml .= $bodyClass . "#billing_sg_checkout_location_picker input[type=text]{padding:12px 10px;}";
		$cssHtml .= $bodyClass . "#shipping_sg_checkout_location_picker{width:100%;}";
		$cssHtml .= $bodyClass . "#shipping_sg_checkout_location_picker input[type=text]{padding:12px 10px;}";
		$cssHtml .= "</style>";
		
		echo $cssHtml;


	}
}

WFACP_Plugin_Compatibilities::register( new The_Courier_Guy_Shipping_For_WC(), 'wfacp-the-courier-guy-shipping-for-wc' );

