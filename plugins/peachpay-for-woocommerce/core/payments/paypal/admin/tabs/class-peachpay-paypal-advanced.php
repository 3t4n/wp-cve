<?php
/**
 * PeachPay PayPal Advanced settings.
 *
 * @package PeachPay/PayPal/Admin
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * PeachPay advanced PayPal settings.
 */
final class PeachPay_PayPal_Advanced extends PeachPay_Admin_Tab {

	/**
	 * The id to reference the stored settings with.
	 *
	 * @var string
	 */
	public $id = 'paypal_advanced';

	/**
	 * Gets the section url key.
	 */
	public function get_section() {
		return 'paypal';
	}

	/**
	 * Gets the tab url key.
	 */
	public function get_tab() {
		return 'advanced';
	}

	/**
	 * Gets the tab title.
	 */
	public function get_title() {
		return __( 'PayPal advanced settings', 'peachpay-for-woocommerce' );
	}

	/**
	 * Gets the tab description.
	 */
	public function get_description() {
		return __( 'Configure additional options for PayPal through PeachPay.', 'peachpay-for-woocommerce' );
	}


	/**
	 * Include dependencies here.
	 */
	protected function includes() {}

	/**
	 * Register form fields here. This is optional but required if you want to display settings.
	 */
	protected function register_form_fields() {
		return array(
			'store_name'                  => array(
				'type'        => 'text',
				'title'       => __( 'Store name', 'peachpay-for-woocommerce' ),
				'description' => __( 'The name of the store displayed in the PayPal window.', 'peachpay-for-woocommerce' ),
				'default'     => get_bloginfo( 'name' ),
			),
			'refund_on_cancel'            => array(
				'type'        => 'checkbox',
				'title'       => __( 'Refund on cancel', 'peachpay-for-woocommerce' ),
				'description' => __( 'Automatically refund the payment when the order status is changed to cancelled.', 'peachpay-for-woocommerce' ),
				'default'     => 'no',
			),
			'itemized_order_details'      => array(
				'type'        => 'checkbox',
				'title'       => __( 'Order details (experimental)', 'peachpay-for-woocommerce' ),
				'label'       => __( 'Show itemized order details in the PayPal window', 'peachpay-for-woocommerce' ),
				'description' => __( 'Show line items in the PayPal window. This setting is not compatible with the WooCommerce tax setting "Prices entered with tax: Yes, I will enter prices inclusive of tax".', 'peachpay-for-woocommerce' ),
				'default'     => 'no',
			),
			'payment_description_prefix'  => array(
				'type'        => 'text',
				'title'       => __( 'Payment description prefix', 'peachpay-for-woocommerce' ),
				'description' => __( 'Customize the prefix for the payment description. Default prefix is the site name. Example: "Site Name - Order 1234" where "Site Name" is the prefix.', 'peachpay-for-woocommerce' ),
				'default'     => '',
			),
			'payment_description_postfix' => array(
				'type'        => 'text',
				'title'       => __( 'Payment description postfix', 'peachpay-for-woocommerce' ),
				'description' => __( 'Customize the postfix for the payment description. Default postfix is nothing. Example: "Site Name - Order 1234 (PeachPay)" where "(PeachPay)" is the postfix.', 'peachpay-for-woocommerce' ),
				'default'     => '',
			),
		);
	}

	/**
	 * Renders the Admin page.
	 */
	public function do_admin_view() {
		parent::do_admin_view()
		?>
			<div class="gateway-list">
			<?php
				$gateway_list = PeachPay_PayPal_Integration::get_payment_gateways();
				require PeachPay::get_plugin_path() . '/core/admin/views/html-gateways.php';
			?>
			</div>
		<?php
	}
}

// Migration code, should only run once.
if ( get_option( 'peachpay_migrated_payment_description_paypal', 0 ) === 0 ) {
	if ( peachpay_get_settings_option( 'peachpay_payment_options', 'payment_description_prefix' ) ) {
		$value = peachpay_get_settings_option( 'peachpay_payment_options', 'payment_description_prefix' );
		PeachPay_PayPal_Advanced::update_setting( 'payment_description_prefix', $value );
	}

	if ( peachpay_get_settings_option( 'peachpay_payment_options', 'payment_description_postfix' ) ) {
		$value = peachpay_get_settings_option( 'peachpay_payment_options', 'payment_description_postfix' );
		PeachPay_PayPal_Advanced::update_setting( 'payment_description_postfix', $value );
	}

	update_option( 'peachpay_migrated_payment_description_paypal', 1 );
}
