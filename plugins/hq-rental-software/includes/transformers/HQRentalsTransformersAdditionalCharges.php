<?php

namespace HQRentalsPlugin\HQRentalsTransformers;

class HQRentalsTransformersAdditionalCharges extends HQRentalsTransformersBase
{
    protected static $singleChargeProperties = [
        'id',
        'name',
        'charge_type',
        'mandatory',
        'selection_type',
        'icon',
        'labels',
    ];

    public static function transformDataFromApi($apiData)
    {
        return HQRentalsTransformersAdditionalCharges::resolveArrayOfObjects($apiData, function ($apiSingleBrand) {
            return HQRentalsTransformersAdditionalCharges::transformSingleBrand($apiSingleBrand);
        });
    }

    public static function transformSingleBrand($apiBrand)
    {
        return HQRentalsTransformersBrands::extractDataFromApiObject(HQRentalsTransformersAdditionalCharges::$singleChargeProperties, $apiBrand);
    }
}
