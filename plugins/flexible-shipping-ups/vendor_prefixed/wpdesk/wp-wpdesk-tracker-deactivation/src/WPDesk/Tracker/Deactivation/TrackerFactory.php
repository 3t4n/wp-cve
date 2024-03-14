<?php

namespace UpsFreeVendor\WPDesk\Tracker\Deactivation;

/**
 * Can create tracker.
 */
class TrackerFactory
{
    /**
     * Create default tracker.
     *
     * @param string $plugin_slug .
     * @param string $plugin_file .
     * @param string $plugin_title .
     *
     * @return Tracker
     */
    public static function createDefaultTracker($plugin_slug, $plugin_file, $plugin_title)
    {
        $plugin_data = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\PluginData($plugin_slug, $plugin_file, $plugin_title);
        return self::createCustomTracker($plugin_data);
    }
    /**
     * Create default tracker.
     *
     * @param PluginData $plugin_data .
     *
     * @return Tracker
     */
    public static function createDefaultTrackerFromPluginData(\UpsFreeVendor\WPDesk\Tracker\Deactivation\PluginData $plugin_data)
    {
        return self::createCustomTracker($plugin_data);
    }
    /**
     * Create custom tracker.
     *
     * @param PluginData $plugin_data .
     * @param Scripts|null $scripts .
     * @param Thickbox|null $thickbox .
     * @param AjaxDeactivationDataHandler|null $ajax
     *
     * @return Tracker
     */
    public static function createCustomTracker(\UpsFreeVendor\WPDesk\Tracker\Deactivation\PluginData $plugin_data, $scripts = null, $thickbox = null, $ajax = null, \UpsFreeVendor\WPDesk\Tracker\Deactivation\ReasonsFactory $reasons_factory = null)
    {
        $reasons_factory = $reasons_factory ?? new \UpsFreeVendor\WPDesk\Tracker\Deactivation\DefaultReasonsFactory();
        if (empty($scripts)) {
            $scripts = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Scripts($plugin_data, $reasons_factory);
        }
        if (empty($thickbox)) {
            $thickbox = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Thickbox($plugin_data, $reasons_factory);
        }
        if (empty($ajax)) {
            $sender = \apply_filters('wpdesk/tracker/sender/' . $plugin_data->getPluginSlug(), new \UpsFreeVendor\WPDesk_Tracker_Sender_Wordpress_To_WPDesk());
            $sender = new \UpsFreeVendor\WPDesk_Tracker_Sender_Logged($sender instanceof \WPDesk_Tracker_Sender ? $sender : new \UpsFreeVendor\WPDesk_Tracker_Sender_Wordpress_To_WPDesk());
            $sender = new \UpsFreeVendor\WPDesk_Tracker_Sender_Logged($sender);
            $ajax = new \UpsFreeVendor\WPDesk\Tracker\Deactivation\AjaxDeactivationDataHandler($plugin_data, $sender);
        }
        return new \UpsFreeVendor\WPDesk\Tracker\Deactivation\Tracker($plugin_data, $scripts, $thickbox, $ajax);
    }
}
