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

require_once FBOX_PLUGIN_DIR . 'Inc/Framework/Inc/Admin/Includes/Uninstall.php';

class PluginUninstall extends \Uninstall
{
	/**
	 * Runs once we uninstall the plugin.
	 * 
	 * @return  void
	 */
	public function start()
	{
		if (!$settings = get_option('firebox_settings'))
		{
			return;
		}
		
		if (isset($settings['keep_data_on_uninstall']) && $settings['keep_data_on_uninstall'] == '1')
		{
			return;
		}

		require_once FBOX_PLUGIN_DIR . 'Inc/Framework/Inc/Helpers/Directory.php';
		require_once FBOX_PLUGIN_DIR . 'Inc/Framework/Inc/Helpers/WPHelper.php';

		// remove all db tables
		$this->pluginUninstall();

		// de-register post type
		unregister_post_type('firebox');

		// remove all custom post types data
		$items = get_posts(['post_type' => 'firebox', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids']);

		if ($items)
		{
			foreach ($items as $item)
			{
				wp_delete_post($item, true);
			}
		}

		// remove all options
		delete_option('firebox_version');
		delete_option('firebox_settings');
		delete_option('firebox_import');
		delete_option('firebox_license_status');
		delete_option('firebox_license_key');

		// Delete /wp-content/uploads/firebox directory
		\FPFramework\Helpers\Directory::delete(\FPFramework\Helpers\WPHelper::getPluginUploadsDirectory('firebox'));
	}
}