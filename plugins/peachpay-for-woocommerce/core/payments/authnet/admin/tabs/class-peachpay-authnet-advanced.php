<?php
/**
 * PeachPay Authorize.net Advanced settings.
 *
 * @package PeachPay/Authnet/Admin
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * PeachPay advanced Authorize.net settings.
 */
final class PeachPay_Authnet_Advanced extends PeachPay_Admin_Tab {
	/**
	 * The id to reference the stored settings with.
	 *
	 * @var string
	 */
	public $id = 'authnet_advanced';

	/**
	 * Gets the section url key.
	 */
	public function get_section() {
		return 'authnet';
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
		return __( 'Authorize.net advanced settings', 'peachpay-for-woocommerce' );
	}

	/**
	 * Gets the tab description.
	 */
	public function get_description() {
		return __( 'Configure additional options for Authorize.net through PeachPay.', 'peachpay-for-woocommerce' );
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
			'capture_on_complete'         => array(
				'type'        => 'checkbox',
				'title'       => __( 'Capture on complete', 'peachpay-for-woocommerce' ),
				'description' => __( 'Automatically capture authorized payments when the order status is changed to complete.', 'peachpay-for-woocommerce' ),
				'default'     => 'no',
			),
			'refund_on_cancel'            => array(
				'type'        => 'checkbox',
				'title'       => __( 'Refund on cancel', 'peachpay-for-woocommerce' ),
				'description' => __( 'Automatically refund the payment when the order status is changed to cancelled.', 'peachpay-for-woocommerce' ),
				'default'     => 'no',
			),
			'itemized_order_details'      => array(
				'type'        => 'checkbox',
				'title'       => __( 'Itemized order information', 'peachpay-for-woocommerce' ),
				'label'       => __( 'Include itemized order in Authorize.net payment transaction request', 'peachpay-for-woocommerce' ),
				'description' => __( 'Show itemized order information in the Transaction Detail page', 'peachpay-for-woocommerce' ),
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
				$gateway_list = PeachPay_Authnet_Integration::get_payment_gateways();
				require PeachPay::get_plugin_path() . '/core/admin/views/html-gateways.php';
			?>
			</div>
		<?php
	}
}

// Migration code, should only run once.
if ( get_option( 'peachpay_migrated_payment_description_authnet', 0 ) === 0 ) {
	if ( peachpay_get_settings_option( 'peachpay_payment_options', 'payment_description_prefix' ) ) {
		$value = peachpay_get_settings_option( 'peachpay_payment_options', 'payment_description_prefix' );
		PeachPay_Authnet_Advanced::update_setting( 'payment_description_prefix', $value );
	}

	if ( peachpay_get_settings_option( 'peachpay_payment_options', 'payment_description_postfix' ) ) {
		$value = peachpay_get_settings_option( 'peachpay_payment_options', 'payment_description_postfix' );
		PeachPay_Authnet_Advanced::update_setting( 'payment_description_postfix', $value );
	}

	update_option( 'peachpay_migrated_payment_description_authnet', 1 );
}
