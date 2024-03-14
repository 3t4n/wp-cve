<?php

namespace HQRentalsPlugin\HQRentalsSettings;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsEncryptionHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsTasks\HQRentalsCronJob;
use HQRentalsPlugin\HQRentalsTasks\HQRentalsScheduler;

class HQRentalsSettings
{
    public $api_user_token = 'hq_wordpress_api_user_token_key_option';
    public $api_tenant_token = 'hq_wordpress_api_tenant_token_key_option';
    public $api_encoded_token = 'hq_wordpress_api_encoded_token_option';
    public $hq_datetime_format = 'hq_wordpress_system_datetime_format_option';
    public $front_end_datetime_format = 'hq_wordpress_front_end_datetime_format_option';
    public $api_base_url = 'hq_wordpress_api_base_url_option';
    public $new_auth_scheme = 'hq_wordpress_new_auth_scheme_enabled';
    public $hq_integration_on_home = 'hq_wordpress_home_integration_enabled';
    public $hq_disable_cronjob_option = 'hq_wordpress_disable_cronjob_option';
    public $hq_enable_decreasing_rate_order_on_vehicles_query = 'hq_enable_decreasing_rate_order_on_vehicles_query';
    public $hq_tenant_datetime_format = 'hq_wordpress_tenant_datetime_format';
    public $hq_tenant_link = 'hq_wordpress_tenant_link';
    public $hq_disable_safari_functionality = 'hq_disable_safari_functionality';
    public $hq_location_coordinate_field = 'hq_location_coordinate_field';
    public $hq_location_image_field = 'hq_location_image_field';
    public $hq_location_description_field = 'hq_location_description_field';
    public $hq_location_address_label_field = 'hq_location_address_label_field';
    public $hq_location_brands_field = 'hq_location_brands_field';
    public $hq_location_phone_field = 'hq_location_phone_field';
    public $hq_location_address_field = 'hq_location_address_field';
    public $hq_location_office_hours_field = 'hq_location_office_hours_field';
    public $hq_replace_url_on_brand_option = 'hq_replace_url_on_brand_option';
    public $hq_url_to_replace_on_brands_option = 'hq_url_to_replace_on_brands_option';
    public $hq_default_latitude_for_map_shortcode = 'hq_default_latitude_for_map_shortcode';
    public $hq_default_longitude_for_map_shortcode = 'hq_default_longitude_for_map_shortcode';
    public $hq_auth_email = 'hq_auth_email';
    public $hq_currency_symbol = 'hq_currency_symbol';
    public $hq_enable_custom_post_pages = 'hq_enable_custom_post_pages';
    public $hq_google_api_key = 'hq_google_api_key';
    public $hq_captcha_key = 'hq_captcha_key';
    public $hq_captcha_secret = 'hq_captcha_secret';
    public $hq_default_pick_up_time = 'hq_default_pick_up_time';
    public $hq_default_return_time = 'hq_default_return_time';
    public $hq_override_daily_rate_with_price_interval = 'hq_override_daily_rate_with_price_interval';
    public $hq_webhook_sync = 'hq_webhook_sync';
    public $hq_last_sync_date = 'hq_last_sync_date';
    public $hq_google_country = 'hq_google_country';
    public $hq_vehicle_class_type_field = 'hq_vehicle_class_type_field';
    public $hq_tenant_metric_system = 'hq_tenant_metric_system';
    public $hq_vehicle_class_banner_image_field = 'hq_vehicle_class_banner_image_field';
    public $hq_public_reservation_workflow_url = 'hq_public_reservation_workflow_url';


    public function __construct()
    {
        $this->helper = new HQRentalsFrontHelper();
    }

    /**
     * Retrieve Api User Token from DB
     * @return mixed|void
     */
    public function getApiUserToken()
    {
        $option = get_option($this->api_user_token, "");
        if (!empty($option)) {
            return HQRentalsEncryptionHandler::decrypt(get_option($this->api_user_token));
        } else {
            return '';
        }
    }

    /**
     * Retrieve Api Tenant Token from DB
     * @return mixed|void
     */
    public function getApiTenantToken()
    {
        $option = get_option($this->api_tenant_token, "");
        if (!empty($option)) {
            return HQRentalsEncryptionHandler::decrypt(get_option($this->api_tenant_token));
        } else {
            return '';
        }
    }

    /**
     * Retrieve encoded api token
     * @return mixed|void
     */
    public function getApiEncodedToken()
    {
        return HQRentalsEncryptionHandler::decrypt(get_option($this->api_encoded_token));
    }

    /**
     * Retrieve System Datetime format
     * @return mixed|void
     */
    public function getHQDatetimeFormat()
    {
        return get_option($this->hq_datetime_format);
    }

    /***
     * Retrieve front-end datetime format
     * @return mixed|void
     */
    public function getFrontEndDatetimeFormat()
    {
        return get_option($this->front_end_datetime_format);
    }

    public function getSupportForHomeIntegration()
    {
        return get_option($this->hq_integration_on_home, 'false');
    }

    public function getDisableCronjobOption()
    {
        return get_option($this->hq_disable_cronjob_option, 'false');
    }

    public function getTenantDatetimeFormat()
    {
        return get_option($this->hq_tenant_datetime_format, '');
    }

    public function getDisableSafari()
    {
        return get_option($this->hq_disable_safari_functionality, 'false');
    }

    public function getDisableSafariValue()
    {
        return $this->getDisableSafari() == 'true';
    }

    /**
     * Retrieve Base Api Option
     * @return mixed|void
     */
    public function getApiBaseUrl()
    {
        return get_option($this->api_base_url, true);
    }

    /**
     * Save api base url
     * @param $newApiUrl
     * @return bool
     */
    public function saveApiBaseUrl($newApiUrl)
    {
        return update_option($this->api_base_url, sanitize_text_field($newApiUrl));
    }

    /**
     * Save api user token
     * @param $token
     * @return bool
     */
    public function saveApiUserToken($token)
    {
        if (empty($token)) {
            return update_option($this->api_user_token, "");
        } else {
            return update_option($this->api_user_token, HQRentalsEncryptionHandler::encrypt(sanitize_text_field($token)));
        }
    }

    public function saveApiTenantToken($token)
    {
        if (empty($token)) {
            return update_option($this->api_tenant_token, "");
        } else {
            return update_option($this->api_tenant_token, HQRentalsEncryptionHandler::encrypt(sanitize_text_field($token)));
        }
    }


    /**
     * Save system datetime format
     * @param $datetime_format
     * @return bool
     */
    public function saveHQDatetimeFormat($datetime_format)
    {
        return update_option($this->hq_datetime_format, sanitize_text_field($datetime_format));
    }

    /**
     * Save front end datetime format
     * @param $datetime_format
     * @return bool
     */
    public function saveFrontEndDateTimeFormat($datetime_format)
    {
        return update_option($this->front_end_datetime_format, sanitize_text_field($datetime_format));
    }

    /**
     * Save encoded api token
     * @param $tenantKey
     * @param $userKey
     * @return bool
     */
    public function saveEncodedApiKey($tenantKey, $userKey)
    {
        return update_option($this->api_encoded_token, HQRentalsEncryptionHandler::encrypt(sanitize_text_field(base64_encode($tenantKey . ':' . $userKey))));
    }

    public function saveNewAuthScheme($data)
    {
        return update_option($this->new_auth_scheme, sanitize_text_field($data));
    }

    public function saveHomeIntegration($data)
    {
        return update_option($this->hq_integration_on_home, sanitize_text_field($data));
    }

    public function saveDisableCronjobOption($data)
    {
        return update_option($this->hq_disable_cronjob_option, sanitize_text_field($data));
    }

    public function saveTenantDatetimeOption($data)
    {
        return update_option($this->hq_tenant_datetime_format, sanitize_text_field($data));
    }

    public function saveTenantLink($data)
    {
        return update_option($this->hq_tenant_link, sanitize_text_field($data));
    }

    public function getTenantLink()
    {
        return get_option($this->hq_tenant_link, '');
    }

    public function saveDisableSafariOption($data)
    {
        return update_option($this->hq_disable_safari_functionality, sanitize_text_field($data));
    }


    public function noLocationCoordinateSetting()
    {
        return empty(get_option($this->hq_location_coordinate_field));
    }

    public function saveLocationCoordinateSetting($data)
    {
        return update_option($this->hq_location_coordinate_field, sanitize_text_field($data));
    }

    public function getLocationCoordinateField()
    {
        return get_option($this->hq_location_coordinate_field, '');
    }

    public function noLocationImageSetting()
    {
        return empty(get_option($this->hq_location_image_field));
    }

    public function saveLocationImageSetting($data)
    {
        return update_option($this->hq_location_image_field, $data);
    }

    public function getLocationImageField()
    {
        return get_option($this->hq_location_image_field, '');
    }

    public function noLocationDescriptionSetting()
    {
        return empty(get_option($this->hq_location_description_field));
    }

    public function saveLocationDescriptionSetting($data)
    {
        return update_option($this->hq_location_description_field, $data);
    }

    public function getLocationDescriptionField()
    {
        return get_option($this->hq_location_description_field, '');
    }

    public function noAddressLabelSetting()
    {
        return empty(get_option($this->hq_location_address_label_field));
    }

    public function saveAddressLabelSetting($data)
    {
        return update_option($this->hq_location_address_label_field, $data);
    }

    public function getAddressLabelField()
    {
        return get_option($this->hq_location_address_label_field, '');
    }

    public function noOfficeHoursSetting()
    {
        return empty(get_option($this->hq_location_office_hours_field));
    }

    public function saveOfficeHoursSetting($data)
    {
        return update_option($this->hq_location_office_hours_field, $data);
    }

    public function getOfficeHoursSetting()
    {
        return get_option($this->hq_location_office_hours_field, '');
    }

    public function noBrandsSetting()
    {
        return empty(get_option($this->hq_location_brands_field));
    }

    public function saveBrandsSetting($data)
    {
        return update_option($this->hq_location_brands_field, $data);
    }

    public function getBrandsSetting()
    {
        return get_option($this->hq_location_brands_field, '');
    }


    public function noPhoneSetting()
    {
        return empty(get_option($this->hq_location_phone_field));
    }

    public function savePhoneSetting($data)
    {
        return update_option($this->hq_location_phone_field, $data);
    }

    public function getPhoneSetting()
    {
        return get_option($this->hq_location_phone_field, '');
    }

    public function noAddressSetting()
    {
        return empty(get_option($this->hq_location_address_field));
    }

    public function saveAddressSetting($data)
    {
        return update_option($this->hq_location_address_field, $data);
    }

    public function getAddressSetting()
    {
        return get_option($this->hq_location_address_field, '');
    }

    public function noDecreasingRateOrder()
    {
        return empty(get_option($this->hq_enable_decreasing_rate_order_on_vehicles_query));
    }

    public function saveDecreasingRateOrder($data)
    {
        return update_option($this->hq_enable_decreasing_rate_order_on_vehicles_query, $data);
    }

    public function getDecreasingRateOrder()
    {
        return get_option($this->hq_enable_decreasing_rate_order_on_vehicles_query, 'false');
    }

    public function isDecreasingRateOrderActive()
    {
        return $this->getDecreasingRateOrder() === 'true';
    }

    public function getEnableCustomPostsPages()
    {
        return get_option($this->hq_enable_custom_post_pages, 'false');
    }
    public function setEnableCustomPostsPages($data)
    {
        return update_option($this->hq_enable_custom_post_pages, $data);
    }
    public function isEnableCustomPostsPages()
    {
        return $this->getEnableCustomPostsPages() === 'true';
    }

    public function noReplaceBaseURLOnBrandsSetting()
    {
        return empty(get_option($this->hq_replace_url_on_brand_option));
    }

    public function saveReplaceBaseURLOnBrandsSetting($data)
    {
        return update_option($this->hq_replace_url_on_brand_option, $data);
    }

    public function getReplaceBaseURLOnBrandsSetting()
    {
        return get_option($this->hq_replace_url_on_brand_option, '');
    }

    public function noBrandURLToReplaceSetting()
    {
        return empty(get_option($this->hq_url_to_replace_on_brands_option));
    }

    public function saveBrandURLToReplaceSetting($data)
    {
        return update_option($this->hq_url_to_replace_on_brands_option, $data);
    }

    public function getBrandURLToReplaceSetting()
    {
        return get_option($this->hq_url_to_replace_on_brands_option, '');
    }

    /*
     * Latitude setting
     * */
    public function noDefaultLatitudeSetting()
    {
        return empty(get_option($this->hq_default_latitude_for_map_shortcode));
    }

    public function getDefaultLatitudeSetting()
    {
        return get_option($this->hq_default_latitude_for_map_shortcode, "");
    }

    public function setDefaultLatitudeSetting($data)
    {
        return update_option($this->hq_default_latitude_for_map_shortcode, $data);
    }

    /*
     * Longitude setting
     * */
    public function noDefaultLongitudeSetting()
    {
        return empty(get_option($this->hq_default_longitude_for_map_shortcode));
    }

    public function getDefaultLongitudeSetting()
    {
        return get_option($this->hq_default_longitude_for_map_shortcode, "");
    }

    public function setDefaultLongitudeSetting($data)
    {
        return update_option($this->hq_default_longitude_for_map_shortcode, $data);
    }

    public function updateSettings($postDataFromSettings)
    {
        $postDataFromSettings = $this->helper->sanitizeTextInputs($postDataFromSettings);
        $this->saveEncodedApiKey($postDataFromSettings[$this->api_tenant_token], $postDataFromSettings[$this->api_user_token]);
        $this->saveNewAuthScheme('true');
        foreach ($postDataFromSettings as $key => $data) {
            if ($key != 'save') {
                if ($key == $this->api_tenant_token) {
                    $this->saveApiTenantToken($postDataFromSettings[$this->api_tenant_token]);
                } elseif ($key == $this->api_user_token) {
                    $this->saveApiUserToken($postDataFromSettings[$this->api_user_token]);
                } elseif ($key == $this->hq_google_api_key) {
                    $this->setGoogleAPIKey($postDataFromSettings[$this->hq_google_api_key]);
                } elseif ($key == $this->hq_captcha_key) {
                    $this->setCaptchaKey($postDataFromSettings[$this->hq_captcha_key]);
                } elseif ($key == $this->hq_captcha_secret) {
                    $this->setCaptchaSecret($postDataFromSettings[$this->hq_captcha_secret]);
                } else {
                    update_option($key, sanitize_text_field($data));
                }
            }
        }
        if (empty($postDataFromSettings[$this->hq_integration_on_home])) {
            update_option($this->hq_integration_on_home, "false");
        }
        if (empty($postDataFromSettings[$this->hq_disable_cronjob_option])) {
            update_option($this->hq_disable_cronjob_option, "false");
        }
        if (empty($postDataFromSettings[$this->hq_disable_safari_functionality])) {
            update_option($this->hq_disable_safari_functionality, "false");
        }
        if (empty($postDataFromSettings[$this->hq_replace_url_on_brand_option])) {
            update_option($this->hq_replace_url_on_brand_option, "false");
        }
        if (empty($postDataFromSettings[$this->hq_enable_decreasing_rate_order_on_vehicles_query])) {
            update_option($this->hq_enable_decreasing_rate_order_on_vehicles_query, "false");
        }
        if (empty($postDataFromSettings[$this->hq_enable_custom_post_pages])) {
            update_option($this->hq_enable_custom_post_pages, "false");
        }
        if (empty($postDataFromSettings[$this->hq_override_daily_rate_with_price_interval])) {
            update_option($this->hq_override_daily_rate_with_price_interval, "false");
        }
        if (empty($postDataFromSettings[$this->hq_webhook_sync])) {
            update_option($this->hq_webhook_sync, "false");
        }

        /*Refresh data on save */
        $worker = new HQRentalsCronJob();
        $worker->refreshAllData();

        /*delete page in case*/
        if (!$this->isEnableCustomPostsPages()) {
            $pages = new HQRentalsPagesHandler();
            $pages->deleteAllPages();
        }
    }

    /***
     * Retrieve all Settings as an array
     * @return array
     */
    public function getSettings()
    {
        return array(
            $this->api_user_token => $this->getApiUserToken(),
            $this->api_tenant_token => $this->getApiTenantToken(),
            $this->api_encoded_token => $this->getApiEncodedToken(),
            $this->hq_datetime_format => $this->getHQDatetimeFormat(),
            $this->front_end_datetime_format => $this->getFrontEndDatetimeFormat(),
            $this->api_base_url => $this->getApiBaseUrl()
        );
    }


    /***
     *
     * There is something missing on the plugin Configuration?
     * @return bool
     */
    public function thereAreSomeSettingMissing()
    {
        /*
         * Options missing on DB
         * */
        return empty(get_option($this->api_tenant_token)) or
            empty(get_option($this->api_user_token)) or
            empty(get_option($this->front_end_datetime_format)) or
            empty(get_option($this->hq_datetime_format));
    }

    /**
     * Checks if new auth scheme exits
     * @return bool
     */
    public function noNewAuthSchemeOption()
    {
        return empty(get_option($this->new_auth_scheme));
    }

    public function noDisableSafariFunctionality()
    {
        return empty(get_option($this->hq_disable_safari_functionality));
    }

    /**
     * Check if new Auth Scheme is enabled
     * @return bool
     */
    public function newAuthSchemeEnabled()
    {
        return get_option($this->new_auth_scheme) == 'true';
    }

    public function noTenantDatetimeFormat()
    {
        return empty(get_option($this->hq_tenant_datetime_format));
    }

    public function noHomeIntegrationOption()
    {
        return empty(get_option($this->hq_integration_on_home));
    }

    public function noDisabledCronjobOption()
    {
        return empty(get_option($this->hq_disable_cronjob_option));
    }

    public function homeIntegration()
    {
        return get_option($this->hq_integration_on_home) == 'true';
    }

    public function forceSyncOnHQData()
    {
        $schedule = new HQRentalsScheduler();
        $res = $schedule->refreshHQData();
        if ($res === true) {
            $_POST['forcing_update'] = 'success';
        } else {
            $_POST['forcing_update'] = $res;
        }
    }

    public function resolveSettingsOnAuth($response)
    {
        if ($response->data->success) {
            $tenants = $response->data->data->tenants;
            if (is_array($tenants)) {
                $first = $response->data->data->tenants[0];
                $user = $response->data->data->user;
                $link = $first->api_link;
                $userToken = $user->api_token;
                $tenantToken = $first->api_token;
                if ($link and $userToken and $tenants) {
                    $this->saveApiBaseUrl($link);
                    $this->saveApiTenantToken($tenantToken);
                    $this->saveApiUserToken($userToken);
                    $this->saveEncodedApiKey($tenantToken, $userToken);
                }
            }
        }
    }

    public function isApiOkay()
    {
        $connector = new HQRentalsApiConnector();
        $apiTestCall = $connector->getHQRentalsBrands();
        return $apiTestCall->success;
    }

    public function updateEmail($email)
    {
        update_option($this->hq_auth_email, $email);
    }

    public function getEmail()
    {
        return get_option($this->hq_auth_email, "");
    }

    public function noEnableCustomPostsPages()
    {
        return empty(get_option($this->hq_enable_custom_post_pages));
    }
    public function noCurrencyIconOption()
    {
        return empty(get_option($this->hq_currency_symbol));
    }
    public function setCurrencyIconOption($icon)
    {
        return update_option($this->hq_currency_symbol, $icon);
    }

    public function getCurrencyIconOption()
    {
        return get_option($this->hq_currency_symbol, '');
    }
    public function noGoogleAPIKey()
    {
        return empty(get_option($this->hq_google_api_key));
    }
    public function setGoogleAPIKey($key)
    {
        return update_option($this->hq_google_api_key, HQRentalsEncryptionHandler::encrypt(sanitize_text_field($key)));
    }
    public function getGoogleAPIKey()
    {
        return HQRentalsEncryptionHandler::decrypt(get_option($this->hq_google_api_key));
    }
    public function setCaptchaKey($key)
    {
        return update_option($this->hq_captcha_key, HQRentalsEncryptionHandler::encrypt(sanitize_text_field($key)));
    }
    public function getCaptchaKey()
    {
        return HQRentalsEncryptionHandler::decrypt(get_option($this->hq_captcha_key));
    }
    public function setCaptchaSecret($key)
    {
        return update_option($this->hq_captcha_secret, HQRentalsEncryptionHandler::encrypt(sanitize_text_field($key)));
    }
    public function getCaptchaSecret()
    {
        return HQRentalsEncryptionHandler::decrypt(get_option($this->hq_captcha_secret));
    }
    public function noGoogleCountry()
    {
        return empty(get_option($this->hq_google_country));
    }
    public function setGoogleCountry($data)
    {
        return update_option($this->hq_google_country, $data);
    }
    public function getGoogleCountry()
    {
        return get_option($this->hq_google_country, '');
    }

    public function noDefaultPickupTime(): string
    {
        return empty(get_option($this->hq_default_pick_up_time));
    }
    public function setDefaultPickupTime($data): bool
    {
        return update_option($this->hq_default_pick_up_time, $data);
    }

    public function getDefaultPickupTime(): string
    {
        return get_option($this->hq_default_pick_up_time);
    }
    public function noDefaultReturnTime(): bool
    {
        return empty(get_option($this->hq_default_return_time));
    }
    public function setDefaultReturnTime($data): bool
    {
        return update_option($this->hq_default_return_time, $data);
    }

    public function getDefaultReturnTime(): string
    {
        return get_option($this->hq_default_return_time);
    }

    public function noOverrideDailyRateWithCheapestPriceInterval(): bool
    {
        return empty(get_option($this->hq_override_daily_rate_with_price_interval));
    }
    public function setOverrideDailyRateWithCheapestPriceInterval($data): bool
    {
        return update_option($this->hq_override_daily_rate_with_price_interval, $data);
    }

    public function getOverrideDailyRateWithCheapestPriceInterval(): string
    {
        return get_option($this->hq_override_daily_rate_with_price_interval);
    }


    public function noWebhookSyncOption(): bool
    {
        return empty(get_option($this->hq_webhook_sync));
    }
    public function setWebhookSyncOption($data): bool
    {
        return update_option($this->hq_webhook_sync, $data);
    }

    public function getWebhookSyncOption(): string
    {
        return get_option($this->hq_webhook_sync);
    }

    public function noLastSyncOption(): bool
    {
        return empty(get_option($this->hq_last_sync_date));
    }
    public function setLastSyncOption(): bool
    {
        return update_option($this->hq_last_sync_date, current_time('mysql', 1));
    }
    public function getLastSyncOption(): string
    {
        return get_option($this->hq_last_sync_date);
    }

    public function noVehicleClassTypeField()
    {
        return empty(get_option($this->hq_vehicle_class_type_field));
    }

    public function getVehicleClassTypeField(): string
    {
        return get_option($this->hq_vehicle_class_type_field, "");
    }

    public function setVehicleClassTypeField($data)
    {
        return update_option($this->hq_vehicle_class_type_field, $data);
    }

    public function getMetricSystem(): string
    {
        return get_option($this->hq_tenant_metric_system, "");
    }

    public function saveMetricSystem($data)
    {
        return update_option($this->hq_tenant_metric_system, $data);
    }
    public function getVehicleClassBannerImageField(): string
    {
        return get_option($this->hq_vehicle_class_banner_image_field, "");
    }

    public function setVehicleClassBannerImageField($data)
    {
        return update_option($this->hq_vehicle_class_banner_image_field, $data);
    }
    public function getPublicReservationWorkflowURL(): string
    {
        return get_option($this->hq_public_reservation_workflow_url, "");
    }

    public function setPublicReservationWorkflowURL($data)
    {
        return update_option($this->hq_public_reservation_workflow_url, $data);
    }
}
