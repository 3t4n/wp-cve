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

namespace FireBox\Core\Admin\Includes\Cpts;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Cpts
{
	/**
	 * Plugin Custom Post Types
	 * 
	 * @var  array
	 */
	private $plugin_cpts = [
		'firebox'
	];
	
	/**
	 * CPTs namespace.
	 * 
	 * @var  array
	 */
	public $cpts_namespace = '\FireBox\Core\Admin\Includes\Cpts\\';

	/**
	 * Initializes all Custom Post Types
	 * 
	 * @return  void
	 */
	public function init()
	{
		foreach ($this->plugin_cpts as $cpt)
		{
			$class = $this->cpts_namespace . ucfirst(strtolower($cpt));
			if (!class_exists($class))
			{
				continue;
			}

			$cpt = new $class();
			$cpt->register();
		}
	}
}