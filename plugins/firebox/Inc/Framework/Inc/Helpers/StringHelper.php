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

class StringHelper
{
	/**
	 * UTF-8 aware alternative to strpos()
	 *
	 * Find position of first occurrence of a string.
	 *
	 * @param   string   $str     String being examined
	 * @param   string   $search  String being searched for
	 * @param   integer  $offset  Optional, specifies the position from which the search should be performed
	 *
	 * @return  integer|boolean  Number of characters before the first match or FALSE on failure
	 */
	public static function strpos($str, $search, $offset = false)
	{
		if ($offset === false)
		{
			return self::utf8_strpos($str, $search);
		}

		return self::utf8_strpos($str, $search, $offset);
	}

	/**
	* Assumes mbstring internal encoding is set to UTF-8
	* Wrapper around mb_strpos
	* Find position of first occurrence of a string
	*
	* @param string haystack
	* @param string needle (you should validate this with utf8_is_valid)
	* @param integer offset in characters (from left)
	*
	* @return mixed integer position or FALSE on failure
	*/
	public static function utf8_strpos($str, $search, $offset = FALSE)
	{
		if ($offset === FALSE)
		{
			return \mb_strpos($str, $search);
		}
		else
		{
			return \mb_strpos($str, $search, $offset);
		}
	}

	/**
	 * Checks whether $haystack starts with $needle.
	 * 
	 * @param  string  $haystack
	 * @param  string  $needle
	 * 
	 * @return  int
	 */
	public static function startsWith($haystack, $needle)
	{
		return \substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
	}

	/**
	 * Checks whether $haystack ends with $needle.
	 * 
	 * @param  string  $haystack
	 * @param  string  $needle
	 * 
	 * @return  int
	 */
	public static function endsWith($haystack, $needle)
	{
		return \substr_compare($haystack, $needle, -strlen($needle)) === 0;
	}

	/**
	 * UTF-8 aware alternative to substr()
	 *
	 * Return part of a string given character offset (and optionally length).
	 *
	 * @param   string                $str     String being processed
	 * @param   integer               $offset  Number of UTF-8 characters offset (from left)
	 * @param   integer|null|boolean  $length  Optional length in UTF-8 characters from offset
	 *
	 * @return  string|boolean
	 */
	public static function substr($str, $offset, $length = false)
	{
		if ($length === false)
		{
			return self::utf8_substr($str, $offset);
		}

		return self::utf8_substr($str, $offset, $length);
	}

	public static function utf8_substr($str, $offset, $length = FALSE)
	{
		if ($length === FALSE)
		{
			return \mb_substr($str, $offset);
		}
		else
		{
			return \mb_substr($str, $offset, $length);
		}
	}

	/**
	 * UTF-8 aware alternative to strlen()
	 *
	 * Returns the number of characters in the string (NOT THE NUMBER OF BYTES).
	 *
	 * @param   string  $str  UTF-8 string.
	 *
	 * @return  integer  Number of UTF-8 characters in string.
	 */
	public static function strlen($str)
	{
		return \mb_strlen($str);
	}
}