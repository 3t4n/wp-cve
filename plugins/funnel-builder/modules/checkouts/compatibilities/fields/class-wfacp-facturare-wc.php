<?php

/**
 * Facturare WooCommerce By George Ciobanu
 * Plugin URI: https://wordpress.org/plugins/facturare-persoana-fizica-sau-juridica/
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_Facturare_WC {
	private $obj = null;
	private $px = 7;
	private $facturare_arr = [ 'tip_facturare', 'cnp', 'cui', 'nr_reg_com', 'nume_banca', 'iban', 'billing_company' ];

	public function __construct() {

		/* Register Add field */ //
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_action' ] );
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_tip_facturare', '__return_false' );

		add_filter( 'wfacp_html_fields_tip_facturare_fields', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );


	}


	public function remove_action() {
		$this->obj = WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'Woo_Facturare_Public', 'override_checkout_fields' );
	}

	public function add_field( $fields ) {

		$fields['tip_facturare']        = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'WooFacturare' ],
			'id'         => 'tip_facturare',
			'field_type' => 'tip_facturare',
			'label'      => __( 'WooFacturare', 'woofunnels-aero-checkout' ),

		];
		$fields['tip_facturare_fields'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'WooFacturare_fields' ],
			'id'         => 'tip_facturare_fields',
			'field_type' => 'tip_facturare_fields',
			'label'      => __( 'WooFacturare Fields', 'woofunnels-aero-checkout' ),

		];

		return $fields;

	}

	public function process_wfacp_html( $field, $key ) {


		if ( ! $this->obj instanceof Woo_Facturare_Public ) {
			return;
		}

		$instance = wfacp_template();

		if ( 'pre_built' !== $instance->get_template_type() ) {
			$this->px = "7";
		} else {
			$this->px = $instance->get_template_type_px();
		}

		$facturare_arr  = $this->facturare_arr;
		$checkoutFields = $instance->get_checkout_fields();


		if ( isset( $checkoutFields['billing']['billing_company'] ) ) {
			$billing_company = $checkoutFields['billing']['billing_company'];
			if ( is_array( $billing_company ) && count( $billing_company ) > 0 ) {
				$checkoutFields['billing_company'] = [
					'label'         => 'Company',
					'class'         => array( 0 => 'form-row-wide', ),
					'autocomplete'  => 'organization',
					'priority'      => 30,
					'type'          => 'text',
					'cssready'      => array(),
					'placeholder'   => '',
					'id'            => 'billing_company',
					'address_group' => true,
				];
			}
		}
		if ( isset( $checkoutFields['billing']['billing_last_name'] ) ) {
			$billing_last_name = $checkoutFields['billing']['billing_last_name'];
			if ( is_array( $billing_last_name ) && count( $billing_last_name ) > 0 ) {
				$checkoutFields['billing_last_name'] = [
					'label'        => 'Last name',
					'required'     => 'true',
					'class'        => array( 'form-row-last', ),
					'autocomplete' => 'family-name',
					'priority'     => '20',
					'type'         => 'text',
					'id'           => 'billing_last_name',
					'field_type'   => 'billing',
					'placeholder'  => 'Doe',
					'data_label'   => 'Last name',
				];
			}
		}


		$fields = $this->obj->override_checkout_fields( $checkoutFields );

		$finalfields = [];

		foreach ( $fields as $key1 => $value ) {
			if ( in_array( $key1, $facturare_arr ) ) {

				$finalfields[ $key1 ] = $value;
			}

		}

		if ( 'tip_facturare' == $key ) {
			woocommerce_form_field( $key, $finalfields[ $key ] );

		} elseif ( 'tip_facturare_fields' == $key ) {
			unset( $finalfields['tip_facturare'] );
			if ( is_array( $finalfields ) && count( $finalfields ) > 0 ) {
				foreach ( $finalfields as $keyField => $keyField_value ) {
					woocommerce_form_field( $keyField, $keyField_value );
				}
			}

		}

	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( ! in_array( $key, $this->facturare_arr ) ) {
			return $args;
		}

		$width_class = 'wfacp-col-left-half';

		if ( ! in_array( 'av_tip_facturare_radio', $args['class'] ) ) {
			$all_cls             = array_merge( [ 'wfacp-form-control-wrapper ', $width_class ], $args['class'] );
			$input_class         = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$label_class         = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );
			$args['class']       = $all_cls;
			$args['cssready']    = [ $width_class ];
			$args['input_class'] = $input_class;
			$args['label_class'] = $label_class;
		}


		return $args;
	}

	public function internal_css() {
		?>
        <style>

            body .wfacp_main_form.woocommerce #tip_facturare_field {
                padding: 0 <?php echo $this->px ?>px;
            }

            body .wfacp_main_form.woocommerce p#tip_facturare_field input[type="radio"] {
                position: relative;
                left: auto;
                right: auto;
            }

            body .wfacp_main_form.woocommerce p#tip_facturare_field input[type="radio"] + label {
                padding-left: 10px;
            }

            body .wfacp_main_form.woocommerce .av-hide,
            body .wfacp_main_form.woocommerce .form-row.av-hide {
                display: none;
            }
        </style>
        <script>
            window.addEventListener('bwf_checkout_load', function () {
                if (jQuery('#tip_facturare').length > 0) {
                    jQuery('#tip_facturare').trigger('change');
                }
            });
        </script>
		<?php
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Facturare_WC(), 'facturare-wc' );

