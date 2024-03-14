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

class ArrayHelper
{
    /**
     * Fix arrays, remove duplicate items, null items and whitespace around item values.
     *
     * @param   array  $subject
     * 
     * @return  array  The new cleaned array
     */
    public static function cleanArray($subject)
    {
        if (!is_array($subject))
        {
            return $subject;
        }

        $subject = array_unique($subject);
        $subject = array_map('trim', $subject);

        // Remove empty items. We use a custom callback here because the default behavior of array_filter removes 0 values as well.
        $subject = array_filter($subject, function($value)
        {
            return ($value !== null && $value !== false && $value !== ''); 
        });

        return $subject;
    }

	/**
	 * Merges 2 arrays
	 * 
	 * @param   array  $arr1
	 * @param   array  $arr2
	 * 
	 * @return  return
	 */
	public static function arrayMerge($arr1, $arr2)
	{
		$new_arr = $arr1;

		foreach ($arr2 as $key => $value)
		{
			if (isset($new_arr[$key]) && is_array($new_arr[$key]) && is_array($value))
			{
				$tmp = array_unique(array_merge($new_arr[$key], $value));
				$new_arr[$key] = $tmp;
			}
			else
			{
				$new_arr[$key] = $value;
			}
		}
		
		return $new_arr;
	}

	/**
	 * Method to determine if an array is an associative array.
	 *
	 * @param   array  $array  An array to test.
	 *
	 * @return  boolean
	 */
	public static function isAssociative($array)
	{
		if (\is_array($array))
		{
			foreach (array_keys($array) as $k => $v)
			{
				if ($k !== $v)
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Inserts value after given key.
	 * 
	 * @param   array  $input     The input where we will search and add the new $element
	 * @param   mixed  $index     The index that we use to find the key and insert after
	 * @param   mixed  $newKey    The new index that will be used for this key, value pair
	 * @param   mixed  $newValue  The new value of the key, value pair
	 * 
	 * @return  array
	 */
	public static function insertAfter($input, $index, $newKey, $newValue)
	{
		if (!array_key_exists($index, $input))
		{
			throw new \Exception('Index not found');
		}

		$tmpArray = [];
		
		foreach ($input as $key => $value)
		{
			$tmpArray[$key] = $value;

			if ($key === $index)
			{
				$tmpArray[$newKey] = $newValue;
			}
		}

		return $tmpArray;
	}
}