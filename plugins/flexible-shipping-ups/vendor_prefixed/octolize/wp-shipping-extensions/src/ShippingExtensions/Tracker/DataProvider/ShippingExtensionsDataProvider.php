<?php

/**
 * Class ShippingExtensionsDataProvider
 */
namespace UpsFreeVendor\Octolize\ShippingExtensions\Tracker\DataProvider;

use UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker;
/**
 * Provider data for page.
 */
class ShippingExtensionsDataProvider implements \WPDesk_Tracker_Data_Provider
{
    private const PROVIDER_KEY = 'shipping_extensions';
    /**
     * @var ViewPageTracker
     */
    private $tracker;
    /**
     * @param ViewPageTracker $tracker
     */
    public function __construct(\UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker $tracker)
    {
        $this->tracker = $tracker;
    }
    /**
     * @return array
     */
    public function get_data() : array
    {
        return [self::PROVIDER_KEY => ['views' => [\UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker::OPTION_DIRECT => $this->tracker->get_views(\UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker::OPTION_DIRECT), \UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker::OPTION_PLUGINS_LIST => $this->tracker->get_views(\UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker::OPTION_PLUGINS_LIST)]]];
    }
}
