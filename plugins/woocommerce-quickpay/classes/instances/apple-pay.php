<?php

class WC_QuickPay_Apple_Pay extends WC_QuickPay_Instance {

	public $main_settings = null;

	public function __construct() {
		parent::__construct();

		// Get gateway variables
		$this->id = 'quickpay_apple_pay';

		$this->method_title = 'QuickPay - Apple Pay';

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
				'title'   => __( 'Enable', 'woo-quickpay' ),
				'type'    => 'checkbox',
				'label'   => sprintf( __( 'Enable %s payment', 'woo-quickpay' ), 'Apple Pay' ),
				'default' => 'no',
				'description' => sprintf(__( 'Works only in %s.', 'woo-quickpay' ), 'Safari' )
			],
			'_Shop_setup' => [
				'type'  => 'title',
				'title' => __( 'Shop setup', 'woo-quickpay' ),
			],
			'title'       => [
				'title'       => __( 'Title', 'woo-quickpay' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => __( 'Apple Pay', 'woo-quickpay' )
			],
			'description' => [
				'title'       => __( 'Customer Message', 'woo-quickpay' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => sprintf( __( 'Pay with %s', 'woo-quickpay' ), 'Apple Pay' )
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
		return 'apple-pay';
	}

	/**
	 * @param $icon
	 *
	 * @return string
	 */
	public function filter_icon( $icon ) {
		if ( 'apple_pay' === $icon ) {
			$icon = 'apple-pay';
		}

		return $icon;
	}

	/**
	 * @return bool
	 */
	public function is_available() {
		$available = parent::is_available();
		if ( $available && ! WC_QuickPay_Helper::is_browser( 'safari' ) && ! is_admin() ) {
			$available = false;
		}

		return $available;
	}
}
