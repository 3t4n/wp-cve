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

namespace FPFramework\Base\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Geo extends SmartTag
{
    /**
     * The Geolocation object
     *
     * @var mixed   Object on success, Null when GeoIP plugin can't be loaded.
     */
    private $geo;

    /**
     * Class constructor
     */
    public function __construct($factory = null, $options = null)
    {
        parent::__construct($factory = null, $options = null);
        $this->loadGeo();
    }

    /**
     * Return the visitor's detected multilingual Country Name
     *
     * @return mixed    String on success, null on failure
     */
    public function getCountry()
    {
        if ($this->geo && $code = $this->geo->getCountryCode())
        {
            return fpframework()->_('FPF_COUNTRY_' . $code);
        }
    }

    /**
     * Return the visitor's detected Country code
     *
     * @return mixed    String on success, null on failure
     */
    public function getCountryCode()
    {
        if ($this->geo)
        {
            return $this->geo->getCountryCode();
        }
    }

    /**
     * Return the visitor's detected City name
     *
     * @return mixed    String on success, null on failure
     */
    public function getCity()
    {
        if ($this->geo)
        {
            return $this->geo->getCity();
        }
    }

    /**
     *  Return the visitor's detected Regions
     *
     * @return mixed    String on success, null on failure
     */
    public function getRegion()
    {
        if (!$record = $this->geo)
        {
            return;
        }

        // Ensure we have regions
        if (!isset($record->subdivisions))
        {
            return;
        }

        $regions = [];

        // Skip if no regions found
        if (!$record->subdivisions)
        {
            return;
        }

        $langCode = $this->factory->getLanguage()->getTag();
        $langCode = explode('-', $langCode)[0];
        
        foreach ($record->subdivisions as $region)
        {
            $regions[] = isset($region->names[$langCode]) ? $region->names[$langCode] : $region->names['en'];
        }

        return implode(', ', $regions);
    }

    /**
     * Return the visitor's full geo location (Country, City, Regions)
     *
     * @return mixed    String on success, null on failure
     */
    public function getLocation()
    {
        $location_parts = array_filter([
            $this->getCountry(),
            $this->getCity(),
            $this->getRegion()
        ]);

        return implode(', ', $location_parts);
    }

    /**
     *  Load GeoIP Classes
     *
     *  @return  void
     */
    private function loadGeo($ip = null)
    {
        $this->geo = new \FPFramework\Libs\Vendors\GeoIP\GeoIP($ip);
    }
}