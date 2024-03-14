<?php

namespace HQRentalsPlugin\HQRentalsQueries;

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsLocation;

class HQRentalsQueriesLocations extends HQRentalsQueriesBaseClass
{
    public function __construct()
    {
        $this->model = new HQRentalsModelsLocation();
    }

    public function allLocations()
    {
        $locations = $this->model->all();
        return $this->fillModelWithPosts($locations);
    }

    public function getAllMetaKey()
    {
        return 'hq_wordpress_location_all_for_frontend';
    }

    public function allToFrontEnd()
    {
        $locationsPost = $this->model->all();
        $data = [];
        foreach ($locationsPost as $post) {
            $location = new HQRentalsModelsLocation($post);
            $data[] = $this->locationPublicInterface($location);
        }
        return $data;
    }

    public function fillModelWithPosts($posts)
    {
        $data = [];
        foreach ($posts as $post) {
            $location = new HQRentalsModelsLocation($post);
            $data[] = $location;
        }
        return $data;
    }

    public function getLocationsByBrand($brandId)
    {
        $args = array_merge(
            $this->model->postArgs,
            array(
                'meta_query' => array(
                    array(
                        'value' => $brandId,
                        'compare' => '=',
                        'key' => $this->model->getMetaKeyFromBrandID()
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        $data = [];
        foreach ($query->posts as $post) {
            $data[] = new HQRentalsModelsLocation($post);
        }
        return $data;
    }

    public function getLocationsForBrandsFrontEnd($brandId)
    {
        $location = $this->getLocationsByBrand($brandId);
        return array_map(function ($location) {
            $newObject = new \stdClass();
            $newObject->id = $location->id;
            $newObject->name = $location->name;
            $newObject->coordinates = $location->coordinates;
            $newObject->label = $location->getLabelForWebsite();
            return $newObject;
        }, $location);
    }

    public function locationsPublicInterface()
    {
        $locations = $this->allLocations();
        return array_map(function ($location) {
            return $this->locationPublicInterface($location);
        }, $locations);
    }

    public function locationPublicInterface($location)
    {
        return $this->parseObject(array(
            'id',
            'name' => array(
                'property_name' => 'name',
                'values' => $location->getLabelForWebsite() ?? $location->getName()
            ),
            'coordinates',
            'brand_id' => array(
                'property_name' => 'brand_id',
                'values' => $location->getBrandId()
            ),
            'address' => array(
                'property_name' => 'address',
                'values' => $location->getCustomFieldForAddress()
            ),
            'office_hours' => array(
                'property_name' => 'office_hours',
                'values' => $location->getCustomFieldForOfficeHours()
            ),
            'phone' => array(
                'property_name' => 'phone',
                'values' => $location->getCustomFieldForPhone()
            )

        ), $location);
    }
}
