<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce - Active Campaign Integration By Jason Kadlec: ActiveWoo.com
 * Plugin URI: http://activewoo.com
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_WC_Active_Campaign_Integration {
	/**
	 * @var AW_Newsletter
	 */
	private $instance = null;


	public function __construct() {

		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ] );
		add_action( 'process_wfacp_html', [ $this, 'add_field_hook' ], 10, 3 );
		add_filter( 'wfacp_html_fields_wfacp-aw-news-letter', '__return_false' );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
	}

	public function add_field( $field ) {

		$field['wfacp-aw-news-letter'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp_active_campaign_integration' ],
			'id'         => 'wfacp-aw-news-letter',
			'field_type' => 'advanced',
			'label'      => __( 'ActiveWoo', 'woofunnels-aero-checkout' ),

		];

		return $field;
	}


	public function add_field_hook( $field, $key, $args ) {

		if ( ! empty( $key ) && $key == 'wfacp-aw-news-letter' ) {
			$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'AW_Newsletter', 'newsletter_checkout_field' );
			if ( $this->instance instanceof AW_Newsletter ) {
				$this->instance->newsletter_checkout_field( WC()->checkout() );
			}
		}

	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( $key == 'aw-news-letter' ) {
			$all_cls          = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$args['class']    = $all_cls;
			$args['cssready'] = [ 'wfacp-col-full' ];
		}

		return $args;
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Active_Campaign_Integration(), 'wfacp-wcaci' );
