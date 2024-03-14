<?php

/**
 * Name: WooCommerce Delivery Slots by Iconic (up to 1.13.4)
 * Plugin URL: https://iconicwp.com/products/woocommerce-delivery-slots/
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Woo_Delivery_Slots_Premium {
	public $draggable_field = false;
	public $instance = false;

	public function __construct() {

		add_filter( 'wpsf_register_settings_jckwds', [ $this, 'active_setting' ], 12 );

		/* Add field in the advanced option */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_delivery_date_slote_iconic', '__return_false' );

		add_filter( 'wfacp_after_checkout_page_found', [ $this, 'action' ], 12 );


		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 50, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
		add_filter( 'wfacp_css_js_deque', [ $this, 'deque_css_js' ], 10, 3 );

		/* Display the field */
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );

	}

	public function is_enabled() {
		if ( class_exists( 'jckWooDeliverySlots' ) || class_exists( 'Iconic_WDS' ) ) {
			return true;
		}

		return false;
	}

	public function add_field( $fields ) {

		if ( ! $this->is_enabled() ) {
			return $fields;
		}

		$fields['delivery_date_slote_iconic'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'wfacp_delivery_date_slote_iconic' ],
			'id'         => 'delivery_date_slote_iconic',
			'field_type' => 'delivery_date_slote_iconic',
			'label'      => __( 'WC Delivery Slots (Iconic)', 'woofunnels-aero-checkout' ),

		];

		return $fields;

	}

	public function action() {

		if ( ! $this->is_enabled() ) {
			return '';
		}
		add_filter( 'wfacp_print_shipping_hidden_fields', '__return_false' );
		add_filter( 'wfacp_show_shipping_options', '__return_true' );
		$page_version = WFACP_Common::get_checkout_page_version();
		global $jckwds;

		if ( $jckwds instanceof Iconic_WDS && isset( $jckwds->settings['general_setup_position'] ) ) {
			$general_setup_position = $jckwds->settings['general_setup_position'];
		}
		if ( version_compare( $page_version, '2.7.0', '>=' ) && ! strpos( $general_setup_position, 'wfacp_' ) !== false ) {

			$this->instance        = WFACP_Common::remove_actions( $general_setup_position, 'Iconic_WDS', 'display_checkout_fields' );
			$this->draggable_field = true;
		}

	}

	public function active_setting( $wpsf_settings ) {
		global $jckwds;
		if ( ! $jckwds || ! function_exists( 'WC' ) || ! isset( $wpsf_settings['sections'] ) || empty( $wpsf_settings['sections'] ) ) {
			return $wpsf_settings;
		}

		foreach ( $wpsf_settings['sections'] as $key => $value ) {
			if ( ! isset( $value['tab_id'] ) || $value['tab_id'] != 'general' ) {
				continue;
			}
			if ( ! isset( $value['fields'] ) || ( ! is_array( $value['fields'] ) || count( $value['fields'] ) == 0 ) ) {
				continue;
			}
			foreach ( $value['fields'] as $field_key => $field_value ) {
				if ( isset( $field_value['id'] ) && $field_value['id'] == 'position' ) {
					$wpsf_settings['sections'][ $key ]['fields'][ $field_key ]['choices']['wfacp_after_wfacp_divider_shipping_end_field'] = "FunnelKit Checkout After Shipping Fields";
					$wpsf_settings['sections'][ $key ]['fields'][ $field_key ]['choices']['wfacp_after_wfacp_divider_billing_end_field']  = "FunnelKit Checkout After Billing Fields";

				}
			}
		}

		return $wpsf_settings;
	}

	public function process_wfacp_html( $field, $key ) {


		if ( ! $this->is_enabled() || 'delivery_date_slote_iconic' != $key || false == $this->draggable_field || ! $this->instance instanceof Iconic_WDS ) {
			return $field;
		}


		echo "<div id=wfacp_delivery_date_slote_iconic>";
		$this->instance->display_checkout_fields();
		echo "</div>";


	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( $key == 'jckwds-delivery-date' || $key == 'jckwds-delivery-time' ) {
			$args['input_class'] = array_merge( $args['input_class'], [ 'wfacp-form-control' ] );
			$args['label_class'] = array_merge( $args['label_class'], [ 'wfacp-form-control-label' ] );
			$args['class']       = array_merge( $args['class'], [ 'wfacp-col-full', 'wfacp-form-control-wrapper' ] );
		}

		return $args;
	}

	public function deque_css_js( $bool, $path, $url ) {
		if ( false !== strpos( $url, 'ajax.googleapis.com/ajax/libs/jqueryui/' ) ) {
			return false;
		}

		return $bool;
	}

	public function internal_css() {
		?>
        <style>
            h3.iconic-wds-fields__title {
                padding: 0 7px;
                margin: 0 0 10px;
            }

            p#jckwds-delivery-date_field:not(.wfacp-anim-wrap) label {
                top: 30px;
                bottom: auto;
            }
        </style>
        <script>
            window.addEventListener('bwf_checkout_load', function () {
                jQuery(document).ajaxComplete(function (event, jqxhr, settings) {
                    if (settings.hasOwnProperty('data') && settings.data.indexOf("iconic_wds_is_delivery_slots_allowed") > -1) {
                        remove_validate_required();
                    }
                });

                function remove_validate_required() {
                    var $ele = jQuery(".jckwds-delivery-date");
                    if ($ele.length == 0) {
                        return;
                    }
                    if ($ele.hasClass('woocommerce-invalid-required-field')) {
                        $ele.removeClass('woocommerce-invalid-required-field');
                    }
                }
            });
        </script>
		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Woo_Delivery_Slots_Premium(), 'wdsp-iconic' );