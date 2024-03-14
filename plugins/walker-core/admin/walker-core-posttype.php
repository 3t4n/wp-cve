<?php

/**
 * Walker Core post types
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Walker_Core
 * @subpackage Walker_Core/admin
 */
if ( wc_fs()->can_use_premium_code() ) {
function wcr_custom_post_type() {
    register_post_type('wcr_testimonials',
        array(
            'labels'      => array(
                'name'          => __('Testimonials', 'walker-core'),
                'singular_name' => __('Testimonial', 'walker-core'),
                'all_items'         => __( 'All Testimonials', 'walker-core' ),
                'edit_item'         => __( 'Edit Testimonial', 'walker-core' ), 
                'update_item'       => __( 'Update Testimonial', 'walker-core' ),
                'add_new_item'      => __( 'Add New Testimonial', 'walker-core'),
                'new_item_name'     => __( 'New Testimonial', 'walker-core' ),
            ),
            'public'      => true, 
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'wcr_testimonials' ),
            'has_archive'  => true,
            'supports' => array( 'title', 'editor', 'thumbnail'),
            'show_in_rest'       => true
        )
    );

    register_post_type('wcr_slider',
        array(
            'labels'      => array(
                'name'          => __('Slider', 'walker-core'),
                'singular_name' => __('Slider', 'walker-core'),
                'all_items'         => __( 'All Sliders', 'walker-core' ),
                'edit_item'         => __( 'Edit Slider', 'walker-core' ), 
                'update_item'       => __( 'Update Slider', 'walker-core' ),
                'add_new_item'      => __( 'Add New Slider', 'walker-core' ),
                'new_item_name'     => __( 'New Slider', 'walker-core' ),
            ),
            'public'      => true, 
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'wcr_slider' ),
            'has_archive'  => true,
            'supports' => array( 'title','editor', 'thumbnail'),
            'show_in_rest'       => true
        )   
    );
    register_post_type('wcr_teams',
        array(
            'labels'      => array(
                'name'          => __('Teams', 'walker-core'),
                'singular_name' => __('Team', 'walker-core'),
                'all_items'         => __( 'All Members', 'walker-core' ),
                'edit_item'         => __( 'Edit Member', 'walker-core' ), 
                'update_item'       => __( 'Update Member', 'walker-core' ),
                'add_new_item'      => __( 'Add New Member', 'walker-core' ),
                'new_item_name'     => __( 'New Member' , 'walker-core'),
            ),
            'public'      => true, 
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'wcr_teams' ),
            'has_archive'  => true,
            'supports' => array( 'title',  'editor', 'thumbnail'),
            'show_in_rest'       => true
        )   
    );
    register_post_type('wcr_faqs',
        array(
            'labels'      => array(
                'name'          => __('FAQs', 'walker-core'),
                'singular_name' => __('Faq', 'walker-core'),
                'all_items'         => __( 'All Faq', 'walker-core' ),
                'edit_item'         => __( 'Edit Faq', 'walker-core' ), 
                'update_item'       => __( 'Update Faq', 'walker-core' ),
                'add_new_item'      => __( 'Add New Faq', 'walker-core' ),
                'new_item_name'     => __( 'New Faq' , 'walker-core'),
            ),
            'public'      => true, 
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'wcr_faqs' ),
            'has_archive'  => true,
            'supports' => array( 'title',  'editor'),
            'show_in_rest'       => true
        )   
    );
    register_post_type('wcr_brands',
        array(
            'labels'      => array(
                'name'          => __('Brands', 'walker-core'),
                'singular_name' => __('Brand', 'walker-core'),
                'all_items'         => __( 'All Brand', 'walker-core' ),
                'edit_item'         => __( 'Edit Brand', 'walker-core' ), 
                'update_item'       => __( 'Update Brand', 'walker-core' ),
                'add_new_item'      => __( 'Add New Brand', 'walker-core' ),
                'new_item_name'     => __( 'New Brand' , 'walker-core'),
            ),
            'public'      => true, 
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'wcr_brands' ),
            'has_archive'  => true,
            'supports' => array( 'title',  'editor', 'thumbnail'),
            'show_in_rest'       => true
        )   
    );
    
}
add_action( 'init', 'wcr_custom_post_type' );

$theme = wp_get_theme();
if ( 'Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme || 'Walker Charity' == $theme->name || 'Walker Charity' == $theme->parent_theme || 'MularX' == $theme->name || 'MularX' == $theme->parent_theme  ):
    if(!get_theme_mod('disable_walker_core_portfolio')){
    function walker_core_portfolio_init() {
        $labels = array(
            'name'                  => _x( 'Portfolios', 'Portfolio', 'walker-core' ),
            'singular_name'         => _x( 'Portfolio', 'Portfolio', 'walker-core' ),
            'menu_name'             => _x( 'Portfolio', 'Admin Menu text', 'walker-core' ),
            'name_admin_bar'        => _x( 'Portfolio', 'Add New on Toolbar', 'walker-core' ),
            'add_new'               => __( 'Add New', 'walker-core' ),
            'add_new_item'          => __( 'Add New Portfolio', 'walker-core' ),
            'new_item'              => __( 'New Portfolio', 'walker-core' ),
            'edit_item'             => __( 'Edit Portfolio', 'walker-core' ),
            'view_item'             => __( 'View Portfolio', 'walker-core' ),
            'all_items'             => __( 'All Portfolios', 'walker-core' ),
            'search_items'          => __( 'Search Portfolios', 'walker-core' ),
            'parent_item_colon'     => __( 'Parent Portfolios:', 'walker-core' ),
            'not_found'             => __( 'No portfolio found.', 'walker-core' ),
            
        );     
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'wcr_portfolio' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => 20,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
            'show_in_rest'       => true,
            'menu_icon'           => 'dashicons-open-folder',
        );
          
        register_post_type( 'wcr_portfolio', $args );
    }
    add_action( 'init', 'walker_core_portfolio_init' );


    function walker_core_portfolio_taxonomy() {
     
      $labels = array(
        'name' => _x( 'Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'all_items' => __( 'All Categories' ),
        'parent_item' => __( 'Parent Category' ),
        'parent_item_colon' => __( 'Parent Category:' ),
        'edit_item' => __( 'Edit Category' ), 
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add New Category' ),
        'new_item_name' => __( 'New Category' ),
        'menu_name' => __( 'Category' ),
      );    
     
      register_taxonomy('wcr_portfolio_category',array('wcr_portfolio'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'wcr_portfolio_category' ),
        'show_in_rest'       => true
      ));
    } 
    add_action( 'init', 'walker_core_portfolio_taxonomy', 30 );
}
endif;
}
?>