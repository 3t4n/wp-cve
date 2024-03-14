<?php
/**
 * https://makewebbetter.com/
 * #[AllowDynamicProperties]

  class WFACP_Hubspot_MakeWebBetter_Compatibilities
 */

#[AllowDynamicProperties]

  class WFACP_Hubspot_MakeWebBetter_Compatibilities {
	/***
	 * @var Hubwoo
	 */
	private $instance = null;

	public function __construct() {
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_filter( 'wfacp_html_fields_wfacp_hubspot_makewebbetter_field', '__return_false' );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
	}

	public function actions() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_checkout_billing_form', 'Hubwoo_Public', 'hubwoo_pro_checkout_field' );

		$instance = WFACP_Common::remove_actions( 'woocommerce_after_checkout_billing_form', 'Hubwoo_Public', 'hubwoo_track_email_for_guest_users' );
		if ( $instance instanceof Hubwoo_Public && method_exists( $instance, 'hubwoo_track_email_for_guest_users' ) ) {
			add_action( 'wfacp_after_form', [ $instance, 'hubwoo_track_email_for_guest_users' ] );
		}
	}

	public function add_fields( $field ) {
		$field['wfacp_hubspot_makewebbetter_field'] = [
			'type'          => 'wfacp_html',
			'default'       => false,
			'label'         => 'HubSpot',
			'validate'      => [],
			'id'            => 'wfacp_hubspot_makewebbetter_field',
			'required'      => false,
			'wrapper_class' => [],
		];

		return $field;
	}


	public function process_wfacp_html( $field, $key ) {
		if ( $this->instance instanceof Hubwoo_Public && $key == 'wfacp_hubspot_makewebbetter_field' ) {
			$this->instance->hubwoo_pro_checkout_field( WC()->checkout() );
		}

	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( 'hubwoo_checkout_marketing_optin' == $key ) {
			$all_cls             = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full wfacp_checkbox_field' ], $args['class'] );
			$input_class         = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$args['class']       = $all_cls;
			$args['input_class'] = $input_class;
		}

		return $args;
	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Hubspot_MakeWebBetter_Compatibilities(), 'hubspot_mwb' );
