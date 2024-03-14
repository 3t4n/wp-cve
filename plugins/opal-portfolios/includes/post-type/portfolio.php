<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Custom_Post_Type_Portfolio
 */
class Custom_Post_Type_Portfolio {

    public function __construct() {
        $this->create_post_type();
        $this->create_portfolio_taxonomies();
        $this->create_portfolio_metaboxes();
        
    }

    /**
     * init action and filter data to define service post type
     */
    public static function create_portfolio_metaboxes(){ 

        add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
        //
        define( 'OPAL_PORTFOLIO_PREFIX', 'opal_portfolio_' );
    }

    /**
     * @return void
     */
    public function create_post_type() {

        $labels = array(
            'name'               => __('Portfolios', "opalportfolios"),
            'singular_name'      => __('Portfolios', "opalportfolios"),
            'add_new'            => __('Add New Portfolio', "opalportfolios"),
            'add_new_item'       => __('Add New Portfolio', "opalportfolios"),
            'edit_item'          => __('Edit Portfolio', "opalportfolios"),
            'new_item'           => __('New Portfolio', "opalportfolios"),
            'view_item'          => __('View Portfolio', "opalportfolios"),
            'search_items'       => __('Search Portfolios', "opalportfolios"),
            'not_found'          => __('No Portfolios found', "opalportfolios"),
            'not_found_in_trash' => __('No Portfolios found in Trash', "opalportfolios"),
            'parent_item_colon'  => __('Parent Portfolio:', "opalportfolios"),
            'menu_name'          => __('Opal Portfolio', "opalportfolios"),
        );

        $slug_field = portfolio_get_option( 'slug_portfolios' );
        $slug = isset($slug_field) ? $slug_field : "portfolio";

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => true,
            'description'         => __('List Portfolio', "opalportfolios"),
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'), //page-attributes, post-formats
            'public'              => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-images-alt2',
            'has_archive'         => true,
            'rewrite'             => array( 'slug' => $slug ),
        );
        register_post_type('opal_portfolio', $args);
        add_post_type_support( 'opal_portfolio', 'elementor' );
    }
    /*-----------------------------------------------------------------------------------*/
    /*  Creating Custom Taxonomy 
    /*-----------------------------------------------------------------------------------*/
    public function create_portfolio_taxonomies() {
        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
            'name'              => _x( 'Portfolio Categories', 'taxonomy general name', 'opalportfolios' ),
            'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name', 'opalportfolios' ),
            'search_items'      => __( 'Search Portfolio Categories', 'opalportfolios' ),
            'all_items'         => __( 'All Portfolio Categories', 'opalportfolios' ),
            'parent_item'       => __( 'Parent Portfolio Category', 'opalportfolios' ),
            'parent_item_colon' => __( 'Parent Portfolio Category:', 'opalportfolios' ),
            'edit_item'         => __( 'Edit Portfolio Category', 'opalportfolios' ),
            'update_item'       => __( 'Update Portfolio Category', 'opalportfolios' ),
            'add_new_item'      => __( 'Add New Portfolio Category', 'opalportfolios' ),
            'new_item_name'     => __( 'New Portfolio Category', 'opalportfolios' ),
            'menu_name'         => __( 'Portfolio Categories', 'opalportfolios' ),
        );

        $slug_category_field = portfolio_get_option( 'slug_category_portfolio' );
        $slug_category = isset($slug_category_field) ? $slug_category_field : "portfolio_cat";

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => $slug_category),
        );

        register_taxonomy( 'portfolio_cat', array( 'opal_portfolio' ), $args );
    }

    /**
     *
     */
    public static function metaboxes( array $metaboxes ) {
        $prefix = OPAL_PORTFOLIO_PREFIX;       
        //$metaboxes = array();
        $metaboxes[ $prefix . 'managements' ] = array(
            'id'                        => $prefix . 'managements',
            'title'                     => __( 'Management', 'opalportfolios' ),
            'object_types'              => array( PE_POST_TYPE ),
            'context'                   => 'normal',
            'priority'                  => 'high',
            'show_names'                => true,
            'fields'                    => self::metaboxes_management_fields()
        );
        return $metaboxes;
    }

    /**
     *
     */ 
    public static function metaboxes_management_fields(){
        $prefix = OPAL_PORTFOLIO_PREFIX;
        $fields = array(
            array(
                'id'                => $prefix.'color',
                'type'              => 'colorpicker',
                'name'              => __( 'Mask Color', 'opalportfolios' ),
            ),
            array(
                'id'                => $prefix.'client',
                'type'              => 'text',
                'name'              => __( 'Client', 'opalportfolios' ),
            ),
            array(
                'id'                => $prefix.'budgets',
                'type'              => 'text',
                'name'              => __( 'Budgets', 'opalportfolios' ),
            ),
            array(
                'id'                => $prefix.'completed',
                'type'              => 'text_date',
                'name'              => __( 'Completed', 'opalportfolios' ), 
            ),
            array(
                'id'                => $prefix.'location',
                'type'              => 'text',
                'name'              => __( 'Location', 'opalportfolios' ),  
            ),
            array(
                'id'                => $prefix.'link',
                'type'              => 'text_url',
                'name'              => __( 'Project url', 'opalportfolios' ),
            ),
            array(
                'id'                => $prefix.'desc',
                'type'              => 'wysiwyg',
                'name'              => __( 'Description', 'opalportfolios' ),   
            ),
            
        );

        return apply_filters( 'opalportfolio_postype_portfolio_metaboxes_fields_managements' , $fields );
    }
}

new Custom_Post_Type_Portfolio;

