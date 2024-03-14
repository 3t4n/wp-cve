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

class CSS
{
	/**
	 * Transforms an array of key,value to inline CSS
	 * 
	 * @param   array  $array
	 * 
	 * @return  string
	 */
    public static function arrayToCSS($array)
    {
        if (!is_array($array))
        {
            return '';
        }

        if (empty($array))
        {
            return '';
        }

        $styles = '';

        foreach ($array as $key => $value)
        {
            if ($key === '' || $value === '')
            {
                continue;
            }

            if (is_array($key) || is_array($value))
            {
                continue;
            }
            
            $styles .= $key . ':' . $value . ';';
        }

        return $styles;
	}

	/**
	 * Converts an array of CSS variables to its corresponding strings.
	 * 
	 * @param   array   $cssVars
	 * @param   string  $namespace
	 * 
	 * @return  string
	 */
	public static function cssVarsToString($cssVars, $namespace)
    {
        if (empty($cssVars))
        {
            return;
        }
        
        $output = '';

        foreach ($cssVars as $key => $value)
        {
            $output .= '--' . $key . ': ' . $value . ';' . "\n";
        }

        return $namespace . ' {
                ' . $output . '
            }
        ';
    }
}