<?php
if( !defined('ABSPATH') ) exit;

/*---------------------------
*    Register Post type
* ---------------------------*/

if ( ! class_exists( 'Skyboot_Portfolio_Gallery_Post_Type') ) {

	class Skyboot_Portfolio_Gallery_Post_Type {

		public function __construct(){
			add_action( 'init', array( $this, 'Skyboot_Portfolio_Gallery_Register_Post_Type' ), 0 );
		}

		public function Skyboot_Portfolio_Gallery_Register_Post_Type() {

        // Item
        $labels = array(
            'name'                  => _x( 'Portfolio', 'Post Type General Name', 'skyboot-pg' ),
            'singular_name'         => _x( 'portfolio', 'Post Type Singular Name', 'skyboot-pg' ),
            'menu_name'             => __( 'Portfolio Gallery', 'skyboot-pg' ),
            'name_admin_bar'        => __( 'Portfolio Gallery', 'skyboot-pg' ),
            'archives'              => __( 'Item Archives', 'skyboot-pg' ),
            'parent_item_colon'     => __( 'Parent Item:', 'skyboot-pg' ),
            'all_items'             => __( 'All Items', 'skyboot-pg' ),
            'add_new_item'          => __( 'Add New Item', 'skyboot-pg' ),
            'add_new'               => __( 'Add New', 'skyboot-pg' ),
            'new_item'              => __( 'New Item', 'skyboot-pg' ),
            'edit_item'             => __( 'Edit Item', 'skyboot-pg' ),
            'update_item'           => __( 'Update Item', 'skyboot-pg' ),
            'view_item'             => __( 'View Item', 'skyboot-pg' ),
            'search_items'          => __( 'Search Item', 'skyboot-pg' ),
            'not_found'             => __( 'Not found', 'skyboot-pg' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'skyboot-pg' ),
            'featured_image'        => __( 'Featured Image', 'skyboot-pg' ),
            'set_featured_image'    => __( 'Set featured image', 'skyboot-pg' ),
            'remove_featured_image' => __( 'Remove featured image', 'skyboot-pg' ),
            'use_featured_image'    => __( 'Use as featured image', 'skyboot-pg' ),
            'insert_into_item'      => __( 'Insert into item', 'skyboot-pg' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'skyboot-pg' ),
            'items_list'            => __( 'Items list', 'skyboot-pg' ),
            'items_list_navigation' => __( 'Items list navigation', 'skyboot-pg' ),
            'filter_items_list'     => __( 'Filter items list', 'skyboot-pg' ),
         );
         $args = array(
            'label'                 => __( 'Portfolio', 'skyboot-pg' ),
            'labels'                => $labels,
            'supports'              => array('title','editor', 'thumbnail', 'custom-fields', 'excerpt' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_rest' 			=> true,
            'menu_icon'             => 'dashicons-format-gallery',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,		
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
         );
         register_post_type( 'skyboot_portfolio', $args );


		}

	}
}

$postregister = new Skyboot_Portfolio_Gallery_Post_Type();



