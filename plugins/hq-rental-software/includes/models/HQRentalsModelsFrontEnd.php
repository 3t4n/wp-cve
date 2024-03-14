<?php

namespace HQRentalsPlugin\HQRentalsModels;

class HQRentalsModelsFrontEnd
{
    /*
     * Custom Post Configuration
     */
    public $frontEndPostName = 'hqwp_frontend';
    public $frontEndSlugName = 'hq-frontend';

    /**
     * Metas for Front End
     * @var string
     */
    protected $metaBrands = 'hq_wordpress_frontend_brands_meta';
    protected $metaVehiclesClass = 'hq_wordpress_frontend_vehicles_classes_meta';
    protected $metaLocations = 'hq_wordpress_frontend_location_meta';

    public $brands = '';
    public $vehicleClasses = '';
    public $locations = '';

    public function __construct()
    {
        $this->postArgs = array(
            'post_type' => $this->frontEndPostName,
            'post_status' => 'publish'
        );
        $this->labels = array();
        $this->customPostArgs = array(
            'labels' => $this->labels,
            'public' => false,
            'show_in_admin_bar' => false,
            'publicly_queryable' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'query_var' => false,
            'rewrite' => array('slug' => $this->frontEndSlugName),
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'capabilities' => array(
                'create_posts' => 'do_not_allow'
            )
        );
    }
}
