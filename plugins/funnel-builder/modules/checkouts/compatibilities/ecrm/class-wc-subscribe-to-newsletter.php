<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_WC_Subscribe_To_Newsletter {

	private $wc_news_obj = null;
	private $field_arg = null;

	public function __construct() {

		add_action( 'init', [ $this, 'init_class' ], 4 );
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_news_field' ] );
		add_filter( 'wfacp_html_fields_wc_subscribe_to_newsletter', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_wc_news_hook' ], 10, 3 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_action( 'wfacp_after_template_found', [ $this, 'remove_action' ] );

	}

	public function remove_action() {
		$location = apply_filters( 'wc_newsletter_subscription_checkout_content_location', 'after_terms' );
		if ( 'after_billing' === $location ) {
			WFACP_Common::remove_actions( 'woocommerce_after_checkout_billing_form', 'WC_Newsletter_Subscription_Checkout', 'checkout_content' );
		} else {
			WFACP_Common::remove_actions( 'woocommerce_review_order_before_submit', 'WC_Newsletter_Subscription_Checkout', 'checkout_content' );
		}

	}

	public function init_class() {

		if ( ! isset( $GLOBALS['WC_Subscribe_To_Newsletter'] ) || ! $GLOBALS['WC_Subscribe_To_Newsletter'] instanceof WC_Subscribe_To_Newsletter ) {
			return '';
		}
		$this->wc_news_obj = $GLOBALS['WC_Subscribe_To_Newsletter'];
	}


	public function add_news_field( $field ) {
		$field['wc_subscribe_to_newsletter'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'form-row-wide' ],
			'id'         => 'wc_subscribe_to_newsletter',
			'field_type' => 'advanced',
			'label'      => __( 'Subscribe to Newsletter', 'woocommerce' ),

		];

		return $field;
	}

	public function call_wc_news_hook( $field, $key, $args ) {

		if ( empty( $key ) || $key !== 'wc_subscribe_to_newsletter' ) {
			return '';
		}
		$this->field_arg = $args;
		if ( $this->wc_news_obj instanceof WC_Subscribe_To_Newsletter && method_exists( $this->wc_news_obj, 'newsletter_field' ) ) {
			$this->wc_news_obj->newsletter_field( WC()->checkout() );

			return '';
		}

		$checkout = WC()->checkout();
		$fields   = $checkout->get_checkout_fields( 'newsletter' );
		if ( empty( $fields ) ) {
			return '';
		}
		foreach ( $fields as $key => $field ) {
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}

	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( $key !== 'subscribe_to_newsletter' || is_null( $this->field_arg ) ) {
			return $args;
		}

		$all_cls = array_merge( [ 'wfacp-form-control-wrapper wfacp_custom_field_cls wfacp_drip_wrap' ], $args['class'] );
		if ( isset( $this->field_arg['cssready'] ) && is_array( $this->field_arg['cssready'] ) ) {
			$all_cls = array_merge( $all_cls, $this->field_arg['cssready'] );
		}
		$args['class'] = $all_cls;

		return $args;
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Subscribe_To_Newsletter(), 'wcac' );
