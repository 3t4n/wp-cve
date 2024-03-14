<?php

namespace UpsFreeVendor\Octolize\ShippingExtensions\Tracker;

use UpsFreeVendor\Octolize\ShippingExtensions\Tracker\DataProvider\ShippingExtensionsDataProvider;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use UpsFreeVendor\WPDesk_Tracker;
/**
 * .
 */
class Tracker implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var ViewPageTracker
     */
    private $view_page_tracker;
    /**
     * @param ViewPageTracker $view_page_tracker
     */
    public function __construct(\UpsFreeVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker $view_page_tracker)
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
        if ($tracker instanceof \UpsFreeVendor\WPDesk_Tracker) {
            $tracker->add_data_provider(new \UpsFreeVendor\Octolize\ShippingExtensions\Tracker\DataProvider\ShippingExtensionsDataProvider($this->view_page_tracker));
        }
    }
}
