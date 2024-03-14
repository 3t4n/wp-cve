<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase\Settings;

use function GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\Fields\QuickCountDownTimer\setup_settings_fields;

/**
 * Quick Countdown Timer Settings.
 */
class QuickCountDownTimerSettings extends Settings {

	/**
	 * Singular Instance.
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Prepare Settings.
	 *
	 * @return void
	 */
	protected function prepare() {
		$this->id     = self::$plugin_info['name'] . '-quick-countdown-timer-settings';
		$this->fields = setup_settings_fields( self::$core, self::$plugin_info );
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	protected function hooks() {

    }
}
