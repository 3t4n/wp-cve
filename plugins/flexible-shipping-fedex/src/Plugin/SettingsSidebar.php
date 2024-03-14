<?php
/**
 * Settings sidebar.
 *
 * @package WPDesk\FlexibleShippingFedex
 */

namespace WPDesk\FlexibleShippingFedex;

use FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can display settings sidebar.
 */
class SettingsSidebar implements Hookable {

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'flexible_shipping_fedex_settings_sidebar', array( $this, 'maybe_display_settings_sidebar' ) );
	}

	/**
	 * Maybe display settings sidebar.
	 *
	 * @return void
	 */
	public function maybe_display_settings_sidebar() {
		if ( ! defined( 'FLEXIBLE_SHIPPING_FEDEX_PRO_VERSION' ) ) {
			$pro_url = 'pl_PL' === get_locale() ? 'https://octol.io/fedex-upgrade-box-pl' : 'https://octol.io/fedex-upgrade-box';
			include __DIR__ . '/view/settings-sidebar-html.php';
		}
	}

}
