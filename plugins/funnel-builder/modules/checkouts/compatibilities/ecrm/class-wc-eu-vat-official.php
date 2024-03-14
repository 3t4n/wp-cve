<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_WC_EU_Vat_Official {

	public function __construct() {
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_eu_fields' ] );
		add_filter( 'wfacp_html_fields_wc_eu_vat_official_vat_number', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );


	}


	public function is_enable() {

		if ( defined( 'WC_EU_VAT_VERSION' ) && class_exists( 'WC_EU_VAT_Number' ) ) {
			return true;
		}

		return false;

	}

	public function action() {
		if ( ! $this->is_enable() ) {
			return;
		}
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
	}

	public function add_eu_fields( $field ) {

		if ( $this->is_enable() ) {
			$field['wc_eu_vat_official_vat_number'] = [
				'type'        => 'wfacp_html',
				'field_type'  => 'advanced',
				'class'       => [ 'wfacp_wc_eu_vat_official_vat_number' ],
				'id'          => 'wc_eu_vat_official_vat_number',
				'label'       => __( 'EU VAT', 'woocommerce' ),
				'placeholder' => __( 'EU VAT', 'woocommerce' ),
			];
		}

		return $field;
	}

	public function process_wfacp_html( $field, $key ) {
		if ( 'wc_eu_vat_official_vat_number' == $key && $this->is_enable() ) {
			$user_id = get_current_user_id();
			if ( defined( 'WC_EU_VAT_VERSION' ) && version_compare( WC_EU_VAT_VERSION, '2.3.1', '>' ) ) {
				woocommerce_form_field( 'billing_vat_number', [
					'required'    => 'yes' === get_option( 'woocommerce_eu_vat_number_b2b', 'false' ),
					'label'       => get_option( 'woocommerce_eu_vat_number_field_label', 'VAT number' ),
					'placeholder' => get_option( 'woocommerce_eu_vat_number_field_label', 'VAT number' ),
					'default'     => $user_id > 0 ? get_user_meta( $user_id, 'vat_number', true ) : '',
					'class'       => [
						'form-row-wide',
						'update_totals_on_change',
						'wfacp_billing_vat_number',
					],

					'description' => get_option( 'woocommerce_eu_vat_number_field_description', '' ),
					'id'          => 'woocommerce_eu_vat_number',
					'priority'    => 120,
				] );

			} else {
				WC_EU_VAT_Number::vat_number_field();
			}

		}
	}

	public function add_default_wfacp_styling( $args, $key ) {


		if ( $key == 'billing_vat_number' && $this->is_enable() ) {

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
		if ( defined( 'WC_EU_VAT_VERSION' ) ) {
			?>
            <style>

                p#billing_vat_number_field:not(.wfacp-anim-wrap) label {
                    top: 17px;
                    margin-top: 0;
                    bottom: auto;
                    line-height: 20px;
                }

                p#billing_vat_number_field:not(.wfacp-anim-wrap) input {
                    padding-top: 12px;
                    padding-bottom: 10px;
                }

                body .wfacp_main_form #billing_vat_number_field.wfacp-col-full #billing_vat_number-description {
                    position: relative;
                    bottom: 0;
                    left: 0;
                    font-size: 13px;
                    color: #777777;
                    left: 0;
                }

                body .wfacp_main_form.woocommerce #woocommerce_eu_vat_number_field span#woocommerce_eu_vat_number-description {
                    font-size: 13px;
                    line-height: 18px;
                    color: #777777;
                }

                body .wfacp_main_form.woocommerce #woocommerce_eu_vat_number {
                    margin-bottom: 6px;
                }

                body .wfacp_main_form.woocommerce #woocommerce_eu_vat_number_field:not(.wfacp-anim-wrap) label {
                    top: 20px;
                    margin: 0;
                    bottom: auto;
                    line-height: 1;
                }


            </style>
			<?php
		}

	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_EU_Vat_Official(), 'wc-eu-vat-official' );
