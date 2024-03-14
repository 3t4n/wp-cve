<?php

class WC_QuickPay_Google_Pay extends WC_QuickPay_Instance {

	public $main_settings = null;

	public function __construct() {
		parent::__construct();

		$this->supports = [
			'products',
			'subscriptions',
			'subscription_cancellation',
			'subscription_reactivation',
			'subscription_suspension',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change_admin',
			'subscription_payment_method_change_customer',
			'refunds',
			'multiple_subscriptions',
		];

		// Get gateway variables
		$this->id = 'quickpay_google_pay';

		$this->method_title = 'QuickPay - Google Pay';

		$this->setup();

		$this->title       = $this->s( 'title' );
		$this->description = $this->s( 'description' );

		add_filter( 'woocommerce_quickpay_cardtypelock_' . $this->id, [ $this, 'filter_cardtypelock' ] );

		add_filter( 'woocommerce_quickpay_checkout_gateway_icon', [ $this, 'filter_icon' ] );
	}


	/**
	 * init_form_fields function.
	 *
	 * Initiates the plugin settings form fields
	 *
	 * @access public
	 * @return array
	 */
	public function init_form_fields(): void {
		$this->form_fields = [
			'enabled'     => [
				'title'       => __( 'Enable', 'woo-quickpay' ),
				'type'        => 'checkbox',
				'label'       => sprintf( __( 'Enable %s payment', 'woo-quickpay' ), 'Google Pay' ),
				'description' => sprintf( __( 'Works only in %s.', 'woo-quickpay' ), 'Chrome' ),
				'default'     => 'no'
			],
			'_Shop_setup' => [
				'type'  => 'title',
				'title' => __( 'Shop setup', 'woo-quickpay' ),
			],
			'title'       => [
				'title'       => __( 'Title', 'woo-quickpay' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => __( 'Google Pay', 'woo-quickpay' )
			],
			'description' => [
				'title'       => __( 'Customer Message', 'woo-quickpay' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => sprintf( __( 'Pay with %s', 'woo-quickpay' ), 'Google Pay' )
			],
		];
	}


	/**
	 * filter_cardtypelock function.
	 *
	 * Sets the cardtypelock
	 *
	 * @access public
	 * @return string
	 */
	public function filter_cardtypelock() {
		return 'google-pay';
	}

	/**
	 * @param $icon
	 *
	 * @return string
	 */
	public function filter_icon( $icon ) {
		if ( 'google_pay' === $icon ) {
			$icon = 'google-pay';
		}

		return $icon;
	}

	/**
	 * @return bool
	 */
	public function is_available() {
		$available = parent::is_available();
		if ( $available && ! WC_QuickPay_Helper::is_browser( 'chrome' ) ) {
			$available = false;
		}

		return $available;
	}
}
