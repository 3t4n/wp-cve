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

class Date
{
    public static function getTimezone()
    {
        $timezone = get_option('timezone_string');
    
        if (empty($timezone))
        {
            $gmt_offset = get_option('gmt_offset', 0);
    
            if ($gmt_offset !== '0')
            {
                $timezone = timezone_name_from_abbr('', (int) $gmt_offset * HOUR_IN_SECONDS, gmdate('I'));
            }
    
            // If the offset was 0 or timezone is empty, just use 'UTC'.
            if ($gmt_offset === '0' || empty($timezone))
            {
                $timezone = 'UTC';
            }
        }
    
        return $timezone;
    }
}