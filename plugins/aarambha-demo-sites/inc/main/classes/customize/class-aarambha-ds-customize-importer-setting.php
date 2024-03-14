<?php
/**
 * Customize API: Aarambha_DS_Customize_Importer_Setting class
 *
 * @package Customizer_Importer_Setting
 * @version 1.1.6
 */

/**
 * Customizer Demo Importer Setting class.
 *
 * @see WP_Customize_Setting
 */
final class Aarambha_DS_Customize_Importer_Setting extends WP_Customize_Setting {

	/**
	 * Import an option value for this setting.
	 *
	 * @param mixed $value The value to update.
	 */
	public function import( $value ) {
		$this->update( $value );
	}
}
