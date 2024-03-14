<?php

namespace HQRentalsPlugin\HQRentalsQueries;

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsFeature;

class HQRentalsQueriesFeatures extends HQRentalsQueriesBaseClass
{
    public function __construct()
    {
        $this->model = new HQRentalsModelsFeature();
    }

    public function getVehicleClassFeatures($classId)
    {
        $data = array();
        $args = array_merge(
            $this->model->postArgs,
            array(
                'posts_per_page' => -1,
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                //'meta_key'          =>  $this->model->getOrderMetaKey(),
                'meta_query' => array(
                    array(
                        'key' => $this->model->getVehicleClassIdMetaKey(),
                        'value' => $classId,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        foreach ($query->posts as $post) {
            $data[] = new HQRentalsModelsFeature($post);
        }
        return $data;
    }

    public function fillModelWithPosts($posts)
    {
        // TODO: Implement fillModelWithPosts() method.
    }

    public function allToFrontEnd()
    {
        // TODO: Implement allToFrontEnd() method.
    }

    public function getAllMetaKey()
    {
        // TODO: Implement getAllMetaKey() method.
    }

    public function featurePublicInterface($feature)
    {
        return $this->parseObject(array(
            'label',
            'icon'
        ), $feature);
    }

    public function featuresPublicInterfaceWithLocale($features)
    {
        return array_map(function ($feature) {
            return $this->parseObject(array(
                array(
                    'property_name' => 'label',
                    'values' => $feature->getLabelsForWebsite()
                ),
                'icon'
            ), $feature);
        }, $features);
    }

    public function featuresPublicInterface($features = null)
    {
        return array_map(function ($feature) {
            return $this->featurePublicInterface($feature);
        }, $features);
    }
}
