<?php

namespace HQRentalsPlugin\HQRentalsTransformers;

use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsTransformersVehicleClasses extends HQRentalsTransformersBase
{

    public function __construct()
    {
        $this->pluginSettings = new HQRentalsSettings();
    }

    public function transformApiData($data)
    {
            $transformation = new \stdClass();
            $transformation->id = $data->id;
            $transformation->name = $data->name;
            $transformation->uuid = $data->uuid;
            $transformation->brand_id = $data->brand->id;
            $transformation->order = $data->order;
            $transformation->available_on_website = 1;
            $transformation->recommended = 1;
            $transformation->active = 1;
            $transformation->public_image_link = $data->public_image_link;
            $transformation->label_for_website = $data->label_for_website;
            $transformation->short_description_for_website = $data->short_description_for_website;
            $transformation->description_for_website = $data->description_for_website;
            $transformation->images = $data->images;
            $transformation->features = $data->features;
            $transformation->active_rates = $data->activeRates;
            $transformation->allData = $data;
            return $transformation;
        
    }

    public static function transformDataFromApi($apiData)
    {

    }
}