<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Plugin name: WooCommerce EU/UK VAT Compliance (Premium) By David Anderson v.1.29.11
 */

#[AllowDynamicProperties]
class WFACP_Compatibility_With_WC_EU_Vat {
	private $actives = [];

	public function __construct() {

		$this->init();
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_eu_fields' ] );
		add_filter( 'wfacp_html_fields_wc_eu_vat_compliance_vat_number', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function add_eu_fields( $field ) {

		if ( $this->is_enable( 'WC_EU_VAT_Compliance_VAT_Number' ) ) {
			$field['wc_eu_vat_compliance_vat_number'] = [
				'type'       => 'wfacp_html',
				'field_type' => 'advanced',
				'class'      => [ 'wfacp_wc_eu_vat_compliance_vat_number' ],
				'id'         => 'wc_eu_vat_compliance_vat_number',
				'label'      => __( 'EU VAT', 'woocommerce' ),
			];
		}

		return $field;
	}

	public function init() {
		global $woocommerce_eu_vat_compliance_classes;
		if ( ! is_null( $woocommerce_eu_vat_compliance_classes ) && isset( $woocommerce_eu_vat_compliance_classes['WC_EU_VAT_Compliance_VAT_Number'] ) && ( $woocommerce_eu_vat_compliance_classes['WC_EU_VAT_Compliance_VAT_Number'] instanceof WC_EU_VAT_Compliance_VAT_Number ) ) {
			$this->actives['WC_EU_VAT_Compliance_VAT_Number'] = $woocommerce_eu_vat_compliance_classes['WC_EU_VAT_Compliance_VAT_Number'];

		}

	}


	public function process_wfacp_html( $field, $key ) {

		global $woocommerce_eu_vat_compliance_classes;
		if ( ! empty( $key ) && $key == 'wc_eu_vat_compliance_vat_number' && class_exists( 'WC_EU_VAT_Compliance_VAT_Number' ) && isset( $woocommerce_eu_vat_compliance_classes['WC_EU_VAT_Compliance_VAT_Number'] ) ) {


			echo "<div class=wfacp_woocommerce_eu_vat_compliance>";
			$instance = $woocommerce_eu_vat_compliance_classes['WC_EU_VAT_Compliance_VAT_Number'];

			if ( method_exists( $instance, 'get_shortcode_checkout' ) ) {
				$shortcode_checkout = $woocommerce_eu_vat_compliance_classes['WC_EU_VAT_Compliance_VAT_Number']->get_shortcode_checkout();
				if ( $shortcode_checkout instanceof WC_VAT_Compliance_Shortcode_Checkout ) {
					$shortcode_checkout->vat_number_field( false );
				}


			} else {
				$instance->vat_number_field();
			}

			echo "</div>";


		}

	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( $key == 'vat_number' && $this->is_enable( 'WC_EU_VAT_Compliance_VAT_Number' ) ) {

			$all_cls     = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$input_class = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$label_class = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );

			$args['class']       = $all_cls;
			$args['cssready']    = [ 'wfacp-col-full' ];
			$args['input_class'] = $input_class;
			$args['label_class'] = $label_class;

		}

		return $args;
	}

	public function internal_css() {
		if ( ! $this->is_enable( 'WC_EU_VAT_Compliance_VAT_Number' ) || ! function_exists( 'wfacp_template' ) ) {
			return;
		}


		$instance = wfacp_template();
		$px       = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$px = "7px";
		}

		?>
        <style>

            body .wfacp_main_form #woocommerce_eu_vat_compliance {
                float: none;
                clear: both;
            }

            body .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 {
                margin: 0 0 5px;
                padding-top: 0;
                padding-bottom: 0;
                padding-left: <?php echo $px; ?>;
                padding-right: <?php echo $px; ?>;
            }

            body .wfacp_main_form #woocommerce_eu_vat_compliance .form-row {
                margin-bottom: 16px;
                padding-top: 0;
                padding-bottom: 0;
                padding-left: <?php echo $px; ?>;
                padding-right: <?php echo $px; ?>;
            }

            body .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 + p {
                margin-bottom: 15px;
                padding-top: 0;
                padding-bottom: 0;
                padding-left: <?php echo $px; ?>;
                padding-right: <?php echo $px; ?>;
            }

            #wfacp-e-form .wfacp_main_form .ia_subscription_items h3,
            #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 {
                color: #333333;
            }

            #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 + p {
                font-size: 14px;
                font-weight: normal;
            }

            #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 + p,
            #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 {
                text-align: left;
            }

            #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 {
                font-weight: normal;
            }

            #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 + p {
                color: #737373;
            }

            #wfacp-e-form .wfacp_main_form .ia_subscription_items h3,
            #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 {
                font-size: 20px;
            }


            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce .wfacp_woocommerce_eu_vat_compliance input[type="radio"] {
                position: relative;
                left: auto;
                right: auto;
                top: auto;
                bottom: auto;
                margin: 0;
            }


            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce .wfacp_woocommerce_eu_vat_compliance #vat_self_certify_field > label {
                display: block !important;
                width: 100%;
                margin: 0 0 8px;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce .wfacp_woocommerce_eu_vat_compliance #vat_self_certify_field input[type="radio"] {
                float: left;
                clear: left;
            }

            body #wfacp-sec-wrapper .wfacp_main_form.woocommerce .wfacp_woocommerce_eu_vat_compliance #vat_self_certify_field input[type="radio"] + label {
                float: left;
                padding-left: 0;
                margin-left: 10px;
                line-height: 16px;
                margin-bottom: 8px;
            }

            @media (max-width: 991px) {
                #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 {
                    font-size: 20px;
                }

                #wfacp-e-form .wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 + p {
                    font-size: 14px;
                }
            }

            @media (max-width: 767px) {
                #wfacp-e-form.wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 {
                    font-size: 20px;
                }

                #wfacp-e-form.wfacp_main_form #woocommerce_eu_vat_compliance #woocommerce_eu_vat_compliance_vat_number h3 + p {
                    font-size: 14px;
                }

            }

        </style>
		<?php

	}

	public function is_enable( $slug ) {

		if ( isset( $this->actives[ $slug ] ) ) {
			return true;
		}

		return false;

	}


}

global $woocommerce_eu_vat_compliance_classes;
if ( ! is_null( $woocommerce_eu_vat_compliance_classes ) && isset( $woocommerce_eu_vat_compliance_classes['WC_EU_VAT_Compliance_VAT_Number'] ) && ( $woocommerce_eu_vat_compliance_classes['WC_EU_VAT_Compliance_VAT_Number'] instanceof WC_EU_VAT_Compliance_VAT_Number ) ) {
	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_EU_Vat(), 'wc-eu-vats' );
}
