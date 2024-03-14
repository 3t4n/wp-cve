<?php

namespace HQRentalsPlugin\HQRentalsApi;

use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsApiEndpoint
{
    private static $authURL = 'https://api.caagcrm.com/api/auth?check_other_regions=true';
    public static $CAPTCHA_VALIDATION_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
    }

    public function getAvailabilityEndpoint()
    {
        return $this->settings->getApiBaseUrl() . 'car-rental/availability';
    }

    public function getBrandsApiEndpoint()
    {
        return $this->settings->getApiBaseUrl() . 'fleets/brands';
    }

    public function getVehicleClassesApiEndpoint()
    {
        return $this->settings->getApiBaseUrl() . 'fleets/vehicle-classes?only_available_on_website=1&minified_response=1';
    }

    public function getLocationsApiEndpoint()
    {
        return $this->settings->getApiBaseUrl() . 'fleets/locations';
    }

    public function getVehicleTypesEndpoint()
    {
        return $this->settings->getApiBaseUrl() . 'fleets/vehicle-types';
    }

    public function getAdditionalChargesEndpoint()
    {
        return $this->settings->getApiBaseUrl() . 'fleets/additional-charges';
    }

    public function getHQAssetsEndpoint()
    {
        return 'https://api.caagcrm.com/api/assets/files';
    }

    public function getVehicleClassCustomFields()
    {
        return $this->settings->getApiBaseUrl() . 'fields?item_type=fleets.vehicle_classes&limit=100';
    }

    public function getTenantsSettingsEndpoint()
    {
        return $this->settings->getApiBaseUrl() . 'tenants/current';
    }
    public function getCarRentalSettingEndpoint()
    {
        return $this->settings->getApiBaseUrl() . 'preferences';
    }

    public function getGoogleAutocompleteEndpoint($input)
    {
        $setting = new HQRentalsSettings();
        $googleKey = $setting->getGoogleAPIKey();
        $country = $setting->getGoogleCountry();
        $args = array(
            'key' => $googleKey,
            'input' => $input,
            'components' => 'country:' . $country,
            'lang' => 'en'
        );
        return 'https://maps.googleapis.com/maps/api/place/autocomplete/json?' . http_build_query($args);
    }

    public function getGooglePlaceDetailsEndpoint($placeId)
    {
        $setting = new HQRentalsSettings();
        $args = array(
            'key' => $setting->getGoogleAPIKey(),
            'place_id' => $placeId,
        );
        return 'https://maps.googleapis.com/maps/api/place/details/json?' . http_build_query($args);
    }

    public function getWebsiteRegistrationEndpoint(): string
    {
        $args = array(
            'site' => get_site_url(),
            'version' => HQ_RENTALS_PLUGIN_VERSION
        );
        return $this->settings->getApiBaseUrl() . 'car-rental/websites/register?' . http_build_query($args);
    }

    public function getAuthEndpoint(): string
    {
        return HQRentalsApiEndpoint::$authURL;
    }

    public function getVehicleClassFormEndpoint(): string
    {
        $query = new HQRentalsQueriesVehicleClasses();
        $vehicles = $query->allVehicleClasses();
        $vehicle = $vehicles[0];
        return $this->settings->getApiBaseUrl() . 'fleets/vehicle-classes/' . $vehicle->id . '/form';
    }

    public function getAvailabilityDatesEndpoint(): string
    {
        return $this->settings->getApiBaseUrl() . 'car-rental/reservations/dates';
    }
    public function getSendAQuoteEndpoint(): string
    {
        return $this->settings->getApiBaseUrl() . 'car-rental/reservations/send-quote';
    }
    public function getCaptchaValidationEndpoint(): string
    {
        return HQRentalsApiEndpoint::$CAPTCHA_VALIDATION_URL;
    }
}
