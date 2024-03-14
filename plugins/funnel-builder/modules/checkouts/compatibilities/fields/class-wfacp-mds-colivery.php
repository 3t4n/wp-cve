<?php

/**
 * MDS Collivery By MDS Technologies
 * Plugin URI: https://collivery.net/integration/woocommerce
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_Colivery {

	private $checkout_keys = [];
	private $temp_fields = [];


	public function __construct() {
		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_billing' ] );
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_shipping' ] );
		} else {
			add_action( 'init', [ $this, 'setup_fields_billing' ], 20 );
			add_action( 'init', [ $this, 'setup_fields_shipping' ], 20 );
		}

		add_action( 'wfacp_forms_field', [ $this, 'wfacp_forms_field' ], 20, 2 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'assign_data' ], 20 );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );


	}

	public function assign_data() {

		if ( false === $this->is_enabled() ) {
			return;
		}

		if ( ! class_exists( '\MdsSupportingClasses\MdsCheckoutFields' ) ) {
			return;
		}

		$mdsCheckoutFields = new \MdsSupportingClasses\MdsCheckoutFields( [] );

		if ( ! $mdsCheckoutFields instanceof MdsSupportingClasses\MdsCheckoutFields ) {
			return;
		}
		$address_fields['billing']  = $mdsCheckoutFields->getCheckoutFields( 'billing' );
		$address_fields['shipping'] = $mdsCheckoutFields->getCheckoutFields( 'shipping' );

		if ( ! is_array( $address_fields['billing'] ) ) {
			$address_fields['billing'] = [];
		}

		if ( ! is_array( $address_fields['shipping'] ) ) {
			$address_fields['shipping'] = [];
		}
		$this->temp_fields = array_merge( $address_fields['billing'], $address_fields['shipping'] );


	}


	public function setup_fields_billing() {

		if ( false === $this->is_enabled() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'city_int', array(
			'label'    => __( 'City Int', 'woocommerce' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => apply_filters( 'colivery_custom_address_field_class', array( 'form-row-third first', 'wfacp-col-full' ) ),
			'required' => false,
			'priority' => 60,
		) );

		new WFACP_Add_Address_Field( 'suburb', array(
			'label'    => __( 'Suburb', 'woocommerce' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => apply_filters( 'colivery_custom_address_field_class', array( 'form-row-third first', 'wfacp-col-full' ) ),
			'required' => true,
			'priority' => 60,
		) );


		new WFACP_Add_Address_Field( 'location_type', array(
			'label'    => __( 'Location Type', 'woocommerce' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => apply_filters( 'colivery_custom_address_field_class', array( 'form-row-third first', 'wfacp-col-full' ) ),
			'required' => true,
			'priority' => 60,
		) );

	}

	public function setup_fields_shipping() {

		if ( false === $this->is_enabled() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'city_int', array(
			'label'    => __( 'City Int', 'woocommerce' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => apply_filters( 'colivery_custom_address_field_class', array( 'form-row-third first', 'wfacp-col-full' ) ),
			'required' => false,
			'priority' => 60,
		), 'shipping' );
		new WFACP_Add_Address_Field( 'suburb', array(
			'label'    => __( 'Suburb', 'woocommerce' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => apply_filters( 'colivery_custom_address_field_class', array( 'form-row-third first', 'wfacp-col-full' ) ),
			'required' => true,

			'priority' => 60,
		), 'shipping' );
		new WFACP_Add_Address_Field( 'location_type', array(
			'label'    => __( 'Location Type', 'woocommerce' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => apply_filters( 'colivery_custom_address_field_class', array( 'form-row-third first', 'wfacp-col-full' ) ),
			'required' => true,
			'priority' => 60,
		), 'shipping' );


	}

	public function wfacp_forms_field( $field, $key ) {

		if ( false === $this->is_enabled() || empty( $key ) ) {
			return $field;
		}
		if ( ! is_array( $this->temp_fields ) || count( $this->temp_fields ) == 0 || ! isset( $this->temp_fields[ $key ] ) ) {
			return $field;
		}


		if ( isset( $this->temp_fields[ $key ]['class'] ) ) {

			if ( ! is_array( $field['class'] ) ) {
				$field['class'] = [];
			}
			$field['class'] = array_merge( $field['class'], $this->temp_fields[ $key ]['class'] );
		}

		if ( isset( $this->temp_fields[ $key ]['label'] ) ) {
			$field['label'] = $this->temp_fields[ $key ]['label'];
		}
		if ( isset( $this->temp_fields[ $key ]['type'] ) ) {
			$field['type'] = $this->temp_fields[ $key ]['type'];
		}

		if ( isset( $this->temp_fields[ $key ]['options'] ) ) {
			$field['options'] = $this->temp_fields[ $key ]['options'];
			$field['class'][] = 'wfacp-anim-wrap';
		}

		if ( isset( $this->temp_fields[ $key ]['required'] ) ) {
			$field['required'] = $this->temp_fields[ $key ]['required'];
		}
		if ( isset( $this->temp_fields[ $key ]['placeholder'] ) ) {
			$field['placeholder'] = $this->temp_fields[ $key ]['placeholder'];
		}


		return $field;

	}

	public function is_enabled() {
		return class_exists( 'MdsColliveryService' );
	}


	public function add_internal_css() {
		if ( ! $this->is_enabled() || ! function_exists( 'wfacp_template' ) ) {
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

		echo "<style>";
		echo $bodyClass . '.colliveryfied p.wfacp_shipping_field_hide.active{display:none !important;}';
		echo $bodyClass . '.colliveryfied p.wfacp_billing_field_hide.active{display:none !important;}';
		echo $bodyClass . '.colliveryfied p.wfacp_billing_field_show.active{display:block !important;}';
		echo $bodyClass . '.colliveryfied p.wfacp_shipping_field_show.active{display:block !important;}';
		echo $bodyClass . '.colliveryfied p.inactive{display:none !important;} .colliveryfield .wfacp_shipping_fields.wfacp_shipping_field_hide {
                           display: none !important;
							}
#wfacp-e-form .wfacp_main_form .select2-container .select2-selection--single .select2-selection__rendered, #wfacp-e-form .wfacp_main_form .woocommerce-checkout select#join_referral_program {
    line-height: 1.5;
}
';
		echo "</style>";

	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Colivery(), 'mds-colivery' );

