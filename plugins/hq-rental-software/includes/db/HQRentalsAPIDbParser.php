<?php

namespace HQRentalsPlugin\HQRentalsDb;

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsAPiDbParser
{
    public static function parseBrandToDB($apiData)
    {
        $dataAsArray = (array)$apiData;
        $dataAsArray['integration_snippets'] = json_encode($dataAsArray['integration_snippets']);
        return $dataAsArray;
    }

    public static function parseBrandsToWP($dbBrands)
    {
        if ($dbBrands) {
            return array_map(function ($brandRow) {
                return HQRentalsAPiDBParser::parseBrandToWP($brandRow);
            }, $dbBrands);
        } else {
            return [];
        }
    }

    public static function parseBrandToWP($brandDB)
    {
        return new HQRentalsModelsBrand($brandDB, true);
    }

    public static function parseLocationToDB($apiData)
    {
        $dataAsArray = (array)$apiData;
        $dataAsArray['label_for_website'] = json_decode($dataAsArray['label_for_website']);
        unset($dataAsArray['order']);
        return $dataAsArray;
    }
}
