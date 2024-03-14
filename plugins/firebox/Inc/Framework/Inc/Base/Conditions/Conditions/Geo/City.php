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

class City extends GeoBase
{
    /**
     * Checks if the user's city is within the selection.
     *
     * @return bool
     */
    public function pass()
    {
        // Get user's city
        $user_city = $this->value();

        // Get selection and prepare cities
        $cities = $this->prepareCities($this->selection);

		return in_array($user_city, $cities);
    }

    /**
     * Prepare an array of cities.
     *
     * @param string $cities
     * @return array
     */
    protected function prepareCities($cities)
    {
        if (is_array($cities))
        {
            $cities = implode(',', $cities);
        }
        // replace newlines with commas
        $cities = preg_replace('/\s+/',',',trim($cities));

        // strip out empty values, reorder array keys and return ip ranges as an array
        return array_values(array_filter(explode(',', $cities)));
    }

    /**
     *  Returns the assignment's value
     * 
     *  @return string City name
     */
	public function value()
	{
		return $this->geo->getCity();
	}
}