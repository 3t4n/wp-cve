<?php
/**
 * Class WooCommerceSettingsPageChecker
 */

namespace Octolize\Shipping\Notices\Helpers;

/**
 * Helper for WooCommerce Settings Page.
 */
class WooCommerceSettingsPageChecker {
	/**
	 * @param string $section .
	 *
	 * @return bool
	 */
	public function is_settings_page_section( string $section ): bool {
		return $this->is_settings_shipping_page() && $this->filter_input( INPUT_GET, 'section' ) === $section;
	}

	/**
	 * @return bool
	 */
	public function is_settings_shipping_zone_page(): bool {
		return $this->is_settings_shipping_page() && $this->filter_input( INPUT_GET, 'zone_id' ) !== null;
	}

	/**
	 * @return bool
	 */
	public function is_settings_shipping_instance_page(): bool {
		return $this->is_settings_shipping_page() && $this->filter_input( INPUT_GET, 'instance_id' ) > 0;
	}

	/**
	 * @return bool
	 */
	private function is_settings_shipping_page(): bool {
		$tab  = $this->filter_input( INPUT_GET, 'tab' );
		$page = $this->filter_input( INPUT_GET, 'page' );

		return 'wc-settings' === $page || 'shipping' === $tab;
	}

	/**
	 * @param int    $type     .
	 * @param string $var_name .
	 *
	 * @return mixed
	 * @codeCoverageIgnore
	 */
	protected function filter_input( int $type, string $var_name ) {
		return filter_input( $type, $var_name );
	}
}
