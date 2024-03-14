<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Twilio_SMS {

	public $process_field = false;

	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
	}

	public function actions() {

		$this->process_field = true;
		$instance            = wc_twilio_sms();
		if ( $instance instanceof WC_Twilio_SMS ) {
			remove_action( 'woocommerce_after_checkout_billing_form', array( $instance, 'add_opt_in_checkbox' ) );
			add_action( 'wfacp_after_billing_email_field', array( $instance, 'add_opt_in_checkbox' ), 11 );
		}
	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( $key == 'wc_twilio_sms_optin' && true == $this->process_field ) {
			$args['class']       = [ 'wfacp-form-control-wrapper wfacp-col-full wfacp_checkbox_field' ];
			$args['cssready']    = [ 'wfacp-col-full' ];
			$args['input_class'] = [];
			$args['label_class'] = [];
		}

		return $args;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Twilio_SMS(), 'wc_twilio_sms' );
