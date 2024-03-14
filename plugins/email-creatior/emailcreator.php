<?php
/**
 * Plugin Name: WooCommerce Email Template Customizer - Email Creator
 * Plugin URI: https://emailcreator.app/
 * Author: wiloke
 * Author URI: https://woocommerce.myshopkit.app/
 * Version: 1.1.1
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Text-Domain: emailcreator
 * License:     GPL-2.0+
 * Copyright:   2022- Wiloke
 * Description: Email Creator – WooCommerce Email Customizer – Bundled With One-Of-A-Kind Email Templates With Lower Unsubscribe Rates, Higher Click-Through Rates & More Sales!
 * Tags: email, email templates, visual email builder, email builder, woocommerce email templates, customize email
 * woocommerce, email customizer for woocommerce, best email builders
 */


define('WILOKE_EMAIL_CREATOR_VERSION', defined('WP_DEBUG') && WP_DEBUG ? uniqid() : '1.1.1');
define('WILOKE_EMAIL_CREATOR_HOOK_PREFIX', 'wiloke-email-creator/');
define('WILOKE_EMAIL_CREATOR_PREFIX', 'wilETP_');
define('WILOKE_EMAIL_CREATOR_REST_VERSION', 'v1');
define('WILOKE_EMAIL_CREATOR_REST_NAMESPACE', 'emailcreator');
define('WILOKE_EMAIL_CREATOR_REST', WILOKE_EMAIL_CREATOR_REST_NAMESPACE . '/' . WILOKE_EMAIL_CREATOR_REST_VERSION);
define('WILOKE_EMAIL_CREATOR_URL', plugin_dir_url(__FILE__));
define('WILOKE_EMAIL_CREATOR_PATH', plugin_dir_path(__FILE__));
define('WILOKE_EMAIL_CREATOR_IMAGE_URL', WILOKE_EMAIL_CREATOR_URL . 'src/DataFactory/DataImport/');

add_filter('woocommerce_email_get_option', 'emailCreatorAddHtmlContentTypeToEmail', 10, 4);
function emailCreatorAddHtmlContentTypeToEmail($value, $that, $value1, $key)
{
	if ($key == 'email_type') {
		$value = 'html';
	}
	return $value;
}

add_action('admin_notices', function () {

	if (!class_exists('WooCommerce')) {
		?>
        <div id="mysmbwp-converter-warning" class="notice notice-error sf-notice-nux is-dismissible">
			<?php esc_html_e('In order to use Email Creator, You must install WooCommerce plugin', 'emailcreator'); ?>
        </div>
		<?php
	}

	if (!class_exists('DOMDocument')) {
		?>
        <div class="notice notice-error sf-notice-nux is-dismissible">
			<?php esc_html_e('Oops! We found DOMDocument is missing. Please install php-dom extension to use Email Creator.',
				'emailcreator'); ?>
        </div>
		<?php
	}
});

add_action('plugins_loaded', 'WilokeEmailCreatorLoadPluginDomain');
if (!function_exists('WilokeEmailCreatorLoadPluginDomain')) {
	function WilokeEmailCreatorLoadPluginDomain()
	{
		load_plugin_textdomain('emailcreator', false, plugin_dir_path(__FILE__) . 'languages');
	}
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
if (in_array('woocommerce/woocommerce.php', get_option('active_plugins'))) {
	require_once plugin_dir_path(__FILE__) . 'src/Dashboard/Dashboard.php';
	require_once plugin_dir_path(__FILE__) . 'src/Templates/Templates.php';
	require_once plugin_dir_path(__FILE__) . 'src/Email/Email.php';
}
