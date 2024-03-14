<?php

/**
 * We load support payment and settings input.
 *
 * @package Tilopay
 */

namespace Tilopay;

class TilopayConfig {
	public static function allowedPayments() {
		//Payment options allowed
		return array(
			'products',
			'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change_customer',
			'subscription_payment_method_change',
			'subscription_payment_method_change_admin'
		);
	}

	public static function formConfigFields() {
		//Form fields to config TILOPAY
		return array(
			'enabled' => array(
				'title' => __('Enable/Disable', 'tilopay'),
				'label' => __('Enable TILOPAY', 'tilopay'),
				'type' => 'checkbox',
				'description' => '',
				'default' => 'no'
			),
			'title' => array(
				'title' => __('Title', 'tilopay'),
				'type' => 'text',
				'description' => __('Title to be displayed in the payment methods', 'tilopay'),
				'default' => 'Tilopay',
				'placeholder' => __('Pay with', 'tilopay') . ' Tilopay',
			),
			// 'icon' => array(
			// 	'title' => __('Icon', 'tilopay'),
			// 	'type' => 'hidden',
			// 	'description' => __('Click on the image to change the payment method icon.', 'tilopay'),
			// 	'default' => '',
			// ),
			'tpay_key' => array(
				'title' => __('Integration key', 'tilopay'),
				'type' => 'text',
				'description' => __('Key of the integration provided by TILOPAY.', 'tilopay'),
				'default' => __('Payment with credit cards.', 'tilopay')
			),
			'tpay_user' => array(
				'title' => __('API user', 'tilopay'),
				'type' => 'text',
				'description' => __('Integration user provided by TILOPAY.', 'tilopay'),
				'default' => __('Payment with credit cards.', 'tilopay')
			),
			'tpay_password' => array(
				'title' => __('API password', 'tilopay'),
				'type' => 'text',
				'description' => __('Integration API password  provided by TILOPAY.', 'tilopay'),
				'default' => __('Payment with credit cards.', 'tilopay')
			),
			'tpay_capture' => array(
				'title' => __('Immediate capture', 'tilopay'),
				'type' => 'select',
				'options' => array('yes' => __('Yes, capture', 'tilopay'), 'no' => __('Do not capture', 'tilopay')),
				'description' => __('Select no, if you require authorization without capture, the orders will be in Pending payment status. To capture, the order status must be changed to Processing. Maximum date to capture: 7 days after authorized, after 7 days the collection is automatically canceled', 'tilopay'),
				'default' => 'yes'
			),
			'tpay_capture_yes' => array(
				'title' => __('Order Status', 'tilopay'),
				'type' => 'select',
				'options' => array('processing' => __('Processing', 'tilopay'), 'completed' => __('Completed', 'tilopay')),
				'description' => __('Select the order payment status', 'tilopay'),
				'default' => 'processing'
			),
			'tpay_logo_options' => array(
				'title' => __('Set up logos', 'tilopay'),
				'description' => __('Select which logos to show, you can show all of them or select which ones you prefer.', 'tilopay'),
				'type' => 'multiselect',
				//'default' => 'visa',
				//'class' => 'msf_multiselect_container',
				//'css' => 'CSS rules added line to the input',
				//'label' => 'Label', // checkbox only
				'options' => array(
					'visa' => 'Visa',
					'mastercard' => 'Mastercard',
					'american_express' => 'American Express',
					'sinpemovil' => 'Sinpemóvil',
					//'credix' => 'Credix',//uncommetn when is ready
					'sistema_clave' => 'Sistema Clave',
					'mini_cuotas' => 'Minicuotas',
					'tasa_cero' => 'Tasa Cero',
				) // array of options for select/multiselects only
			),
			'tpay_redirect' => array(
				'title' => __('Embedded native payment or through redirect', 'tilopay'),
				'type' => 'select',
				'options' => array('yes' => __('Redirect to payment form', 'tilopay'), 'no' => __('Native checkout payment form', 'tilopay')),
				'description' => __('Select if you want to redirect the user to process the payment or use native checkout payment', 'tilopay'),
				'default' => 'yes'
			),
		);
	}
}
