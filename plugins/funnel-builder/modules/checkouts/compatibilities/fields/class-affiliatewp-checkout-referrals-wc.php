<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AffiliateWP - Checkout Referrals by Sandhills Development, LLC (v.1.0.9)
 * Plugin Path : https://affiliatewp.com/add-ons/official-free/checkout-referrals/
 */
#[AllowDynamicProperties]

  class WFACP_AffiliateWP_Checkout_Referrals_WC {
	/**
	 * @var AffiliateWP_Checkout_Referrals
	 */
	private $instance = null;
	private $field_arg = null;
	private $actives = [];

	public function __construct() {
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_affiliate_checkout_referrals_wc', '__return_false' );
		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		/* Display Fields */
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 10, 3 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function is_enable() {
		return class_exists( 'AffiliateWP_Checkout_Referrals_WooCommerce' );
	}

	public function action() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'AffiliateWP_Checkout_Referrals_WooCommerce', 'affiliate_select_or_input' );
	}

	public function add_field( $fields ) {
		$fields['affiliate_checkout_referrals_wc'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'affiliate_checkout_referrals_wc' ],
			'id'         => 'affiliate_checkout_referrals_wc',
			'field_type' => 'affiliate_checkout_referrals_wc',
			'label'      => __( 'AffiliateWC Referrals', 'woofunnels-aero-checkout' ),
		];

		return $fields;
	}

	public function display_field( $field, $key ) {

		if ( ! $this->is_enable() || empty( $key ) || 'affiliate_checkout_referrals_wc' !== $key || ! $this->instance instanceof AffiliateWP_Checkout_Referrals_WooCommerce ) {
			return '';
		}

		?>
        <div class="wfacp_affiliate_referrals_wc" id="wfacp_affiliate_referrals_wc">
			<?php $this->instance->affiliate_select_or_input( WC()->checkout() ); ?>
        </div>
		<?php

	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( ! $this->is_enable() || empty( $key ) || 'affiliate_checkout_referrals_wc' !== $key ) {
			return $args;
		}
		$args['class']       = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
		$args['cssready']    = [ 'wfacp-col-full' ];
		$args['input_class'] = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
		$args['label_class'] = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );

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
		$px = $instance->get_template_type_px() . "px";

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_affiliate_referrals_wc {padding-left:$px;padding-right:$px;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_AffiliateWP_Checkout_Referrals_WC(), 'WFACP-affiliate-wp-checkout-referrals-wc' );
