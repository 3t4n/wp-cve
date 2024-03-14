<?php
/**
 * Class PluginSettings
 */

namespace Octolize\Shipping\Notices;

use Octolize\Shipping\Notices\WooCommerceSettings\ArchiveSectionSettingsFields;
use Octolize\Shipping\Notices\WooCommerceSettings\WooCommerceSettingsPage;

/**
 * Plugin settings.
 */
class PluginSettings {

	/**
	 * @return bool
	 */
	public function is_enabled(): bool {
		return wc_string_to_bool( $this->get_options()[ ArchiveSectionSettingsFields::ENABLED_FIELD ] ?? 'no' );
	}

	/**
	 * @return string[]
	 */
	private function get_options(): array {
		// @phpstan-ignore-next-line
		return (array) get_option( WooCommerceSettingsPage::OPTION_NAME, [] );
	}
}
