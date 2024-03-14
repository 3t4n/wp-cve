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

namespace FireBox\Core;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Widgets
{
	/**
	 * All Plugin Widgets
	 * 
	 * @return  array
	 */
	private $widgets = [
		'FireBoxButton'
	];
	
    public function __construct()
    {
		add_action('widgets_init', [$this, 'registerAllWidgets']);
	}
	
	/**
	 * Registers all widgets
	 * 
	 * @return  void
	 */
	public function registerAllWidgets()
	{
		foreach ($this->widgets as $widget)
		{
			register_widget('\FireBox\Core\Widgets\\' . $widget);
		}
	}
}