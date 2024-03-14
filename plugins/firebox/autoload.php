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

// If this file is called directly, abort.
if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

if (!function_exists('firebox_autoloader'))
{
	function firebox_autoloader($class)
	{
		if (strpos($class, 'FireBox\\') !== 0)
		{
			return;
		}
		
		// plugin namespaces
		$firebox_namespaces = [
			'FireBox\\Core\\' => [ __DIR__ . '/Inc/Core/' ],
			'FireBox\\Testing\\' => [ dirname(__DIR__) . '/tests/' ],
		];

		$class = firepluginsframework_fixClassBasedOnNamespace($firebox_namespaces, $class);

		$file = str_replace(['\\'], ['/'], $class) . '.php';

		if (file_exists($file))
		{
			require_once $file;
		}
	}
}

// autoload classes
spl_autoload_register('firebox_autoloader');