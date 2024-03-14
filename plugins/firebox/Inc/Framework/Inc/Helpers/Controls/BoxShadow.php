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

namespace FPFramework\Helpers\Controls;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class BoxShadow extends Responsive
{
	/**
	 * Returns the responsive box shadow control value.
	 * 
	 * @param   array   $boxShadow
	 * @param   string  $prefix
	 * @param   string  $unit
	 * 
	 * @return  array
	 */
	public static function getResponsiveControlValue($boxShadow, $prefix = '', $unit = '')
	{
		$breakpoints = array_fill_keys(array_values(self::$breakpoints), []);

		foreach ($breakpoints as $breakpoint => &$value)
		{
			if (!isset($boxShadow[$breakpoint]))
			{
				$value = '';
				continue;
			}

			$item = $boxShadow[$breakpoint];
			
			if (!$item['color'])
			{
				$value = '';
				continue;
			}

			$value_prefix = $item['type'] === 'inset' ? 'inset ' : '';
			
			$value = $prefix . ': ' . $value_prefix . $item['left'] . $unit . ' ' . $item['top'] . $unit . ' ' . $item['width'] . $unit . ' ' . $item['spread'] . $unit . ' ' . $item['color'];
		}
		
		return $breakpoints;
	}
}