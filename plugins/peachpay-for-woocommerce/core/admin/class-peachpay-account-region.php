<?php
/**
 * PeachPay Account Data Settings page.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * PeachPay Account Data Setting page.
 */
final class PeachPay_Account_Region extends PeachPay_Admin_Tab {

	/**
	 * The id to reference the stored settings with.
	 *
	 * @var string
	 */
	public $id = 'account_region';

	/**
	 * Gets the section url key.
	 */
	public function get_section() {
		return 'account';
	}

	/**
	 * Gets the tab url key.
	 */
	public function get_tab() {
		return 'region';
	}

	/**
	 * Gets the tab title.
	 */
	public function get_title() {
		return __( 'PeachPay region', 'peachpay-for-woocommerce' );
	}

	/**
	 * Gets the tab description.
	 */
	public function get_description() {
		return __( 'Configure PeachPay region settings.', 'peachpay-for-woocommerce' );
	}


	/**
	 * Include dependencies here.
	 */
	protected function includes() {}

	/**
	 * Register form fields here. This is optional but required if you want to display settings.
	 */
	protected function register_form_fields() {
		$default = 'us-east-1';
		$options = array(
			'us-east-1'      => __( 'United States', 'peachpay-for-woocommerce' ),
			'ap-southeast-2' => __( 'Australia', 'peachpay-for-woocommerce' ),
		);

		switch ( get_home_url() ) {
			case 'https://theme1.peachpay.app':
			case 'https://theme2.peachpay.app':
			case 'https://theme3.peachpay.app':
			case 'https://theme4.peachpay.app':
			case 'https://theme5.peachpay.app':
			case 'https://demo.peachpay.app':
			case 'https://ui-test.peachpay.app':
				$default = 'us-staging';
				$options = array_merge(
					$options,
					array(
						'us-staging' => __( 'Staging', 'peachpay-for-woocommerce' ),
					)
				);
				break;
			case 'https://woo.store.local':
			case 'https://store.local':
				$default = 'local';
				$options = array_merge(
					$options,
					array(
						'local' => __( 'Local Development', 'peachpay-for-woocommerce' ),
					)
				);
				break;
		}

		return array(
			'region' => array(
				'type'        => 'select',
				'title'       => __( 'Region', 'peachpay-for-woocommerce' ),
				'description' => __( 'If PeachPay feels slow, choose the region closest to you to give your shoppers a better experience.', 'peachpay-for-woocommerce' ),
				'default'     => $default,
				'options'     => $options,
			),
		);
	}

	/**
	 * Renders the Admin page.
	 */
	public function do_admin_view() {
		?>
		<h1>
			<?php echo esc_html( $this->get_title() ); ?>
		</h1>
		<?php

		parent::do_admin_view();
	}
}
