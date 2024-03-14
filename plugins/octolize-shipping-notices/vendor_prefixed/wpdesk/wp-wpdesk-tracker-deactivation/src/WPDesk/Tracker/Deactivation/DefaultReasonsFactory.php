<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation;

class DefaultReasonsFactory implements \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\ReasonsFactory
{
    public function createReasons() : array
    {
        $reasons = [];
        $reason = new \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason('not_selected', '', '', \false, '', \true, \true);
        $reasons[$reason->getValue()] = $reason;
        $reason = new \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason('plugin_stopped_working', \__('The plugin suddenly stopped working', 'octolize-shipping-notices'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason('broke_my_site', \__('The plugin broke my site', 'octolize-shipping-notices'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason('found_better_plugin', \__('I have found a better plugin', 'octolize-shipping-notices'), '', \true, \__('What\'s the plugin\'s name?', 'octolize-shipping-notices'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason('plugin_for_short_period', \__('I only needed the plugin for a short period', 'octolize-shipping-notices'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason('no_longer_need', \__('I no longer need the plugin', 'octolize-shipping-notices'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason('temporary_deactivation', \__('It\'s a temporary deactivation. I\'m just debugging an issue.', 'octolize-shipping-notices'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason('other', \__('Other', 'octolize-shipping-notices'), '', \true, \__('Please let us know how we can improve our plugin', 'octolize-shipping-notices'));
        $reasons[$reason->getValue()] = $reason;
        return $reasons;
    }
}
