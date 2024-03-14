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

class Geolocation
{
    /**
     * Checks whether the Geolocation db needs an update
     * 
     * @return boolean
     */
    public static function geoNeedsUpdate()
    {
        // Check if database needs update.
        $geo = new \FPFramework\Libs\Vendors\GeoIP\GeoIP();
        if (!$geo->needsUpdate())
        {
            return false;
        }

        // Database is too old and needs an update! Let's inform user.
        return true;
    }
}