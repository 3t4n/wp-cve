<?php
/*

Copyright 2020 Refersion, Inc. (email : helpme@refersion.com)

This file is part of Refersion for WooCommerce.

Refersion for WooCommerce is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Refersion for WooCommerce is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Refersion for WooCommerce. If not, see <http://www.gnu.org/licenses/>.

*/

class Refersion_Admin
{

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;
	private $menu_id;
	private $menu_name = 'refersion-navigation';

	/**
	 * Start up
	 */
	public function __construct()
	{

		if (is_admin()) {
			$options = get_option('refersion_settings');
			add_action('admin_menu', array('Refersion_Admin', 'add_plugin_page'));
			add_action('admin_init', array('Refersion_Admin', 'page_init'));
		}

	}

	/**
	 * Display message upon plug-in activation
	 */
	public static function activation_message()
	{

		if (!is_array(get_option('refersion_settings'))) {

			$message = __('Refersion for WooCommerce is almost ready.', 'refersion-for-woocommerce');
			$link = sprintf(__('<a href="%1$s">Click here to configure the plugin</a>.', 'refersion-setting-admin'), 'admin.php?page=refersion-setting-admin');
			echo sprintf('<div id="refersion-message-warning" class="updated fade"><p><strong>%1$s</strong> %2$s</p></div>', $message, $link);

		}

	}

	/**
	 * Add options page
	 */
	public static function add_plugin_page()
	{

		// This page will be under the "WooCommerce" menu
		add_submenu_page(
			'woocommerce',
			'Refersion',
			'Refersion',
			'manage_options',
			'refersion-setting-admin',
			array('Refersion_Admin', 'create_admin_page')
		);

	}

	/**
	 * Add Settings link to Plugins page
	 */
	public static function add_plugins_settings($links)
	{

		$url = get_admin_url() . "admin.php?page=refersion-setting-admin";
		$settings_link = '<a href="' . $url . '">Settings</a>';
		array_unshift($links, $settings_link);

		return $links;
	}

	/**
	 * Options page callback
	 */
	public static function create_admin_page()
	{

		global $refersion_settings_page;

		// Does the user have permission to do this?
		if (!current_user_can('manage_options')) {
			return;
		}

		// Success message after updated
		if (isset($_GET['settings-updated'])) {
			echo '<div class="notice notice-success is-dismissible"><p>';
			_e(' Settings saved. ');
			echo '</p></div>';
		}

		// Set class property
		$refersion_settings_page->options = get_option('refersion_settings');
		?>

		<div class="wrap">

			<div>

				<img height="100px" src="<?php echo plugins_url('refersion_by_pantastic_logo.png', __FILE__); ?>" alt="Refersion by Pantastic"/>

				<p>
					<?php _e('In order to automatically setup Refersion tracking on your WooCommerce shop, the following settings must be filled out. For help, visit our <a href="https://support.refersion.com?utm_source=woocommerce-plugin" target="_blank">Knowledge Base</a>.', 'refersion-for-woocommerce'); ?>
				</p>

				<p>
					<?php _e('This plugin requires a <a href="https://www.refersion.com?utm_source=woocommerce-plugin" target="_blank">Refersion</a> account. If you do not already have an account, you can <a href="https://www.refersion.com/pricing?utm_source=woocommerce-plugin" target="_blank">sign up</a> right now.', 'refersion-for-woocommerce'); ?>
				</p>

			</div>

			<div>

				<form method="post" action="options.php">

					<?php

					settings_fields('refersion_option_group');
					do_settings_sections('refersion-setting-admin');

					submit_button();
					?>

				</form>

			</div>

		</div>

		<?php
	}

	/**
	 * Register and add settings
	 */
	public static function page_init()
	{

		register_setting(
			'refersion_option_group',
			'refersion_settings',
			array('Refersion_Admin', 'sanitize')
		);

		add_settings_section(
			'setting_section_1',
			'Configuration',
			array('Refersion_Admin', 'print_section_1_info'),
			'refersion-setting-admin'
		);

		add_settings_field(
			'refersion_status',
			'Refersion tracking enabled?',
			array('Refersion_Admin', 'refersion_status_callback'),
			'refersion-setting-admin',
			'setting_section_1'
		);

		add_settings_field(
			'refersion_public_api_key',
			'Your Refersion public API key',
			array('Refersion_Admin', 'refersion_public_api_key_callback'),
			'refersion-setting-admin',
			'setting_section_1'
		);

		add_settings_field(
			'refersion_secret_api_key',
			'Your Refersion secret API key',
			array('Refersion_Admin', 'refersion_secret_api_key_callback'),
			'refersion-setting-admin',
			'setting_section_1'
		);

		add_settings_section(
			'setting_section_2',
			'Advanced Settings',
			array('Refersion_Admin', 'print_section_2_info'),
			'refersion-setting-admin'
		);

		add_settings_field(
			'refersion_subdomain',
			'Your Refersion Subdomain ',
			array('Refersion_Admin', 'refersion_subdomain_callback'),
			'refersion-setting-admin',
			'setting_section_2'
		);

		add_settings_field(
			'refersion_item_price_choice',
			'How should individual line item prices be sent to Refersion? ',
			array('Refersion_Admin', 'refersion_item_price_choice_callback'),
			'refersion-setting-admin',
			'setting_section_2'
		);

		add_settings_field(
			'refersion_order_status_setting',
			'What status of order should be sent to Refersion? ',
			array('Refersion_Admin', 'refersion_order_status_setting_callback'),
			'refersion-setting-admin',
			'setting_section_2'
		);

		add_settings_field(
			'refersion_ip_address_setting',
			'How should Refersion find the IP address of the customer for tracking? ',
			array('Refersion_Admin', 'refersion_ip_address_setting_callback'),
			'refersion-setting-admin',
			'setting_section_2'
		);

		add_settings_field(
			'refersion_tracking_version',
			'Tracking Version',
			array('Refersion_Admin', 'refersion_tracking_version_callback'),
			'refersion-setting-admin',
			'setting_section_2'
		);

		add_settings_section(
			'setting_section_3',
			'Optional Features',
			array('Refersion_Admin', 'print_section_3_info'),
			'refersion-setting-admin'
		);

		add_settings_field(
			'refersion_cancellation_tracking',
			'Adjust Refersion conversions for cancellations in WooCommerce?',
			array('Refersion_Admin', 'refersion_cancellation_tracking_callback'),
			'refersion-setting-admin',
			'setting_section_3'
		);

		add_settings_field(
			'refersion_post_purchase_setting',
			'Show the post purchase widget on the Woocommerce thank you page?',
			array('Refersion_Admin', 'refersion_post_purchase_setting_callback'),
			'refersion-setting-admin',
			'setting_section_3'
		);

		add_settings_field(
			'refersion_post_purchase_code',
			'Post Purchase Widget Code',
			array('Refersion_Admin', 'refersion_post_purchase_code_callback'),
			'refersion-setting-admin',
			'setting_section_3'
		);

		add_settings_field(
			'refersion_order_prefix',
			'Add a prefix to orders sent into Refersion',
			array('Refersion_Admin', 'refersion_order_prefix_callback'),
			'refersion-setting-admin',
			'setting_section_3'
		);

	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 *
	 * @return array
	 */
	public static function sanitize($input)
	{

		$new_input = array();

		if (isset($input['refersion_public_api_key'])) {
			$new_input['refersion_public_api_key'] = trim($input['refersion_public_api_key']);
		}

		if (isset($input['refersion_secret_api_key'])) {
			$new_input['refersion_secret_api_key'] = trim($input['refersion_secret_api_key']);
		}

		if (isset($input['refersion_subdomain'])) {
			$new_input['refersion_subdomain'] = trim($input['refersion_subdomain']);
		}

		if (isset($input['refersion_status']) && in_array((int)$input['refersion_status'], array(0, 1), true)) {
			$new_input['refersion_status'] = $input['refersion_status'];
		}

		if (isset($input['refersion_item_price_choice']) && in_array($input['refersion_item_price_choice'], array('PRODUCT', 'ORDER'), true)) {
			$new_input['refersion_item_price_choice'] = trim($input['refersion_item_price_choice']);
		}

		if (isset($input['refersion_order_status_setting']) && in_array($input['refersion_order_status_setting'], array('COMPLETED', 'PROCESSING'), true)) {
			$new_input['refersion_order_status_setting'] = trim($input['refersion_order_status_setting']);
		}

		if (isset($input['refersion_ip_address_setting']) && in_array($input['refersion_ip_address_setting'], array('AUTO', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'), true)) {
			$new_input['refersion_ip_address_setting'] = trim($input['refersion_ip_address_setting']);
		}

		if (isset($input['refersion_tracking_version']) && in_array($input['refersion_tracking_version'], array('v3', 'v4'), true)) {
			$new_input['refersion_tracking_version'] = trim($input['refersion_tracking_version']);
		}

		if (isset($input['refersion_cancellation_tracking']) && in_array((int)$input['refersion_cancellation_tracking'], array(0, 1), true)) {
			$new_input['refersion_cancellation_tracking'] = $input['refersion_cancellation_tracking'];
		}

		if (isset($input['refersion_order_prefix'])) {
			// Remove all special characters except underscores and hyphens from user input
			$prefix = preg_replace('/[^\w-]/', '', $input['refersion_order_prefix']);
			$new_input['refersion_order_prefix'] = trim($prefix);
		}

		if (isset($input['refersion_post_purchase_setting']) && in_array((int)$input['refersion_post_purchase_setting'], array(0, 1), true)) {
			$new_input['refersion_post_purchase_setting'] = $input['refersion_post_purchase_setting'];
		}

		if (isset($input['refersion_post_purchase_code'])) {
			$new_input['refersion_post_purchase_code'] = trim($input['refersion_post_purchase_code']);
		}

		return $new_input;

	}

	/**
	 * A heading for the main configuration settings (section 1)
	 */
	public static function print_section_1_info()
	{

		print 'Enter your settings below:';
	}

	/**
	 * A heading for the advanced settings section (section 2)
	 */
	public static function print_section_2_info()
	{

		print 'The settings below are optional and for advanced use only:';
	}

	/**
	 * A heading for the optional features section (section 3)
	 */
	public static function print_section_3_info()
	{

		print 'The settings below enable additional plugin-specific functionality:';
	}

	/**
	 * Public key field
	 */
	public static function refersion_public_api_key_callback()
	{

		global $refersion_settings_page;

		printf(
			'<input type="text" id="refersion_public_api_key" name="refersion_settings[refersion_public_api_key]" value="%s" style="width:300px;" />',
			isset($refersion_settings_page->options['refersion_public_api_key']) ? esc_attr($refersion_settings_page->options['refersion_public_api_key']) : ''
		);

	}

	/**
	 * Secret key field
	 */
	public static function refersion_secret_api_key_callback()
	{

		global $refersion_settings_page;

		printf(
			'<input type="text" id="refersion_secret_api_key" name="refersion_settings[refersion_secret_api_key]" value="%s" style="width:300px;" />',
			isset($refersion_settings_page->options['refersion_secret_api_key']) ? esc_attr($refersion_settings_page->options['refersion_secret_api_key']) : ''
		);

	}

	/**
	 * Subdomain
	 */
	public static function refersion_subdomain_callback()
	{

		global $refersion_settings_page;

		printf(
			'<input type="text" id="refersion_subdomain" name="refersion_settings[refersion_subdomain]" value="%s" style="width:300px;" />',
			isset($refersion_settings_page->options['refersion_subdomain']) ? esc_attr($refersion_settings_page->options['refersion_subdomain']) : ''
		);

	}

	/**
	 * Enabled field
	 */
	public static function refersion_status_callback()
	{

		global $refersion_settings_page;

		$a = 'selected=selected';
		$b = '';

		if (isset($refersion_settings_page->options['refersion_status'])) {

			if ($refersion_settings_page->options['refersion_status'] == 1) {
				$a = '';
				$b = 'selected=selected';
			}

		}

		echo '<select id="secret_api_key" name="refersion_settings[refersion_status]"><option value="0" ' . $a . '>No, turn off Refersion reporting</option><option value="1" ' . $b . '>Yes, send orders to Refersion</option></select> ';

	}

	/**
	 * Line item price choice field
	 */
	public static function refersion_item_price_choice_callback()
	{

		global $refersion_settings_page;

		$a = 'selected=selected';
		$b = '';

		if (isset($refersion_settings_page->options['refersion_item_price_choice'])) {

			if ($refersion_settings_page->options['refersion_item_price_choice'] == 'ORDER') {
				$a = '';
				$b = 'selected=selected';
			}

		}

		echo '<select id="refersion_item_price_choice" name="refersion_settings[refersion_item_price_choice]"><option value="PRODUCT" ' . $a . '>Use original item price</option><option value="ORDER" ' . $b . '>Use order item price</option></select> ';

	}

	/**
	 * Default order status field
	 */
	public static function refersion_order_status_setting_callback()
	{

		global $refersion_settings_page;

		$a = 'selected=selected';
		$b = '';

		if (isset($refersion_settings_page->options['refersion_order_status_setting'])) {

			if ($refersion_settings_page->options['refersion_order_status_setting'] == 'PROCESSING') {

				$a = '';
				$b = 'selected=selected';

			}

		}

		echo '<select id="refersion_order_status_setting" name="refersion_settings[refersion_order_status_setting]">
					<option value="COMPLETED" ' . $a . '>Completed</option>
					<option value="PROCESSING" ' . $b . '>Processing</option>
				</select> ';

	}

	/**
	 * Default ip address setting field
	 */
	public static function refersion_ip_address_setting_callback()
	{

		global $refersion_settings_page;

		// Default settings
		$autoOption = 'selected=selected';
		$httpClientIpOption = '';
		$httpXForwardedForOption = '';
		$httpXForwarded = '';
		$httpForwardedFor = '';
		$httpForwarded = '';
		$remoteAddr = '';

		if (isset($refersion_settings_page->options['refersion_ip_address_setting'])) {

			// Set to not selected
			$autoOption = '';

			switch (strtoupper(trim($refersion_settings_page->options['refersion_ip_address_setting']))) {

				case "HTTP_CLIENT_IP":
					$httpClientIpOption = 'selected=selected';
					break;
				case "HTTP_X_FORWARDED_FOR":
					$httpXForwardedForOption = 'selected=selected';
					break;
				case "HTTP_X_FORWARDED":
					$httpXForwarded = 'selected=selected';
					break;
				case "HTTP_FORWARDED_FOR":
					$httpForwardedFor = 'selected=selected';
					break;
				case "HTTP_FORWARDED":
					$httpForwarded = 'selected=selected';
					break;
				case "REMOTE_ADDR":
					$remoteAddr = 'selected=selected';
					break;
				default:
					$autoOption = 'selected=selected';
					break;

			}

		}

		echo '<select id="refersion_ip_address_setting" name="refersion_settings[refersion_ip_address_setting]">
					<option value="AUTO" ' . $autoOption . '>Automatic</option>
					<option value="HTTP_CLIENT_IP" ' . $httpClientIpOption . '>HTTP_CLIENT_IP</option>
					<option value="HTTP_X_FORWARDED_FOR" ' . $httpXForwardedForOption . '>HTTP_X_FORWARDED_FOR</option>
					<option value="HTTP_X_FORWARDED" ' . $httpXForwarded . '>HTTP_X_FORWARDED</option>
					<option value="HTTP_FORWARDED_FOR" ' . $httpForwardedFor . '>HTTP_FORWARDED_FOR</option>
					<option value="HTTP_FORWARDED" ' . $httpForwarded . '>HTTP_FORWARDED</option>
					<option value="REMOTE_ADDR" ' . $remoteAddr . '>REMOTE_ADDR</option>
				</select> ';

	}

	/**
	 * Determine user tracking preference.
	 */
	public static function refersion_tracking_version_callback()
	{

		global $refersion_settings_page;

		$a = 'selected';
		$b = '';

		if (isset($refersion_settings_page->options['refersion_tracking_version'])) {

			if ($refersion_settings_page->options['refersion_tracking_version'] == 'v4') {

				$a = '';
				$b = 'selected=selected';

			}

		}

		echo '<select id="refersion_tracking_version" name="refersion_settings[refersion_tracking_version]">
					<option value="v3" ' . $a . '>v3</option>
					<option value="v4" ' . $b . '>v4</option>
				</select> ';

	}

	/**
	 * Enables the order cancellation tracking feature
	 */
	public static function refersion_cancellation_tracking_callback()
	{

		global $refersion_settings_page;

		if (!isset($refersion_settings_page->options['refersion_cancellation_tracking'])) {
			$refersion_settings_page->options['refersion_cancellation_tracking'] = 0;
		}
		?>
		<select id="refersion_cancellation_tracking" name="refersion_settings[refersion_cancellation_tracking]">
			<option value="0" <?php selected(0, $refersion_settings_page->options['refersion_cancellation_tracking']); ?>>No, turn this off.</option>
			<option value="1" <?php selected(1, $refersion_settings_page->options['refersion_cancellation_tracking']); ?>>Yes, track WooCommerce cancellations in Refersion.</option>
		</select>
		<?php
	}

	/**
	 * Enables the post purchase feature
	 */
	public static function refersion_post_purchase_setting_callback()
	{

		global $refersion_settings_page;

		if (!isset($refersion_settings_page->options['refersion_post_purchase_setting'])) {
			$refersion_settings_page->options['refersion_post_purchase_setting'] = 0;
		}
		?>
		<select id="refersion_post_purchase_setting" name="refersion_settings[refersion_post_purchase_setting]">
			<option value="0" <?php selected(0, $refersion_settings_page->options['refersion_post_purchase_setting']); ?>>No</option>
			<option value="1" <?php selected(1, $refersion_settings_page->options['refersion_post_purchase_setting']); ?>>Yes</option>
		</select>
		<?php
	}

	/**
	 * The post purchase code needed to display the post purchase widget for the specified account
	 */
	public static function refersion_post_purchase_code_callback()
	{

		global $refersion_settings_page;

		printf(
			'<input type="text" id="refersion_post_purchase_code" name="refersion_settings[refersion_post_purchase_code]" value="%s" style="width:100px;" />',
			isset($refersion_settings_page->options['refersion_post_purchase_code']) ? esc_attr($refersion_settings_page->options['refersion_post_purchase_code']) : ''
		);

	}

	/**
	 * Lets users set a prefix for orders sent into Refersion (for multi-domain, multi-regional store setups)
	 */
	public static function refersion_order_prefix_callback()
	{

		global $refersion_settings_page;

		printf(
			'<input type="text" id="refersion_order_prefix" name="refersion_settings[refersion_order_prefix]" value="%s" style="width:100px;" />',
			isset($refersion_settings_page->options['refersion_order_prefix']) ? esc_attr($refersion_settings_page->options['refersion_order_prefix']) : ''
		);

	}

	/**
	 * Enqueues an optional stylesheet to load Refersion setting page CSS
	 */
	public static function add_refersion_admin_css($hook)
	{

		if ($hook !== 'woocommerce_page_refersion-setting-admin') {
			return;
		}
		wp_enqueue_style('rfsn_admin_css', REFERSION__PLUGIN_URL . 'rfsn-admin-styles.css');
	}

}