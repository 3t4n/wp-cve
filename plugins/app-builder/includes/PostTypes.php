<?php

/**
 * Admin
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 * @author     ngocdt
 *
 */

namespace AppBuilder;

defined( 'ABSPATH' ) || exit;

/**
 * Post types Class.
 */

if ( ! class_exists( 'PostTypes' ) ) {
	class PostTypes {

		/**
		 * Post_Types constructor.
		 *
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_taxonomies' ), 5 );
			add_action( 'init', array( $this, 'register_post_types' ), 5 );
		}

		/**
		 * Register core taxonomies.
		 */
		public function register_taxonomies() {

			if ( ! is_blog_installed() ) {
				return;
			}

			if ( ! taxonomy_exists( 'app_builder_template_tag' ) ) {
				register_taxonomy( 'app_builder_template_tag', 'app_builder', array(
					'labels'            => array(
						'name'              => _x( 'App Builder Tags', 'taxonomy general name', 'app-builder' ),
						'singular_name'     => _x( 'App Builder Tag', 'taxonomy singular name', 'app-builder' ),
						'search_items'      => __( 'Search App Builder Tags', 'app-builder' ),
						'all_items'         => __( 'All App Builder Tags', 'app-builder' ),
						'view_item'         => __( 'View App Builder Tag', 'app-builder' ),
						'parent_item'       => __( 'Parent App Builder Tag', 'app-builder' ),
						'parent_item_colon' => __( 'Parent App Builder Tag:', 'app-builder' ),
						'edit_item'         => __( 'Edit App Builder Tag', 'app-builder' ),
						'update_item'       => __( 'Update App Builder Tag', 'app-builder' ),
						'add_new_item'      => __( 'Add New App Builder Tag', 'app-builder' ),
						'new_item_name'     => __( 'New App Builder Tag Name', 'app-builder' ),
						'not_found'         => __( 'No App Builder Tags Found', 'app-builder' ),
						'back_to_items'     => __( 'Back to App Builder Tags', 'app-builder' ),
						'menu_name'         => __( 'App Builder Tag', 'app-builder' ),
					),
					'hierarchical'      => false,
					'public'            => true,
					'show_ui'           => APP_BUILDER_SHOW_UI,
					'show_in_menu'      => 'tools.php',
					'show_in_admin_bar' => APP_BUILDER_SHOW_UI,
					'show_in_nav_menus' => APP_BUILDER_SHOW_UI,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => 'app_builder_template_tag' ),
					'show_in_rest'      => true,
				) );
			}

			if ( ! taxonomy_exists( 'app_builder_preset_tag' ) ) {
				register_taxonomy( 'app_builder_preset_tag', 'app_builder_preset', array(
					'public'             => APP_BUILDER_SHOW_UI,
					'labels'             => array(
						'name'              => _x( 'Preset Tags', 'taxonomy general name', 'app-builder' ),
						'singular_name'     => _x( 'Preset Tag', 'taxonomy singular name', 'app-builder' ),
						'search_items'      => __( 'Search Preset Tags', 'app-builder' ),
						'all_items'         => __( 'All Preset Tags', 'app-builder' ),
						'view_item'         => __( 'View Preset Tag', 'app-builder' ),
						'parent_item'       => __( 'Parent Preset Tag', 'app-builder' ),
						'parent_item_colon' => __( 'Parent Preset Tag:', 'app-builder' ),
						'edit_item'         => __( 'Edit Preset Tag', 'app-builder' ),
						'update_item'       => __( 'Update Preset Tag', 'app-builder' ),
						'add_new_item'      => __( 'Add New Preset Tag', 'app-builder' ),
						'new_item_name'     => __( 'New Preset Tag Name', 'app-builder' ),
						'not_found'         => __( 'No Preset Tags Found', 'app-builder' ),
						'back_to_items'     => __( 'Back to Preset Tags', 'app-builder' ),
						'menu_name'         => __( 'Preset Tag', 'app-builder' ),
					),
					'hierarchical'       => false,
					'show_ui'            => APP_BUILDER_SHOW_UI,
					'show_admin_column'  => APP_BUILDER_SHOW_UI,
					'show_in_quick_edit' => APP_BUILDER_SHOW_UI,
					'query_var'          => true,
					'publicly_queryable' => APP_BUILDER_SHOW_UI,
					'show_in_nav_menus'  => APP_BUILDER_SHOW_UI,
					'rewrite'            => array( 'slug' => 'app_builder_preset_tag' ),
					'show_in_rest'       => true,
				) );
			}
		}

		/**
		 * Register core post types.
		 */
		public function register_post_types() {
			if ( ! is_blog_installed() ) {
				return;
			}

			if ( ! post_type_exists( 'app_builder_template' ) ) {
				register_post_type( 'app_builder_template', array(
					'labels'              => array(
						'name'               => esc_html__( 'App Builder Template', 'app-builder' ),
						'singular_name'      => esc_html__( 'App Builder Entry', 'app-builder' ),
						'add_new'            => esc_html__( 'Add New Template', 'app-builder' ),
						'add_new_item'       => esc_html__( 'Add New Entry', 'app-builder' ),
						'new_item'           => esc_html__( 'New Entry', 'app-builder' ),
						'edit_item'          => esc_html__( 'Edit Publication Page', 'app-builder' ),
						'view_item'          => esc_html__( 'View Entry', 'app-builder' ),
						'all_items'          => esc_html__( '[App] - Templates', 'app-builder' ),
						'search_items'       => esc_html__( 'Search Entries', 'app-builder' ),
						'not_found'          => esc_html__( 'No Template found', 'app-builder' ),
						'not_found_in_trash' => esc_html__( 'No Template found in Trash', 'app-builder' ),
					),
					'show_in_rest'        => true,
					'rest_base'           => 'app-builder-templates',
					'hierarchical'        => false,
					'public'              => false,
					'show_ui'             => APP_BUILDER_SHOW_UI,
					'show_in_menu'        => 'tools.php',
					'show_in_admin_bar'   => APP_BUILDER_SHOW_UI,
					'show_in_nav_menus'   => APP_BUILDER_SHOW_UI,
					'publicly_queryable'  => true,
					'exclude_from_search' => true,
					'has_archive'         => false,
					'query_var'           => false,
					'can_export'          => true,
					'rewrite'             => false,
					'map_meta_cap'        => true,
					'supports'            => array(
						'title',
						'author',
						'thumbnail',
						'custom-fields',
						'revisions',
						'editor',
					),
					'taxonomies'          => array( 'app_builder_template_tag' ),
					'capability_type'     => 'post'
				) );
			}

			if ( ! post_type_exists( 'app_builder_preset' ) ) {
				register_post_type( 'app_builder_preset', array(
					'labels'              => array(
						'name'               => esc_html__( 'Preset Preset', 'app-builder' ),
						'singular_name'      => esc_html__( 'Preset Entry', 'app-builder' ),
						'add_new'            => esc_html__( 'Add New Preset', 'app-builder' ),
						'add_new_item'       => esc_html__( 'Add New Preset Entry', 'app-builder' ),
						'new_item'           => esc_html__( 'New Preset Entry', 'app-builder' ),
						'edit_item'          => esc_html__( 'Edit Publication Page', 'app-builder' ),
						'view_item'          => esc_html__( 'View Preset Entry', 'app-builder' ),
						'all_items'          => esc_html__( '[App] - Presets', 'app-builder' ),
						'search_items'       => esc_html__( 'Search Entries', 'app-builder' ),
						'not_found'          => esc_html__( 'No Preset found', 'app-builder' ),
						'not_found_in_trash' => esc_html__( 'No Preset found in Trash', 'app-builder' ),
					),
					'show_in_rest'        => true,
					'rest_base'           => 'app-builder-presets',
					'hierarchical'        => false,
					'public'              => false,
					'show_ui'             => APP_BUILDER_SHOW_UI,
					'show_in_menu'        => 'tools.php',
					'show_in_admin_bar'   => APP_BUILDER_SHOW_UI,
					'show_in_nav_menus'   => APP_BUILDER_SHOW_UI,
					'publicly_queryable'  => true,
					'exclude_from_search' => true,
					'has_archive'         => false,
					'query_var'           => false,
					'can_export'          => true,
					'rewrite'             => false,
					'map_meta_cap'        => true,
					'supports'            => array(
						'title',
						'author',
						'thumbnail',
						'custom-fields',
						'revisions',
						'editor',
					),
					'taxonomies'          => array( 'app_builder_preset_tag' ),
					'capability_type'     => 'post'
				) );
			}
		}
	}
}
