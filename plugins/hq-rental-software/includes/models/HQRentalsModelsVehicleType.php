<?php

namespace HQRentalsPlugin\HQRentalsModels;

use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsModelsVehicleType extends HQRentalsBaseModel
{
    /*
     * Custom Post Configuration
     */
    public $vehicleTypeCustomPostName = 'hqwp_vehicle_types';

    /*
     * HQ Rentals Image Data
     * Custom Post Metas
     */
    protected $metaId = 'hq_wordpress_vehicle_type_id_meta';

    /*
     * Object Data to Display
     */
    public $id = '';

    public $label = '';

    public function __construct($post = null)
    {
        $this->post_id = '';
        $this->settings = new HQRentalsSettings();
        $this->postArgs = array(
            'post_type' => $this->vehicleTypeCustomPostName,
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $this->labels = array(
            'name' => _x('Vehicle Types', 'post type general name', 'hq-wordpress'),
            'singular_name' => _x('Vehicle Type', 'post type singular name', 'hq-wordpress'),
            'menu_name' => _x('Vehicle Types', 'admin menu', 'hq-wordpress'),
            'name_admin_bar' => _x('Vehicle Type', 'add new on admin bar', 'hq-wordpress'),
            'view_item' => __('View', 'hq-wordpress'),
        );
        $this->customPostArgs = array(
            'labels' => $this->labels,
            'public' => false,
            'show_in_admin_bar' => true,
            'publicly_queryable' => $this->settings->isEnableCustomPostsPages(),
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'has_archive' => false,
            'hierarchical' => false,
            'exclude_from_search' => true,
            'menu_icon' => 'dashicons-location-alt',
            'menu_position' => 7,
            'capabilities' => array(
                'create_posts' => 'do_not_allow'
            )
        );
        if (!empty($post)) {
            $this->setFromPost($post);
        }
    }

    public function setVehicleTypeFromApi($data)
    {
        $this->id = $data->id;
        $this->label = $data->label;
    }

    public function create()
    {
        $this->postArgs = array_merge(
            $this->postArgs,
            array(
                'post_title' => $this->label,
                'post_name' => $this->label
            )
        );
        $post_id = wp_insert_post($this->postArgs);
        $this->post_id = $post_id;
        hq_update_post_meta($post_id, $this->metaId, $this->id);
    }

    public function find($caagImage)
    {
    }

    protected function all()
    {
        $query = new \WP_Query($this->postArgs);

        return $query->posts;
    }
}
