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
final class PeachPay_Account_Data extends PeachPay_Admin_Tab {

	/**
	 * The id to reference the stored settings with.
	 *
	 * @var string
	 */
	public $id = 'account_data';

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
		return 'data';
	}

	/**
	 * Gets the tab title.
	 */
	public function get_title() {
		return __( 'PeachPay data', 'peachpay-for-woocommerce' );
	}

	/**
	 * Gets the tab description.
	 */
	public function get_description() {
		return __( 'Configure PeachPay account settings.', 'peachpay-for-woocommerce' );
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
			'api_permission' => array(
				'type'        => 'custom_api_permissions',
				'title'       => 'WooCommerce API Permissions',
				'description' => __( 'If needed, our support team may ask you to give us permission to read non-sensitive data, like a list of installed plugins, in case you experience an issue with PeachPay.', 'peachpay-for-woocommerce' ),
				'class'       => '',
			),
			'data_retention' => array(
				'type'        => 'checkbox',
				'title'       => __( 'Data retention', 'peachpay-for-woocommerce' ),
				'label'       => __( 'Remove data on uninstall', 'peachpay-for-woocommerce' ),
				'description' => __( 'PeachPay settings and data will be removed if the plugin is uninstalled.', 'peachpay-for-woocommerce' ),
				'default'     => 'no',
				'class'       => 'toggle',
			),
		);
	}

	/**
	 * Generate the api permissions HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 *
	 * @return string
	 */
	public function generate_custom_api_permissions_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'label'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		if ( ! $data['label'] ) {
			$data['label'] = $data['title'];
		}

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<div style="display:flex; flex-direction: row;margin: 0 0 0.5rem;">
						<a class="button-primary pp-button-primary" style="display: inline-block;margin-right: 0.5rem;" href="<?php echo esc_attr( $this->wc_permissions_authorize_url() ); ?>"><?php esc_html_e( 'Give access', 'peachpay-for-woocommerce' ); ?></a>
						<a class="button-secondary" style="border: none; color: inherit;" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=advanced&section=keys' ) ); ?>" style="display: inline-block;"><?php esc_html_e( 'Manage access', 'peachpay-for-woocommerce' ); ?></a>
					</div>
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
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

		settings_fields( 'peachpay_account_data_admin_settings' );
	}

	/**
	 * Attach to enqueue scripts hook.
	 */
	public function enqueue_admin_scripts() {
	}

	/**
	 * Gets the URL to authorize the WC rest API.
	 *
	 * @return string
	 */
	private function wc_permissions_authorize_url() {
		$params       = array(
			'app_name'     => 'PeachPay',
			'scope'        => 'read',
			'user_id'      => 1,
			'return_url'   => admin_url( 'admin.php?page=peachpay&tab=data&section=account' ),
			'callback_url' => peachpay_api_url( 'live' ) . 'api/v1/plugin/link?merchant_id=' . peachpay_plugin_merchant_id(),
		);
		$query_string = http_build_query( $params );

		return home_url( '/wc-auth/v1/authorize?' . $query_string );
	}
}

if ( 'yes' === PeachPay_Account_Data::get_setting( 'data_retention' ) ) {
	update_option( 'peachpay_data_retention', 'yes' );
} elseif ( 'no' === PeachPay_Account_Data::get_setting( 'data_retention' ) ) {
	update_option( 'peachpay_data_retention', 'no' );
}
