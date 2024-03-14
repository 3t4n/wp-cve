<?php

namespace DhlVendor\Octolize\ShippingExtensions\Tracker;

use DhlVendor\Octolize\ShippingExtensions\Tracker\DataProvider\ShippingExtensionsDataProvider;
use DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use DhlVendor\WPDesk_Tracker;
/**
 * .
 */
class Tracker implements \DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var ViewPageTracker
     */
    private $view_page_tracker;
    /**
     * @param ViewPageTracker $view_page_tracker
     */
    public function __construct(\DhlVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker $view_page_tracker)
    {
        $this->view_page_tracker = $view_page_tracker;
    }
    /**
     * Hooks.
     */
    public function hooks() : void
    {
        \add_action('wpdesk_tracker_started', [$this, 'register_tracker_provider']);
    }
    public function register_tracker_provider($tracker) : void
    {
        if ($tracker instanceof \DhlVendor\WPDesk_Tracker) {
            $tracker->add_data_provider(new \DhlVendor\Octolize\ShippingExtensions\Tracker\DataProvider\ShippingExtensionsDataProvider($this->view_page_tracker));
        }
    }
}
