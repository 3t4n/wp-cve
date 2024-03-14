<?php

#[AllowDynamicProperties]

  class WFACP_Aweber_Compatibilities {
	/***
	 * @var WC_Aweber_Checkout
	 */
	private $instance = null;

	public function __construct() {
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_filter( 'wfacp_html_fields_wfacp_aweber_field', '__return_false' );
		add_action( 'wfacp_after_template_found', [ $this, 'actions' ] );
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
	}

	private function is_enable() {
		return class_exists( 'WC_Aweber_Checkout' );
	}

	public function actions() {

		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_checkout_billing_form', 'WC_Aweber_Checkout', 'checkout_content' );
		if ( is_null( $this->instance ) ) {
			$this->instance = WFACP_Common::remove_actions( 'woocommerce_review_order_before_submit', 'WC_Aweber_Checkout', 'checkout_content' );
		}
	}

	public function add_fields( $field ) {
		if ( $this->is_enable() ) {
			$field['wfacp_aweber_field'] = [
				'type'          => 'wfacp_html',
				'default'       => false,
				'label'         => 'Aweber',
				'validate'      => [],
				'id'            => 'wfacp_aweber_field',
				'required'      => false,
				'wrapper_class' => [],
			];

		}

		return $field;
	}


	public function process_wfacp_html( $field, $key ) {
		if ( ! empty( $key ) && $key == 'wfacp_aweber_field' && $this->is_enable() ) {
			if ( $this->instance instanceof WC_Aweber_Checkout ) {
				$this->instance->checkout_content();
			}
		}

	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( 'subscribe_to_aweber' !== $key ) {
			return $args;
		}
		$all_cls             = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full wfacp_checkbox_field' ], $args['class'] );
		$input_class         = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
		$args['class']       = $all_cls;
		$args['input_class'] = $input_class;

		return $args;
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Aweber_Compatibilities(), 'aweber' );


