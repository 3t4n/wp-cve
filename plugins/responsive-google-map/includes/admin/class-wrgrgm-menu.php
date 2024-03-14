<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WRGRGM_Admin_Menu {

	/**
	 * Initialize the class
	 */
	public function __construct() {

        add_action( 'init', array( $this, 'register_rgm_menu' ) );
        add_filter( 'get_sample_permalink_html', array( $this, 'wrg_rgm_remove_permalink' ) );
        add_action( 'edit_form_after_title', array( $this, 'wrg_rgm_single_map_preview') );
        
        add_filter( 'manage_wrg_rgm_posts_columns', array( $this, 'wrg_rgm_table_head' ) );
        add_action( 'manage_wrg_rgm_posts_custom_column', array( $this, 'wrg_rgm_table_content' ), 10, 2 );
    }

    public function wrg_rgm_table_content( $column_name, $post_id ) {
        if ( $column_name == 'shortcode' ) {
            echo '<code>[wrg_rgm id="'.$post_id.'"]</code>';
        }
    }

    public function wrg_rgm_table_head( $defaults ) {

        unset($defaults['date']);

        $defaults['shortcode']  = 'Shortcode';
        $defaults['author'] = 'Author';
        $defaults['date'] = 'Date';
        return $defaults;
    }
    
    public function wrg_rgm_single_map_preview() {

        global $post;

        $map_settings       = MapSettings::get( $post->ID );
        $simple_marker      = new Marker( 'simple', $post->ID );
        $advanced_marker    = new Marker( 'advanced', $post->ID );

        $markers = array(
            'simple' => $simple_marker->get_markers(),
            'advanced' => $advanced_marker->get_markers()
        );

        print( '<div id="rgm_root"></div><div id="__rgm-gmap" data-map-settings="'. htmlspecialchars(json_encode($map_settings), ENT_QUOTES, 'UTF-8') .'" data-markers="'. htmlspecialchars(json_encode($markers), ENT_QUOTES, 'UTF-8') .'" style="height:400px;background:#fff;"></div>' );
    }

    public function wrg_rgm_remove_permalink() {
        return '';
    }

	/**
	 * Register admin menus.
	 *
	 * @return void
	 */
	public function register_rgm_menu() {

        $labels = array(
            'name'               => _x( 'Maps', 'post type general name' ),
            'singular_name'      => _x( 'Map', 'post type singular name' ),
            'add_new'            => _x( 'Add New', 'wrg_rgm' ),
            'add_new_item'       => __( 'Add New Map' ),
            'edit_item'          => __( 'Edit Map' ),
            'new_item'           => __( 'New Map' ),
            'all_items'          => __( 'All Maps' ),
            'view_item'          => __( 'View Map' ),
            'search_items'       => __( 'Search Maps' ),
            'not_found'          => __( 'No maps found' ),
            'not_found_in_trash' => __( 'No maps found in the Trash' ), 
            'menu_name'          => 'RGM Maps'
        );
     
        $args = array(
            'labels'             => $labels,
            'description'        => 'Holds our maps and map specific data',
            'public'             => true,
            'menu_position'      => 58.1,
            'menu_icon'          => 'dashicons-location-alt',
            'supports'           => array( 'title' ),
        );
        
        register_post_type( 'wrg_rgm', $args );
	}
}

new WRGRGM_Admin_Menu();
