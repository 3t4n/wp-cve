<?php

namespace HQRentalsPlugin\HQRentalsSettings;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector;
use HQRentalsPlugin\HQRentalsDb\HQRentalsDbBootstrapper;

class HQRentalsBootstrap
{
    /*
     * Plugin Option to be configured by users
     * The names of this option are on the Settings
     */
    public $woocommerce_hq_sync_default_value = false;
    public $hq_datetime_format_default_value = "Y-m-d H:i";
    public $front_end_datetime_format_default_value = "Y-m-d H:i";
    public $api_base_url_default_value = "https://api.caagcrm.com/api/";
    public $hq_new_auth_scheme_default_value = 'false';
    public $hq_integration_on_home_default_value = 'false';
    public $hq_cronjob_disable_option_default_value = 'false';
    public $hq_tenant_date_time_format = "Y-m-d H:i";
    public $hq_disable_safari_option_default_value = 'false';
    public $hq_default_value_for_string = '';

    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
        $this->dbBootstrap = new HQRentalsDbBootstrapper();
    }

    public function onPluginActivation()
    {
        if ($this->settings->thereAreSomeSettingMissing()) {
            $this->settings->saveApiTenantToken($this->hq_default_value_for_string);
            $this->settings->saveApiUserToken($this->hq_default_value_for_string);
            $this->settings->saveHQDatetimeFormat($this->hq_datetime_format_default_value);
            $this->settings->saveFrontEndDateTimeFormat($this->front_end_datetime_format_default_value);
            $this->settings->saveApiBaseUrl($this->api_base_url_default_value);
        }
        if ($this->settings->noNewAuthSchemeOption()) {
            //Encrypt on existing websites
            $this->settings->saveNewAuthScheme($this->hq_new_auth_scheme_default_value);
        }
        if ($this->settings->noHomeIntegrationOption()) {
            $this->settings->saveHomeIntegration($this->hq_integration_on_home_default_value);
        }
        if ($this->settings->noDisabledCronjobOption()) {
            $this->settings->saveDisableCronjobOption($this->hq_cronjob_disable_option_default_value);
        }
        if ($this->settings->noTenantDatetimeFormat()) {
            $this->settings->saveTenantDatetimeOption($this->hq_tenant_date_time_format);
        }
        if ($this->settings->noDisableSafariFunctionality()) {
            $this->settings->saveDisableSafariOption($this->hq_disable_safari_option_default_value);
        }
        if ($this->settings->noDecreasingRateOrder()) {
            $this->settings->saveDecreasingRateOrder('false');
        }
        if ($this->settings->noLocationCoordinateSetting()) {
            $this->settings->saveLocationCoordinateSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noLocationImageSetting()) {
            $this->settings->saveLocationImageSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noLocationDescriptionSetting()) {
            $this->settings->saveLocationDescriptionSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noAddressLabelSetting()) {
            $this->settings->saveAddressLabelSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noPhoneSetting()) {
            $this->settings->saveAddressLabelSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noAddressSetting()) {
            $this->settings->saveAddressLabelSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noBrandsSetting()) {
            $this->settings->saveBrandsSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noOfficeHoursSetting()) {
            $this->settings->saveOfficeHoursSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noBrandURLToReplaceSetting()) {
            $this->settings->saveBrandURLToReplaceSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noReplaceBaseURLOnBrandsSetting()) {
            $this->settings->saveReplaceBaseURLOnBrandsSetting($this->hq_integration_on_home_default_value);
        }
        if ($this->settings->noDefaultLatitudeSetting()) {
            $this->settings->setDefaultLatitudeSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noDefaultLongitudeSetting()) {
            $this->settings->setDefaultLongitudeSetting($this->hq_default_value_for_string);
        }
        if ($this->settings->noCurrencyIconOption()) {
            $this->settings->setCurrencyIconOption($this->hq_default_value_for_string);
        }
        if ($this->settings->noEnableCustomPostsPages()) {
            $this->settings->setEnableCustomPostsPages('false');
        }
        if ($this->settings->noOverrideDailyRateWithCheapestPriceInterval()) {
            $this->settings->setOverrideDailyRateWithCheapestPriceInterval('false');
        }
        if ($this->settings->noGoogleAPIKey()) {
            $this->settings->setGoogleAPIKey('');
        }
        if ($this->settings->noGoogleCountry()) {
            $this->settings->setGoogleCountry('');
        }
        if ($this->settings->isEnableCustomPostsPages()) {
            $this->resolveDefaultPages();
        }
        if ($this->settings->noDefaultPickupTime()) {
            $this->settings->setDefaultPickupTime('');
        }
        if ($this->settings->noDefaultReturnTime()) {
            $this->settings->setDefaultReturnTime('');
        }
        if ($this->settings->noWebhookSyncOption()) {
            $this->settings->setWebhookSyncOption('false');
        }
        if ($this->settings->getVehicleClassTypeField()) {
            $this->settings->setVehicleClassTypeField('');
        }
        $this->notifyToSystemOnActivation();
        $this->dbBootstrap->createTablesOnInit();
    }

    public function resolveDefaultPages()
    {
        $pages = new HQRentalsPagesHandler();
        $pages->createPagesOnInit();
    }

    public function notifyToSystemOnActivation()
    {
        $api = new HQRentalsApiConnector();
        $response = $api->notifyOnActivation();
    }
}
