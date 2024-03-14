<?php

class PMCS_Settings_General extends PMCS_Setting_Abstract {
	public $id = 'general';
	public $title = '';

	public function __construct() {
		$this->title = __( 'General', 'pmcs' );

	}

	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
	}

	public function get_settings() {
		$fields = array();

		$currency_code_options = get_woocommerce_currencies();
		$currency_code = get_woocommerce_currency();

		foreach ( $currency_code_options as $code => $name ) {
			$currency_code_options[ $code ] = $name;
		}

		$fields[] = array(
			'title' => __( 'Garenal Settings', 'pmcs' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => $this->id,
		);

		$setting_url = admin_url( 'admin.php?page=' . pmcs()->admin->get_menu_slug() . '&tab=currencies' );

		$fields[] = array(
			'name'     => __( 'Default currency', 'pmcs' ),
			'desc'     => sprintf( __( 'You can set default currency in <a href="%1$s">Currency options</a>', 'pmcs' ), $setting_url ),
			'id'       => 'pmcs_default_currency_notice',
			'type'     => 'pmcs_custom_html',
			'class'    => 'wc-enhanced-select',
			'html'    => sprintf( 'The default currency is <strong>%1$s</strong>.', $currency_code_options[ $currency_code ] ),
		);

		$fields[] = array(
			'name'     => __( 'Store user data', 'pmcs' ),
			'desc_tip' => __( 'Use section or cookies to store user data', 'pmcs' ),
			'id'       => 'pmcs_store_data_type',
			'type'     => 'checkbox',
			'type'     => 'select',
			'options'  => array(
				'cookie' => __( 'Cookie', 'pmcs' ),
				'session' => __( 'Session', 'pmcs' ),
			),
		);

		$fields[] = array(
			'title'    => __( 'Convert currencies automatically if missing currency country price', 'pmcs' ),
			'desc'     => __( 'If disable the product that missing currency will remove from cart or checkout page.', 'pmcs' ),
			'id'       => 'pmcs_currency_auto_convert',
			'default'  => 'yes',
			'type'     => 'checkbox',
		);

		$fields[] = array(
			'title'    => __( 'Currency Countries (by IP)', 'pmcs' ),
			'desc'     => __( 'Automatically change price base on location (GeoIp).', 'pmcs' ),
			'id'       => 'pmcs_currency_by_ip',
			'default'  => 'yes',
			'type'     => 'checkbox',
		);

		// $fields[] = array(
		// 'title'    => __( 'Currency Languages (Locales) ', 'pmcs' ),
		// 'desc'     => __( 'Change price base on current languages automatically.', 'pmcs' ),
		// 'id'       => 'pmcs_currency_by_lang',
		// 'default'  => '',
		// 'type'     => 'checkbox',
		// );
		$fields[] = array(
			'type' => 'sectionend',
			'id' => $this->id,
		);

		$fields[] = array(
			'title' => __( 'Cart', 'pmcs' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => $this->id,
		);

		$fields[] = array(
			'title'    => __( 'Cart in default currency', 'pmcs' ),
			'desc'     => __( 'Force use default currency when users on cart page.', 'pmcs' ),
			'id'       => 'pmcs_cart_default_currency',
			'default'  => 'yes',
			'type'     => 'checkbox',
		);

		$fields[] = array(
			'type' => 'sectionend',
			'id' => $this->id,
		);

		$fields[] = array(
			'title' => __( 'Checkout', 'pmcs' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => $this->id,
		);

		$fields[] = array(
			'title'    => __( 'Checkout in default currency', 'pmcs' ),
			'desc'     => __( 'Force use default currency when users on checkout page.', 'pmcs' ),
			'id'       => 'pmcs_checkout_default_currency',
			'default'  => 'yes',
			'type'     => 'checkbox',
		);

		$fields[] = array(
			'type' => 'sectionend',
			'id' => $this->id,
		);

		return $fields;

	}
}
