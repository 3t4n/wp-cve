<?php

namespace HQRentalsPlugin\HQRentalsTransformers;

use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

abstract class HQRentalsTransformersBase
{
    abstract public static function transformDataFromApi($data);

    public static function resolveSingleAttribute($propertyValue, $default = null)
    {
        return !empty($propertyValue) ? ($propertyValue) : (!empty($default) ? $default : '');
    }

    public static function resolveArrayOfObjects($arrayApiData, $callback = null)
    {
        if (is_array($arrayApiData) and !empty($callback)) {
            return array_map(function ($item) use ($callback) {
                return call_user_func($callback, $item);
            }, $arrayApiData);
        } else {
            return [];
        }
    }

    public static function extractDataFromApiObject($properties, $apiObject, $nestedObject = null, $isLocation = false)
    {
        $objectToReturn = new \stdClass();

        foreach ($properties as $property) {
            if (empty($nestedObject)) {
                if (isset($apiObject->{$property})) {
                    $objectToReturn->{$property} = HQRentalsTransformersBase::resolveSingleAttribute($apiObject->{$property});
                } else {
                    $objectToReturn->{$property} = '';
                }
            }
        }
        if ($isLocation) {
            HQRentalsTransformersBase::resolveCustomFieldsOnLocation($objectToReturn, $apiObject);
        }
        return $objectToReturn;
    }

    public static function resolveCustomFieldsOnLocation($objectToReturn, $apiObject)
    {
        $setting = new HQRentalsSettings();
        $coordinates = $setting->getLocationCoordinateField();
        $image = $setting->getLocationImageField();
        $description = $setting->getLocationDescriptionField();
        $hours = $setting->getOfficeHoursSetting();
        $addressLabel = $setting->getAddressLabelField();
        $brands = $setting->getBrandsSetting();
        $phone = $setting->getPhoneSetting();
        $address = $setting->getAddressSetting();
        HQRentalsTransformersBase::resolveSingleCustomField($coordinates, $objectToReturn, $apiObject, 'coordinates');
        HQRentalsTransformersBase::resolveSingleCustomField($image, $objectToReturn, $apiObject, 'image');
        HQRentalsTransformersBase::resolveSingleCustomField($description, $objectToReturn, $apiObject, 'description');
        HQRentalsTransformersBase::resolveSingleCustomField($hours, $objectToReturn, $apiObject, 'officeHours');
        HQRentalsTransformersBase::resolveSingleCustomField($addressLabel, $objectToReturn, $apiObject, 'addressLabel');
        HQRentalsTransformersBase::resolveSingleCustomField($brands, $objectToReturn, $apiObject, 'brands');
        HQRentalsTransformersBase::resolveSingleCustomField($address, $objectToReturn, $apiObject, 'address');
        HQRentalsTransformersBase::resolveSingleCustomField($phone, $objectToReturn, $apiObject, 'phone');
    }

    public static function resolveSingleCustomField($settingField, $objectToReturn, $apiObject, $newPropertyName)
    {
        if (!empty($settingField)) {
            $objectToReturn->{$newPropertyName} = HQRentalsTransformersBase::resolveSingleAttribute($apiObject->{$settingField});
        }
    }
}
