<?php

namespace UpsFreeVendor\Octolize\Onboarding\PluginUpgrade;

use UpsFreeVendor\Octolize\Onboarding\OnboardingOption;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can store plugin old version on upgrading process.
 */
class PluginUpgradeWatcher implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var string
     */
    private $plugin_file_name;
    /**
     * @var OnboardingOption
     */
    private $onboarding_option;
    /**
     * @param string $plugin_file_name
     * @param OnboardingOption $onboarding_option
     */
    public function __construct(string $plugin_file_name, \UpsFreeVendor\Octolize\Onboarding\OnboardingOption $onboarding_option)
    {
        $this->plugin_file_name = $plugin_file_name;
        $this->onboarding_option = $onboarding_option;
    }
    public function hooks()
    {
        \add_action('upgrader_process_complete', [$this, 'save_plugin_version_from_upgrader'], 10, 2);
    }
    /**
     * @param \WP_Upgrader $upgrader
     * @param array $options
     * @return void
     */
    public function save_plugin_version_from_upgrader($upgrader, $options)
    {
        if ($options['action'] === 'update' && $options['type'] === 'plugin') {
            foreach ($options['plugins'] as $plugin) {
                if ($plugin === $this->plugin_file_name && isset($upgrader->skin->plugin_info['Version'])) {
                    $this->onboarding_option->update_option('plugin_version', $upgrader->skin->plugin_info['Version']);
                }
            }
        }
    }
}
