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

class SearchDropdownBaseHelper
{
	/**
	 * Parses given data to a key, value, lang array
	 * 
	 * @param   array  $items
	 * 
	 * @return  array
	 */
	public static function parseData($items)
	{
		$data = [];

		foreach ($items as $key => $value)
		{
			$payload = [
				'id' => $key,
				'title' => $value,
				'lang' => $key
			];

			if (is_array($value))
			{
				$payload = $value;
			}
			
			$data[] = $payload;
		}
		
		return $data;
	}
	
	/**
	 * Gets items from the Selected Items IDs
	 * 
	 * @param   array   $needle
	 * @param   array   $haystack
	 * 
	 * @return  array
	 */
    public function getSelectedItems($needle, $haystack)
    {
		$needle = !is_array($needle) ? (array) $needle : $needle;
		
		$parsed = [];

		foreach ($haystack as $key => $value)
		{
			if (!in_array($value['id'], $needle))
			{
				continue;
			}
			
			$parsed[] = [
				'id' => $value['id'],
				'title' => $value['title']
			];
		}
		
		return $parsed;
    }
}