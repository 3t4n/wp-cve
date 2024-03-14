<?php
/**
 * Settings sidebar.
 *
 * @package WPDesk\FlexibleShippingDhl
 */

namespace WPDesk\FlexibleShippingDhl;

use DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can display settings sidebar.
 */
class SettingsSidebar implements Hookable {

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'flexible_shipping_dhl_express_settings_sidebar', array( $this, 'display_settings_sidebar_when_no_pro_version' ) );
	}

	/**
	 * Maybe display settings sidebar.
	 *
	 * @return void
	 */
	public function display_settings_sidebar_when_no_pro_version() {
		if ( ! defined( 'FLEXIBLE_SHIPPING_DHL_EXPRESS_PRO_VERSION' ) ) {
			$pro_url  = 'https://octol.io/dhl-express-up-box';
			include __DIR__ . '/views/settings-sidebar-html.php';
		}
	}

}
