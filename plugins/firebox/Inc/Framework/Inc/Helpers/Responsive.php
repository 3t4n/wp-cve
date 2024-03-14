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

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Responsive
{
	/**
	 * Renders the given CSS.
	 * 
	 * @param   array   $css
	 * @param   string  $namespace
	 * 
	 * @return  string
	 */
	public static function renderResponsiveCSS($css, $namespace = '')
	{
		$output = '';

		foreach (Controls\Responsive::$breakpoints as $breakpoint)
		{
			if (!isset($css[$breakpoint]) || empty($css[$breakpoint]))
			{
				continue;
			}

			/**
			 * If we were given an array of strings of CSS, transform them to a string so we can output it.
			 * 
			 * i.e. transform
			 * [
			 * 	 'color: #fff;',
			 * 	 'background: #000;'
			 * ]
			 * 
			 * to:
			 * 
			 * 'color: #fff;background: #000;'
			 */
			if (!is_string($css[$breakpoint]))
			{
				$css[$breakpoint] = implode(' ', $css[$breakpoint]);
			}

			$function = 'get' . ucfirst($breakpoint) . 'Output';
			if (!$output .= self::$function($css[$breakpoint], $namespace))
			{
				continue;
			}
		}

		return $output;
	}

	/**
	 * Returns the desktop CSS.
	 * 
	 * @param   string  $css
	 * @param   string  $namespace
	 * 
	 * @return  string
	 */
	public static function getDesktopOutput($css, $namespace)
	{
		return self::getGenericTemplate($css, '', $namespace);
	}
	
	/**
	 * Returns the tablet CSS.
	 * 
	 * @param   string  $css
	 * @param   string  $namespace
	 * 
	 * @return  string
	 */
	public static function getTabletOutput($css, $namespace)
	{
		return self::getGenericTemplate($css, '(max-width: 991px)', $namespace);
	}

	/**
	 * Returns the mobile CSS.
	 * 
	 * @param   string  $css
	 * @param   string  $namespace
	 * 
	 * @return  string
	 */
	public static function getMobileOutput($css, $namespace)
	{
		return self::getGenericTemplate($css, '(max-width: 575px)', $namespace);
	}

	/**
	 * Returns the responsive output of a specific media query size.
	 * 
	 * @param   string  $css
	 * @param   string  $size
	 * @param   string  $namespace
	 * 
	 * @return  string
	 */
	public static function getGenericTemplate($css, $size = '', $namespace = '')
	{
		if (!is_string($css) || !is_string($size) || !is_string($namespace))
		{
			return '';
		}

		if (empty($css))
		{
			return '';
		}
		
		$namespace_prefix = $namespace_suffix = $size_prefix = $size_suffix = '';
		
		if (!empty($size))
		{
			$size_prefix = '@media screen and ' . $size . ' { ';
			$size_suffix = ' }';
		}

		if (!empty($namespace))
		{
			$namespace_prefix = $namespace . ' { ';
			$namespace_suffix = ' }';
		}
		
		return $size_prefix . $namespace_prefix . $css . $namespace_suffix . $size_suffix;
	}
}