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

namespace FPFramework\Base\Conditions\Conditions\Geo;

defined('ABSPATH') or die;

class Region extends GeoBase
{
    /**
     *  Returns the assignment's value
     * 
     *  @return string Region codes
     */
	public function value()
	{
		return $this->getRegions();
    }
    
    /**
     *  Get list of all ISO 3611 Country Region Codes
     *
     *  @return array
     */
    private function getRegions()
    {
        $regionCodes = [];
		$record = $this->geo->getRecord();

		if ($record === false || is_null($record))
		{
			return $regionCodes;
		}

        // Skip if no regions found
        if (!$regions = $record->subdivisions)
        {
            return $regionCodes;
        }
        
        foreach ($regions as $key => $region)
        {
            // Get the Region's full name
            $regionCodes[] = $region->names['en'];

            // Get the Region's code by preppending the country isocode to the region code
            $regionCodes[] = $record->country->isoCode . '-' . $region->isoCode;
        }

        return $regionCodes;
    }
}