<?php

namespace HQRentalsPlugin\HQRentalsModels;

use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsLocaleHelper;

class HQRentalsModelsFeature extends HQRentalsBaseModel
{
    /*
     * Custom Post Configuration
     */
    public $featureVehicleClassPostName = 'hqwp_feature';
    public $featureVehicleClassPostSlug = 'features';

    /*
     * HQ Rentals Feature Data
     * Custom Post Metas
     */

    protected $metaVehicleClassId = 'hq_wordpress_feature_vehicle_id_meta';
    protected $metaLabel = 'hq_wordpress_feature_label_meta';
    protected $metaIcon = 'hq_wordpress_feature_icon_meta';
    protected $metaLabelForWebsite = 'hq_wordpress_feature_label_for_website_meta';
    protected $metaOrder = 'hq_wordpress_feature_order_for_website_meta';

    //Labels per Language
    public $vehicleClassId = '';
    public $label = '';
    public $label_for_website = '';
    public $icon = '';
    public $order = '';
    public $metaClassId = 'hq_wordpress_feature_vehicle_id_meta';
    public $metaOrderKey = 'hq_wordpress_feature_order_meta';

    public function __construct($post = null)
    {
        $this->post_id = '';
        $this->locale = new HQRentalsLocaleHelper();
        $this->postArgs = array(
            'post_type' => $this->featureVehicleClassPostName,
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $this->labelsPost = [
            'name' => _x('Features', 'post type general name', 'hq-wordpress'),
            'singular_name' => _x('Feature', 'post type singular name', 'hq-wordpress'),
            'menu_name' => _x('Features', 'admin menu', 'hq-wordpress'),
            'name_admin_bar' => _x('Feature', 'add new on admin bar', 'hq-wordpress'),
            'add_new' => _x('Add New', 'brand', 'hq-wordpress'),
            'add_new_item' => __('Add New Feature', 'hq-wordpress'),
            'new_item' => __('New Feature', 'hq-wordpress'),
            'edit_item' => __('Edit Feature', 'hq-wordpress'),
            'view_item' => __('View Feature', 'hq-wordpress'),
            'all_items' => __('All Features', 'hq-wordpress'),
            'search_items' => __('Search Features', 'hq-wordpress'),
            'parent_item_colon' => __('Parent Features', 'hq-wordpress'),
            'not_found' => __('No features found.', 'hq-wordpress'),
            'not_found_in_trash' => __('No features found in Trash.', 'hq-wordpress'),
        ];
        $this->customPostArgs = [
            'labels' => $this->labelsPost,
            'public' => false,
            'show_in_admin_bar' => false,
            'publicly_queryable' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'query_var' => true,
            'rewrite' => ['slug' => $this->featureVehicleClassPostSlug],
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
        if (!empty($post)) {
            $this->setFromPost($post);
        }
    }

    public function setFeatureFromApi($vehicle_id, $data)
    {
        $this->vehicleClassId = $vehicle_id;
        $this->label = $data->label;
        $this->label_for_website = $data->label_for_website;
        $this->icon = $data->icon;
        $this->order = $data->order;
    }

    public function create()
    {
        $this->postArgs = array_merge(
            $this->postArgs,
            array(
                'post_title' => (empty($this->label) ? "Feature" : $this->label),
                'post_name' => (empty($this->label) ? "Feature" : $this->label)
            )
        );
        $post_id = wp_insert_post($this->postArgs);
        $this->post_id = $post_id;
        hq_update_post_meta($post_id, $this->metaVehicleClassId, $this->vehicleClassId);
        hq_update_post_meta($post_id, $this->metaLabel, $this->label);
        hq_update_post_meta($post_id, $this->metaIcon, $this->icon);
        hq_update_post_meta($post_id, $this->metaLabelForWebsite, $this->label_for_website);
        hq_update_post_meta($post_id, $this->metaOrder, $this->order);
    }

    public function find($caag_id)
    {
        $query = new \WP_Query($this->postArgs);
    }

    /*
     * Return All Posts
     */
    public function all()
    {
        $query = new \WP_Query($this->postArgs);
        return $query->posts;
    }

    public function setFromPost($post)
    {
        $this->postID = $post->ID;
        foreach ($this->getAllMetaTags() as $property => $metakey) {
            $this->{$property} = get_post_meta($post->ID, $metakey, true);
        }
    }

    public function getAllMetaTags()
    {
        return array(
            'vehicleClassId' => $this->metaVehicleClassId,
            'label' => $this->metaLabel,
            'icon' => $this->metaIcon,
            'label_for_website' => $this->metaLabelForWebsite,
            'order' => $this->metaOrder
        );
    }

    public function getIcon()
    {
        return HQRentalsFrontHelper::resolveFontAwesomeIcon($this->icon);
    }

    public function getLabelForWebsite($override = false, $lang = 'en')
    {
        if ($override) {
            return $this->label_for_website;
        }
        if ($this->locale->language === "zh") {
            return $this->label_for_website->{"zh-Hans"};
        }
        if ($this instanceof \stdClass) {
        }
        return $this->label_for_website->{explode('_', get_locale())[0]};
    }

    public function getLabelsForWebsite()
    {
        return $this->label_for_website;
    }

    public function getOrderMetaKey()
    {
        return $this->metaOrder;
    }

    public function getVehicleClassIdMetaKey()
    {
        return $this->metaVehicleClassId;
    }
}
