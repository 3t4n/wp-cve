<?php

/**
 * Name: WooCommerce Disability VAT Exemption by WooCommerce (up to 1.4.4)
 * URL: https://woocommerce.com/products/disability-vat-exemption/
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties]

  class WFACP_WC_Disability_Vat_Exemption {
	public $instance = null;

	public function __construct() {


		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_disability_vat_exemption', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 3 );

		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		/* internal css for plugin */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function action() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'WC_Disability_VAT_Exemption', 'exemption_field' );
	}

	public function add_field( $fields ) {


		$fields['disability_vat_exemption'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_disability_vat_exemption' ],
			'id'         => 'disability_vat_exemption',
			'field_type' => 'disability_vat_exemption',
			'label'      => __( 'WC Disability VAT Exemption', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function display_field( $field, $key, $args ) {

		if ( ! $this->is_enable() || empty( $key ) || 'disability_vat_exemption' !== $key ) {
			return '';
		}
		echo "<div id=wfacp_exemption_field>";
		$this->instance->exemption_field();
		echo "</div>";


	}

	public function is_enable() {

		if ( ! is_null( $this->instance ) && $this->instance instanceof WC_Disability_VAT_Exemption ) {
			return true;
		}

		return false;

	}

	public function internal_css() {

		if ( ! $this->is_enable() || ! function_exists( 'wfacp_template' ) ) {
			return '';
		}

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body";
		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
		}


		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_exemption_field{margin-bottom: 25px;padding: 0 7px;}";
		$cssHtml .= $bodyClass . "#wfacp_exemption_field input[type='checkbox'],$bodyClass #wfacp_exemption_field input[type='radio']{position: relative;left: auto;right: auto;top: auto;bottom: auto;margin: 0 10px 0 0;}";
		$cssHtml .= $bodyClass . "#wfacp_exemption_field #wdve_radio_exemption label{padding-left: 0;margin-bottom: 15px;display: inline-block;font-size: 14px;color: #333333;line-height: 14px;}";
		$cssHtml .= $bodyClass . "#wfacp_exemption_field h3{ margin: 0 0 10px;font-size: 16px;line-height: 1.5;color: #333333;}";
		$cssHtml .= $bodyClass . "#wfacp_exemption_field input{padding: 10px 15px;}";
		$cssHtml .= $bodyClass . "#wfacp_exemption_field #wdve_radio_exemption {margin-bottom: 15px;}";
		$cssHtml .= $bodyClass . "#wfacp_exemption_field label {float: none;clear: both;display: block;width: auto;}";
		$cssHtml .= $bodyClass . "#wfacp_exemption_field input[type='text'],$bodyClass #wfacp_exemption_field input[type='date'],$bodyClass #wfacp-e-form #wfacp_exemption_field input[type='number'] {width: 100%;text-align: left;float: none;}";
		$cssHtml .= "</style>";

		echo $cssHtml;


	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_WC_Disability_Vat_Exemption(), 'wfacp-wdve' );

