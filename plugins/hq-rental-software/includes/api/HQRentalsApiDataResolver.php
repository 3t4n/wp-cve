<?php

namespace HQRentalsPlugin\HQRentalsApi;

class HQRentalsApiDataResolver
{
    public static function resolveImage($imageField)
    {
        $backgroundImage = '';
        if (is_array($imageField)) {
            if (count($imageField)) {
                $backgroundImage = $imageField[0]->public_link;
            }
        }
        return $backgroundImage;
    }

    public static function resolveCKEditor($data)
    {
        if (is_string($data) and $data !== '') {
            return $data;
        }
        return '';
    }
}
