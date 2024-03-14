<?php

namespace HQRentalsPlugin\HQRentalsTransformers;

class HQRentalsTransformersGoogle
{
    public static function transformGoogleAutocompleteData($googleData)
    {
        $predictions = $googleData->predictions;
        return array_map(function ($prediction) {
            return HQRentalsTransformersGoogle::transformPrediction($prediction);
        }, $predictions);
    }

    public static function transformPrediction($prediction)
    {
        $newPrediction = new \stdClass();
        $newPrediction->description = $prediction->description;
        $newPrediction->id = $prediction->id;
        $newPrediction->place_id = $prediction->place_id;
        $newPrediction->reference = $prediction->reference;
        return $newPrediction;
    }

    public static function transformGooglePlaceData($googlePlaceData)
    {
        $place = $googlePlaceData->result;
        $placeObject = new \stdClass();
        $placeObject->geometry = $place->geometry;
        return $placeObject;
    }
}
