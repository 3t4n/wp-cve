<?php

namespace HQRentalsPlugin\HQRentalsModels;

use HQRentalsPlugin\HQRentalsDb\HQRentalsDbManager;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsLocaleHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesBrands;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesCarRentalSetting;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesBrands;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesFeatures;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsModelsVehicleClass extends HQRentalsBaseModel
{
    public static $custom_fields = [];
    private static $additionalChargeForExceededDistanceType = 'additional_charge_for_exceeded_distance';
    public $vehicleClassesCustomPostName = 'hqwp_veh_classes';
    public $vehicleClassesCustomPostSlug = 'vehicle-classes';
    private $tableName = 'hq_vehicle_classes';
    private $columns = array(
        array(
            'column_name' => 'id',
            'column_data_type' => 'int'
        ),
        array(
            'column_name' => 'name',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'public_image_link',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'vehicle_class_order',
            'column_data_type' => 'int'
        ),
        array(
            'column_name' => 'brand_id',
            'column_data_type' => 'int'
        ),
        array(
            'column_name' => 'label_for_website',
            'column_data_type' => 'LONGTEXT'
        ),
        array(
            'column_name' => 'short_description_for_website',
            'column_data_type' => 'LONGTEXT'
        ),
        array(
            'column_name' => 'description_for_website',
            'column_data_type' => 'LONGTEXT'
        ),
        array(
            'column_name' => 'images',
            'column_data_type' => 'LONGTEXT'
        ),
        array(
            'column_name' => 'active_rates',
            'column_data_type' => 'LONGTEXT'
        ),
        array(
            'column_name' => 'features',
            'column_data_type' => 'LONGTEXT'
        ),
        array(
            'column_name' => 'custom_fields',
            'column_data_type' => 'LONGTEXT'
        ),
        array(
            'column_name' => 'updated_at',
            'column_data_type' => 'varchar(50)'
        )
    );

    protected $metaId = 'hq_wordpress_vehicle_class_id_meta';
    protected $metaBrandId = 'hq_wordpress_vehicle_class_brand_id_meta';
    protected $metaName = 'hq_wordpress_vehicle_class_name_meta';
    protected $metaUUID = 'hq_wordpress_vehicle_class_uuid_meta';
    protected $metaOrder = 'hq_wordpress_vehicle_class_order_meta';
    protected $metaAvailableOnWebsite = 'hq_wordpress_vehicle_class_available_on_website_meta';
    protected $metaPublicImageLink = 'hq_wordpress_vehicle_class_public_image_link_meta';
    protected $metaLabelForWebsite = 'hq_wordpress_vehicle_class_label_for_website_meta';
    protected $metashortDescriptionForWebiste = 'hq_wordpress_vehicle_class_short_description_meta';
    protected $metaDescriptionForWebiste = 'hq_wordpress_vehicle_class_description_for_webiste_meta';
    protected $metaForRate = 'hq_wordpress_vehicle_class_rate_meta';
    protected $metaCustomField = 'hq_wordpress_vehicle_class_custom_field_';
    protected $metaDistanceLimit = 'hq_wordpress_vehicle_class_distance_limit_meta';
    protected $metaDistanceLimitPerDay = 'hq_wordpress_vehicle_class_distance_limit_per_day_meta';
    protected $metaDistanceLimitPerWeek = 'hq_wordpress_vehicle_class_distance_limit_per_week_meta';
    protected $metaDistanceLimitPerMonth = 'hq_wordpress_vehicle_class_distance_limit_per_month_meta';

    public $id = '';
    public $postId = '';
    public $brandId = '';
    public $name = '';
    public $uuid = '';
    public $order = '';
    public $availableOnWebsite = '';
    public $publicImageLink = '';
    public $labels = [];
    public $shortDescriptions = [];
    public $descriptions = [];
    public $images = [];
    public $features = [];
    public $rate = [];
    public $customField = [];
    public $permalink = '';
    public $priceIntervals = [];
    public $rates = [];
    public $imageForDB = '';
    public $activeRateDB = '';
    public $featuresDB = '';
    public $imagesDB = '';
    public $customFields = null;
    public $distanceLimit = '';
    public $distanceLimitPerDay = '';
    public $distanceLimitPerWeek = '';
    public $distanceLimitPerMonth = '';
    public $additionalChargeForExceededDistance = null;
    public $updated_at = '';

    public function __construct($post = null)
    {
        $this->post_id = '';
        $this->locale = new HQRentalsLocaleHelper();
        $this->queryFeatures = new HQRentalsQueriesFeatures();
        $this->pluginSettings = new HQRentalsSettings();
        $this->activeRate = new HQRentalsModelsActiveRate();
        $this->customFields = new \stdClass();
        $this->postArgs = [
            'post_type' => $this->vehicleClassesCustomPostName,
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ];
        $this->labelsPost = [
            'name' => _x('Vehicle Classes', 'post type general name', 'hq-rental-software'),
            'singular_name' => _x('Vehicle Class', 'post type singular name', 'hq-rental-software'),
            'menu_name' => _x('Vehicle Classes', 'admin menu', 'hq-rental-software'),
            'name_admin_bar' => _x('Vehicle Class', 'add new on admin bar', 'hq-rental-software'),
            'add_new' => _x('Add New', 'brand', 'hq-rental-software'),
            'add_new_item' => __('Add New Vehicle Class', 'hq-rental-software'),
            'new_item' => __('New Vehicle Class', 'hq-rental-software'),
            'edit_item' => __('Edit Vehicle Class', 'hq-rental-software'),
            'view_item' => __('View Vehicle Class', 'hq-rental-software'),
            'all_items' => __('All Vehicle Classes', 'hq-rental-software'),
            'search_items' => __('Search Vehicle Classes', 'hq-rental-software'),
            'parent_item_colon' => __('Parent Vehicle Classes', 'hq-rental-software'),
            'not_found' => __('No vehicles classes found.', 'hq-rental-software'),
            'not_found_in_trash' => __('No vehicles classes found in Trash.', 'hq-rental-software'),
        ];
        $this->customPostArgs = [
            'labels' => $this->labelsPost,
            'public' => true,
            'show_in_admin_bar' => true,
            'publicly_queryable' => $this->pluginSettings->isEnableCustomPostsPages(),
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => ['slug' => $this->vehicleClassesCustomPostSlug],
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'menu_icon' => 'dashicons-thumbs-up',
            'menu_position' => 8,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'capabilities' => [
                'create_posts' => 'do_not_allow',
            ],
        ];
        $this->db = new HQRentalsDbManager();
        if (!empty($post)) {
            $this->setFromPost($post);
        }
    }

    public function setVehicleClassFromApi($data, $customFields = null)
    {
        $this->id = $data->id;
        $this->brandId = $data->brand->id;
        $this->name = $data->name;
        $this->uuid = $data->uuid;
        $this->order = $data->order;
        $this->publicImageLink = $data->public_image_link;
        $this->imageForDB = '';
        $this->activeRateDB = '';
        $this->featuresDB = '';
        if (!empty($data->label_for_website)) {
            foreach ($data->label_for_website as $key => $label) {
                $this->labels[$key] = $label;
            }
        }

        if (!empty($data->short_description_for_website)) {
            foreach ($data->short_description_for_website as $key => $shortDescription) {
                $this->shortDescriptions[$key] = $shortDescription;
            }
        }
        if (!empty($data->description_for_website)) {
            foreach ($data->description_for_website as $key => $description) {
                $this->descriptions[$key] = $description;
            }
        }

        if (!empty($data->images)) {
            foreach ($data->images as $key => $value) {
                $newImage = new HQRentalsModelsVehicleClassImage();
                $newImage->setVehicleClassImageFromApi($this->id, $value, ((int) $key) + 1);
                $this->images[] = $newImage;
            }
        }
        $this->featuresDB = $data->features;
        $this->activeRateDB = $data->activeRates;
        $this->imagesDB = $data->images;
        $this->updated_at = current_time('mysql', 1);
        foreach ($data->features as $feature) {
            $newFeature = new HQRentalsModelsFeature();
            $newFeature->setFeatureFromApi($this->id, $feature);
            $this->features[] = $newFeature;
        }
        if (!empty($data->activeRates)) {
            foreach ($data->activeRates as $rate) {
                $newRate = new HQRentalsModelsActiveRate();
                $newRate->setActiveRateFromApi($this->id, $rate);
                $this->rate[] = $newRate;
                if (is_array($rate->price_intervals)) {
                    if (count($rate->price_intervals) > 0) {
                        foreach ($rate->price_intervals as $price) {
                            $newPrice = new HQRentalsModelsPriceInterval();
                            $newPrice->setIntervalRateFromApi($price, $this->id);
                            $this->priceIntervals[] = $newPrice;
                        }
                    }
                }
            }
            if (isset($data->activeRates[0])) {
                $types = ['minute_rate', 'hourly_rate', 'daily_rate', 'weekly_rate', 'monthly_rate'];
                foreach ($types as $type) {
                    if (isset($data->activeRates[0]->{$type})) {
                        $rate = $data->activeRates[0]->{$type};
                        $this->rates[$type] = $rate->amount_for_display;
                        $this->rates[$type . '_no_format'] = $rate->amount;
                        $this->rates[$type . '_no_decimals'] = $rate->currency_icon . round($rate->amount, 0);
                    }
                }
            }
        }
        if (!empty($customFields->data)) {
            foreach ($customFields->data as $custom_field) {
                $this->{$this->metaCustomField . $custom_field->dbcolumn} = $data->{$custom_field->dbcolumn};
                $this->customFields->{$custom_field->dbcolumn} = $data->{$custom_field->dbcolumn};
            }
        }
        $this->distanceLimit = $data->distance_limit;
        $this->distanceLimitPerDay = $data->distance_limit_per_day;
        $this->distanceLimitPerWeek = $data->distance_limit_per_week;
        $this->distanceLimitPerMonth = $data->distance_limit_per_month;
        if (!empty($data->additional_charge_for_exceeded_distance->id)) {
            $this->additionalChargeForExceededDistance = new HQRentalsModelsVehicleCharge();
            $this->additionalChargeForExceededDistance->setVehicleChargeFromApi(
                $data->additional_charge_for_exceeded_distance,
                $this->id,
                HQRentalsModelsVehicleClass::$additionalChargeForExceededDistanceType
            );
        }
    }

    public function create()
    {
        $this->postArgs = array_merge(
            $this->postArgs,
            [
                'post_title' => $this->name,
                'post_name' => $this->name,
                'post_content' => $this->descriptions['en'] . $this->shortDescriptions['en'],
            ]
        );
        $post_id = wp_insert_post($this->postArgs);
        $this->post_id = $post_id;
        hq_update_post_meta($post_id, $this->metaId, $this->id);
        hq_update_post_meta($post_id, $this->metaBrandId, $this->brandId);
        hq_update_post_meta($post_id, $this->metaName, $this->name);
        hq_update_post_meta($post_id, $this->metaUUID, $this->uuid);
        hq_update_post_meta($post_id, $this->metaOrder, $this->order);
        hq_update_post_meta($post_id, $this->metaAvailableOnWebsite, $this->availableOnWebsite);
        hq_update_post_meta($post_id, $this->metaPublicImageLink, $this->publicImageLink);
        foreach ($this->labels as $key => $value) {
            hq_update_post_meta($post_id, $this->metaLabelForWebsite . '_' . $key, $value);
        }
        foreach ($this->shortDescriptions as $key => $value) {
            hq_update_post_meta($post_id, $this->metashortDescriptionForWebiste . '_' . $key, $value);
        }
        foreach ($this->descriptions as $key => $value) {
            hq_update_post_meta($post_id, $this->metaDescriptionForWebiste . '_' . $key, $value);
        }
        foreach ($this->rates as $key => $value) {
            hq_update_post_meta($post_id, $this->metaForRate . '_' . $key, $value);
        }
        foreach ($this->features as $feature) {
            $feature->create();
        }
        foreach ($this->images as $image) {
            $image->create();
        }
        foreach (static::$custom_fields as $custom_field) {
            hq_update_post_meta($post_id, $this->metaCustomField . $custom_field, $this->{$this->metaCustomField . $custom_field});
        }
        if (!empty($this->rate)) {
            foreach ($this->rate as $rate) {
                if ($rate instanceof HQRentalsModelsActiveRate) {
                    $rate->create();
                    $rate->setDBFromAPI($this->id, $rate);
                }
            }
        }
        if (!empty($this->priceIntervals)) {
            foreach ($this->priceIntervals as $price) {
                if (!empty($price)) {
                    $price->create();
                }
            }
        }
        hq_update_post_meta($post_id, $this->metaDistanceLimit, $this->distanceLimit);
        hq_update_post_meta($post_id, $this->metaDistanceLimitPerDay, $this->distanceLimitPerDay);
        hq_update_post_meta($post_id, $this->metaDistanceLimitPerWeek, $this->distanceLimitPerWeek);
        hq_update_post_meta($post_id, $this->metaDistanceLimitPerMonth, $this->distanceLimitPerMonth);
        if ($this->additionalChargeForExceededDistance) {
            $this->additionalChargeForExceededDistance->setVehicleClassPostId($post_id);
            $this->additionalChargeForExceededDistance->create();
        }
    }

    public function all()
    {
        $query = new \WP_Query($this->postArgs);
        return $query->posts;
    }

    public function getAllMetaTags()
    {
        return [
            'id' => $this->metaId,
            'brandId' => $this->metaBrandId,
            'name' => $this->metaName,
            'order' => $this->metaOrder,
            'availableOnWebsite' => $this->metaAvailableOnWebsite,
            'publicImageLink' => $this->metaPublicImageLink,
        ];
    }

    public function setFromPost($post)
    {
        $this->postId = $post->ID;
        $this->name = $post->post_name;
        $labelsMetaKeys = $this->getMetaKeysFromLabel();
        $shortDescriptionKeys = $this->getMetaKeysFromShortDescription();
        $descriptionsKeys = $this->getMetaKeysFromDescription();
        foreach ($this->getAllMetaTags() as $property => $metakey) {
            if (!in_array($property, ['labels', 'shortDescriptions', 'descriptions'])) {
                $this->{$property} = get_post_meta($post->ID, $metakey, true);
            }
        }
        foreach ($labelsMetaKeys as $key => $value) {
            $metakey = explode('_', $value[0]);
            $this->labels[end($metakey)] = get_post_meta($post->ID, $value[0], true);
        }
        foreach ($shortDescriptionKeys as $key => $value) {
            $metakey = explode('_', $value[0]);
            $this->shortDescriptions[end($metakey)] = get_post_meta($post->ID, $value[0], true);
        }
        foreach ($descriptionsKeys as $key => $value) {
            $metakey = explode('_', $value[0]);
            $this->descriptions[end($metakey)] = get_post_meta($post->ID, $value[0], true);
        }
        $this->permalink = get_permalink($post->ID);
        $this->distanceLimit = get_post_meta($post->ID, $this->metaDistanceLimit, true);
        $this->distanceLimitPerDay = get_post_meta($post->ID, $this->metaDistanceLimitPerDay, true);
        $this->distanceLimitPerWeek = get_post_meta($post->ID, $this->metaDistanceLimitPerWeek, true);
        $this->distanceLimitPerMonth = get_post_meta($post->ID, $this->metaDistanceLimitPerMonth, true);
        $this->additionalChargeForExceededDistance = $this->getDistanceCharge();
        $this->uuid = get_post_meta($post->ID, $this->metaUUID, true);
    }

    public function getMetaKeysFromLabel()
    {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT DISTINCT(meta_key)
                    FROM {$wpdb->prefix}postmeta 
                    WHERE meta_key 
                    LIKE '{$this->metaLabelForWebsite}%'
                    ",
            ARRAY_N
        );
    }

    public function getMetaKeysFromShortDescription()
    {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT DISTINCT(meta_key)
                    FROM {$wpdb->prefix}postmeta 
                    WHERE meta_key 
                    LIKE '{$this->metashortDescriptionForWebiste}%'
                    ",
            ARRAY_N
        );
    }

    public function getMetaKeysFromDescription()
    {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT DISTINCT(meta_key)
                    FROM {$wpdb->prefix}postmeta 
                    WHERE meta_key 
                    LIKE '{$this->metaDescriptionForWebiste}%'
                    ",
            ARRAY_N
        );
    }

    public function rate()
    {
        return new HQRentalsModelsActiveRate($this->id);
    }

    public function rates()
    {
        try {
            $rateModel = new HQRentalsModelsActiveRate();
            return $rateModel->allRatesFromVehicleClass($this->id);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getPriceIntervals()
    {
        $prices = new HQRentalsModelsPriceInterval();
        $data = [];
        foreach ($prices->getIntervalPricesByVehicleId($this->id) as $pricePost) {
            $data[] = new HQRentalsModelsPriceInterval($pricePost);
        }
        return $data;
    }

    public function images()
    {
        $images = new HQRentalsModelsVehicleClassImage();
        $imagesForReturn = [];
        foreach ($images->getImagesPostByVehicleClassID($this->id) as $post) {
            $imagesForReturn[] = new HQRentalsModelsVehicleClassImage($post);
        }

        return $imagesForReturn;
    }

    public function getImage()
    {
        $imageModel = new HQRentalsModelsVehicleClassImage();
        $imagePost = $imageModel->getImageFromPostByVehicleClassID($this->id);
        return new HQRentalsModelsVehicleClassImage($imagePost);
    }

    public function features()
    {
        $query = new HQRentalsQueriesFeatures();
        return $query->getVehicleClassFeatures($this->id);
    }

    public function getDescription($forced_locale = null)
    {
        if (!empty($forced_locale)) {
            return $this->descriptions[$forced_locale];
        } else {
            return $this->descriptions[$this->locale->language];
        }
    }

    public function getLabel($forcedLocale = null)
    {
        if (!empty($forcedLocale)) {
            return $this->labels[$forcedLocale];
        } else {
            if ($this->locale->language === "zh") {
                return $this->labels["zh-Hans"];
            }
            return $this->labels[$this->locale->language];
        }
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function getDescriptions()
    {
        return $this->descriptions;
    }

    public function getCustomFields()
    {
        return $this->customField;
    }

    public function getShortDescription($forced_locale = null)
    {
        if (!empty($forced_locale)) {
            return $this->shortDescriptions[$forced_locale];
        } else {
            return $this->shortDescriptions[$this->locale->language];
        }
    }

    public function getCustomDataProperties()
    {
        $properties = get_object_vars($this);
        $customProperties = [];
        foreach ($properties as $key => $property) {
            if (strpos($key, $this->metaCustomField) >= 0) {
                $customProperties[] = $key;
            }
        }

        return $customProperties;
    }

    public function getCustomField($dbColumn)
    {
        return get_post_meta($this->postId, $this->metaCustomField . $dbColumn, true);
    }

    public function getTranslatableCustomField($dbColumn, $forced_locale = null)
    {
        $field = $this->getCustomField($dbColumn);
        if (!empty($forced_locale)) {
            return $field[$forced_locale];
        } else {
            try {
                $content = $field->{$this->locale->language};
            } catch (\Throwable $e) {
                $content = $field[$this->locale->language];
            }
            return ($content) ? $content : '';
        }
    }

    public function getCustomFieldMetaPrefix()
    {
        return $this->metaCustomField;
    }

    public function getVehicleClassIdMeta()
    {
        return $this->metaId;
    }

    public function getCheapestPriceInterval()
    {
        $price = new HQRentalsModelsPriceInterval();
        $cheapestPost = $price->getCheapestPriceInterval($this->id);
        $interval = new HQRentalsModelsPriceInterval($cheapestPost);
        return $interval;
    }

    public function getUsersPriceIntervalOption($cheapest = true)
    {
        $price = new HQRentalsModelsPriceInterval();
        if ($cheapest) {
            $post = $price->getCheapestPriceInterval($this->id);
        } else {
            $post = $price->getHighestPriceInterval($this->id);
        }
        $interval = new HQRentalsModelsPriceInterval($post);
        return $interval;
    }

    public function getOrderMetaKey()
    {
        return $this->metaOrder;
    }

    public function getBrandIdMetaKey()
    {
        return $this->metaBrandId;
    }

    public function getFeatureImage($size = '500')
    {
        return str_replace('size=1000', 'size=' . $size, $this->publicImageLink);
    }

    public function getFeaturesPublicInterface()
    {
        $queryFeatures = new HQRentalsQueriesFeatures();
        return $queryFeatures->featuresPublicInterfaceWithLocale($this->features());
    }

    public function getRatePublicInterface()
    {
        $rate = $this->rate();
        return $rate->ratePublicInterface();
    }

    public function getBrand()
    {
        $queryBrand = new HQRentalsQueriesBrands();
        return $queryBrand->getBrand($this->brandId);
    }

    public function getDataToCreateTable()
    {
        return array(
            'table_name' => $this->tableName,
            'table_columns' => $this->columns
        );
    }

    public function getDistanceLimit()
    {
        return $this->distanceLimit;
    }

    public function getDistanceLimitDay()
    {
        return $this->distanceLimitPerDay;
    }

    public function getDistanceLimitePerWeek()
    {
        return $this->distanceLimitPerWeek;
    }

    public function getDistanceLimitPerMonth()
    {
        return $this->distanceLimitPerMonth;
    }

    public function getDistanceCharge()
    {
        $charge = new HQRentalsModelsVehicleCharge();
        $charge->setChargeByVehicleClassPostId($this->postId);
        return $charge;
    }

    public function getUUID()
    {
        return $this->uuid;
    }

    public function saveOrUpdate(): void
    {
        $result = $this->db->selectFromTable($this->tableName, '*', 'id=' . $this->id);
        if ($result->success) {
            $resultUpdate = $this->db->updateIntoTable($this->tableName, $this->parseDataToSaveOnDB(), array('id' => $this->id));
        } else {
            $resultInsert = $this->db->insertIntoTable($this->tableName, $this->parseDataToSaveOnDB());
        }
        if (isset($this->rate) and is_array($this->rate)) {
            $rate = $this->rate[0];
            if ($rate instanceof HQRentalsModelsActiveRate) {
                $existResult = $this->db->selectFromTable($this->activeRate->getTableName(), '*', 'vehicle_class_id=' . $this->getId());
                if ($existResult->success) {
                    $resultUpdateActive = $this->db->updateIntoTable(
                        $this->activeRate->getTableName(),
                        $rate->parseDataToSaveOnDB(),
                        array('vehicle_class_id' => $this->getId())
                    );
                } else {
                    $resultInsertActive = $this->db->insertIntoTable($this->activeRate->getTableName(), $rate->parseDataToSaveOnDB());
                }
            } else {
                $resultDelete = $this->db->delete($this->activeRate->getTableName(), null, array('vehicle_class_id' => $this->getId()));
            }
        }
    }

    private function parseDataToSaveOnDB(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'brand_id' => $this->brandId,
            'public_image_link' => $this->publicImageLink,
            'vehicle_class_order' => $this->order,
            'label_for_website' => json_encode($this->labels),
            'short_description_for_website' => json_encode($this->shortDescriptions),
            'description_for_website' => json_encode($this->descriptions),
            'images' => json_encode($this->imagesDB),
            'active_rates' => json_encode($this->activeRateDB),
            'features' => json_encode($this->featuresDB),
            'custom_fields' => json_encode($this->getCustomFieldsAsArray()),
            'updated_at' => $this->updated_at
        );
    }

    public function setFromDB($vehicleDB)
    {
        $this->id = $vehicleDB->id;
        $this->name = $vehicleDB->name;
        $this->brandId = $vehicleDB->brand_id;
        $this->order = $vehicleDB->vehicle_class_order;
        $this->publicImageLink = $vehicleDB->public_image_link;
        $this->labels = json_decode($vehicleDB->label_for_website);
        $this->shortDescriptions = json_decode($vehicleDB->short_description_for_website);
        $this->descriptions = json_decode($vehicleDB->description_for_website);
        $this->images = json_decode($vehicleDB->images);
        $this->features = json_decode($vehicleDB->features);
        $this->customFields = json_decode($vehicleDB->custom_fields);
        $this->setUpdatedAt($vehicleDB->updated_at);
        $query = new HQRentalsDBQueriesCarRentalSetting();
        if ($query->getCarRentalSetting('show_prices_including_sales_tax')->settings == 1) {
            $this->calculateRatesWithTaxes($vehicleDB);
        } else {
            $this->rates = json_decode($vehicleDB->active_rates)[0] ?? [];
        }
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getCustomFieldsAsArray(): array
    {
        return (array)$this->customFields;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getPublicImage()
    {
        return $this->publicImageLink;
    }
    public function getLabelForWebsite()
    {
        if (!empty($forcedLocale)) {
            return $this->labels[$forcedLocale];
        } else {
            if ($this->locale->language === "zh") {
                return $this->labels->{"zh-Hans"};
            }
            return empty($this->labels->{$this->locale->language}) ? $this->name : $this->labels->{$this->locale->language};
        }
    }
    public function getVehicleFeatures()
    {
        return $this->features;
    }
    public function getActiveRate()
    {
        return $this->rates;
    }
    public function getShortDescriptionForWebsite($forcedLocale = null)
    {
        if (!empty($forcedLocale)) {
            return $this->shortDescriptions->{$forcedLocale};
        } else {
            if ($this->locale->language === "zh") {
                return $this->shortDescriptions->{"zh-Hans"};
            }
            return empty($this->shortDescriptions->{$this->locale->language}) ? $this->name : $this->shortDescriptions->{$this->locale->language};
        }
    }
    public function getDescriptionForWebsite()
    {
        if (!empty($forcedLocale)) {
            return $this->descriptions[$forcedLocale];
        } else {
            if ($this->locale->language === "zh") {
                return $this->descriptions->{"zh-Hans"};
            }
            return empty($this->descriptions->{$this->locale->language}) ? $this->name : $this->descriptions->{$this->locale->language};
        }
    }
    public function getPriceIntervalsForWebsite()
    {
        if (is_array($this->getPriceIntervals())) {
            $data = $this->getPriceIntervals();
            usort($data, function ($a, $b) {
                return $a->getPriceAsANumber() - $b->getPriceAsANumber();
            });
            return $data;
        }
        return $this->rates->price_intervals;
    }
    public function getCheapestPriceIntervalForWebsite()
    {
        if (is_array($this->getPriceIntervalsForWebsite())) {
            return $this->getPriceIntervalsForWebsite()[0];
        }
        return $this->getPriceIntervalsForWebsite();
    }
    public function getImageForWebsite()
    {
        return $this->images;
    }
    public function getCustomFieldForWebsite($dbColumn)
    {
        return $this->customFields->{$dbColumn};
    }
    private function transformRate($rate, HQRentalsModelsBrand $brand, $separators): \stdClass
    {
        $data = new \stdClass();
        $price = floatval($rate->amount) * $brand->getAbbTaxAsNumber();
        $amount = number_format($price, 2, $separators['decimals'], $separators['thousands']);
        $amountForDisplay = $rate->currency_icon . $amount;
        $data->currency = $rate->currency;
        $data->currency_icon = $rate->currency_icon;
        $data->amount = $amount;
        $data->usd_amount = $amount;
        $data->amount_for_display = $amountForDisplay;
        return $data;
    }
    private function calculateRatesWithTaxes($vehicleDB): void
    {
        $rates = $this->rates = json_decode($vehicleDB->active_rates)[0];
        if ($rates) {
            $brandQuery = new HQRentalsDBQueriesBrands();
            $brand = $brandQuery->getBrand($this->brandId);
            $metricSystem = $this->pluginSettings->getMetricSystem();
            $separators = array(
                'decimals' => $metricSystem === 'imperial' ? '.' : ',',
                'thousands' => $metricSystem === 'imperial' ? ',' : '.'
            );
            $this->rates->minute_rate = $this->transformRate($rates->minute_rate, $brand, $separators);
            $this->rates->hourly_rate = $this->transformRate($rates->hourly_rate, $brand, $separators);
            $this->rates->daily_rate = $this->transformRate($rates->daily_rate, $brand, $separators);
            $this->rates->weekly_rate = $this->transformRate($rates->weekly_rate, $brand, $separators);
            $this->rates->monthly_rate = $this->transformRate($rates->monthly_rate, $brand, $separators);
        } else {
            $this->rates = null;
        }
    }
}
