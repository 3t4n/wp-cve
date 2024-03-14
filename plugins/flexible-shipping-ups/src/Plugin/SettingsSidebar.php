<?php
/**
 * Settings sidebar.
 *
 * @package WPDesk\FlexibleShippingUps
 */

namespace WPDesk\FlexibleShippingUps;

use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsShippingService;

/**
 * Can display settings sidebar.
 */
class SettingsSidebar implements Hookable {

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'flexible_shipping_ups_settings_sidebar', [ $this, 'maybe_display_settings_sidebar' ] );
	}

	/**
	 * Maybe display settings sidebar.
	 */
	public function maybe_display_settings_sidebar(): void {
		if ( ! defined( 'FLEXIBLE_SHIPPING_UPS_PRO_VERSION' ) ) {
			$pro_url = 'pl_PL' === get_locale() ? 'https://octol.io/ups-upgrade-box-pl' : 'https://octol.io/ups-upgrade-box';

			$show_pro_features = $this->show_pro_features();

			if ( $show_pro_features ) {
				wp_enqueue_style( UpsShippingService::UNIQUE_ID );
				wp_enqueue_script( UpsShippingService::UNIQUE_ID );
			}

			include __DIR__ . '/view/settings-sidebar-html.php';
		}
	}

	/**
	 * @return bool
	 */
	private function show_pro_features(): bool {
		$page        = filter_input( INPUT_GET, 'page' );
		$tab         = filter_input( INPUT_GET, 'tab' );
		$instance_id = filter_input( INPUT_GET, 'instance_id' );

		return $page === 'wc-settings' && $tab === 'shipping' && $instance_id;
	}
}
