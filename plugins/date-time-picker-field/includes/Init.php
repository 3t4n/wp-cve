<?php

/**
 * Init. 
 * This file is loaded on every page load.
 * It's the main plugin file.
 * It's the first file loaded by WordPress when the plugin is activated.
 * 
 * @package date-time-picker-field
 * @author InputWP <support@inputwp.com>
 * @link https://www.inputwp.com InputWP
 * @license https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 */

namespace CMoreira\Plugins\DateTimePicker;

if ( ! class_exists( 'Init' ) ) {
	class Init {

		public static function init(){

			//Import existing input
			new Integration\IntegrationImport();

			// Creates Settings Page & Link.
			new Admin\SettingsPage();
			new Admin\SettingsLink();

			// Create Date Picker Instance
			new DateTimePicker();
		}
	}
}
