<?php

namespace HQRentalsPlugin\HQRentalsModels;

use HQRentalsPlugin\HQRentalsHelpers\HQRentalsDataFilter;

class HQRentalsModelsVehicleCharge extends HQRentalsBaseModel
{
    public static $custom_fields = [];
    /*
     * HQ Rental Custom Post Type Configuration
     */
    public $vehicleChargeCustomPostName = 'hqwp_veh_charge';

    protected $metaId = 'hq_wordpress_vehicle_charge_id_meta';
    protected $metaVehicleClassId = 'hq_wordpress_vehicle_charge_vehicle_class_id_meta';
    protected $metaVehicleClassPostId = 'hq_wordpress_vehicle_charge_vehicle_class_post_id_meta';
    protected $metaChargeType = 'hq_wordpress_vehicle_charge_charge_type_meta';
    protected $metaName = 'hq_wordpress_vehicle_charge_name_meta';
    protected $metaLabel = 'hq_wordpress_vehicle_charge_label_meta';
    protected $metaPrice = 'hq_wordpress_vehicle_charge_price_meta';
    protected $metaImages = 'hq_wordpress_vehicle_charge_images_meta';


    protected $id = '';
    protected $vehicle_class_id = '';
    protected $vehicle_class_post_id = '';
    protected $type = '';
    protected $name = '';
    protected $label = '';
    protected $price = '';
    protected $images = [];

    public function __construct($data = null)
    {
        $this->post_id = '';
        $this->postArgs = [
            'post_type' => $this->vehicleChargeCustomPostName,
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ];
        $this->dataChecker = new HQRentalsDataFilter();
        // not need to register post
        if ($data) {
            if ($this->dataChecker->isPost($data)) {
                $this->setFromPost($data);
            } else {
                $this->setVehicleChargeFromApi($data);
            }
        }
    }

    public function setVehicleChargeFromApi($data, $vehicle_class_id, $charge_type)
    {
        $this->id = $data->id;
        $this->vehicle_class_id = $vehicle_class_id;
        $this->type = $charge_type;
        $this->name = $data->name;
        $this->label = $data->label;
        $this->price = $data->price;
        $this->images = $data->images;
    }

    /*
     * Create Vehicle Class Custom Post
     */
    public function create()
    {
        $this->postArgs = array_merge(
            $this->postArgs,
            [
                'post_title' => $this->name,
                'post_name' => $this->name,
            ]
        );
        $post_id = wp_insert_post($this->postArgs);
        $this->post_id = $post_id;
        hq_update_post_meta($post_id, $this->metaId, $this->id);
        hq_update_post_meta($post_id, $this->metaVehicleClassId, $this->vehicle_class_id);
        hq_update_post_meta($post_id, $this->metaVehicleClassPostId, $this->vehicle_class_post_id);
        hq_update_post_meta($post_id, $this->metaChargeType, $this->type);
        hq_update_post_meta($post_id, $this->metaName, $this->name);
        hq_update_post_meta($post_id, $this->metaLabel, $this->label);
        hq_update_post_meta($post_id, $this->metaPrice, $this->price);
        hq_update_post_meta($post_id, $this->metaImages, $this->images);
    }

    /*
    * Find
    */
    public function find($caag_id)
    {
        $query = new \WP_Query($this->postArgs);
    }

    public function first()
    {
        // TODO: Implement first() method.
    }

    public function all()
    {
        $query = new \WP_Query($this->postArgs);

        return $query->posts;
    }

    public function getAllMetaTags()
    {
        return [
            'id'                    => $this->metaId,
            'vehicle_class_id'      =>  $this->metaVehicleClassId,
            'vehicle_class_post_id' =>  $this->metaVehicleClassPostId,
            'type'                  =>  $this->metaChargeType,
            'name'                  => $this->metaName,
            'label'                 => $this->metaLabel,
            'price'                 => $this->metaPrice,
            'images'                => $this->metaImages,
        ];
    }

    public function setFromPost($post)
    {
        foreach ($this->getAllMetaTags() as $property => $metakey) {
            $this->{$property} = get_post_meta($post->ID, $metakey, true);
        }
    }
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getPrice(): \stdClass
    {
        return $this->dataChecker->formatPriceObject($this->price);
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param string $vehicle_class_post_id
     */
    public function setVehicleClassPostId(string $vehicle_class_post_id): void
    {
        $this->vehicle_class_post_id = $vehicle_class_post_id;
    }
    public function setChargeByVehicleClassPostId($vehicleClassPostId)
    {
        $this->vehicle_class_post_id = $vehicleClassPostId;
        $args = array_merge(
            $this->postArgs,
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->metaVehicleClassPostId,
                        'value' => $vehicleClassPostId,
                        'compare' => '=',
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        if (is_array($query->posts) and count($query->posts)) {
            $charge_post = $query->posts[0];
            $this->setFromPost($charge_post);
        }
    }
    public function getAmountForDisplay()
    {
        return $this->getPrice()->amount_for_display;
    }
}
