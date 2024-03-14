<?php

namespace UpsFreeVendor\Octolize\Onboarding\PluginUpgrade;

use UpsFreeVendor\Octolize\Onboarding\OnboardingAjax;
use UpsFreeVendor\Octolize\Onboarding\OnboardingOption;
/**
 * Can handle plugin upgrade onboarding Ajax actions.
 */
class PluginUpgradeAjax extends \UpsFreeVendor\Octolize\Onboarding\OnboardingAjax
{
    /**
     * @var string
     */
    private $plugin_version;
    /**
     * @param string $plugin_version
     */
    public function __construct(\UpsFreeVendor\Octolize\Onboarding\OnboardingOption $onboarding_option, string $plugin_version)
    {
        parent::__construct($onboarding_option);
        $this->plugin_version = $plugin_version;
    }
    public function handle_ajax_action_auto_show_popup()
    {
        $this->option->update_option('plugin_version', $this->plugin_version);
        parent::handle_ajax_action_auto_show_popup();
    }
}
