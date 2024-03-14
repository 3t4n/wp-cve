<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

class Sibs_Backend_Settings {

	
	public static function sibs_create_backend_payment_settings( $payment_id ) {

	    $form_fields = [];

		$form_fields = array_merge($form_fields, array(
			'enabled'     => array(
				'title'   => __( 'BACKEND_CH_ACTIVE', 'wc-sibs' ),
				'type'    => 'checkbox',
				'default' => '',
			),
            'server_mode' => array(
                'title'   => __( 'BACKEND_CH_SERVER', 'wc-sibs' ),
                'type'    => 'select',
                'options' => array(
                    'TEST' => __( 'BACKEND_CH_MODE_TEST', 'wc-sibs' ),
                    'LIVE' => __( 'BACKEND_CH_MODE_LIVE', 'wc-sibs' ),
                ),
                'default' => '0',
            )
		));

		if ('' === $payment_id) {
		    $form_fields = array_merge($form_fields, [
                'dpgUser' => array(
                    'title' => __( 'BACKEND_CH_LOGIN', 'wc-sibs' ),
                    'type'    => 'text',
                    'default' => '',
                ),
                'dpgPassword' => array(
                    'title' => __( 'BACKEND_CH_PASSWORD', 'wc-sibs' ),
                    'type'    => 'text',
                    'default' => '',
                ),
            ]);
        }

		if ( 'sibs_cc' === $payment_id || 'sibs_ccsaved' === $payment_id ) {

            $form_fields['token'] = array(
                'title' => __('BACKEND_CH_TOKEN', 'wc-sibs'),
                'type' => 'text',
                'default' => ''
            );
			$form_fields['card_types'] = array(
				'title'   => __( 'BACKEND_CH_CARDS', 'wc-sibs' ),
				'type'    => 'multiselect',
				'css'   => 'height:12em;',
				'options' => array(
					'AMEX'         => __( 'BACKEND_CC_AMEX', 'wc-sibs' ),
					'MAESTRO'      => __( 'BACKEND_CC_MAESTRO', 'wc-sibs' ),
					'MASTER'       => __( 'BACKEND_CC_MASTER', 'wc-sibs' ),
					'MASTERDEBIT'  => __( 'BACKEND_CC_MASTERDEBIT', 'wc-sibs' ),
					'VISA'         => __( 'BACKEND_CC_VISA', 'wc-sibs' ),
					'VISADEBIT'    => __( 'BACKEND_CC_VISADEBIT', 'wc-sibs' ),
					'VISAELECTRON' => __( 'BACKEND_CC_VISAELECTRON', 'wc-sibs' ),
					'VPAY'         => __( 'BACKEND_CC_VPAY', 'wc-sibs' ),
				),
				'description'    => '<br />' . esc_attr( __( 'Hold Ctrl while selecting the card you want to activate the payment gateway for the selected card.', 'wc-sibs' ) ),
			);
            $form_fields['payment_desc'] = array(
                'title' => __('BACKEND_CH_PAYMENTDESC', 'wc-sibs'),
                'type' => 'textarea',
                'default' => ''
            );
		}

		if ( 'sibs_mbway' === $payment_id || 'sibs_ccsaved' === $payment_id || 'sibs_dd' === $payment_id || 'sibs_ddsaved' === $payment_id || 'sibs_paydirekt' === $payment_id || 'sibs_cc' === $payment_id) {

			$form_fields['trans_mode'] = array(
				'title'   => __( 'BACKEND_CH_MODE', 'wc-sibs' ),
				'type'    => 'select',
				'options' => array(
					'DB' => __( 'BACKEND_CH_MODEDEBIT', 'wc-sibs' ),
					'PA' => __( 'BACKEND_CH_MODEPREAUTH', 'wc-sibs' ),
					'PA.CP' => __( 'PA.CP', 'wc-sibs' ),
				),
			);
		}

		if ( 'sibs_ccsaved' === $payment_id || 'sibs_ddsaved' === $payment_id || 'sibs_paypalsaved' === $payment_id ) {

			$form_fields['amount_registration'] = array(
				'title'       => __( 'BACKEND_CH_AMOUNT', 'wc-sibs' ),
				'type'        => 'text',
				'default'     => '',
				'description' => __( 'BACKEND_TT_REGISTRATION_AMOUNT', 'wc-sibs' ),
			);
		}

		if ( 'sibs_ccsaved' === $payment_id ) {

			$form_fields['multichannel'] = array(
				'title'       => __( 'BACKEND_CH_MULTICHANNEL', 'wc-sibs' ),
				'type'        => 'select',
				'options'     => array(
					true  => __( 'BACKEND_BT_YES', 'wc-sibs' ),
					false => __( 'BACKEND_BT_NO', 'wc-sibs' ),
				),
				'default'     => 0,
				'description' => __( 'BACKEND_TT_MULTICHANNEL', 'wc-sibs' ),
			);
		}

		if ( 'sibs_paydirekt' === $payment_id ) {

			$form_fields['minimum_age']        = array(
				'title'   => __( 'BACKEND_CH_MINIMUM_AGE', 'wc-sibs' ),
				'type'    => 'text',
				'default' => '',
			);
			$form_fields['payment_is_partial'] = array(
				'title'   => __( 'BACKEND_CH_PAYMENT_IS_PARTIAL', 'wc-sibs' ),
				'type'    => 'select',
				'options' => array(
					'true'  => __( 'BACKEND_BT_YES', 'wc-sibs' ),
					'false' => __( 'BACKEND_BT_NO', 'wc-sibs' ),
				),
				'default' => 'false',
			);
		}

		if ( 'sibs_klarnains' === $payment_id ) {

			$form_fields['pclass'] = array(
				'title'       => __( 'BACKEND_CH_KLARNA_PCLASS', 'wc-sibs' ),
				'type'        => 'text',
				'default'     => '',
				'description' => __( 'BACKEND_TT_KLARNAPCLASS', 'wc-sibs' ),
			);
		}

		$form_fields['channel_id'] = array(
			'title'   => __( 'BACKEND_CH_CHANNEL', 'wc-sibs' ),
			'type'    => 'text',
			'default' => '',
		);

		if ( 'sibs_ccsaved' === $payment_id ) {

			$form_fields['channel_moto'] = array(
				'title'       => __( 'BACKEND_CH_MOTO', 'wc-sibs' ),
				'type'        => 'text',
				'default'     => '',
				'description' => __( 'BACKEND_TT_CHANNEL_MOTO', 'wc-sibs' ),
			);
		}

		if ( 'sibs_multibanco' === $payment_id ) {

            $form_fields['token'] = array(
                'title' => __('BACKEND_CH_TOKEN', 'wc-sibs'),
                'type' => 'text',
                'default' => ''
            );

			$form_fields['sibs_payment_entity'] = array(
				'title'   => __( 'BACKEND_TT_SIBS_ENTITY', 'wc-sibs' ),
				'type'    => 'text',
				'default' => '',
			);

			$form_fields['sibs_payment_date_value'] = array(
				'title'   => __( 'SIBS Reference Expiration Value', 'wc-sibs' ),
				'type'    => 'text',
				'default' => '',
			);

			$form_fields['sibs_payment_date_unit'] = array(
				'title'   => __( 'SIBS Reference Expiration Unit', 'wc-sibs' ),
				'type'    => 'select',
				'options' => array(
					'minute' => __( 'Minute', 'wc-sibs' ),
					'hour' => __( 'Hour', 'wc-sibs' ),
					'day' => __( 'Day', 'wc-sibs' ),
					'month' => __( 'Month', 'wc-sibs' ),
				),
				'default' => 'false',
			);

            $form_fields['payment_desc'] = array(
                'title' => __('BACKEND_CH_PAYMENTDESC', 'wc-sibs'),
                'type' => 'textarea',
                'default' => ''
            );
		}

		if ('sibs_mbway' === $payment_id) {
            $form_fields['token'] = array(
                'title' => __('BACKEND_CH_TOKEN', 'wc-sibs'),
                'type' => 'text',
                'default' => ''
            );

            $form_fields['payment_desc'] = array(
                'title' => __('BACKEND_CH_PAYMENTDESC', 'wc-sibs'),
                'type' => 'textarea',
                'default' => ''
            );
        }

		return $form_fields;
	}
	
	public static function sibs_backend_payment_title( $payment_id ) {

		return 'SIBS ' . Sibs_General_Functions::sibs_translate_backend_payment( $payment_id );

	}

	public static function sibs_backend_payment_desc( $payment_id ) {

		if ( $payment_id == 'sibs_cc'){
			return __( 'Accept Card payments from VISA, MasterCard and AMEX brands.', 'wc-sibs' );
		} else if ( $payment_id == 'sibs_multibanco'){
			return __( 'Accept MULTIBANCO reference payments. The client makes the payment in the ATM, home banking, mobile banking and POS channels.', 'wc-sibs' );
		} else {
			return __( 'Allow your clients to purchase online by authorizing the purchase in the MB WAY app.', 'wc-sibs' );
		}
	}

}

