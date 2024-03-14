<?php

namespace HQRentalsPlugin\HQRentalsQueries;

use HQRentalsPlugin\HQRentalsHelpers\HQRentalsCacheHandler;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleClass;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsQueriesVehicleClasses extends HQRentalsQueriesBaseClass
{
    /**
     * HQRentalsQueriesVehicleClasses constructor.
     */
    public function __construct()
    {
        $this->model = new HQRentalsModelsVehicleClass();
        $this->rateQuery = new HQRentalsQueriesActiveRates();
        $this->cache = new HQRentalsCacheHandler();
        $this->settings = new HQRentalsSettings();
    }


    /***
     * Return all vehicles classes order by daily rate
     * @param null $order
     * @return array
     */
    public function allVehicleClasses($order = null)
    {
        $cacheData = $this->cache->getVehicleClassesFromCache();
        if ($cacheData) {
            /* Return data if Cache*/
            return $cacheData;
        }
        return $this->allVehiclesByRate();
    }

    public function allVehiclesWithoutOrder()
    {
        $args = $this->model->postArgs;
        $query = new \WP_Query($args);
        return $this->fillModelWithPosts($query->posts);
    }

    public function allVehiclesByRate()
    {
        $vehicles = $this->allVehiclesWithoutOrder();
        $option = $this->settings->isDecreasingRateOrderActive();
        if (is_array($vehicles)) {
            usort($vehicles, function ($oneVehicle, $otherVehicle) use ($option) {
                if ($option) {
                    return $oneVehicle->rate()->getFormattedDailyRateAsNumber() < $otherVehicle->rate()->getFormattedDailyRateAsNumber();
                } else {
                    return $oneVehicle->rate()->getFormattedDailyRateAsNumber() > $otherVehicle->rate()->getFormattedDailyRateAsNumber();
                }
            });
        }
        return $vehicles;
    }

    /**
     * Retrieve classes by order
     * @return array
     */
    public function allVehicleClassesByOrder()
    {
        $args = $this->resolveQueryArray(
            array(
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => $this->model->getOrderMetaKey(),
            )
        );
        $query = new \WP_Query($args);
        return $this->fillModelWithPosts($query->posts);
    }

    /**
     * Retrieve vehicle class id from WP Post
     * @param $post
     * @return string
     */
    public function getVehicleClassIdFromPost($post)
    {
        if (get_post_type($post) === 'hqwp_veh_classes') {
            $class = new HQRentalsModelsVehicleClass($post);
            return $class->id;
        } else {
            return '';
        }
    }


    public function getVehicleClassFilterByCustomField($dbColumn, $value)
    {
        $args = $this->resolveQueryArray(
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->model->getCustomFieldMetaPrefix() . $dbColumn,
                        'value' => $value,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return $this->fillModelWithPosts($query->posts);
    }


    /**
     * Retrieve vehicle class by system id
     * @param $hqId
     * @return HQRentalsModelsVehicleClass
     */
    public function getVehicleClassBySystemId($hqId)
    {
        $args = $this->resolveQueryArray(
            array(
                'meta_query' => array(
                    array(
                        'key' => 'hq_wordpress_vehicle_class_id_meta',
                        'value' => $hqId,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        if ($query->posts[0]) {
            return new HQRentalsModelsVehicleClass($query->posts[0]);
        } else {
            return null;
        }
    }

    /**
     * Retrieve all vehicle classes to front end
     * @return array
     */
    public function allToFrontEnd()
    {
        $vehiclesPost = $this->model->all();
        $data = [];
        foreach ($vehiclesPost as $post) {
            $vehicle = new HQRentalsModelsVehicleClass($post);
            $newData = new \stdClass();
            $newData->id = $vehicle->id;
            $newData->name = $vehicle->name;
            $newData->permalink = $vehicle->permalink;
            $data[] = $newData;
        }
        return $data;
    }


    /**
     * Retrieves all distinct meta value from the vehicle classes
     * @param $dbColumn
     * @return array|null|object
     */
    public function getAllDifferentsValuesFromCustomField($dbColumn)
    {
        global $wpdb;
        $queryString = "SELECT DISTINCT(meta_value)
                    FROM {$wpdb->prefix}postmeta 
                    WHERE meta_key = '" . $this->model->getCustomFieldMetaPrefix() . $dbColumn . "'
                    ";
        $data = $wpdb->get_results($queryString, ARRAY_A);
        return array_filter($data, function ($item) {
            return $item['meta_value'] != 'N;';
        });
    }

    /***
     * Retrieve Cheaspest classes from custom field value
     * @param $dbColumn
     * @return array
     */
    public function getCheapestClassesFromCustomField($dbColumn)
    {
        $customFieldsValues = $this->getAllDifferentsValuesFromCustomField($dbColumn);
        $data = [];
        foreach ($customFieldsValues as $value) {
            $data[] = $this->getCheapestClassFromCustomFieldValue($dbColumn, $value['meta_value']);
        }
        return $data;
    }

    public function getCheapestClassesFromCustomFieldAndPriceIntervals($dbColumn)
    {
        $customFieldsValues = $this->getAllDifferentsValuesFromCustomField($dbColumn);
        $data = [];
        foreach ($customFieldsValues as $value) {
            $data[] = $this->getCheapestClassFromCustomFieldValueAndPriceInterval($dbColumn, $value['meta_value']);
        }
        return $data;
    }

    /**
     * Retrieve vehicle classes filter by a single custom field value
     * @param $dbColumn
     * @param $value
     * @return array
     */
    public function getClassFromCustomField($dbColumn, $value)
    {
        $args = $this->resolveQueryArray(
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->model->getCustomFieldMetaPrefix() . $dbColumn,
                        'value' => $value,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return $this->fillModelWithPosts($query->posts);
    }

    public function getClassesFilterByCustomField($dbColumn, $value)
    {
        $args = $this->resolveQueryArray(
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->model->getCustomFieldMetaPrefix() . $dbColumn,
                        'value' => $value,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return $query->posts;
    }

    /***
     * Retrieve a single vehicle class filter by custom field
     * @param $dbColumn
     * @param $value
     * @return array|HQRentalsModelsVehicleClass
     */
    public function getCheapestClassFromCustomFieldValue($dbColumn, $value)
    {
        $args = $this->resolveQueryArray(
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->model->getCustomFieldMetaPrefix() . $dbColumn,
                        'value' => $value,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        if (empty($query->posts)) {
            return array();
        } else {
            $cheapestPost = new HQRentalsModelsVehicleClass($query->posts[0]);
        }
        foreach ($query->posts as $post) {
            $newClass = new HQRentalsModelsVehicleClass($post);
            if ($cheapestPost->rate()->getFormattedDailyRateAsNumber() > $newClass->rate()->getFormattedDailyRateAsNumber()) {
                $cheapestPost = $newClass;
            }
        }
        return $cheapestPost;
    }

    /**
     * Retrieve a single class filter by custom field and based on price intervals
     * @param $dbColumn
     * @param $value
     * @return array|HQRentalsModelsVehicleClass
     */
    public function getCheapestClassFromCustomFieldValueAndPriceInterval($dbColumn, $value)
    {
        $args = $this->resolveQueryArray(
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->model->getCustomFieldMetaPrefix() . $dbColumn,
                        'value' => $value,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);

        if (empty($query->posts)) {
            return array();
        } else {
            $cheapestPost = new HQRentalsModelsVehicleClass($query->posts[0]);
        }
        foreach ($query->posts as $post) {
            $newClass = new HQRentalsModelsVehicleClass($post);
            if ($cheapestPost->getCheapestPriceInterval()->getPriceAsANumber() > $newClass->getCheapestPriceInterval()->getPriceAsANumber()) {
                $cheapestPost = $newClass;
            }
        }
        return $cheapestPost;
    }

    public function getAllMetaKey()
    {
        return 'hq_wordpress_vehicle_class_all_for_frontend';
    }

    /***
     * Fills Models with Posts
     * @param $posts
     * @return array
     */
    public function fillModelWithPosts($posts)
    {
        $data = [];
        foreach ($posts as $post) {
            $data[] = new HQRentalsModelsVehicleClass($post);
        }
        return $data;
    }

    /***
     * Retrieve ids from vehicles classes filtering by custom field
     *
     * @param $dbColumn
     * @param $value
     * @return array
     */
    public function getVehiclesIdsFromCustomField($dbColumn, $value)
    {
        $classes = $this->getVehicleClassFilterByCustomField($dbColumn, $value);
        $data = [];
        foreach ($classes as $class) {
            $data[] = $class->id;
        }
        return $data;
    }

    public function getVehicleClassesByBrand($brandId)
    {
        $args = $this->resolveQueryArray(
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->model->getBrandIdMetaKey(),
                        'value' => $brandId,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return $this->fillModelWithPosts($query->posts);
    }

    public function vehiclesPublicInterface($brandId = null)
    {
        if (empty($brandId)) {
            $vehicles = $this->allVehicleClasses();
        } else {
            $vehicles = $this->getVehicleClassesByBrand($brandId);
        }
        return array_map(function ($vehicle) {
            return $this->vehiclePublicInterface($vehicle);
        }, $vehicles);
    }

    public function vehiclePublicInterface($vehicle)
    {
        return $this->parseObject(array(
            'id',
            'name',
            'publicImageLink',
            'order',
            'brandId',
            'permalink' => array(
                'property_name' => 'permalink',
                'values' => get_permalink($vehicle->postId)
            ),
            'labels' => array(
                'property_name' => 'labels',
                'values' => $vehicle->getLabels()
            ),
            'descriptions' => array(
                'property_name' => 'descriptions',
                'values' => $vehicle->getDescriptions()
            ),
            'custom_fields' => array(
                'property_name' => 'custom_fields',
                'values' => $vehicle->getCustomFields()
            ),
            'features' => array(
                'property_name' => 'features',
                'values' => $vehicle->getFeaturesPublicInterface()
            ),
            'rate' => array(
                'property_name' => 'rate',
                'values' => $vehicle->getRatePublicInterface()
            ),
        ), $vehicle);
    }

    public function vehiclesPublicInterfaceFiltered($brandId, $customField, $customFieldValue)
    {
        $vehicles = $this->getVehiclesByBrandAndCustomField($brandId, $customField, $customFieldValue);
        return array_map(function ($vehicle) use ($brandId) {
            return $this->vehiclePublicInterface($vehicle);
        }, $vehicles);
    }

    public function getVehiclesByBrandAndCustomField($brandId, $customField, $customFieldValue)
    {
        $args = $this->resolveQueryArray(
            array(
                'meta_query' => array(
                    'relation' => 'AND',
                    'custom_field_clause' => array(
                        'key' => $this->model->getCustomFieldMetaPrefix() . $customField,
                        'value' => $customFieldValue,
                        'compare' => '='
                    ),
                    'brand_clause' => array(
                        'key' => $this->model->getBrandIdMetaKey(),
                        'value' => $brandId,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return $this->fillModelWithPosts($query->posts);
    }

    protected function resolveQueryArray(array $metaQueryArray)
    {
        return array_merge(
            $this->model->postArgs,
            $metaQueryArray
        );
    }

    public function vehiclesPublicInterfaceFromHQDatesApi($classes)
    {
        if (is_array($classes)) {
            return array_map(function ($vehicle) {
                return $this->vehiclePublicInterfaceFromHQDatesApi($vehicle);
            }, $classes);
        } else {
            return [];
        }
    }

    public function vehiclePublicInterfaceFromHQDatesApi($vehicle)
    {
        $data = new \stdClass();
        $model = $this->getVehicleClassBySystemId($vehicle->vehicle_class_id);
        $data->id = $vehicle->vehicle_class_id;
        $data->rate = ($vehicle->price->details) ? $vehicle->price->details[0] : null;
        $data->price = ($vehicle->price) ? $vehicle->price : null;
        $data->permalink = get_permalink($model->postId);
        $data->features = $vehicle->vehicle_class->features;
        $data->label = $vehicle->vehicle_class->label;
        $data->short_description = $vehicle->vehicle_class->short_description;
        $data->description = $vehicle->vehicle_class->description;
        $data->image = $vehicle->vehicle_class->image;
        $data->label = $vehicle->vehicle_class->label;
        return $data;
    }
}
