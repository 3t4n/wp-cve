<?php
/**
 * PayPal Credit WC gateway.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * This class allows us to submit orders with the PeachPay PayPal Credit payment method.
 */
class PeachPay_PayPal_Credit_Gateway extends PeachPay_PayPal_Payment_Gateway {
	/**
	 * Default constructor.
	 */
	public function __construct() {
		$this->id                = 'peachpay_paypal_credit';
		$this->icons             = array(
			'full'  => array(
				'color' => PeachPay::get_asset_url( 'img/marks/paypal/credit-full.svg' ),
			),
			'small' => array(
				'color' => PeachPay::get_asset_url( 'img/marks/paypal/credit-small-color.svg' ),
			),
		);
		$this->settings_priority = 2;

		$this->title              = 'PayPal Credit';
		$this->description        = '';
		$this->method_title       = 'PayPal Credit (PeachPay)';
		$this->method_description = 'Accept PayPal Credit payments';

		$this->countries             = array( 'US', 'GB' );
		$this->min_amount            = 99;
		$this->min_max_currency      = 'USD';
		$this->payment_method_family = __( 'Revolving line of credit similar to a credit card', 'peachpay-for-woocommerce' );

		$global_fields = array();
		$global_fields = $this->paypal_button_header_settings( $global_fields );
		$global_fields = $this->paypal_button_color_settings( $global_fields );
		$global_fields = $this->paypal_button_shape_settings( $global_fields );
		$global_fields = $this->paypal_button_label_settings( $global_fields );
		$global_fields = $this->paypal_button_height_settings( $global_fields );

		$this->form_fields = array_merge(
			$this->form_fields,
			$global_fields
		);

		parent::__construct();
	}

	/**
	 * Gateway paypal button color settings. PayPal credit only supports 3 colors.
	 *
	 * @param array $form_fields The current fields.
	 */
	protected function paypal_button_color_settings( $form_fields ) {
		return array_merge(
			$form_fields,
			array(
				'paypal_button_color' => array(
					'type'    => 'select',
					'title'   => __( 'Button Color', 'peachpay-for-woocommerce' ),
					'default' => 'darkblue',
					'options' => array(
						'darkblue' => __( 'Dark Blue', 'peachpay-for-woocommerce' ),
						'black'    => __( 'Black', 'peachpay-for-woocommerce' ),
						'white'    => __( 'White', 'peachpay-for-woocommerce' ),
					),
				),
			)
		);
	}
}
