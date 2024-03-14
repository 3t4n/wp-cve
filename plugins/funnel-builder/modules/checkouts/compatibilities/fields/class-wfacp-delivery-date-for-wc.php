<?php

/**
 * WooCommerce - Delivery Date for WooCommerce by Pixlogix
 * Plugin URL - https://wordpress.org/plugins/delivery-date-for-woocommerce/
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_Delivery_Date_For_WC {
	private $object = null;
	private $ddfw_version = '1.0.0';
	private $ddfw_plugin_name = 'delivery_date_for_woocommerce';


	public function __construct() {

		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 50, 3 );
		add_filter( 'wfacp_html_fields_wfacp_ddfw_enable_delivery', '__return_false' );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function add_field( $fields ) {
		if ( $this->is_enabled() ) {
			$fields['wfacp_ddfw_enable_delivery'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'wfacp_ddfw_enable_delivery' ],
				'id'         => 'wfacp_ddfw_enable_delivery',
				'field_type' => 'advanced',
				'label'      => __( 'Delivery Date', 'woofunnels-aero-checkout' ),
			];
		}

		return $fields;
	}

	public function action() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		if ( defined( 'DELIVERY_DATE_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->ddfw_version = DELIVERY_DATE_FOR_WOOCOMMERCE_VERSION;
		}
		$this->object = new DDFW_Public( $this->ddfw_plugin_name, $this->ddfw_version );
	}

	public function call_fields_hook( $field, $key, $args ) {

		if ( $this->is_enabled() && ( ! empty( $key ) && ( 'wfacp_ddfw_enable_delivery' === $key ) ) && $this->object instanceof DDFW_Public ) {
			$this->object->ddfw_delivery_options( WC()->checkout() );
		}
	}


	public function add_default_wfacp_styling( $args, $key ) {

		if ( $key == 'ddfw_delivery_date' ) {

			$args['input_class'] = array_merge( $args['input_class'], [ 'wfacp-form-control' ] );
			$args['label_class'] = array_merge( $args['label_class'], [ 'wfacp-form-control-label' ] );
			$args['class']       = array_merge( $args['class'], [ 'wfacp-col-left-half', 'wfacp-form-control-wrapper', 'wfacp-delivery-date-for-wc' ] );
		}

		return $args;
	}


	public function is_enabled() {
		return class_exists( 'DDFW_Public' );
	}

	public function internal_css() {
		if ( ! $this->is_enabled() || ! function_exists( 'wfacp_template' ) ) {
			return;
		}


		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$px        = "7";
			$bodyClass = "body #wfacp-e-form";
		}

		echo "<style>";
		echo $bodyClass . ' .wfacp_main_form.woocommerce .wfacp-delivery-date-for-wc input#datepicker{padding-left: 32px;}';
		echo $bodyClass . ' .wfacp_main_form.woocommerce .wfacp-delivery-date-for-wc .ddfwCalander:before{width: 20px;height: 20px;background-size: 20px;top: 50%;margin-top: -10px;}';
		echo $bodyClass . ' .wfacp_main_form.woocommerce .wfacp-delivery-date-for-wc label.wfacp-form-control-label{left: 40px;}';
		echo $bodyClass . ' .wfacp_main_form.woocommerce p.wfacp-form-control-wrapper.wfacp-delivery-date-for-wc.wfacp-anim-wrap label.wfacp-form-control-label{left: 40px;}';
		echo $bodyClass . ' .wfacp_main_form.woocommerce span.woocommerce-input-wrapper.ddfwCalander{position: relative;display: block;}';
		echo "</style>";


	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Delivery_Date_For_WC(), 'wfacp-delivery-date-for-wc' );
