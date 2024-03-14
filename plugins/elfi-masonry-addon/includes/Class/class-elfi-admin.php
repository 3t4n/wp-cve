<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://sharabindu.com
 * @since      1.4.0
 *
 * @package    Elfi
 * @subpackage Elfi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Elfi
 * @subpackage Elfi/admin
 * @author     BakshiWp <sharabindu.bakshi@gmail.com>
 */
class Elfi_Light_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.4.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	function elfi_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'elfi-category',
			[
				'title' => esc_html__( 'Elfi Addon', 'elfi-masonry-addon' ),
				'icon' => 'fa fa-plug',
			]
		);

	}
	function rw_post_updated_messages( $messages ) {

		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );
		
		$messages['elfi'] = array(
			0  => '', // Unused. Messages start at index 1.
			//1  => __( 'Elfi Item updated.' ),
			1  => sprintf( __( 'Elfi Item updated. <a href="%s">View the item</a>' ), esc_url( get_permalink($post->ID) )),
			2  => __( 'Elfi Item.' ),
			3  => __( 'Elfi Item deleted.'),
			4  => __( 'Elfi Item updated.' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Elfi Item restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Elfi Item published. <a href="%s">View the item</a>' ), esc_url( get_permalink($post->ID) )),
			7  => __( 'Elfi Item saved.' ),
			8  => sprintf( __( 'Elfi Item submitted. <a href="%s">Preview the item</a>' ), esc_url( get_permalink($post->ID) )),
			9  => sprintf(
				__( 'Elfi Item scheduled for: <strong>%1$s</strong>.' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
			),
			10  => sprintf( __( 'Elfi Item draft updated. <a href="%s">Preview the item</a>' ), esc_url( get_permalink($post->ID) )),
		);

	        //you can also access items this way
	        // $messages['post'][1] = "I just totally changed the Updated messages for standards posts";

	        //return the new messaging 
		return $messages;
	}

	function elfi_post_type() {
       $value = get_option( 'elfi__cpt_base' ); 

       $tiembav =  $value ? $value : 'elfi';
		/**
		 * Post Type: Elfi Masonry.
		 */

		$labels = [
			"name" => esc_html__( "Elfi Masonry", "elfi-masonry-addon" ),
			"singular_name" => esc_html__( "Elfi Masonry", "elfi-masonry-addon" ),
			"menu_name" => esc_html__( "Elfi Masonry", "elfi-masonry-addon" ),
			"all_items" => esc_html__( "All Elfi Items", "elfi-masonry-addon" ),
			"add_new" => esc_html__( "Add New Elfi Item", "elfi-masonry-addon" ),
			"add_new_item" => esc_html__( "Add New Elfi Item", "elfi-masonry-addon" ),
			"edit_item" => esc_html__( "Edit Elfi Item", "elfi-masonry-addon" ),
			"new_item" => esc_html__( "New Elfi Item", "elfi-masonry-addon" ),
			"view_item" => esc_html__( "View Elfi Item", "elfi-masonry-addon" ),
			"view_items" => esc_html__( "View All Elfi Items", "elfi-masonry-addon" ),
			"search_items" => esc_html__( "Search Elfi Item", "elfi-masonry-addon" ),
			"not_found" => esc_html__( "Not Found Elfi Item", "elfi-masonry-addon" ),
			"not_found_in_trash" => esc_html__( "Not Elfi found in Trash", "elfi-masonry-addon" ),
			"parent" => esc_html__( "Parent Elfi", "elfi-masonry-addon" ),
			"featured_image" => esc_html__( "Portfolio Image", "elfi-masonry-addon" ),
			"set_featured_image" => esc_html__( "Set Portfolio Image", "elfi-masonry-addon" ),
			"remove_featured_image" => esc_html__( "Remove Portfolio Image", "elfi-masonry-addon" ),
			"use_featured_image" => esc_html__( "Use Portfolio Image", "elfi-masonry-addon" ),
			"archives" => esc_html__( "Portfolio Archive", "elfi-masonry-addon" ),
			"parent_item_colon" => esc_html__( "Parent Elfi", "elfi-masonry-addon" ),
		];

		$args = [
			"label" => esc_html__( "Elfi Masonry", "elfi-masonry-addon" ),
			"labels" => $labels,
			"description" => "Elfi Masonry is a Portfolio Filter Addons for Elementor",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"delete_with_user" => false,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"delete_with_user" => false,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => true,
			"menu_icon" => "dashicons-tagcloud",
			"rewrite" => [ "slug" => "".$tiembav."", "with_front" => true ],
			"query_var" => true,
			"supports" => [ "title", "editor", "thumbnail" ],
		];

		register_post_type( "elfi", $args );




			/**
			 * Taxonomy: Elfi Portfolio.
			 */

			$labels = [
				"name" => esc_html__( "Elfi Portfolio", "elfi-masonry-addon" ),
				"singular_name" => esc_html__( "Elfi Portfolio", "elfi-masonry-addon" ),
				"menu_name" => esc_html__( "Elfi Category", "elfi-masonry-addon" ),
				"all_items" => esc_html__( "All Elfi Categories", "elfi-masonry-addon" ),
				"edit_item" => esc_html__( "Edit Elfi Categories", "elfi-masonry-addon" ),
				"view_item" => esc_html__( "View Elfi Categories", "elfi-masonry-addon" ),
				"update_item" => esc_html__( "Update Elfi Category", "elfi-masonry-addon" ),
				"add_new_item" => esc_html__( "Add New Elfi Category", "elfi-masonry-addon" ),
				"parent_item" => esc_html__( "Parent Elfi Category", "elfi-masonry-addon" ),
				"search_items" => esc_html__( "Search Elfi Category", "elfi-masonry-addon" ),
				"popular_items" => esc_html__( "Popular Elfi Category", "elfi-masonry-addon" ),
				"add_or_remove_items" => esc_html__( "Add or Remove Elfi Category", "elfi-masonry-addon" ),
			];

			$args = [
				"label" => esc_html__( "Elfi Portfolio", "elfi-masonry-addon" ),
				"labels" => $labels,
				"public" => true,
				"publicly_queryable" => true,
				"hierarchical" => true,
				"show_ui" => true,
				"show_in_menu" => true,
				"show_in_nav_menus" => true,
				"query_var" => true,
				"rewrite" => [ 'slug' => 'el_portfolio', 'with_front' => true, ],
				"show_admin_column" => true,
				"show_in_rest" => true,
				"rest_base" => "el_portfolio",
				"rest_controller_class" => "WP_REST_Terms_Controller",
				"show_in_quick_edit" => false,
				];
			register_taxonomy( "el_portfolio", [ "elfi" ], $args );
		}



		 

		 
		function elfi_plugin_row_meta( $links, $file ) {    
		    if ( ELFI_BASENAME_LIGHT == $file ) {
		        $row_meta = array(
		          'docs'    => '<a href="' . esc_url( 'https://elfi.sharabindu.com/wp/docs/plugin-installation/' ) . '" target="_blank">' . esc_html__( 'Docs', 'elfi-masonry-addon' ) . '</a>',

		        );
		 
		        return array_merge( $links, $row_meta );
		    }
		    return (array) $links;
		}








}
