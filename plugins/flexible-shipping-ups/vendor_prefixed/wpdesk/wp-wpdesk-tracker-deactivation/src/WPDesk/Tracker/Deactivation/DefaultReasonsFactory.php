<?php

namespace UpsFreeVendor\WPDesk\Tracker\Deactivation;

class DefaultReasonsFactory implements \UpsFreeVendor\WPDesk\Tracker\Deactivation\ReasonsFactory
{
    public function createReasons() : array
    {
        $reasons = [];
        $reason = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Reason('not_selected', '', '', \false, '', \true, \true);
        $reasons[$reason->getValue()] = $reason;
        $reason = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Reason('plugin_stopped_working', \__('The plugin suddenly stopped working', 'flexible-shipping-ups'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Reason('broke_my_site', \__('The plugin broke my site', 'flexible-shipping-ups'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Reason('found_better_plugin', \__('I have found a better plugin', 'flexible-shipping-ups'), '', \true, \__('What\'s the plugin\'s name?', 'flexible-shipping-ups'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Reason('plugin_for_short_period', \__('I only needed the plugin for a short period', 'flexible-shipping-ups'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Reason('no_longer_need', \__('I no longer need the plugin', 'flexible-shipping-ups'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Reason('temporary_deactivation', \__('It\'s a temporary deactivation. I\'m just debugging an issue.', 'flexible-shipping-ups'));
        $reasons[$reason->getValue()] = $reason;
        $reason = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Reason('other', \__('Other', 'flexible-shipping-ups'), '', \true, \__('Please let us know how we can improve our plugin', 'flexible-shipping-ups'));
        $reasons[$reason->getValue()] = $reason;
        return $reasons;
    }
}
