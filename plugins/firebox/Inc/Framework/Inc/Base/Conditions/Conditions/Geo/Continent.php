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

class Continent extends GeoBase
{
    /**
     *  Continent check
     * 
     *  @return bool
     */
    public function prepareSelection()
    {
        $selection = \FPFramework\Base\Functions::makeArray($this->getSelection());

        // Try to convert continent names to codes
        return array_map(function($c) {
            if (strlen($c) > 2)
            {
                $c = \FPFramework\Helpers\ContinentsHelper::getCode($c);
            }
            return $c;
        }, $selection);
    }

    /**
     *  Return the Continent's code and full name
     * 
     *  @return string Country code
     */
	public function value()
	{
        return [
            $this->geo->getContinentName('en'),
            $this->geo->getContinentCode()
        ];
	}
}