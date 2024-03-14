<?php

namespace UpsFreeVendor\Octolize\Onboarding\PluginUpgrade;

use UpsFreeVendor\Octolize\Onboarding\Field\Html;
use UpsFreeVendor\Octolize\Onboarding\Onboarding;
use UpsFreeVendor\Octolize\Onboarding\OnboardingButton;
use UpsFreeVendor\Octolize\Onboarding\OnboardingOption;
use UpsFreeVendor\Octolize\Onboarding\OnboardingShouldShowAlwaysStrategy;
use UpsFreeVendor\Octolize\Onboarding\OnboardingShouldShowStrategy;
use UpsFreeVendor\Octolize\Onboarding\OnboardingStep;
use UpsFreeVendor\Octolize\Onboarding\OnboardingTrackerData;
use UpsFreeVendor\WPDesk\Forms\Field;
/**
 * Can create popup with plugin upgrade information.
 */
class PluginUpgradeOnboardingFactory
{
    const PLUGIN_VERSION = 'plugin_version';
    const MINIMAL_VERSION = '0.0.1';
    /**
     * @var string
     */
    private $plugin_name;
    /**
     * @var PluginUpgradeMessage[]
     */
    private $upgrade_messages = [];
    /**
     * @var string
     */
    private $current_plugin_version;
    /**
     * @var string
     */
    private $plugin_file;
    /**
     * @var string
     */
    private $append_tracker_data_to;
    /**
     * @param string $plugin_name
     * @param string $current_plugin_version
     * @param string $plugin_file
     * @param string $append_tracker_data_to
     */
    public function __construct(string $plugin_name, string $current_plugin_version, string $plugin_file, string $append_tracker_data_to = '')
    {
        $this->plugin_name = $plugin_name;
        $this->current_plugin_version = $current_plugin_version;
        $this->plugin_file = $plugin_file;
        $this->append_tracker_data_to = $append_tracker_data_to;
    }
    /**
     * @param PluginUpgradeMessage $upgrade_message
     * @return PluginUpgradeOnboardingFactory
     */
    public function add_upgrade_message(\UpsFreeVendor\Octolize\Onboarding\PluginUpgrade\PluginUpgradeMessage $upgrade_message) : \UpsFreeVendor\Octolize\Onboarding\PluginUpgrade\PluginUpgradeOnboardingFactory
    {
        $this->upgrade_messages[] = $upgrade_message;
        return $this;
    }
    /**
     * @return void
     */
    public function create_onboarding() : void
    {
        $onboarding_id = 'upgrade_' . $this->plugin_file;
        $onboarding_option = new \UpsFreeVendor\Octolize\Onboarding\OnboardingOption($onboarding_id);
        $default_plugin_version = $this->plugin_activated_hour_before_or_early() ? self::MINIMAL_VERSION : $this->current_plugin_version;
        $previous_version = $onboarding_option->get_option_value(self::PLUGIN_VERSION, $default_plugin_version);
        $plugin_upgrade_watcher = new \UpsFreeVendor\Octolize\Onboarding\PluginUpgrade\PluginUpgradeWatcher($this->plugin_file, $onboarding_option);
        $plugin_upgrade_watcher->hooks();
        $onboarding_ajax = new \UpsFreeVendor\Octolize\Onboarding\PluginUpgrade\PluginUpgradeAjax($onboarding_option, $this->current_plugin_version);
        $onboarding_ajax->hooks();
        if ($this->has_onboarding_messages($previous_version, $this->current_plugin_version)) {
            $onboarding_should_display_strategy = $this->prepare_display_strategy();
            $onboarding = new \UpsFreeVendor\Octolize\Onboarding\Onboarding($onboarding_id, \true, $onboarding_should_display_strategy, $this->prepare_steps($previous_version, $this->current_plugin_version), $onboarding_ajax, $onboarding_option);
            $onboarding->hooks();
        } else {
            if ($onboarding_option->get_option_value(self::PLUGIN_VERSION, self::MINIMAL_VERSION) !== $this->current_plugin_version) {
                $onboarding_option->update_option(self::PLUGIN_VERSION, $this->current_plugin_version);
            }
        }
        if ($this->append_tracker_data_to !== '') {
            $tracker = new \UpsFreeVendor\Octolize\Onboarding\OnboardingTrackerData($this->append_tracker_data_to, $onboarding_option, 'update_onboarging');
            $tracker->hooks();
        }
    }
    private function plugin_activated_hour_before_or_early() : bool
    {
        $plugin_activation = \get_option('plugin_activation_' . $this->plugin_file, \current_time('mysql'));
        return \strtotime($plugin_activation) < \current_time('timestamp') - 3600;
    }
    private function prepare_display_strategy() : \UpsFreeVendor\Octolize\Onboarding\OnboardingShouldShowStrategy
    {
        return new \UpsFreeVendor\Octolize\Onboarding\OnboardingShouldShowAlwaysStrategy();
    }
    /**
     * @param string $previous_version
     *
     * @return OnboardingStep[]
     */
    private function prepare_steps(string $previous_version, string $current_version) : array
    {
        $onboarding_step = new \UpsFreeVendor\Octolize\Onboarding\OnboardingStep('step_1', 1, $this->plugin_name, $this->prepare_fields($previous_version, $current_version), $this->prepare_buttons());
        $onboarding_step->set_show(\true)->set_heading(\sprintf(\__('Thank you for updating %1$s!', 'flexible-shipping-ups'), $this->plugin_name))->set_sub_heading(\__('It is really important to keep the plugins up to date. We have implemented some improvements and new functionalities. Find out what has changed:', 'flexible-shipping-ups'));
        return [$onboarding_step];
    }
    /**
     * @param string $previous_version
     *
     * @return Field[]
     */
    private function prepare_fields(string $previous_version, $current_version) : array
    {
        $fields = [];
        foreach ($this->upgrade_messages as $upgrade_message) {
            if ($this->is_lower($previous_version, $upgrade_message->get_plugin_version()) && $this->is_grater_or_equal($current_version, $upgrade_message->get_plugin_version())) {
                $fields[] = (new \UpsFreeVendor\Octolize\Onboarding\Field\Html())->set_default_value(\sprintf('<div class="upgrade_message"><img class="icon" src="%1$s" /><div class="content"><div class="title">%2$s</div><div class="message">%3$s</div><div><a target="_blank" href="%4$s">%5$s</a></div></div>', \esc_url($upgrade_message->get_image_url()), $upgrade_message->get_title(), $upgrade_message->get_message(), \esc_url($upgrade_message->get_link_url()), $upgrade_message->get_link_text()));
            }
        }
        return $fields;
    }
    /**
     * @return OnboardingButton[]
     */
    private function prepare_buttons() : array
    {
        return [(new \UpsFreeVendor\Octolize\Onboarding\OnboardingButton())->set_label(\__('I\'m not interested', 'flexible-shipping-ups'))->set_classes(\UpsFreeVendor\Octolize\Onboarding\OnboardingButton::BTN_LINK), (new \UpsFreeVendor\Octolize\Onboarding\OnboardingButton())->set_label(\__('Thanks for letting me know', 'flexible-shipping-ups'))->set_type(\UpsFreeVendor\Octolize\Onboarding\OnboardingButton::TYPE_CLOSE)];
    }
    private function has_onboarding_messages($previous_version, $current_version) : bool
    {
        foreach ($this->upgrade_messages as $upgrade_message) {
            if ($this->is_lower($previous_version, $upgrade_message->get_plugin_version()) && $this->is_grater_or_equal($current_version, $upgrade_message->get_plugin_version())) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param string $previous_version
     * @param string $current_version
     * @return bool
     */
    public function is_lower(string $previous_version, string $current_version)
    {
        return \version_compare($previous_version, $current_version, '<');
    }
    /**
     * @param string $previous_version
     * @param string $current_version
     * @return bool
     */
    public function is_grater_or_equal(string $previous_version, string $current_version)
    {
        return \version_compare($previous_version, $current_version, '>=');
    }
}
