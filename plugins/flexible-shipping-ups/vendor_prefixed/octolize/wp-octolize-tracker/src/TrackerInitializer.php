<?php

namespace UpsFreeVendor\Octolize\Tracker;

use UpsFreeVendor\Octolize\Tracker\DeactivationTracker\OctolizeReasonsFactory;
use UpsFreeVendor\Octolize\Tracker\OptInNotice\OptInNotice;
use UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplay;
use UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayAlways;
use UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayAndConditions;
use UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterPresent;
use UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterValue;
use UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayOrConditions;
use UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayShippingMethodInstanceSettings;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use UpsFreeVendor\WPDesk\Tracker\Deactivation\PluginData;
use UpsFreeVendor\WPDesk\Tracker\Deactivation\ReasonsFactory;
use UpsFreeVendor\WPDesk\Tracker\Deactivation\TrackerFactory;
use UpsFreeVendor\WPDesk\Tracker\OptInOptOut;
/**
 * Can create complete tracker.
 */
class TrackerInitializer implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection
{
    use HookableParent;
    /**
     * @var string
     */
    private $plugin_file;
    /**
     * @var string
     */
    private $plugin_slug;
    /**
     * @var string
     */
    private $plugin_name;
    /**
     * @var string
     */
    private $shop_url;
    /**
     * @var ShouldDisplay
     */
    private $should_display;
    /**
     * @var ReasonsFactory
     */
    private $reasons_factory;
    /**
     * @param string $plugin_file Plugin file.
     * @param string $plugin_slug Plugin slug.
     * @param string $plugin_name Plugin name.
     * @param string $shop_url Shop URL.
     * @param ShouldDisplay $should_display Should display.
     * @param ReasonsFactory|null $reasons_factory Reasons factory.
     */
    public function __construct(string $plugin_file, string $plugin_slug, string $plugin_name, string $shop_url, \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplay $should_display, \UpsFreeVendor\WPDesk\Tracker\Deactivation\ReasonsFactory $reasons_factory = null)
    {
        $this->plugin_file = $plugin_file;
        $this->plugin_slug = $plugin_slug;
        $this->plugin_name = $plugin_name;
        $this->shop_url = $shop_url;
        $this->should_display = $should_display;
        $this->reasons_factory = $reasons_factory ?? new \UpsFreeVendor\Octolize\Tracker\DeactivationTracker\OctolizeReasonsFactory();
    }
    /**
     * Hooks.
     *
     * @return void
     */
    public function hooks()
    {
        $this->add_hookable(new \UpsFreeVendor\Octolize\Tracker\SenderRegistrator($this->plugin_slug));
        $opt_in_opt_out = new \UpsFreeVendor\WPDesk\Tracker\OptInOptOut($this->plugin_file, $this->plugin_slug, $this->shop_url, $this->plugin_name);
        $opt_in_opt_out->create_objects();
        $this->add_hookable($opt_in_opt_out);
        $this->add_hookable(\UpsFreeVendor\WPDesk\Tracker\Deactivation\TrackerFactory::createCustomTracker(new \UpsFreeVendor\WPDesk\Tracker\Deactivation\PluginData($this->plugin_slug, $this->plugin_file, $this->plugin_name), null, null, null, $this->reasons_factory));
        $tracker_consent = new \UpsFreeVendor\WPDesk_Tracker_Persistence_Consent();
        if (!$tracker_consent->is_active()) {
            $this->add_hookable(new \UpsFreeVendor\Octolize\Tracker\OptInNotice\OptInNotice($this->plugin_slug, $this->shop_url, $this->should_display));
        }
        $this->hooks_on_hookable_objects();
        \add_action('plugins_loaded', [$this, 'init_tracker']);
    }
    /**
     * Creates Tracker.
     * All data will be sent to https://data.octolize.org
     *
     * @return void
     */
    public function init_tracker()
    {
        $tracker = \apply_filters('wpdesk_tracker_instance', null);
    }
    /**
     * Creates tracker initializer from plugin info.
     *
     * @param \WPDesk_Plugin_Info $plugin_info .
     * @param ShouldDisplay       $should_display .
     * @param ReasonsFactory|null $reasons_factory .
     *
     * @return TrackerInitializer
     */
    public static function create_from_plugin_info(\UpsFreeVendor\WPDesk_Plugin_Info $plugin_info, $should_display, \UpsFreeVendor\WPDesk\Tracker\Deactivation\ReasonsFactory $reasons_factory = null)
    {
        $shops = $plugin_info->get_plugin_shops();
        $shop_url = $shops[\get_locale()] ?? $shops['default'] ?? 'https://octolize.com';
        return new self($plugin_info->get_plugin_file_name(), $plugin_info->get_plugin_slug(), $plugin_info->get_plugin_name(), $shop_url, $should_display ?? new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayAlways(), $reasons_factory);
    }
    /**
     * Creates tracker initializer from plugin info for shipping method.
     *
     * @param \WPDesk_Plugin_Info $plugin_info .
     * @param string              $shipping_method_id .
     * @param ReasonsFactory|null $reasons_factory .
     *
     * @return TrackerInitializer
     */
    public static function create_from_plugin_info_for_shipping_method(\UpsFreeVendor\WPDesk_Plugin_Info $plugin_info, string $shipping_method_id, \UpsFreeVendor\WPDesk\Tracker\Deactivation\ReasonsFactory $reasons_factory = null)
    {
        $shops = $plugin_info->get_plugin_shops();
        $shop_url = $shops[\get_locale()] ?? $shops['default'] ?? 'https://octolize.com';
        $should_display = new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayOrConditions();
        $should_display_and_conditions = new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayAndConditions();
        $should_display_and_conditions->add_should_diaplay_condition(new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterValue('page', 'wc-settings'));
        $should_display_and_conditions->add_should_diaplay_condition(new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterValue('tab', 'shipping'));
        $should_display_and_conditions->add_should_diaplay_condition(new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterValue('section', $shipping_method_id));
        $should_display->add_should_diaplay_condition($should_display_and_conditions);
        $should_display_and_conditions = new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayAndConditions();
        $should_display_and_conditions->add_should_diaplay_condition(new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterValue('page', 'wc-settings'));
        $should_display_and_conditions->add_should_diaplay_condition(new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterValue('tab', 'shipping'));
        $should_display_and_conditions->add_should_diaplay_condition(new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayGetParameterPresent('instance_id'));
        $should_display_and_conditions->add_should_diaplay_condition(new \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplayShippingMethodInstanceSettings($shipping_method_id));
        $should_display->add_should_diaplay_condition($should_display_and_conditions);
        return new self($plugin_info->get_plugin_file_name(), $plugin_info->get_plugin_slug(), $plugin_info->get_plugin_name(), $shop_url, $should_display, $reasons_factory);
    }
}
