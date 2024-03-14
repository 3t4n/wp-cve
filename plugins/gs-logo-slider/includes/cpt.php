<?php 
namespace GSLOGO;

if ( ! defined( 'ABSPATH' ) ) exit;

class Cpt {

	public function __construct() {
		add_action( 'init', [ $this, 'GS_Logo_Slider' ] );
		add_action( 'init', [ $this, 'gs_logo_category' ], 0 );
		add_action( 'after_setup_theme', [ $this, 'gs_logo_theme_support' ] );
	}

	function GS_Logo_Slider() {

		$labels = array(
			'name'               	=> _x( 'GS Logos', 'gslogo' ),
			'singular_name'      	=> _x( 'GS Logo', 'gslogo' ),
			'menu_name'          	=> _x( 'GS Logos', 'admin menu', 'gslogo' ),
			'name_admin_bar'     	=> _x( 'GS Logo Slider', 'add new on admin bar', 'gslogo' ),
			'add_new'            	=> _x( 'Add New Logo', 'logo', 'gslogo' ),
			'add_new_item'       	=> __( 'Add New Logo', 'gslogo' ),
			'new_item'           	=> __( 'New Logo', 'gslogo' ),
			'edit_item'          	=> __( 'Edit Logo', 'gslogo' ),
			'view_item'          	=> __( 'View Logo', 'gslogo' ),
			'all_items'          	=> __( 'All Logos', 'gslogo' ),
			'search_items'       	=> __( 'Search Logos', 'gslogo' ),
			'parent_item_colon'  	=> __( 'Parent Logos:', 'gslogo' ),
			'not_found'          	=> __( 'No logos found.', 'gslogo' ),
			'not_found_in_trash' 	=> __( 'No logos found in Trash.', 'gslogo' ),
			'featured_image'     	=> __( 'Add Logo', 'gslogo' ),
			'set_featured_image'    => __( 'Add New Logo', 'gslogo' ),
			'remove_featured_image' => __( 'Remove This Logo', 'gslogo' ),
			'use_featured_image'    => __( 'Use This Logo', 'gslogo' ),
		);
	
		$args = array(
			'labels'             	=> $labels,
			'show_ui'            	=> true,
			'exclude_from_search' 	=> true,
			'public'            	=> true,
			'has_archive'       	=> false,
			'hierarchical'       	=> false,
			'show_in_rest'       	=> true,
			'menu_position'      	=> GSL_MENU_POSITION,
			'capability_type'    	=> 'post',
			'menu_icon'          	=> GSL_PLUGIN_URI . 'assets/img/icon.svg',
			'supports'           	=> array( 'title', 'editor', 'thumbnail', 'excerpt')
		);
	
		register_post_type( 'gs-logo-slider', $args );
	}
	
	function gs_logo_category() {
	
		$labels = array(
			'name'                       => _x( 'Logo Categories', 'Taxonomy General Name', 'gslogo' ),
			'singular_name'              => _x( 'Logo Category', 'Taxonomy Singular Name', 'gslogo' ),
			'menu_name'                  => __( 'Logo Category', 'gslogo' ),
			'all_items'                  => __( 'All Logo Category', 'gslogo' ),
			'parent_item'                => __( 'Parent Logo Category', 'gslogo' ),
			'parent_item_colon'          => __( 'Parent Logo Category:', 'gslogo' ),
			'new_item_name'              => __( 'New Logo Category', 'gslogo' ),
			'add_new_item'               => __( 'Add New Logo Category', 'gslogo' ),
			'edit_item'                  => __( 'Edit Logo Category', 'gslogo' ),
			'update_item'                => __( 'Update Logo Category', 'gslogo' ),
			'separate_items_with_commas' => __( 'Separate Logo Category with commas', 'gslogo' ),
			'search_items'               => __( 'Search Logo Category', 'gslogo' ),
			'add_or_remove_items'        => __( 'Add or remove Logo Category', 'gslogo' ),
			'choose_from_most_used'      => __( 'Choose from the most used Logo categories', 'gslogo' ),
			'not_found'                  => __( 'Not Found', 'gslogo' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => false,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_tagcloud'              => false,
		);
		register_taxonomy( 'logo-category', array( 'gs-logo-slider' ), $args );
	
	}	
	
	function gs_logo_theme_support()  {
		// Add theme support for Featured Images
		add_theme_support( 'post-thumbnails', array( 'gs-logo-slider' ) );
		add_theme_support( 'post-thumbnails', array( 'post' ) ); // Add it for posts
		add_theme_support( 'post-thumbnails', array( 'page' ) ); // Add it for pages
		add_theme_support( 'post-thumbnails', array( 'product' ) ); // Add it for products
		add_theme_support( 'post-thumbnails');
		// Add Shortcode support in text widget
		add_filter('widget_text', 'do_shortcode'); 
	}

}
