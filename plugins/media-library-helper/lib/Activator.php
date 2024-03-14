<?php
/**
 * Fired during plugin activation
 *
 * @link       http://codexin.com
 * @since      1.0.0
 *
 * @package    ImageMetadataSettings
 * @subpackage ImageMetadataSettings/includes
 */

namespace Codexin\ImageMetadataSettings;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    ImageMetadataSettings
 * @subpackage ImageMetadataSettings/includes
 * @author     Your Name <email@codexin.com>
 */
class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// cdxn_mlh prefix.
		update_option( 'cdxn_mlh_plugin_activation_time', time() );
	}

}
