<?php
/**
 * A class that extends WP_Customize_Setting so we can access
 * the protected updated method when importing options.
 *
 * Used in the Customizer importer.
 *
 * @since 1.0.0
 * @package Demo Import Kit
 */
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

final class DIK_Customizer_Option extends WP_Customize_Setting {

	/**
	 * Import an option value for this setting.
	 *
	 * @since 1.0.1
	 * @param mixed $value The option value.
	 * @return void
	 */
	public function import( $value ) {
		$this->update( $value );
	}
}
