<?php

namespace HQRentalsPlugin\HQRentalsTransformers;

class HQRentalsTransformersLocations extends HQRentalsTransformersBase
{
    protected static $singleLocationProperties = [
        'id',
        'name',
        'brand_id',
        'name',
        'is_airport',
        'is_office',
        'coordinates',
        'active',
        'order',
        'address',
        'phone',
        'label_for_website'
    ];

    public static function transformDataFromApi($apiData)
    {
        return HQRentalsTransformersLocations::resolveArrayOfObjects($apiData, function ($apiSingleLocation) {
            return HQRentalsTransformersLocations::transformSingleLocation($apiSingleLocation);
        });
    }

    public static function transformSingleLocation($location)
    {
        return HQRentalsTransformersLocations::extractDataFromApiObject(HQRentalsTransformersLocations::$singleLocationProperties, $location, null, true);
    }
}
