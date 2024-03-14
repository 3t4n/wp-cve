<?php
namespace webaware\gf_dpspxpay;

use GFAddOn;
use GFForms;

if (!defined('ABSPATH')) {
	exit;
}

/**
* class for managing the plugin
*/
class Plugin {

	/**
	* static method for getting the instance of this singleton object
	* @return self
	*/
	public static function getInstance() {
		static $instance = null;

		if ($instance === null) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	* hide constructor
	*/
	private function __construct() {}

	/**
	* initialise plugin
	*/
	public function pluginStart() {
		add_action('gform_loaded', [$this, 'addonInit']);
		add_action('init', 'gf_dpspxpay_load_text_domain', 8);	// use priority 8 to get in before our add-on uses translated text
		add_action('admin_notices', [$this, 'checkPrerequisites']);
		add_filter('plugin_row_meta', [$this, 'pluginDetailsLinks'], 10, 2);

		add_action('wp_ajax_gfdpspxpay_upgradev1', [__NAMESPACE__ . '\\GFDpsPxPayUpdateV1', 'ajaxUpgrade']);
	}

	/**
	* initialise the Gravity Forms add-on
	*/
	public function addonInit() {
		if (!method_exists('GFForms', 'include_feed_addon_framework')) {
			return;
		}

		if (has_required_gravityforms()) {
			// load add-on framework and hook our add-on
			GFForms::include_payment_addon_framework();

			require GFDPSPXPAY_PLUGIN_ROOT . 'includes/class.GFDpsPxPayAddOn.php';
			GFAddOn::register(__NAMESPACE__ . '\\AddOn');
		}
	}

	/**
	* check for required prerequisites, tell admin if any are missing
	*/
	public function checkPrerequisites() {
		if (!gf_dpspxpay_can_show_admin_notices()) {
			return;
		}

		// need these PHP extensions
		$missing = array_filter(['libxml', 'SimpleXML', 'xmlwriter'], function($ext) {
			return !extension_loaded($ext);
		});
		if (!empty($missing)) {
			include GFDPSPXPAY_PLUGIN_ROOT . 'views/requires-extensions.php';
		}

		// and of course, we need Gravity Forms
		if (!class_exists('GFCommon', false)) {
			include GFDPSPXPAY_PLUGIN_ROOT . 'views/requires-gravity-forms.php';
		}
		elseif (!has_required_gravityforms()) {
			include GFDPSPXPAY_PLUGIN_ROOT . 'views/requires-gravity-forms-upgrade.php';
		}
	}

	/**
	* add plugin details links
	*/
	public static function pluginDetailsLinks($links, $file) {
		if ($file === GFDPSPXPAY_PLUGIN_NAME) {
			$links[] = sprintf('<a href="https://wordpress.org/support/plugin/gravity-forms-dps-pxpay" rel="noopener" target="_blank">%s</a>', _x('Get help', 'plugin details links', 'gravity-forms-dps-pxpay'));
			$links[] = sprintf('<a href="https://wordpress.org/plugins/gravity-forms-dps-pxpay/" rel="noopener" target="_blank">%s</a>', _x('Rating', 'plugin details links', 'gravity-forms-dps-pxpay'));
			$links[] = sprintf('<a href="https://translate.wordpress.org/projects/wp-plugins/gravity-forms-dps-pxpay" rel="noopener" target="_blank">%s</a>', _x('Translate', 'plugin details links', 'gravity-forms-dps-pxpay'));
			$links[] = sprintf('<a href="https://shop.webaware.com.au/donations/?donation_for=Gravity+Forms+DPS+PxPay" rel="noopener" target="_blank">%s</a>', _x('Donate', 'plugin details links', 'gravity-forms-dps-pxpay'));
		}

		return $links;
	}

}
