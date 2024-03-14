<?php
/**
 * Handles payment methods section of PeachPay's analytics admin panel.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

require_once PEACHPAY_ABSPATH . 'core/abstract/class-peachpay-admin-tab.php';

/**
 * Sets up needed information for the Payment Methods Tab.
 */
final class PeachPay_Analytics_Settings extends PeachPay_Admin_Tab {
	/**
	 * ID for this specific class.
	 *
	 * @var string $id.
	 */
	public $id = 'analytics_settings';

	/**
	 * The value of the $_GET["section"] parameter when this page is loaded.
	 */
	public function get_section() {
		return 'analytics';
	}

	/**
	 * The value of the $_GET["tab"] parameter when this page is loaded.
	 */
	public function get_tab() {
		return 'settings';
	}

	/**
	 * The title for this tab.
	 *
	 * @var string
	 */
	public function get_title() {
		return __( 'Analytics settings', 'peachpay-for-woocommerce' );
	}

	/**
	 * The description for this tab.
	 */
	public function get_description() {
		return __( 'Here are the analytics settings. Change the settings below to customize analytics for this store. Please note: turning this on and off frequently may cause slight inaccuracies in the analytics.', 'peachpay-for-woocommerce' );
	}

	/**
	 * Include dependencies here.
	 */
	protected function includes() {
		PeachPay_Onboarding_Tour::complete_section( 'analytics' );
	}

	/**
	 * Register admin view here.
	 */
	public function do_admin_view() {
		?>
		<form method="POST" action="" enctype="multipart/form-data" class="pp-analytics-payment-methods-wide-graph" id="pp-analytics-settings">
			<h1>
				<?php echo esc_html( $this->get_title() ); ?>
			</h1>
			<p>
				<?php echo esc_html( $this->get_description() ); ?>
			</p>
			<table class="form-table">
				<?php $this->generate_settings_html( $this->get_form_fields(), true ); ?>
			</table>
			<p class="submit">
				<button name="save" class="button-primary pp-button-primary"" type="submit" value="<?php esc_attr_e( 'Save changes', 'peachpay-for-woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'peachpay-for-woocommerce' ); ?></button>
				<?php wp_nonce_field( 'peachpay-settings' ); ?>
			</p>
		</form>
		<?php
	}

	/**
	 * Register form fields here. This is optional but required if you want to display settings.
	 */
	protected function register_form_fields() {
		return array(
			'enabled' => array(
				'type'        => 'checkbox',
				'title'       => __( 'Enabled', 'peachpay-for-woocommerce' ),
				'description' => __( 'Toggle the Analytics on or off.', 'peachpay-for-woocommerce' ),
				'default'     => 'yes',
				'class'       => 'toggle',
			),
		);
	}
}
