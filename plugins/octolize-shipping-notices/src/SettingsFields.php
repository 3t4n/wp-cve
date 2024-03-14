<?php
/**
 * Interface SettingsFields
 */

namespace Octolize\Shipping\Notices;

/**
 * Interface for Method Settings.
 */
interface SettingsFields {
	/**
	 * @return array<string, array<string, string|array<string, string>>>
	 */
	public function get_settings_fields(): array;
}
