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

class DBHelper
{
	/**
	 * Check if the given table exists
	 *
	 * @param   string   $table  The table name
	 * 
	 * @return  boolean
	 */
	public static function table_exists($table)
	{
		if (!is_string($table))
		{
			return false;
        }
        
        if (empty($table))
        {
            return false;
        }
		
		global $wpdb;
		$table = sanitize_text_field($table);
		$table = $wpdb->prefix . $table;
		
		return $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table)) === $table;
	}
	
	/**
	 * Escapes the array and returns a string ready to be used within an SQL query
	 * 
	 * @param   array   $array
	 * 
	 * @return  string
	 */
	public static function escape_array($array)
	{
		if (!$array)
		{
			return;
		}

		global $wpdb;
		
		$escaped = [];

		foreach($array as $key => $value)
		{
			if(is_numeric($value))
			{
				$escaped[] = $wpdb->prepare('%d', $value);
			}
			else
			{
				$escaped[] = $wpdb->prepare('%s', $value);
			}
		}

		return implode(',', $escaped);
	}
}