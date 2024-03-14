<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Admin;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

if (!class_exists('Activation'))
{
	require_once FBOX_PLUGIN_DIR . '/Inc/Framework/Inc/Admin/Includes/Activation.php';
}

class PluginActivation extends \Activation
{
	/**
	 * Runs once we activate the plugin.
	 * Set plugin data
	 * 
	 * @return  void
	 */
	public function start()
	{
		$this->pluginActivation();

		require_once FBOX_PLUGIN_DIR . 'Inc/Framework/Inc/Helpers/Directory.php';
		require_once FBOX_PLUGIN_DIR . 'Inc/Framework/Inc/Helpers/WPHelper.php';
		\FireBox\Core\Helpers\Activation::createLibraryDirectories();
		
		// Set plugin version
		update_option('firebox_version', FBOX_VERSION);

		// set default plugin settings
		if (!get_option('firebox_settings'))
		{
			$settings = [
				'loadCSS' => '1',
				'loadVelocity' => '1',
				'showcopyright' => '1',
				'show_admin_bar_menu_item' => '1',
				'debug' => '0',
				'statsdays' => '730',
				'geo_license_key' => '',
				'keep_data_on_uninstall' => '1',
				'enable_phpscripts' => '0',
				'license_key' => '',
			];
			update_option('firebox_settings', $settings);
		}

		// initialize capabilities
		new Capabilities();
	}
}