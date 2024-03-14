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

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Ajax
{
	public function __construct()
	{
		$this->register();
	}

	/**
	 * Register all AJAX files.
	 * 
	 * @return  void
	 */
	public function register()
	{
		$files = array_diff(scandir( __DIR__ . '/Ajax'), ['.', '..', 'index.php', 'Fields', '.DS_Store']);
		
		foreach ($files as $file)
		{
			$file = str_replace('.php', '', $file);
			
			$class = '\FPFramework\Base\Ajax\\' . $file;

			new $class();
		}

		// Do fields
		$files = array_diff(scandir( __DIR__ . '/Ajax/Fields'), ['.', '..', 'index.php', '.DS_Store']);

		foreach ($files as $file)
		{
			$file = str_replace('.php', '', $file);
			
			$class = '\FPFramework\Base\Ajax\Fields\\' . $file;

			new $class();
		}
	}
}