<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Plugin Name MyParcel by MyParcel v.4.21.0
 * Plugin URI: https://myparcel.nl/
 *
 */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Parcel {
	/**
	 * @var WooCommerce_MyParcel_Frontend
	 */
	private $instance = null;
	private $instance_new = null;
	private $billing_fields_added = false;
	private $shipping_fields_added = false;
	private $billing_country_nl = false;
	private $shipping_country_nl = false;
	private $enable_plugin = false;
	private $wcl_parcel_field_keys = [];

	public $fragment_obj = null;

	private $classess = [
		'old' => [ 'wfacp-col-left-half', 'wfacp-col-middle-third', 'wfacp-col-right-third' ],
		'new' => [ 'wfacp-col-left-half', 'wfacp-col-left-third', 'wfacp-col-left-third' ]
	];

	public function __construct() {
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_filter( 'wfacp_html_fields_wfacp_postcode_my_parcel', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'postcode_my_parcel' ], 10, 3 );
		add_action( 'wfacp_internal_css', [ $this, 'get_instance' ], 101 );
		add_filter( 'wfacp_form_section', [ $this, 'checkout_billing_sections' ] );
		add_filter( 'wfacp_form_section', [ $this, 'checkout_shipping_sections' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'validation_fields' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

		add_filter( 'wfacp_template_localize_data', [ $this, 'remove_optional_shipping_field_validation_error' ] );
		add_filter( 'woocommerce_country_locale_field_selectors', [ $this, 'remove_street_address' ], 50 );

		add_filter( 'wfacp_update_posted_data_vice_versa_keys', [ $this, 'update_address_data' ] );
		add_filter( 'wfacp_unset_vice_versa_keys_shipping_keys', [ $this, 'unset_shipping_address_data' ] );

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		add_filter( 'wfacp_checkout_before_order_review', [ $this, 'add_actions' ], 9 );
	}

	public function action() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		add_action( 'wp_head', function () {
			$position           = WCMYPA()->setting_collection->getByName( WCMYPA_Settings::SETTING_DELIVERY_OPTIONS_POSITION );
			$this->instance_new = WFACP_Common::remove_actions( $position, 'WCMP_Checkout', 'output_delivery_options' );
		} );

	}

	private function is_enabled() {
		if ( class_exists( 'WooCommerce_MyParcel_Frontend' ) ) {
			if ( class_exists( 'WPO_WCNLPC_Checkout' ) ) {
				return true;
			}
			$this->enable_plugin = true;
		} elseif ( class_exists( 'WCMYPA' ) && version_compare( PHP_VERSION, \WCMYPA::PHP_VERSION_7_1, '>=' ) ) {
			$this->enable_plugin = true;
		}

		if ( $this->enable_plugin === true ) {
			$options = get_option( 'woocommerce_myparcel_checkout_settings', [] );
			if ( isset( $options['use_split_address_fields'] ) && wc_string_to_bool( $options['use_split_address_fields'] ) ) {
				return true;
			}
		}


		return false;
	}

	public function add_fields( $fields ) {
		if ( $this->is_enabled() ) {
			$fields['wfacp_postcode_my_parcel'] = [
				'type'    => 'wfacp_html',
				'default' => '',
				'label'   => __( 'My Parcel', 'woocommerce-aero-checkout' ),
				'id'      => 'wfacp_postcode_my_parcel',
			];
		}

		return $fields;
	}

	public function postcode_my_parcel( $field, $key, $args ) {
		if ( ! empty( $key ) && 'wfacp_postcode_my_parcel' === $key && $this->is_enabled() ) {
			if ( ! is_null( $this->instance ) ) {
				echo "<div id=wfacp_output_delivery_options>";
				$this->instance->output_delivery_options();
				echo "</div>";
			}

		}
	}

	public function get_instance() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_thankyou', 'WooCommerce_MyParcel_Frontend', 'thankyou_pickup_html' );
		if ( defined( 'WC_MYPARCEL_NL_VERSION' ) ) {
			$this->instance = $this->instance_new;
		}

	}

	public function remove_street_address( $locale_fields ) {
		if ( ! $this->is_enabled() ) {
			return $locale_fields;
		}
		$locale_fields['address_1'] = '#billing_address_1_field, #shipping_address_1_field';
		$locale_fields['address_2'] = '#billing_address_2_field, #shipping_address_2_field';

		return $locale_fields;
	}

	public function validation_fields() {
		add_filter( 'wfacp_checkout_fields', [ $this, 'make_validation' ] );
	}

	public function make_validation( $template_fields ) {
		if ( ! $this->is_enabled() ) {
			return $template_fields;
		}


		$template = WFACP_Core()->customizer->get_template_instance();
		if ( is_null( $template ) ) {
			return $template_fields;
		}
		$countries       = [ 'NL', 'BE' ];
		if ( $template->have_billing_address() ) {
			WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'Woocommerce_MyParcel_Postcode_Fields', 'nl_billing_fields' );
			$billing_country = WC()->checkout()->get_value( 'billing_country' );

			$required        = in_array( $billing_country, $countries ) ? true : false;


			if ( ! isset( $_REQUEST['billing_same_as_shipping'] ) && isset( $_POST['ship_to_different_address'] ) && isset( $_POST['wfacp_billing_same_as_shipping'] ) && $_POST['wfacp_billing_same_as_shipping'] == 0 ) {
				$required = false;
			}


			if ( isset( $template_fields['billing'] ) ) {
				$form                                                         = 'billing';
				$template_fields['billing'][ $form . '_street_name' ]         = [
					'label'       => __( 'Street name', 'woocommerce-myparcel' ),
					'placeholder' => __( 'Street name', 'woocommerce-myparcel' ),
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third first wfacp_street_name' ) ),
					'required'    => $required,
				];
				$template_fields['billing'][ $form . '_house_number' ]        = array(
					'label'             => __( 'No.', 'woocommerce-myparcel' ),
					'placeholder'       => __( 'No.', 'woocommerce-myparcel' ),
					'custom_attributes' => array( 'pattern' => '[0-9]*' ),
					'type'              => 'number',
					'class'             => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third wfacp_house_number' ) ),
					'required'          => $required,
				);
				$template_fields['billing'][ $form . '_house_number_suffix' ] = array(
					'label'       => __( 'Suffix', 'woocommerce-myparcel' ),
					'placeholder' => __( 'Suffix', 'woocommerce-myparcel' ),
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'wfacp_house_number_suffix' ) ),
					'type'        => 'text',
					'id'          => $form . '_house_number_suffix',
					'required'    => false,

				);
			}
		}
		if ( $template->have_shipping_address() ) {
			$shipping_country = WC()->checkout()->get_value( 'shipping_country' );
			$required         = in_array( $shipping_country, $countries ) ? true : false;
			if ( isset( $template_fields['shipping'] ) ) {
				$form                                                   = 'shipping';
				$template_fields['shipping'][ $form . '_street_name' ]  = [
					'label'       => __( 'Street name', 'woocommerce-myparcel' ),
					'placeholder' => __( 'Street name', 'woocommerce-myparcel' ),
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third first wfacp_street_name' ) ),
					'required'    => $required,
				];
				$template_fields['shipping'][ $form . '_house_number' ] = array(
					'label'             => __( 'No.', 'woocommerce-myparcel' ),
					'placeholder'       => __( 'No.', 'woocommerce-myparcel' ),
					'custom_attributes' => array( 'pattern' => '[0-9]*' ),
					'type'              => 'number',
					'class'             => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third wfacp_house_number' ) ),
					'required'          => $required,
				);

				$template_fields['shipping'][ $form . '_house_number_suffix' ] = array(
					'label'       => __( 'Suffix', 'woocommerce-myparcel' ),
					'placeholder' => __( 'Suffix', 'woocommerce-myparcel' ),
					'type'        => 'text',
					'id'          => $form . '_house_number_suffix',
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'wfacp_house_number_suffix' ) ),
					'required'    => false,

				);
			}
		}

		return $template_fields;
	}

	public function checkout_billing_sections( $sections ) {
		if ( $this->billing_fields_added ) {
			return $sections;
		}
		if ( count( $sections ) == 0 ) {
			return $sections;
		}
		if ( ! $this->is_enabled() ) {
			return $sections;
		}
		$page_version = WFACP_Common::get_checkout_page_version();

		if ( version_compare( $page_version, '2.1.3', '>' ) ) {

			return $sections;
		}


		if ( isset( $sections['fields']['wfacp_end_divider_billing'] ) ) {
			try {
				$this->billing_fields_added = true;
				$end_address_found          = false;
				$end_address_closser        = $sections['fields']['wfacp_end_divider_billing'];
				$after_address_element      = [];
				$is_hidedable               = false;
				$keysVal                    = [];
				foreach ( $sections['fields'] as $index => $field ) {
					if ( isset( $field['id'] ) && isset( $field['priority'] ) ) {
						$keysVal[ $field['id'] ] = $field['priority'];
					}
					if ( $end_address_found ) {
						$after_address_element[] = $field;
						unset( $sections['fields'][ $index ] );
					}
					if ( isset( $field['class'] ) && in_array( 'wfacp_billing_fields', $field['class'] ) ) {
						$is_hidedable = true;
					}
					if ( 'wfacp_end_divider_billing' === $index ) {
						unset( $sections['fields'][ $index ] );
						$end_address_found = true;
					}
				}
				if ( false == $end_address_found ) {
					return $sections;
				}
				$new_fields = array();
				WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'Woocommerce_MyParcel_Postcode_Fields', 'nl_billing_fields' );
				$country   = WC()->checkout()->get_value( 'billing_country' );
				$countries = [ 'NL', 'BE' ];
				// Set required to true if country is NL
				$required = in_array( $country, $countries ) ? true : false;
				if ( true == $required ) {
					$this->billing_country_nl = true;
				}
				$form         = 'billing';
				$templateSlug = WFACP_Core()->customizer->get_template_instance()->get_template_slug();

				$version = WFACP_Common::get_checkout_page_version();

				$class1 = $this->classess['new'][0];
				$class2 = $this->classess['new'][1];
				$class3 = $this->classess['new'][2];


				if ( strpos( $templateSlug, 'embed_forms_' ) !== false ) {
					$class1 = 'wfacp-col-full';

				}

				// Add street name
				$new_fields[] = array(
					'label'       => __( 'Street name', 'woocommerce-myparcel' ),
					'placeholder' => __( 'Street name', 'woocommerce-myparcel' ),
					'cssready'    => [ "wfacp_wc_parcel $class1" ],
					'id'          => $form . '_street_name',
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third first wfacp_street_name' ) ),
					'required'    => $required, // Only required for NL
					'priority'    => 60,
				);
				$new_fields[] = array(
					'label'       => __( 'No.', 'woocommerce-myparcel' ),
					'placeholder' => __( 'No.', 'woocommerce-myparcel' ),
					'cssready'    => [ "wfacp_wc_parcel $class2" ],
					'id'          => $form . '_house_number',
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third wfacp_house_number' ) ),
					'required'    => $required, // Only required for NL
					'priority'    => 61,
				);
				$new_fields[] = array(
					'label'       => __( 'Suffix', 'woocommerce-myparcel' ),
					'placeholder' => __( 'Suffix', 'woocommerce-myparcel' ),
					'cssready'    => [ "wfacp_wc_parcel $class3" ],
					'id'          => $form . '_house_number_suffix',
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third last wfacp_house_number_suffix' ) ),

					'maxlength' => 4,
					'priority'  => 62,
				);

				$this->wcl_parcel_field_keys = array_merge( $this->wcl_parcel_field_keys, $new_fields );
				if ( is_array( $new_fields ) && count( $new_fields ) > 0 ) {
					foreach ( $new_fields as $fkey => $fvalue ) {
						if ( $is_hidedable ) {
							$fvalue['class'][] = 'wfacp_billing_fields';
							$fvalue['class'][] = 'wfacp_billing_field_hide';
						}
						$fvalue               = apply_filters( 'wfacp_wcl_parcel_billing_field', $fvalue, $fkey, $this );
						$sections['fields'][] = $fvalue;
					}
				}
				$sections['fields']['wfacp_end_divider_billing'] = $end_address_closser;
				if ( count( $after_address_element ) > 0 ) {
					$last_field_type = '';
					foreach ( $after_address_element as $element ) {
						if ( $element['type'] === 'wfacp_start_divider' ) {
							if ( false !== strpos( $element['id'], '_shipping' ) ) {
								$last_field_type            = 'shipping';
								$tid                        = 'wfacp_start_divider_shipping';
								$sections['fields'][ $tid ] = WFACP_Common::get_start_divider_field( 'shipping' );
							} elseif ( false !== strpos( $element['id'], '_billing' ) ) {
								$last_field_type            = 'billing';
								$tid                        = 'wfacp_start_divider_billing';
								$sections['fields'][ $tid ] = WFACP_Common::get_start_divider_field( 'billing' );
							}
						} elseif ( $element['type'] === 'wfacp_end_divider' ) {
							$tid                        = 'wfacp_end_divider_' . $last_field_type;
							$sections['fields'][ $tid ] = WFACP_Common::get_end_divider_field();
						} else {
							$sections['fields'][] = $element;
						}
					}
					$sections['fields'] = apply_filters( 'wfacp_wcl_parcel_billing_fields', $sections['fields'], $this );
				}
			} catch ( Exception $e ) {
			}
		}

		return $sections;
	}

	public function checkout_shipping_sections( $sections ) {
		if ( $this->shipping_fields_added ) {
			return $sections;
		}
		if ( count( $sections ) == 0 ) {
			return $sections;
		}
		if ( ! $this->is_enabled() ) {
			return $sections;
		}
		$page_version = WFACP_Common::get_checkout_page_version();

		if ( version_compare( $page_version, '2.1.3', '>' ) ) {

			return $sections;
		}


		$templateSlug = WFACP_Core()->customizer->get_template_instance()->get_template_slug();

		if ( strpos( $templateSlug, 'embed_forms_' ) !== false ) {
			$class1 = 'wfacp-col-full';
		}

		if ( isset( $sections['fields']['wfacp_end_divider_shipping'] ) ) {
			try {
				$this->shipping_fields_added = true;
				$end_address_found           = false;
				$end_address_closser         = $sections['fields']['wfacp_end_divider_shipping'];
				$after_address_element       = [];
				$is_hidedable                = false;
				foreach ( $sections['fields'] as $index => $field ) {
					if ( $end_address_found ) {
						$after_address_element[] = $field;
						unset( $sections['fields'][ $index ] );
					}
					if ( isset( $field['class'] ) && in_array( 'wfacp_shipping_fields', $field['class'] ) ) {
						$is_hidedable = true;
					}
					if ( 'wfacp_end_divider_shipping' === $index ) {
						unset( $sections['fields'][ $index ] );
						$end_address_found = true;
					}
				}
				$new_fields = array();
				WFACP_Common::remove_actions( 'woocommerce_shipping_fields', 'WPO\WC\Postcode_Checker\WC_NLPostcode_Fields', 'nl_shipping_fields' );
				$shipping_country = WC()->checkout()->get_value( 'shipping_country' );
				$countries        = [ 'NL', 'BE' ];
				// Set required to true if country is NL
				$required = in_array( $shipping_country, $countries ) ? true : false;
				if ( true == $required ) {
					$this->shipping_country_nl = true;
				}


				$version = WFACP_Common::get_checkout_page_version();
				$class1  = $this->classess['new'][0];
				$class2  = $this->classess['new'][1];
				$class3  = $this->classess['new'][2];


				$form = 'shipping';
				// Add street name
				$new_fields[]                  = array(
					'label'       => __( 'Street name', 'woocommerce-myparcel' ),
					'placeholder' => __( 'Street name', 'woocommerce-myparcel' ),
					'cssready'    => [ "wfacp_wc_parcel $class1" ],
					'id'          => $form . '_street_name',
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third first wfacp_street_name' ) ),
					'required'    => $required, // Only required for NL
					'priority'    => 60,
				);
				$new_fields[]                  = array(
					'label'       => __( 'No.', 'woocommerce-myparcel' ),
					'placeholder' => __( 'No.', 'woocommerce-myparcel' ),
					'cssready'    => [ "wfacp_wc_parcel $class2" ],
					'id'          => $form . '_house_number',
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third wfacp_house_number' ) ),
					'required'    => $required, // Only required for NL
					'priority'    => 61,
				);
				$new_fields[]                  = array(
					'label'       => __( 'Suffix', 'woocommerce-myparcel' ),
					'placeholder' => __( 'Suffix', 'woocommerce-myparcel' ),
					'cssready'    => [ "wfacp_wc_parcel $class3" ],
					'id'          => $form . '_house_number_suffix',
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-third last wfacp_house_number_suffix' ) ),
					'maxlength'   => 4,
					'priority'    => 62,
				);
				$this->wcl_parcel_field_keys[] = $new_fields;
				if ( is_array( $new_fields ) && count( $new_fields ) > 0 ) {
					foreach ( $new_fields as $fkey => $fvalue ) {
						if ( $is_hidedable ) {
							$fvalue['class'][] = 'wfacp_shipping_fields';
							$fvalue['class'][] = 'wfacp_shipping_field_hide';
						}
						$fvalue               = apply_filters( 'wfacp_wcl_parcel_shipping_field', $fvalue, $fkey, $this );
						$sections['fields'][] = $fvalue;
					}
				}
				$sections['fields']['wfacp_end_divider_shipping'] = $end_address_closser;
				if ( count( $after_address_element ) > 0 ) {
					$last_field_type = '';
					foreach ( $after_address_element as $element ) {
						if ( isset( $element['type'] ) && $element['type'] === 'wfacp_start_divider' ) {
							if ( false !== strpos( $element['id'], '_shipping' ) ) {
								$last_field_type            = 'shipping';
								$tid                        = 'wfacp_start_divider_shipping';
								$sections['fields'][ $tid ] = WFACP_Common::get_start_divider_field( 'shipping' );
							} elseif ( false !== strpos( $element['id'], '_billing' ) ) {
								$last_field_type            = 'billing';
								$tid                        = 'wfacp_start_divider_billing';
								$sections['fields'][ $tid ] = WFACP_Common::get_start_divider_field( 'billing' );
							}
						} elseif ( isset( $element['type'] ) && $element['type'] === 'wfacp_end_divider' ) {
							$tid                        = 'wfacp_end_divider_' . $last_field_type;
							$sections['fields'][ $tid ] = WFACP_Common::get_end_divider_field();
						} else {
							$sections['fields'][] = $element;
						}
					}
					$sections['fields'] = apply_filters( 'wfacp_wcl_parcel_shipping_fields', $sections['fields'], $this );
				}
			} catch ( Exception $e ) {
			}
		}

		return $sections;
	}

	public function remove_optional_shipping_field_validation_error( $data ) {
		$data['wc_customizer_validation_status']['shipping_house_number_suffix_field'] = 'wfacp_required_optional';

		return $data;
	}

	public function internal_css( $selected_template_slug ) {
		if ( ! $this->is_enabled() ) {
			return '';
		}
		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
		}

		$css = "
		<style>
		  $bodyClass .wfacp_main_form.woocommerce table.mypa-delivery-option-table {
                table-layout: unset;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form table tbody tr > td:first-child {
                display: table-cell !important;
                width: 20px !important;
            }

           $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form label {
                display: inline-block;
                padding-left: 0 !important;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form table tbody tr > td:first-child {
                padding-bottom: 0;
                width: auto;
                display: block;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form table {
                border: none;
            }

            $bodyClass .wfacp_main_form.woocommerce form .form-row-third.first.wfacp-col-left-third.wfacp_wc_parcel {
                width: 33.33%;
                margin-left: 0;
                margin-right: 0;
            }

            $bodyClass #mypa-delivery-option-form label {
                padding: 0 !important;
            }

            $bodyClass .wfacp_main_form.woocommerce form .form-row-third.first.wfacp-col-left-third.wfacp_wc_parcel {

                margin-left: 0;
                margin-right: 0;
            }

            $bodyClass .wfacp_main_form.woocommerce form .form-row-third.wfacp-col-left-third.wfacp_wc_parcel {
                margin-right: 0;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-load {
                margin: 0 0 25px;
                padding: 0 12px;;
                clear: both;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form h1,
            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form h2,
            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form h3,
            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form h4,
            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form h5,
            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form h6 {
                margin: 0 0 10px;
            }


            $bodyClass .wfacp_main_form.woocommerce form .form-row-third {
                margin-right: 0;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-message h3 {
                margin: 0;
                font-size: 18px;
                line-height: 1.5;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form input[type='radio'] {
                position: relative;
                top: auto;
                margin: 0;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form label {
                padding-left: 0;
                font-weight: normal;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form table tbody tr > td:first-child {
                padding-bottom: 0;
            }


            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form table tbody tr > td {
                padding-bottom: 10px;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form table tbody select {
                padding: 10px;
                width: 100%;
            }

            $bodyClass .wfacp_main_form.woocommerce #mypa-delivery-option-form tr td:first-child {
                vertical-align: top;
            }

            $bodyClass #mypa-delivery-option-form input[type='checkbox'] {
                position: relative;
                left: auto;
                margin: 0;
                right: auto;
                top: auto;
                margin-right: 5px;
            }

            /* My Parcel Option */
            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options .woocommerce-myparcel__delivery-options input[type='radio'], body #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-myparcel__delivery-options input[type='checkbox'] {
                position: relative;
                left: auto;
                margin: 0 10px 0 0px;
                right: auto;
                top: auto;
                width: auto;
            }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options .woocommerce-myparcel__delivery-options td, body #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-myparcel__delivery-options th {
                padding: 0;

            }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options input {
                width: 100%;
                padding: 10px 12px;
            }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options form label {
                display: block; color: #777;
             }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options form select {
                -webkit-appearance: menulist;
                -moz-appearance: menulist;
                -webkit-appearance: menulist;
                padding: 10px 12px;
            }

            $bodyClass .wfacp_main_form.woocommerce #wfacp_output_delivery_options {
                margin-bottom: 15px;
            }
            
			$bodyClass #wfacp_output_delivery_options {
			    clear: both;
			    padding: 0 7px;
			}
			
			$bodyClass #wfacp_output_delivery_options .myparcel-delivery-options__modal {
			    padding: 0;
			}
			
			
			$bodyClass #wfacp_output_delivery_options h1,
			$bodyClass #wfacp_output_delivery_options h2,
			$bodyClass #wfacp_output_delivery_options h3,
			$bodyClass #wfacp_output_delivery_options h4,
			$bodyClass #wfacp_output_delivery_options h5,
			$bodyClass #wfacp_output_delivery_options h6 {
			    margin: 0 0 15px;
			    line-height: 1.5;
			}
	
			$bodyClass #wfacp_output_delivery_options p label {
			    margin: 0;
			}
			$bodyClass #wfacp_output_delivery_options button {
			    padding: 15px 52px;
			    margin: 0;
			    color: #fff;
			    display: block;
			    text-transform: capitalize;
			    box-shadow: none;
			    font-family: inherit;
			    background-color: #999;
			    font-size: 15px;
			    font-weight: 400;
			    border: none;
			    min-height: 50px;
			    border-radius: 4px;
			    margin-right: 5px;
			}
			$bodyClass #wfacp_output_delivery_options button:last-child {
   				 margin-right: 0;
			}
			
			$bodyClass #wfacp_output_delivery_options button:hover {
			    background-color: #878484;
			    outline: 0;
			    border: none;
			}
		
            @media (min-width: 768px) {
                $bodyClass .wfacp_main_form.woocommerce form .form-row-third:not(.wfacp-draggable):not(.wfacp-col-left-third) {
                    margin-right: 0;
                    width: 33.33%;
                    float: left;
                    clear: none;
                }

                $bodyClass .wfacp_main_form.woocommerce p.form-row:not(.wfacp-draggable).wfacp_house_number,
                $bodyClass .wfacp_main_form.woocommerce p.form-row:not(.wfacp-draggable).wfacp_house_number_suffix {
                    width: 33.33%;
                    float: left;
                }
            }


            @media (max-width: 767px) {
                $bodyClass .wfacp_main_form.woocommerce form .form-row-third:not(.wfacp-col-left-third) {
                    width: 100%;
                }
            }
            </style>
		";

		echo $css;

	}

	public function update_address_data( $keys ) {
		if ( class_exists( 'WPO_WCNLPC_Checkout' ) ) {
			$keys['shipping_house_number']        = 'billing_house_number';
			$keys['shipping_street_name']         = 'billing_street_name';
			$keys['shipping_house_number_suffix'] = 'billing_house_number_suffix';
			$keys['billing_house_number']         = 'shipping_house_number';
			$keys['billing_street_name']          = 'shipping_street_name';
			$keys['billing_house_number_suffix']  = 'shipping_house_number_suffix';
		}

		return $keys;
	}

	public function unset_shipping_address_data( $keys ) {
		if ( class_exists( 'WPO_WCNLPC_Checkout' ) ) {
			$keys[] = 'shipping_house_number';
			$keys[] = 'shipping_street_name';
			$keys[] = 'shipping_house_number_suffix';
		}

		return $keys;
	}

	public function add_actions() {
		if ( ! class_exists( 'WCMP_Frontend' ) ) {
			return;
		}
		$this->fragment_obj = WFACP_Common::remove_actions( 'woocommerce_checkout_before_order_review', 'WCMP_Frontend', 'injectShippingClassInput' );
		if ( ! $this->fragment_obj instanceof WCMP_Frontend ) {
			return;
		}
		$this->fragment_obj->injectShippingClassInput();
	}
}


if ( ! class_exists( 'WCMYPA' ) ) {
	return;
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Parcel(), 'wcparcel' );
