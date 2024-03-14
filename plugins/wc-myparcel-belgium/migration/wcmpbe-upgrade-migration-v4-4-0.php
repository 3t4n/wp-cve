<?php

use migration\WCMPBE_Upgrade_Migration;

if (! defined('ABSPATH')) {
    exit;
}

if (class_exists('WCMPBE_Upgrade_Migration_v4_4_0')) {
    return new WCMPBE_Upgrade_Migration_v4_4_0();
}

/**
 * Migrates pre v4.4.0 settings
 *  - setting show delivery day is moved from checkout to general
 *  - per carrier setting delivery days window is moved to new feature allow show delivery date (1 or 0)
 */
class WCMPBE_Upgrade_Migration_v4_4_0 extends WCMPBE_Upgrade_Migration
{
    /**
     * @var array
     */
    private $newGeneralSettings = [];

    /**
     * @var array
     */
    private $newCheckoutSettings = [];

    /**
     * @var array
     */
    private $newBpostSettings = [];

    /**
     * @var array
     */
    private $newDpdSettings = [];

    /**
     * @var array
     */
    private $newPostNlSettings = [];

    public function __construct()
    {
        parent::__construct();
    }

    protected function import(): void
    {
        require_once(WCMYPABE()->plugin_path() . '/vendor/autoload.php');
        require_once(WCMYPABE()->plugin_path() . '/includes/admin/settings/class-wcmpbe-settings.php');
        require_once(WCMYPABE()->plugin_path() . '/includes/class-wcmpbe-data.php');
    }

    protected function migrate(): void
    {
        $this->newGeneralSettings  = $this->getSettings('woocommerce_myparcelbe_general_settings');
        $this->newCheckoutSettings = $this->getSettings('woocommerce_myparcelbe_checkout_settings');
        $this->newBpostSettings    = $this->getSettings('woocommerce_myparcelbe_bpost_settings');
        $this->newDpdSettings      = $this->getSettings('woocommerce_myparcelbe_dpd_settings');
        $this->newPostNlSettings   = $this->getSettings('woocommerce_myparcelbe_postnl_settings');

        $this->migrateGeneralSettings();
        $this->migrateCarrierSettings();
    }

    protected function setOptionSettingsMap(): void
    {
        $this->optionSettingsMap = [
            'woocommerce_myparcelbe_general_settings'  => $this->newGeneralSettings,
            'woocommerce_myparcelbe_checkout_settings' => $this->newCheckoutSettings,
            'woocommerce_myparcelbe_dpd_settings'      => $this->newDpdSettings,
            'woocommerce_myparcelbe_bpost_settings'    => $this->newBpostSettings,
            'woocommerce_myparcelbe_postnl_settings'   => $this->newPostNlSettings,
        ];
    }

    private function migrateGeneralSettings(): void
    {
        $this->newGeneralSettings[WCMPBE_Settings::SETTING_SHOW_DELIVERY_DAY] =
            $this->newCheckoutSettings[WCMPBE_Settings::SETTING_SHOW_DELIVERY_DAY] ?? WCMPBE_Settings_Data::ENABLED;

        unset($this->newCheckoutSettings[WCMPBE_Settings::SETTING_SHOW_DELIVERY_DAY]);
    }

    private function migrateCarrierSettings(): void
    {
        $this->newBpostSettings = $this->migrateSettings(
            self::getCarrierMap(WCMPBE_Settings::SETTINGS_BPOST),
            $this->newBpostSettings
        );

        $this->newDpdSettings = $this->migrateSettings(
            self::getCarrierMap(WCMPBE_Settings::SETTINGS_DPD),
            $this->newDpdSettings
        );

        $this->newPostNlSettings = $this->migrateSettings(
            self::getCarrierMap(WCMPBE_Settings::SETTINGS_POSTNL),
            $this->newPostNlSettings
        );
    }

    /**
     * @param string $carrier
     *
     * @return array
     */
    private static function getCarrierMap(string $carrier): array
    {
        return [
            "{$carrier}_" . WCMPBE_Settings::SETTING_CARRIER_DELIVERY_DAYS_WINDOW =>
                "{$carrier}_" . WCMPBE_Settings::SETTING_CARRIER_ALLOW_SHOW_DELIVERY_DATE,
        ];
    }
}

return new WCMPBE_Upgrade_Migration_v4_4_0();
