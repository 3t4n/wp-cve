<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * https://wordpress.org/plugins/woo-postnl
 * this official woocommerce plugin for PostNl Field
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_With_Woo_PostNl
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Woo_PostNl {


	public $instance = null;

	public function __construct() {
		add_action( 'init', [ $this, 'setup_fields_billing' ], 20 );
		add_action( 'init', [ $this, 'setup_fields_shipping' ], 20 );

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_action( 'wfacp_css_js_removal_paths', [ $this, 'remove_style' ] );
		add_filter( 'wfacp_template_localize_data', [ $this, 'remove_optional_shipping_field_validation_error' ] );

		// Ajax Actions
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'validation_fields' ] );


	}

	public function is_enabled() {


		if ( class_exists( 'Woocommerce_PostNL_Postcode_Fields' ) || class_exists( 'WCPOST' ) ) {

			$options = get_option( 'woocommerce_postnl_checkout_settings', [] );

			if ( isset( $options['use_split_address_fields'] ) && wc_string_to_bool( $options['use_split_address_fields'] ) ) {
				return true;
			}
		}

		return false;
	}

	public function remove_style( $path ) {
		$path[] = 'woo-postnl/assets/css/nl-checkout.css';

		return $path;
	}

	public function setup_fields_billing() {

		if ( false == $this->is_enabled() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'street_name', array(
			'label'    => __( 'Street name', 'woocommerce-postnl' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third first', 'wfacp-col-full' ) ),
			'required' => false, // Only required for NL
			'priority' => 60,
		) );

		new WFACP_Add_Address_Field( 'house_number', array(
			'label'    => __( 'No.', 'woocommerce-postnl' ),
			'cssready' => [ 'wfacp-col-left-half' ],
			'class'    => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third', 'wfacp-col-left-half' ) ),
			'required' => false, // Only required for NL
			'type'     => 'number',
			'priority' => 61,
		) );

		new WFACP_Add_Address_Field( 'house_number_suffix', array(
			'label'     => __( 'Suffix', 'woocommerce-postnl' ),
			'cssready'  => [ 'wfacp-col-left-half' ],
			'class'     => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third last', 'wfacp-col-left-half' ) ),
			'required'  => false,
			'maxlength' => 4,
			'priority'  => 62,
		) );

	}

	public function setup_fields_shipping() {
		if ( false == $this->is_enabled() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'street_name', array(
			'label'    => __( 'Street name', 'woocommerce-postnl' ),
			'cssready' => [ 'wfacp-col-full' ],
			'class'    => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third first', 'wfacp-col-full' ) ),
			'required' => false, // Only required for NL
			'priority' => 60,
		), 'shipping' );

		new WFACP_Add_Address_Field( 'house_number', array(
			'label'    => __( 'No.', 'woocommerce-postnl' ),
			'cssready' => [ 'wfacp-col-left-half' ],
			'class'    => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third', 'wfacp-col-left-half' ) ),
			'required' => false, // Only required for NL
			'type'     => 'number',
			'priority' => 61,
		), 'shipping' );

		new WFACP_Add_Address_Field( 'house_number_suffix', array(
			'label'     => __( 'Suffix', 'woocommerce-postnl' ),
			'cssready'  => [ 'wfacp-col-left-half' ],
			'class'     => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third last', 'wfacp-col-left-half' ) ),
			'required'  => false,
			'maxlength' => 4,
			'priority'  => 62,
		), 'shipping' );

	}


	public function remove_optional_shipping_field_validation_error( $data ) {
		$data['wc_customizer_validation_status']['shipping_house_number_suffix_field'] = 'wfacp_required_optional';
		$data['wc_customizer_validation_status']['billing_house_number_suffix_field']  = 'wfacp_required_optional';


		return $data;
	}

	public function actions() {
		add_filter( 'woocommerce_country_locale_field_selectors', function ( $locale_fields ) {
			if ( ! $this->is_enabled() ) {
				return $locale_fields;
			}
			$locale_fields['address_1'] = '#billing_address_1_field, #shipping_address_1_field';
			$locale_fields['address_2'] = '#billing_address_2_field, #shipping_address_2_field';

			return $locale_fields;
		}, 50 );
	}


	public function validation_fields() {
		add_filter( 'wfacp_checkout_fields', [ $this, 'make_validation' ] );
	}

	public function make_validation( $template_fields ) {

		if ( ! $this->is_enabled() ) {
			return $template_fields;
		}
		$obj = null;
		if ( class_exists( 'WCPOST' ) ) {
			$obj = WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'WCPN_NL_Postcode_Fields', 'modifyBillingFields' );
		} else if ( class_exists( 'Woocommerce_PostNL_Postcode_Fields' ) ) {
			$obj = WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'Woocommerce_PostNL_Postcode_Fields', 'nl_billing_fields' );
		}
		$billing_country = WC()->checkout()->get_value( 'billing_country' );

		if ( isset( $template_fields['billing'] ) ) {
			$required = false;

			if ( ! is_null( $obj ) ) {
				$required = ( $billing_country == 'NL' || $billing_country == 'BE' ) ? true : false;
			}

			$form = 'billing';
			if ( isset( $template_fields['billing'][ $form . '_street_name' ] ) ) {
				$template_fields['billing'][ $form . '_street_name' ]['required'] = $required;
			}

			if ( isset( $template_fields['billing'][ $form . '_house_number' ] ) ) {
				$template_fields['billing'][ $form . '_house_number' ]['required'] = $required;
			}
		}

		$shipping_country = WC()->checkout()->get_value( 'shipping_country' );


		if ( isset( $template_fields['shipping'] ) ) {
			$required = false;
			if ( ! is_null( $obj ) ) {
				$required = ( $shipping_country == 'NL' || $shipping_country == 'BE' ) ? true : false;
			}

			$form = 'shipping';
			if ( isset( $template_fields[ $form ][ $form . '_street_name' ] ) ) {
				$template_fields[ $form ][ $form . '_street_name' ]['required'] = $required;
			}

			if ( isset( $template_fields[ $form ][ $form . '_house_number' ] ) ) {
				$template_fields[ $form ][ $form . '_house_number' ]['required'] = $required;
			}
		}

		return $template_fields;
	}

	public function myparcel_actions() {

		if ( ! class_exists( 'WCPOST' ) || ! class_exists( 'WCPN_Checkout' ) ) {
			return;
		}

		$this->instance = WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'WCPN_Checkout', 'enqueue_frontend_scripts' );

		$hook = WCPOST()->setting_collection->getByName( WCPOST_Settings::SETTING_DELIVERY_OPTIONS_POSITION );

		WFACP_Common::remove_actions( $hook, 'WCPN_Checkout', 'output_delivery_options' );

		add_action( "wp_enqueue_scripts", [ $this->instance, "enqueue_frontend_scripts" ], 200 );


	}

	public function add_field( $fields ) {

		$fields['wfacp_postnl_parcel'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_postnl_parcel' ],
			'id'         => 'wfacp_postnl_parcel',
			'field_type' => 'wfacp_postnl_parcel',
			'label'      => __( 'Postnl Parcel', 'woofunnels-aero-checkout' ),

		];

		return $fields;
	}

	public function display_field( $field, $key ) {


		if ( empty( $key ) || 'wfacp_postnl_parcel' !== $key || ! $this->instance instanceof WCPN_Checkout ) {
			return '';
		}
		echo '<div id=wfacp_post_nl>';
		$this->instance->output_delivery_options();
		echo '</div>';

	}

	public function add_internal() {
		if ( ! $this->instance instanceof WCPN_Checkout ) {
			return '';
		}
		?>

        <style>
            #wfacp_post_nl {
                clear: both;
                padding: 0 7px;
            }

            #wfacp_post_nl form.myparcel-delivery-options * {
                text-align: left;
            }

            #wfacp_post_nl form.myparcel-delivery-options table {
                text-align: left;
                margin: 0;
            }

            #wfacp_post_nl form.myparcel-delivery-options input[type=radio] {
                position: relative !important;
                left: auto !important;
            }

            #wfacp_post_nl .myparcel-delivery-options table.myparcel-delivery-options__table td label {
                display: block;
            }


            #wfacp_post_nl form.myparcel-delivery-options button {
                padding: 10px 30px;

                font-size: 14px;
                line-height: 1.5;
            }

            #wfacp_post_nl p,
            #wfacp_post_nl p > label,
            #wfacp_post_nl p input[type=text] {
                width: 100% !important;
                display: block;
            }
        </style>
		<?php
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Woo_postnl(), 'woo_postnl' );

