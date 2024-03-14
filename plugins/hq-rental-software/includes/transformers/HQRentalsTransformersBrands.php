<?php

namespace HQRentalsPlugin\HQRentalsTransformers;

class HQRentalsTransformersBrands extends HQRentalsTransformersBase
{
    protected static $singleBrandProperties = [
        'id',
        'name',
        'website_link',
        'tax_label',
        'public_reservations_link_full',
        'public_packages_link_full',
        'public_reservations_link_first_step',
        'public_packages_link_first_step',
        'public_reservations_packages_link_first_step',
        'my_reservations_link',
        'my_package_reservations_link',
        'integration_snippets',
        'uuid',
        'abb_tax'
    ];

    public static function transformDataFromApi($apiData)
    {
        return HQRentalsTransformersBrands::resolveArrayOfObjects($apiData, function ($apiSingleBrand) {
            return HQRentalsTransformersBrands::transformSingleBrand($apiSingleBrand);
        });
    }

    public static function transformSingleBrand($apiBrand)
    {
        return HQRentalsTransformersBrands::extractDataFromApiObject(HQRentalsTransformersBrands::$singleBrandProperties, $apiBrand);
    }
}
