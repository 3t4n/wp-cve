<?php

namespace HQRentalsPlugin\HQRentalsQueries;

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsQueriesBrands extends HQRentalsQueriesBaseClass
{
    public function __construct()
    {
        $this->model = new HQRentalsModelsBrand();
    }

    public function getAllMetaKey()
    {
        return 'hq_wordpress_brand_all_for_frontend';
    }

    public function getAllBrands()
    {
        $brandsPosts = $this->model->all();
        return $this->fillModelWithPosts($brandsPosts);
    }

    /**
     * Data to use on the Front End
     * Can be retrieves as hqRentalsBrands
     * @return array
     */
    public function allToFrontEnd()
    {
        $brandsPost = $this->model->all();
        $queryLocation = new HQRentalsQueriesLocations();
        $data = [];
        foreach ($brandsPost as $post) {
            $brand = new HQRentalsModelsBrand($post);
            $newData = new \stdClass();
            $newData->id = $brand->id;
            $newData->name = $brand->name;
            $newData->iframePageURL = $brand->websiteLink;
            $newData->locations = $queryLocation->getLocationsForBrandsFrontEnd($brand->id);
            $data[] = $newData;
        }
        return $data;
    }

    public function fillModelWithPosts($posts)
    {
        $data = [];
        foreach ($posts as $post) {
            $data[] = new HQRentalsModelsBrand($post);
        }
        return $data;
    }

    public function brandsPublicInterface()
    {
        $brands = $this->getAllBrands();
        return array_map(function ($brand) {
            return $this->brandPublicInterface($brand);
        }, $brands);
    }

    public function singleBrandPublicInterface($brandId)
    {
        $brand = $this->getBrand($brandId);
        return $this->brandPublicInterface($brand);
    }

    public function brandPublicInterface($brand)
    {
        $queryLocation = new HQRentalsQueriesLocations();
        return $this->parseObject(array(
            'id',
            'name',
            'websiteLink',
            'locations' => array(
                'property_name' => 'locations',
                'values' => $queryLocation->getLocationsForBrandsFrontEnd($brand->id)
            ),
            'makes' => array(
                'property_name' => 'makes',
                'values' => 'dadas'
            ),
            'vehicleClasses' => array(
                'property_name' => 'classes',
                'values' => 'classes'
            )
        ), $brand);
    }

    public function getBrand($brandId)
    {
        $args = array_merge(
            $this->model->postArgs,
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->model->metaBrandId,
                        'value' => $brandId,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return new HQRentalsModelsBrand($query->posts[0]);
    }

    public function getBrandByUUID($uuid)
    {
        $args = array_merge(
            $this->model->postArgs,
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->model->getUUIDMetaKey(),
                        'value' => $uuid,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return new HQRentalsModelsBrand($query->posts[0]);
    }
}
