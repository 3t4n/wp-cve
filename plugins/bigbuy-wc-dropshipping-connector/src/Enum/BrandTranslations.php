<?php

namespace WcMipConnector\Enum;

defined('ABSPATH') || exit;

class BrandTranslations
{
    public static $brandTranslations = [
        'EN' => 'Brand',
        'ES' => 'Marca',
        'FR' => 'Marque',
        'DE' => 'Marke',
        'PT' => 'Marca',
        'EL' => 'σημάδι',
        'HR' => 'Marke',
        'IT' => 'Marca',
        'ET' => 'Märk',
        'DA' => 'Brand',
        'FI' => 'Merkki',
        'RO' => 'Marca',
        'BG' => 'Mарка',
        'HU' => 'Jel',
        'SK' => 'Značka',
        'SI' => 'Blagovna znamka',
        'LT' => 'Gamintojas',
        'LV' => 'Zīmols',
        'PL' => 'Marka',
        'NL' => 'Merk',
        'RU' => 'марка',
        'NO' => 'Mark',
        'SV' => 'Mark'
    ];

    /**
     * @param $isoCode
     * @return string
     */
    public static function getBrandTranslation($isoCode): string
    {
        if (\array_key_exists($isoCode, self::$brandTranslations)) {
            return self::$brandTranslations[$isoCode];
        }

        return self::$brandTranslations['EN'];
    }
}