<?php

namespace HQRentalsPlugin\HQRentalsTransformers;

class HQRentalsTransformersCarRentalSettings extends HQRentalsTransformersBase
{
    protected static $singleSettingsProperties = [
        'car_rental_default_pick_up_time',
        'car_rental_default_return_time',
        'car_rental_default_currency',
        'car_rental_allow_return_outside_office',
        'car_rental_allow_return_outside_office',
        'car_rental_set_default_pick_up_time_as_current_time',
        'car_rental_set_default_return_time_as_current_time',
        'car_rental_show_prices_including_sales_tax',
        'car_rental_copy_pick_up_location_to_return_location_only_form'

    ];

    public static function transformDataFromApi($apiData)
    {
        return HQRentalsTransformersCarRentalSettings::transformSettings($apiData);
    }

    public static function transformSettings($apiSettings)
    {
        return (array)HQRentalsTransformersCarRentalSettings::extractDataFromApiObject(
            HQRentalsTransformersCarRentalSettings::$singleSettingsProperties,
            $apiSettings
        );
    }
}
