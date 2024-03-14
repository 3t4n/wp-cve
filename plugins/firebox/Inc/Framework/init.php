<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!function_exists('add_action'))
{
	// We are running outside of the context of WordPress.
	return;
}

if (!function_exists('firepluginsframework_getFrameworkVersion'))
{
	/**
	 * Return the framework version
	 * 
	 * @param   string  $dir
	 * 
	 * @return  void
	 */
	function firepluginsframework_getFrameworkVersion($dir)
	{
		$framework_file = 'framework.xml';
		
		$xmlFile = $dir . '/Framework/' . $framework_file;
		
		/**
		 * On development the xml file is located in /framework/source/framework.xml.
		 * On final zip, the file will rest on /Framework/framework.xml.
		 * 
		 * Thus the check below.
		 */
		$xmlFile = file_exists($xmlFile) ? $xmlFile : $dir . '/source/' . $framework_file;

		$xml = [];

		// load XML file
		if (function_exists('simplexml_load_file'))
		{
			$xml = simplexml_load_file($xmlFile);
		}

		return isset($xml->version) ? (string) $xml->version : '1.0.0';
	}
}

if (!function_exists('firepluginsframework_getFrameworkPriority'))
{
	/**
	 * Return the framework priority
	 * 
	 * @param   string  $dir
	 * 
	 * @return  void
	 */
	function firepluginsframework_getFrameworkPriority($dir)
	{
		$id = PHP_INT_MAX;

		$version = firepluginsframework_getFrameworkVersion($dir);
		$version = array_map('intval', explode('.', $version));

		for ($i = 0; $i < count($version); $i++)
		{
			if ($i == 0)
			{
				$id -= $version[$i] * 105;
			}
			else if ($i == 1)
			{
				$id -= $version[$i] * 55;
			}
			else
			{
				$id -= $version[$i];
			}
		}

		return $id;
	}
}

/**
 * Initialize the Framework
 * 
 * @return  void
 */
add_action('init', function()
{
	if (class_exists('\FPFramework\Framework'))
	{
		return;
	}

	$version = firepluginsframework_getFrameworkVersion(dirname(__DIR__));
	$priority = firepluginsframework_getFrameworkPriority(dirname(__DIR__));
	
	// Framework priority
	if (!defined('FPF_LOADED'))
	{
		define('FPF_LOADED', $priority);
	}

	// Framework version
	if (!defined('FPF_VERSION'))
	{
		define('FPF_VERSION', $version);
	}

	// Site URL
	if (!defined('FPF_SITE_URL'))
	{
		define('FPF_SITE_URL', 'https://www.fireplugins.com/');
	}

	// Framework Folder Path
	if (!defined('FPF_BASE_DIR'))
	{
		define('FPF_BASE_DIR', dirname(__DIR__));
	}

	// Framework Folder Path
	if (!defined('FPF_DIR'))
	{
		define('FPF_DIR', plugin_dir_path(__FILE__) . 'Inc');
	}

	// Framework Layouts Folder Path
	if (!defined('FPF_LAYOUTS_DIR'))
	{
		define('FPF_LAYOUTS_DIR', FPF_DIR . '/Layouts/');
	}

	// Support URL
	if (!defined('FPF_SUPPORT_URL'))
	{
		define('FPF_SUPPORT_URL', FPF_SITE_URL . 'contact');
	}
	
	// Site Tower Endpoint
	if (!defined('FPF_TOWER_ENDPOINT'))
	{
		define('FPF_TOWER_ENDPOINT', FPF_SITE_URL . 'wp-json/tower/v1/');
	}

	// The templates.fireplugins.com Site URL
	if (!defined('FPF_TEMPLATES_SITE_URL'))
	{
		define('FPF_TEMPLATES_SITE_URL', 'https://templates.fireplugins.com/');
	}

	// Site Tower Endpoint
	if (!defined('FPF_TEMPLATES_TOWER_ENDPOINT'))
	{
		define('FPF_TEMPLATES_TOWER_ENDPOINT', FPF_TEMPLATES_SITE_URL . 'wp-json/tower/v1/');
	}

	// URL to retrieve templates
	if (!defined('FPF_TEMPLATES_GET_URL'))
	{
		define('FPF_TEMPLATES_GET_URL', FPF_TEMPLATES_TOWER_ENDPOINT . 'templates/{{PLUGIN}}/{{LICENSE_KEY}}/{{PLUGIN_VERSION}}/{{SITE_URL}}/get');
	}

	// URL to retrieve a template
	if (!defined('FPF_TEMPLATE_GET_URL'))
	{
		define('FPF_TEMPLATE_GET_URL', FPF_TEMPLATES_TOWER_ENDPOINT . 'template/{{PLUGIN}}/{{TEMPLATE}}/{{LICENSE_KEY}}/{{SITE_URL}}/get');
	}

	// URL to check a license
	if (!defined('FPF_CHECK_LICENSE'))
	{
		define('FPF_CHECK_LICENSE', 'https://www.fireplugins.com/wp-json/tower/v1/license/check/{{LICENSE}}/{{PLUGIN}}/{{SITE_URL}}');
	}

	/**
	 * Get License Version URL
	 */
	if (!defined('FPF_GET_LICENSE_VERSION_URL'))
	{
		define('FPF_GET_LICENSE_VERSION_URL', FPF_TOWER_ENDPOINT . 'plugins/get_version/');
	}

	/**
	 * Plugin Changelog URL
	 */
	if (!defined('FPF_PLUGIN_CHANGELOG_URL'))
	{
		define('FPF_PLUGIN_CHANGELOG_URL', FPF_SITE_URL . '%s/changelog/');
	}

	firepluginsframework_load_textdomain(dirname(__DIR__));

	// Now kick off the class autoloader.
	spl_autoload_register(
		/**
		 * Autoload classes
		 * 
		 * @param   string  $class
		 * 
		 * @return  void
		 */
		function ($class)
		{
			$base = dirname(__FILE__) . '/Inc/';

			$namespaces = [
				'FPFramework\\GeoIp2\\' => [ $base . 'Libs/Vendors/geoip2/geoip2/src/' ],
				'FPFramework\\' => [ $base ],
				'MaxMind\\Db\\' => [ $base . 'Libs/Vendors/maxmind-db/reader/src/MaxMind/Db/' ],
				'MaxMind\\' => [ $base . 'Libs/Vendors/maxmind/web-service-common/src/' ],
				'splitbrain\\PHPArchive\\' => [$base . 'Libs/Vendors/splitbrain/php-archive/src/' ],
			];
			
			$found = false;
			foreach ($namespaces as $key => $value)
			{
				if (strpos($class, $key) === 0)
				{
					$found = true;
					break;
				}
			}

			if (!$found)
			{
				return;
			}
			
			$class = firepluginsframework_fixClassBasedOnNamespace($namespaces, $class);

			$file = str_replace('\\', '/', $class) . '.php';

			if (file_exists($file))
			{
				require_once $file;
			}
		}
	);
	
	// load once
	if (FPF_LOADED == $priority)
	{
		/**
		 * Fires when FPFramework is included/loaded
		 */
		do_action('fpf_init');

		if (is_admin())
		{
			/**
			 * Fires on the admin side when FPFramework is included/loaded.
			 */
			do_action('fpf_admin_init');
		}
	}
}, firepluginsframework_getFrameworkPriority(dirname(__DIR__)));

if (!function_exists('firepluginsframework_load_textdomain'))
{
	/**
	 * Load textdomain of plugin
	 * 
	 * @return  void
	 */
	function firepluginsframework_load_textdomain($dir)
	{
		load_plugin_textdomain('fpf-framework', false, $dir . '/Framework/languages/');
	}
}

if (!function_exists('firepluginsframework_fixClassBasedOnNamespace'))
{
	/**
	 * Fixes the class paths
	 * 
	 * @param   string  $namespaces
	 * @param   string  $class
	 * 
	 * @return  void
	 */
	function firepluginsframework_fixClassBasedOnNamespace($namespaces, $class)
	{
		foreach ($namespaces as $key => $value)
		{
			if (strpos($class, $key) !== FALSE)
			{
				$class = str_replace($key, $value[0], $class);
			}
		}

		return $class;
	}
}