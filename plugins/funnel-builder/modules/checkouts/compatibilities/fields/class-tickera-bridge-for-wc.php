<?php

/**
 * Tickera Bridge for WooCommerce by Tickera
 * Plugin URI: https://tickera.com/
 */

#[AllowDynamicProperties]
class Tickera_Bridge_For_WC {
	public $instance = null;
	private $tc_general_settings = [];


	public function __construct() {


		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_tickera_bridge_for_wc', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );

		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'action' ], 20 );


		/* internal css for plugin */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}


	public function add_field( $fields ) {


		$fields['tickera_bridge_for_wc'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'tickera_bridge_for_wc' ],
			'id'         => 'tickera_bridge_for_wc',
			'field_type' => 'tickera_bridge_for_wc',
			'label'      => __( 'Tickera WC', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function action() {

		$this->tc_general_settings = get_option( 'tc_general_setting', false );
		$this->instance            = WFACP_Common::remove_actions( 'woocommerce_checkout_after_customer_details', 'TC_WooCommerce_Bridge', 'add_standard_tc_fields_to_checkout' );
	}

	public function display_field( $field, $key ) {

		if ( ! $this->is_enable() || empty( $key ) || 'tickera_bridge_for_wc' !== $key ) {

			return '';
		}

		?>
        <div class="tickera_wrap" id="tickera_wrap">
			<?php
			$this->instance->add_standard_tc_fields_to_checkout();
			?>
        </div>


		<?php


	}

	public function is_enable() {
		if ( is_null( $this->instance ) || ! $this->instance instanceof TC_WooCommerce_Bridge || "no" === $this->tc_general_settings['show_owner_fields'] ) {
			return false;
		}

		return true;

	}

	public function internal_css() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body";
		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
		}

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#tickera_wrap p:empty,#tickera_wrap div:empty,#tickera_wrap span:empty {display: none;}";
		$cssHtml .= $bodyClass . "#tickera_wrap .tickera_additional_info h1,#tickera_wrap .tickera_additional_info h2,#tickera_wrap .tickera_additional_info h3,#tickera_wrap .tickera_additional_info h4,#tickera_wrap .tickera_additional_info h5,#tickera_wrap .tickera_additional_info h6,#tickera_wrap .tickera_additional_info p {margin: 0 0 15px;}";
		$cssHtml .= $bodyClass . "#tickera_wrap input[type=text],#tickera_wrap input[type=email]{padding: 10px 15px;}";
		$cssHtml .= "</style>";

		echo $cssHtml;

	}

}

WFACP_Plugin_Compatibilities::register( new Tickera_Bridge_For_WC(), 'wfacp-tickera-bridge-for-wc' );
