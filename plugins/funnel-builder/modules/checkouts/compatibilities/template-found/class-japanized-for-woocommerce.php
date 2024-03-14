<?php

/**
 * Author Artisan Workshop  https://wc.artws.info/
 * #[AllowDynamicProperties] 

  class WFACP_Japanized_for_Woocommerce
 *
 */
#[AllowDynamicProperties] 

  class WFACP_Japanized_for_Woocommerce {
	private $instance = null;

	public function __construct() {

		add_action( 'wfacp_internal_css', [ $this, 'capture_instance' ] );
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_filter( 'wfacp_html_fields_wfacp_wc4jp', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_wc4jp_hook' ], 10, 3 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );

	}

	public function capture_instance() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_before_order_notes', 'JP4WC_Delivery', 'delivery_date_designation' );

	}


	public function add_fields( $fields ) {
		if ( $this->is_enable() ) {
			$fields['wfacp_wc4jp'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'form-row-wide' ],
				'id'         => 'wfacp_wc4jp',
				'field_type' => 'advanced',
				'label'      => __( 'WooCommerce for Japan', 'woocommerce' ),

			];
		}

		return $fields;
	}

	public function call_wc4jp_hook() {
		if ( $this->instance instanceof JP4WC_Delivery ) {
			$this->instance->delivery_date_designation();
		}
	}

	public function is_enable() {
		if ( class_exists( 'JP4WC_Delivery' ) ) {
			return true;
		}

		return false;
	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( false !== strpos( $key, 'wc4jp_' ) ) {
			$all_cls             = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full' ], $args['class'] );
			$input_class         = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$args['class']       = $all_cls;
			$args['input_class'] = $input_class;
		}

		return $args;
	}
}

new WFACP_Japanized_for_Woocommerce();

