<?php

/**
 * tefacturo Comprobantes Electronicos
 * Plugin URI: https://tefacturo.pe/solucion-para-pymes/
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_tefacturo_lt
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_tefacturo_lt {

	public function __construct() {

		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function add_field( $fields ) {

		if ( function_exists( 'add_c_comp' ) ) {
			$fields['c_comp'] = [
				'type'        => 'text',
				'class'       => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap' ],
				'id'          => 'c_comp',
				'field_type'  => 'c_comp',
				'label'       => __( 'N° de documento', 'woocommerce' ),
				'placeholder' => _x( 'Ingrese su nro de documento', 'placeholder', 'woocommerce' ),

			];
		}
		if ( function_exists( 'add_ruc' ) ) {
			$fields['ruc'] = [
				'type'        => 'text',
				'class'       => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap' ],
				'id'          => 'ruc',
				'field_type'  => 'ruc',
				'label'       => __( 'RUC', 'woocommerce' ),
				'placeholder' => _x( 'Ingrese su nro de RUC', 'placeholder', 'woocommerce' ),
			];
		}
		if ( function_exists( 'custom_checkout_question_field' ) ) {
			$fields['t_docum'] = [
				'type'           => 'wfacp_radio',
				'class'          => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'tipo_documento' ],
				'id'             => 't_docum',
				'field_type'     => 't_docum',
				'label'          => __( 'Tipo de documento', 'woocommerce' ),
				'data_label'     => __( 'Tipo de documento', 'woocommerce' ),
				'options'        => array( 'option1' => 'DNI', 'option2' => 'CE', ),
				'default'        => 'DNI',
				'required'       => true,
				'is_wfacp_field' => 'true',
				'cssready'       => [ 'wfacp-col-full' ],
				'input_class'    => [ 'wfacp-form-control' ],
				'label_class'    => [ 'wfacp-form-control-label' ],

			];
		}


		return $fields;
	}

	public function internal_css() {

		if ( ! function_exists( 'custom_checkout_question_field' ) ) {
			return;
		}
		?>
        <style>
            p#t_docum_field {
                padding-bottom: 0 !important;
            }


            body .wfacp_main_form.woocommerce input[type=checkbox] + label,
            body .wfacp_main_form.woocommerce input[type=radio] + label {
                display: block !important;
                padding-left: 25px !important;
                line-height: 20px !important;
                margin: 0 !important;
                cursor: pointer;
            }

            p#checkbox_msg3_field select {
                padding-top: 12px;
                padding-bottom: 12px;
            }
        </style>
		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_tefacturo_lt(), 'tefacturo-lt' );

