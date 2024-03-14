<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 * 
 * Plugin Name:       FireBox
 * Plugin URI:        https://www.fireplugins.com/
 * Description:       WordPress Popup and leads generation plugin that converts visitors into subscribers & customers.
 * Version:           2.1.8
 * Author:            FireBox Popup Plugin Team
 * Author URI:        https://www.fireplugins.com/
 * Text Domain:       firebox
 * Domain Path:       /languages
 * Requires at least: 5.4
 * Requires PHP: 	  7.0
 * License: 		  GNU General Public License v2.0+
 * License URI: 	  http://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

// Load Framework
if (!file_exists(dirname(__FILE__) . '/Inc/Framework/init.php'))
{
	return;
}
require_once dirname(__FILE__) . '/Inc/Framework/init.php';

// Plugin Autoloader
if (!file_exists(dirname(__FILE__) . '/autoload.php'))
{
	return;
}
require_once dirname(__FILE__) . '/autoload.php';



// Plugin version
if (!defined('FBOX_VERSION'))
{
	define('FBOX_VERSION', '2.1.8');
}

// Plugin License type (free/pro)
if (!defined('FBOX_LICENSE_TYPE'))
{
	
	$type = 'lite';
	
	
	define('FBOX_LICENSE_TYPE', $type);
}

// PHP Minimm Version
if (!defined('FBOX_MINIMUM_PHP_VERSION'))
{
	define('FBOX_MINIMUM_PHP_VERSION', '7.0');
}


// Go Pro URL
if (!defined('FBOX_GO_PRO_URL'))
{
	define('FBOX_GO_PRO_URL', 'https://www.fireplugins.com/firebox/upgrade?coupon=FREE2PRO&utm_source=Wordpress&utm_medium=upgradebutton&utm_campaign=freeversion');
}


// Plugin Documentation URL
if (!defined('FBOX_DOC_URL'))
{
	define('FBOX_DOC_URL', 'https://www.fireplugins.com/docs/firebox/');
}

// Plugin Folder Path
if (!defined('FBOX_PLUGIN_DIR'))
{
	define('FBOX_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

// Layouts Folder Path
if (!defined('FBOX_LAYOUTS_DIR'))
{
	define('FBOX_LAYOUTS_DIR', FBOX_PLUGIN_DIR . 'Inc/Core/Layouts/');
}

// Admin Layouts Folder Path
if (!defined('FBOX_ADMIN_LAYOUTS_DIR'))
{
	define('FBOX_ADMIN_LAYOUTS_DIR', FBOX_PLUGIN_DIR . 'Inc/Core/Layouts/admin/');
}

// Public Layouts Folder Path
if (!defined('FBOX_PUBLIC_LAYOUTS_DIR'))
{
	define('FBOX_PUBLIC_LAYOUTS_DIR', FBOX_PLUGIN_DIR . 'Inc/Core/Layouts/public/');
}

// Plugin Folder URL
if (!defined('FBOX_PLUGIN_URL'))
{
	define('FBOX_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Media Plugin Folder URL
if (!defined('FBOX_MEDIA_URL'))
{
	define('FBOX_MEDIA_URL', plugin_dir_url(__FILE__) . 'media/');
}

// Admin Media Plugin Folder URL
if (!defined('FBOX_MEDIA_ADMIN_URL'))
{
	define('FBOX_MEDIA_ADMIN_URL', plugin_dir_url(__FILE__) . 'media/admin/');
}

// Public Media Plugin Folder URL
if (!defined('FBOX_MEDIA_PUBLIC_URL'))
{
	define('FBOX_MEDIA_PUBLIC_URL', plugin_dir_url(__FILE__) . 'media/public/');
}

// Plugin Root File
if (!defined('FBOX_PLUGIN_BASE_FILE'))
{
	define('FBOX_PLUGIN_BASE_FILE', __FILE__);
}

// Plugin Root Folder
if (!defined('FBOX_BASE_FOLDER'))
{
	define('FBOX_BASE_FOLDER', dirname(__FILE__));
}

if (!defined('FBOX_PLUGIN_BASENAME'))
{
	define('FBOX_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

// Minimum PHP version check
if (!version_compare(PHP_VERSION, FBOX_MINIMUM_PHP_VERSION, '>='))
{
	add_action('admin_notices', 'firebox_fail_php_version');
}
else
{
	// Migrator
	function firebox_upgrader_migrator_start($upgrader_object, $options)
	{
		if ($options['action'] === 'update' && $options['type'] === 'plugin' && isset($options['plugins']))
		{
			foreach ($options['plugins'] as $plugin)
			{
				if ($plugin !== plugin_basename(__FILE__))
				{
					continue;
				}

				$migrator = new \FireBox\Core\Migrator(get_option('firebox_version', '1.0.0'));
				$migrator->run();
			}
		}
	}
	add_action('upgrader_process_complete', 'firebox_upgrader_migrator_start', 10, 2);

	function firebox_init_migrator_start()
	{
		add_action('fpf_init', function() {
			$migrator = new \FireBox\Core\Migrator(get_option('firebox_version', '1.0.0'));
			$migrator->run();
		});
	}
	add_action('plugins_loaded', 'firebox_init_migrator_start', -1);
	
	// Start Plugin
	require_once dirname(__FILE__) . '/Inc/Core/Plugin.php';
	firebox();

	if (is_admin())
	{
		// Run Activation/Deactivation procedures
		$maintenance = new \FireBox\Core\Admin\Maintenance(firebox()->hook_data);
		$maintenance->init();
	}
}

/**
 * FireBox admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @return void
 */
if (!function_exists('firebox_fail_php_version'))
{
	function firebox_fail_php_version()
	{
		$message = sprintf(fpframework()->_('FPF_PHP_VERSION_FAIL'), 'FireBox', FBOX_MINIMUM_PHP_VERSION);
		$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
		echo wp_kses_post( $html_message );
	}
}