<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Convert_kit_WC {

	private $enabled;
	private $display_opt_in;
	private $opt_in_label;
	private $opt_in_status;
	private $actives = [];


	public function __construct() {

		add_filter( 'init', [ $this, 'init_class' ] );
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_ckwc_field' ] );
		add_filter( 'wfacp_html_fields_ckwc_opt_in', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_wc_drip_hook' ], 10, 3 );

	}

	public function init_class() {


		$instance = WFACP_Common::remove_actions( 'woocommerce_checkout_fields', 'CKWC_Integration', 'add_opt_in_checkbox' );

		if ( $instance instanceof CKWC_Integration && ! empty( $instance ) ) {
			$this->enabled        = $instance->get_option( 'enabled' );
			$this->display_opt_in = $instance->get_option( 'display_opt_in' );
			$this->opt_in_label   = $instance->get_option( 'opt_in_label' );
			$this->opt_in_status  = $instance->get_option( 'opt_in_status' );

			if ( 'yes' === $this->enabled && 'yes' === $this->display_opt_in ) {
				$this->actives['CKWC_Integration'] = $instance;

			}
		}
	}

	public function add_ckwc_field( $field ) {

		if ( $this->is_enable( 'CKWC_Integration' ) ) {
			$field['ckwc_opt_in'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'ckcw_subscribe' ],
				'id'         => 'ckwc_opt_in',
				'field_type' => 'advanced',
				'label'      => __( 'ConvertKit', 'woofunnels-aero-checkout' ),

			];
		}

		return $field;
	}

	public function is_enable( $slug ) {
		if ( isset( $this->actives[ $slug ] ) ) {
			return true;
		}

		return false;
	}

	public function call_wc_drip_hook( $field, $key, $args ) {

		if ( ! empty( $key ) && $key == 'ckwc_opt_in' && $this->is_enable( 'CKWC_Integration' ) ) {

			$all_cls = array_merge( [ 'wfacp-form-control-wrapper wfacp_custom_field_cls wfacp_ckwc_wrap' ], $args['class'] );

			$args = array(
				'type'    => 'checkbox',
				'id'      => $key,
				'class'   => $all_cls,
				'label'   => $this->opt_in_label,
				'default' => 'checked' === $this->opt_in_status,

			);

			woocommerce_form_field( $key, $args );

		}

	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Convert_kit_WC(), 'convert-kit-wc' );
