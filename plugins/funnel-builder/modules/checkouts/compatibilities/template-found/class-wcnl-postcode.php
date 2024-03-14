<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Plugin Name: WooCommerce NL Postcode Checker
 * Plugin URI: https://wpovernight.com/downloads/woocommerce-postcode-checker/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Wcnl_Postcode {
	private $billing_country_nl = false;
	private $shipping_country_nl = false;
	private $wcnl_postcode_field_keys = [];
	private $main_object = null;
	protected static $instance = null;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

		add_filter( 'wfacp_form_section', [ $this, 'checkout_billing_sections' ], 8 );
		add_filter( 'wfacp_form_section', [ $this, 'checkout_shipping_sections' ], 12 );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'validation_fields' ] );
		add_filter( 'wfacp_template_localize_data', [ $this, 'remove_optional_shipping_field_validation_error' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );


	}


	public function remove_optional_shipping_field_validation_error( $data ) {
		$data['wc_customizer_validation_status']['shipping_house_number_suffix_field'] = 'wfacp_required_optional';
		$data['wc_customizer_validation_status']['billing_house_number_suffix_field']  = 'wfacp_required_optional';

		return $data;
	}

	public function actions() {
		add_action( 'wp_footer', [ $this, 'add_js' ] );
		add_filter( 'woocommerce_country_locale_field_selectors', function ( $locale_fields ) {
			if ( ! class_exists( 'WPO\WC\Postcode_Checker\WC_NLPostcode_Fields' ) ) {
				return $locale_fields;
			}
			$locale_fields['address_1'] = '#billing_address_1_field, #shipping_address_1_field';
			$locale_fields['address_2'] = '#billing_address_2_field, #shipping_address_2_field';

			return $locale_fields;
		}, 50 );

		$this->main_object = WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'PostcodeNl\AddressAutocomplete\Main', 'enqueueScripts' );

		WFACP_Common::add_actions( 'wp_enqueue_scripts', 'enqueueScripts', $this->main_object );


	}

	public function validation_fields() {
		add_filter( 'wfacp_checkout_fields', [ $this, 'make_validation' ] );
		add_filter( 'woocommerce_checkout_posted_data', [ $this, 'toev_field' ] );
	}

	public function toev_field( $posted_data ) {
		if ( isset( $_REQUEST['billing_house_number_suffix'] ) ) {
			$posted_data['billing_house_number_suffix'] = $_REQUEST['billing_house_number_suffix'];
		}
		if ( isset( $_REQUEST['shipping_house_number_suffix'] ) ) {
			$posted_data['shipping_house_number_suffix'] = $_REQUEST['shipping_house_number_suffix'];
		}

		return $posted_data;
	}

	public function make_validation( $template_fields ) {
		$obj             = WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'WPO\WC\Postcode_Checker\WC_NLPostcode_Fields', 'nl_billing_fields' );
		$billing_country = WC()->checkout()->get_value( 'billing_country' );
		$required        = false;
		if ( $obj instanceof WPO\WC\Postcode_Checker\WC_NLPostcode_Fields && ! empty( $obj ) ) {
			$required = in_array( $billing_country, $obj->postcode_field_countries() ) ? true : false;
		}
		$house_number_label = __( 'Nr.', 'wpo_wcnlpc' );
		if ( get_option( 'woocommerce_wcnlpc_full_field_names', 'no' ) == 'yes' ) {
			$house_number_label = __( 'House number', 'wpo_wcnlpc' );
		}

		if ( isset( $template_fields['billing'] ) ) {
			$form                                                  = 'billing';
			$template_fields['billing'][ $form . '_street_name' ]  = [
				'label'       => __( 'Street name', 'wpo_wcnlpc' ),
				'placeholder' => __( 'Street name', 'wpo_wcnlpc' ),
				'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-first' ), $form, 'street_name' ),
				'required'    => $required,
			];
			$template_fields['billing'][ $form . '_house_number' ] = array(
				'label'             => $house_number_label,
				'class'             => apply_filters( 'nl_custom_address_field_class', array( 'form-row-quart-first' ), $form, 'house_number' ),
				'required'          => $required, // Only required for NL
				'type'              => 'number',
				'custom_attributes' => array( 'pattern' => '[0-9]*' ),
			);
		}
		$shipping_country = WC()->checkout()->get_value( 'shipping_country' );
		$required         = false;
		if ( $obj instanceof WPO\WC\Postcode_Checker\WC_NLPostcode_Fields && ! empty( $obj ) ) {
			$required = in_array( $shipping_country, $obj->postcode_field_countries() ) ? true : false;
		}
		if ( isset( $template_fields['shipping'] ) ) {
			$form                                                   = 'shipping';
			$template_fields['shipping'][ $form . '_street_name' ]  = [
				'label'       => __( 'Street name', 'wpo_wcnlpc' ),
				'placeholder' => __( 'Street name', 'wpo_wcnlpc' ),
				'class'       => apply_filters( 'nl_custom_address_field_class', array( 'form-row-first' ), $form, 'street_name' ),
				'required'    => $required,
			];
			$template_fields['shipping'][ $form . '_house_number' ] = array(
				'label'             => $house_number_label,
				'class'             => apply_filters( 'nl_custom_address_field_class', array( 'form-row-quart-first' ), $form, 'house_number' ),
				'required'          => $required, // Only required for NL
				'type'              => 'number',
				'custom_attributes' => array( 'pattern' => '[0-9]*' ),
			);
		}

		return $template_fields;
	}

	public function checkout_billing_sections( $sections ) {
		if ( count( $sections ) == 0 ) {
			return $sections;
		}


		if ( isset( $sections['fields']['wfacp_end_divider_billing'] ) ) {
			try {
				$end_address_found     = false;
				$end_address_closser   = $sections['fields']['wfacp_end_divider_billing'];
				$after_address_element = [];
				$is_hidedable          = false;
				$keysVal               = [];
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
				$obj        = WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'WPO\WC\Postcode_Checker\WC_NLPostcode_Fields', 'nl_billing_fields' );
				$country    = WC()->checkout()->get_value( 'billing_country' );
				// Set required to true if country is NL
				$required = false;
				if ( $obj instanceof WPO\WC\Postcode_Checker\WC_NLPostcode_Fields && ! empty( $obj ) ) {
					$required = in_array( $country, $obj->postcode_field_countries() ) ? true : false;
				}
				if ( true == $required ) {
					$this->billing_country_nl = true;
				}
				$form          = 'billing';
				$base_priority = 50;
				if ( isset( $keysVal['billing_address_1'] ) ) {
					$base_priority = intval( $keysVal['billing_address_1'] );
				}
				$templateSlug = wfacp_template()->get_template_slug();
				$class1       = 'wfacp-col-left-half';
				$class2       = 'wfacp-col-left-half';
				if ( strpos( $templateSlug, 'embed_forms_' ) !== false ) {
					$class1 = 'wfacp-col-full';
					$class2 = 'wfacp-col-full';
				}
				$house_number_label  = __( 'Nr.', 'wpo_wcnlpc' );
				$house_number_suffix = _x( 'Suffix', 'abbreviated string', 'wpo_wcnlpc' );
				if ( get_option( 'woocommerce_wcnlpc_full_field_names', 'no' ) == 'yes' ) {
					$house_number_label  = __( 'House number', 'wpo_wcnlpc' );
					$house_number_suffix = _x( 'Suffix', 'full string', 'wpo_wcnlpc' );
				}
				// Add Street name
				$new_fields[] = array(
					'label'       => __( 'Street name', 'wpo_wcnlpc' ),
					'placeholder' => __( 'Street name', 'wpo_wcnlpc' ),
					'cssready'    => [ "wfacp_postcode_checker $class1" ],
					'id'          => $form . '_street_name',
					'class'       => apply_filters( 'nl_custom_address_field_class', array( 'wfacp_postcode_checker form-row-first', $class1 ), $form, 'street_name' ),
					'required'    => $required, // Only required for NL
					'priority'    => $base_priority + 1,
				);
				// Add house number
				$new_fields[] = array(
					'label'             => $house_number_label,
					'class'             => apply_filters( 'nl_custom_address_field_class', array( 'form-row-quart-first', $class1 ), $form, 'house_number' ),
					'required'          => $required, // Only required for NL
					'type'              => 'number',
					'id'                => $form . '_house_number',
					'cssready'          => [ "wfacp_postcode_checker $class1" ],
					'custom_attributes' => array( 'pattern' => '[0-9]*' ),
					'priority'          => $base_priority + 2,
				);
				// Add house number Suffix
				$new_fields[] = array(
					'label'    => $house_number_suffix,
					'cssready' => [ "wfacp_postcode_checker $class2" ],
					'id'       => $form . '_house_number_suffix',
					'class'    => apply_filters( 'nl_custom_address_field_class', array( 'wfacp_postcode_checker form-row-quart', $class1 ), $form, 'house_number_suffix' ),
					'required' => false,
					'priority' => $base_priority + 3,
				);

//                $new_fields[]=$address_fields;
				$this->wcnl_postcode_field_keys = array_merge( $this->wcnl_postcode_field_keys, $new_fields );
				if ( is_array( $new_fields ) && count( $new_fields ) > 0 ) {
					foreach ( $new_fields as $fkey => $fvalue ) {


						if ( $is_hidedable ) {
							$fvalue['class'][] = 'wfacp_billing_fields';
							$fvalue['class'][] = 'wfacp_billing_field_hide';
						}
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
				}
			} catch ( Exception $e ) {
			}
		}

		return $sections;
	}

	public function checkout_shipping_sections( $sections ) {
		if ( count( $sections ) == 0 ) {
			return $sections;
		}
		$templateSlug = wfacp_template()->get_template_slug();
		$class1       = 'wfacp-col-left-half';
		$class2       = 'wfacp-col-left-half';
		if ( strpos( $templateSlug, 'embed_forms_' ) !== false ) {
			$class1 = 'wfacp-col-full';
			$class2 = 'wfacp-col-full';
		}
		if ( isset( $sections['fields']['wfacp_end_divider_shipping'] ) ) {
			try {
				$end_address_found     = false;
				$end_address_closser   = $sections['fields']['wfacp_end_divider_shipping'];
				$after_address_element = [];
				$is_hidedable          = false;
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
				$new_fields       = array();
				$obj              = WFACP_Common::remove_actions( 'woocommerce_shipping_fields', 'WPO\WC\Postcode_Checker\WC_NLPostcode_Fields', 'nl_shipping_fields' );
				$shipping_country = WC()->checkout()->get_value( 'shipping_country' );
				// Set required to true if country is NL
				$required = false;
				if ( $obj instanceof WPO\WC\Postcode_Checker\WC_NLPostcode_Fields && ! empty( $obj ) ) {
					$required = in_array( $shipping_country, $obj->postcode_field_countries() ) ? true : false;
				}
				if ( true == $required ) {
					$this->shipping_country_nl = true;
				}
				$form = 'shipping';
				// Add Street name
				$new_fields[]        = array(
					'label'       => __( 'Street name', 'wpo_wcnlpc' ),
					'placeholder' => __( 'Street name', 'wpo_wcnlpc' ),
					'cssready'    => [ "wfacp_postcode_checker $class1" ],
					'id'          => $form . '_street_name',
					'class'       => apply_filters( 'nl_custom_address_field_class1', array( 'form-row-first', $class1 ), $form, 'street_name' ),
					'required'    => $required, // Only required for NL
				);
				$house_number_label  = __( 'Nr.', 'wpo_wcnlpc' );
				$house_number_suffix = _x( 'Suffix', 'abbreviated string', 'wpo_wcnlpc' );
				if ( get_option( 'woocommerce_wcnlpc_full_field_names', 'no' ) == 'yes' ) {
					$house_number_label  = __( 'House number', 'wpo_wcnlpc' );
					$house_number_suffix = _x( 'Suffix', 'full string', 'wpo_wcnlpc' );
				}
				// Add house number
				$new_fields[] = array(
					'label'             => $house_number_label,
					'class'             => apply_filters( 'nl_custom_address_field_class', array( 'form-row-quart-first', $class1 ), $form, 'house_number' ),
					'required'          => $required, // Only required for NL
					'type'              => 'number',
					'id'                => $form . '_house_number',
					'cssready'          => [ "wfacp_postcode_checker $class1" ],
					'custom_attributes' => array( 'pattern' => '[0-9]*' ),
				);
				// Add house number Suffix
				$new_fields[]                     = array(
					'label'    => $house_number_suffix,
					'cssready' => [ "wfacp_postcode_checker $class2" ],
					'id'       => $form . '_house_number_suffix',
					'class'    => apply_filters( 'nl_custom_address_field_class', array( 'wfacp_postcode_checker form-row-quart', $class1 ), $form, 'house_number_suffix' ),
					'required' => false,
				);
				$this->wcnl_postcode_field_keys[] = $new_fields;
				if ( is_array( $new_fields ) && count( $new_fields ) > 0 ) {
					foreach ( $new_fields as $fkey => $fvalue ) {
						if ( $is_hidedable ) {
							$fvalue['class'][] = 'wfacp_shipping_fields';
							$fvalue['class'][] = 'wfacp_shipping_field_hide';
						}
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
				}
			} catch ( Exception $e ) {
			}
		}

		return $sections;
	}

	public function internal_css( $selected_template_slug ) {
		if ( function_exists( 'wfacp_template' ) ) {
			$instance = wfacp_template();
		}
		if ( is_null( $instance ) ) {
			return;
		}
		$px = $instance->get_template_type_px();
		?>
        <style>
            #billing_house_number_suffix_field.form-row-quart .optional {
                display: inline-block;
            }

            .wfacp_main_form.woocommerce form .form-row-third {
                margin-right: 0;
                width: 100%;
                clear: both;
            }

            <?php
		   if ( isset($px) ) {echo "body .wfacp_main_form p.wcnlpc-manual {padding: 0 $px".'px;';}
			   ?>
            .wfacp_main_form .woocommerce-page form .form-row-quart-first, .woocommerce form .form-row-quart-first {
                margin-right: 0 !important;
            }

            .wfacp_main_form .wfacp_main_form.woocommerce form.checkout input[readonly] {
                background: transparent;
            }

            .woocommerce form .form-row-quart, .woocommerce-page form .form-row-quart,
            .woocommerce form .form-row-quart-first, .woocommerce-page form .form-row-quart-first {
                width: auto;
            }

            p.wcnlpc-manual {
                padding: 0 7px;;
                font-size: 14px;
                line-height: 1.5;
            }
        </style>
		<?php
	}

	public function add_js() {
		if ( ! class_exists( 'WPO\WC\Postcode_Checker\WC_NLPostcode_Fields' ) ) {
			return '';
		}
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    var billing_country_nl, shipping_country_nl;
                    billing_country_nl = "<?php echo $this->billing_country_nl; ?>";
                    shipping_country_nl = "<?php echo $this->shipping_country_nl; ?>";

                    function execute_toggle_slide(country, wrapper) {
                        var thisform = wrapper;
                        var $postcodefield = thisform.find('#billing_postcode_field, #shipping_postcode_field');
                        var $countryfield = thisform.find('#billing_country_field, #shipping_country_field');
                        var $housenumber = thisform.find('#billing_house_number_field, #shipping_house_number_field');
                        if ($.inArray(country, wpo_wcnlpc.postcode_field_countries) !== -1) {
                            $postcodefield.insertBefore($housenumber);
                        } else if (country !== 'NL') {
                            $postcodefield.insertBefore($countryfield);
                        }
                    }

                    function execute_toggle_slide_previous_version(country, wrapper) {
                        var thisform = wrapper;
                        var $postcodefield = thisform.find('#billing_postcode_field, #shipping_postcode_field');
                        var $cityfield = thisform.find('#billing_country_field, #shipping_country_field');
                        if ($.inArray(country, wpo_wcnlpc.postcode_field_countries) !== -1) {
                            $postcodefield.insertAfter($cityfield);
                        } else if (country !== 'NL') {
                            $postcodefield.insertBefore($cityfield);
                        }
                    }

                    if ($('.wfacp_divider_billing').length > 0) {
                        execute_toggle_slide($('#billing_country').val(), $('.wfacp_divider_billing'));
                    }
                    if ($('.wfacp_divider_shipping').length > 0) {
                        execute_toggle_slide($('#shipping_country').val(), $('.wfacp_divider_shipping'));
                    }
                    $(document.body).bind('country_to_state_changing', function (event, country, wrapper) {
                        execute_toggle_slide(country, wrapper);
                    });
                    $(document.body).on("change", "#shipping_country", function (e) {
                        check_field_class($(this));
                    });
                    $(document.body).on("change", "#billing_same_as_shipping_field", function (e) {
                        check_field_class($(this));
                    });
                    $(document.body).on("change", "#shipping_same_as_billing_field", function (e) {
                        check_field_class($(this));
                    });
                    $(document).on('change', '#billing_country', function () {
                        check_field_class($(this));
                    });
                    $(document.body).on('wpo_wcnlpc_fields_updated', function () {
                        remove_hide_animate();
                    });

                    function remove_hide_animate() {
                        var addresses = ['billing', 'shipping'];
                        for (var i in addresses) {
                            var key = addresses[i];
                            $(".wfacp_divider_" + key + " .form-row").each(function () {
                                let field_id = $(this).attr("id");
                                if (field_id != '') {
                                    let field_val_id1 = field_id.replace('_field', '');
                                    let field_val = $('#' + field_val_id1).val();
                                    if (field_val != '' && field_val != null && !$(this).hasClass('wfacp-anim-wrap')) {
                                        $(this).addClass("wfacp-anim-wrap");
                                    }
                                }
                            });
                        }
                    }

                    function check_field_class() {
                        if (typeof wpo_wcnlpc == "undefined") {
                            return;
                        }
                        if (wpo_wcnlpc.street_city_visibility == 'readonly') {
                            $('.form-row ').each(function () {
                                $(this).find('input[readonly]').parents('.form-row').addClass("wfacp_readonly");
                            });
                        }
                    }
                })(jQuery);
            });
        </script>
		<?php
	}
}
WFACP_Compatibility_With_Wcnl_Postcode::get_instance();
