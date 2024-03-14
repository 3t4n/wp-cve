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

class FieldsHelper
{
	/**
	 * Transforms an array(key, value) into html attributes
	 *
	 * @param   array  $atts
	 *
	 * @return  string
	 */
	public static function getHTMLAttributes($atts)
	{
		if (!$atts)
		{
			return '';
		}
		
		$atts = (array) $atts;

		if (!is_array($atts))
		{
			return '';
		}

		$html = implode(
			'',
			array_map(
				function ($k, $v)
				{
					if ($v == '' || (empty($v) && $v != 0))
					{
						return '';
					}

					$v = is_array($v) ? htmlspecialchars(wp_json_encode($v)) : $v;

					return $k . '="' . esc_attr($v) . '" ';
				},
				array_keys($atts),
				$atts
			)
		);
					
		return !empty($html) ? ' ' . $html : '';
	}

	/**
	 * Searches for a name in an array and returns its value or a default value.
	 * Can find value of keys : [name][name2][name3] in array
	 * 
	 * @param   string  $name
	 * @param   string  $default
	 * @param   array   $values
	 * 
	 * @return  string
	 */
	public static function findFieldValueInArray($name, $default, $values)
	{
		if (!is_string($name))
		{
			return null;
		}

		if (!is_array($values) || empty($values))
		{
			return null;
		}
		
		$values = is_object($values) ? (array) $values : $values;

		// field name: [name][name2][name3]
		if (substr_count($name, '[') > 1)
		{
			// split name
			$splited_name = explode('[', $name);
			unset($splited_name[0]);

			// find value
			$tmp_value = $values;

			foreach ($splited_name as $key => $_name)
			{
				$tmp_value = is_object($tmp_value) ? (array) $tmp_value : $tmp_value;
				
				$_name = str_replace(']', '', $_name);
				if (!isset($tmp_value[$_name]))
				{
					// key does not exist, return default
					$tmp_value = $default;
					break;
				}
				else
				{
					if (empty($tmp_value[$_name]) && $tmp_value[$_name] !== '0')
					{
						$tmp_value = $default;
						break;
					}
				}

				$tmp_value = $tmp_value[$_name];
			}

			// return value
			return $tmp_value;
		}
		else
		{
			// if the name contains the name prefix, remove it
			$name = strstr($name, '[') ? strstr($name, '[') : $name;
			// remove all brackets so we can search the data with a clean name
			$name = str_replace(['[', ']'], '', $name);

			return isset($values[$name]) && (!empty($values[$name]) || $values[$name] == '0') ? $values[$name] : $default;
		}
	}
}