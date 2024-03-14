<?php
/*
* class: Properties_Plugin_Content_Type
* since: 0.0.1
* description: Registers Property content type and associated taxonomies in WordPress
* version: 0.0.1
* text-domain: properties
*/
if( !class_exists('Properties_Plugin_Content_Type') ) {

	class Properties_Plugin_Content_Type {

		public static $_instance;

		public static function getInstance() {
			if ( !isset( self::$_instance ) ) {
				self::$_instance = new self();
			}
			/* properties_plugin_content_type_init hook */
			do_action( 'properties_plugin_content_type_init', self::$_instance );
			/* return instance */
			return self::$_instance;
		}

		public static function get_property_taxonomy_full_names() {
			$data = self::get_property_taxonomy_names();
			$out = array();
			foreach( $data as $tax ) {
				$taxonomy = get_taxonomy( $tax );
				$out[ $tax ] = $taxonomy->labels->name;
			}
			return $out;
		}

		public static function get_property_taxonomy_names() {
			return array_keys( self::registration_get_property_taxonomies() );
		}

		public static function registration_get_property_taxonomies() {
			return apply_filters( 'pp_register_property_taxonomies', array(
				'property_type' => array( __CLASS__, 'register_property_taxonomy_type' ),
				'property_area' => array( __CLASS__, 'register_property_taxonomy_area' ),
				'property_complex' => array( __CLASS__, 'register_property_taxonomy_complex' ),
				'property_collection' => array( __CLASS__, 'register_property_taxonomy_collection' )
			) );
		}

		public static function register_property_taxonomies() {
			$taxonomies = self::registration_get_property_taxonomies();
			foreach( $taxonomies as $taxonomy => $callback ) {
				call_user_func( $callback );
			}
		}

		public static function register_property_taxonomy_collection() {
			$labels = array(
				'name'                       => _x( 'Collections', 'Taxonomy General Name', 'properties' ),
				'singular_name'              => _x( 'Collection', 'Taxonomy Singular Name', 'properties' ),
				'menu_name'                  => __( 'Collections', 'properties' ),
				'all_items'                  => __( 'All Collections', 'properties' ),
				'parent_item'                => __( 'Parent Collection', 'properties' ),
				'parent_item_colon'          => __( 'Parent Collection:', 'properties' ),
				'new_item_name'              => __( 'New Collection Name', 'properties' ),
				'add_new_item'               => __( 'Add New Collection', 'properties' ),
				'edit_item'                  => __( 'Edit Collection', 'properties' ),
				'update_item'                => __( 'Update Collection', 'properties' ),
				'view_item'                  => __( 'View Collection', 'properties' ),
				'separate_items_with_commas' => __( 'Separate collections with commas', 'properties' ),
				'add_or_remove_items'        => __( 'Add or remove collections', 'properties' ),
				'choose_from_most_used'      => __( 'Choose from the most used collections', 'properties' ),
				'popular_items'              => __( 'Popular Collections', 'properties' ),
				'search_items'               => __( 'Search Collections', 'properties' ),
				'not_found'                  => __( 'No collections found', 'properties' ),
				'no_terms'                   => __( 'No collections', 'properties' ),
				'items_list'                 => __( 'collections list', 'properties' ),
				'items_list_navigation'      => __( 'collections list navigation', 'properties' ),
			);
			$rewrite = array(
				'slug'                       => self::get_slug_option( 'pp_property_collection_slug', _x( 'property-collections', 'URL friendly slug', 'properties' ) ),
				'with_front'                 => false,
				'hierarchical'               => false,
			);
			$args = array(
				'labels'                     => $labels,
				'hierarchical'               => false,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
				'rewrite'                    => $rewrite,
			);
			register_taxonomy( 'property_collection', array( 'property' ), $args );
		}

		public static function register_property_taxonomy_complex() {
			$labels = array(
				'name'                       => _x( 'Complexes', 'Taxonomy General Name', 'properties' ),
				'singular_name'              => _x( 'Complex', 'Taxonomy Singular Name', 'properties' ),
				'menu_name'                  => __( 'Complexes', 'properties' ),
				'all_items'                  => __( 'All Complexes', 'properties' ),
				'parent_item'                => __( 'Parent Complex', 'properties' ),
				'parent_item_colon'          => __( 'Parent Complex:', 'properties' ),
				'new_item_name'              => __( 'New Complex Name', 'properties' ),
				'add_new_item'               => __( 'Add New Complex', 'properties' ),
				'edit_item'                  => __( 'Edit Complex', 'properties' ),
				'update_item'                => __( 'Update Complex', 'properties' ),
				'view_item'                  => __( 'View Complex', 'properties' ),
				'separate_items_with_commas' => __( 'Separate complexes with commas', 'properties' ),
				'add_or_remove_items'        => __( 'Add or remove complexes', 'properties' ),
				'choose_from_most_used'      => __( 'Choose from the most used complexes', 'properties' ),
				'popular_items'              => __( 'Popular Complexes', 'properties' ),
				'search_items'               => __( 'Search Complexes', 'properties' ),
				'not_found'                  => __( 'No complexes found', 'properties' ),
				'no_terms'                   => __( 'No complexes', 'properties' ),
				'items_list'                 => __( 'complexes list', 'properties' ),
				'items_list_navigation'      => __( 'complexes list navigation', 'properties' ),
			);
			$rewrite = array(
				'slug'                       => self::get_slug_option( 'pp_property_complex_slug', _x( 'property-complexes', 'URL friendly slug', 'properties' ) ),
				'with_front'                 => false,
				'hierarchical'               => false,
			);
			$args = array(
				'labels'                     => $labels,
				'hierarchical'               => false,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
				'rewrite'                    => $rewrite,
			);
			register_taxonomy( 'property_complex', array( 'property' ), $args );
		}

		public static function register_property_taxonomy_area() {
			$labels = array(
				'name'                       => _x( 'Areas', 'Taxonomy General Name', 'properties' ),
				'singular_name'              => _x( 'Area', 'Taxonomy Singular Name', 'properties' ),
				'menu_name'                  => __( 'Areas', 'properties' ),
				'all_items'                  => __( 'All Areas', 'properties' ),
				'parent_item'                => __( 'Parent Area', 'properties' ),
				'parent_item_colon'          => __( 'Parent Area:', 'properties' ),
				'new_item_name'              => __( 'New Area Name', 'properties' ),
				'add_new_item'               => __( 'Add New Area', 'properties' ),
				'edit_item'                  => __( 'Edit Area', 'properties' ),
				'update_item'                => __( 'Update Area', 'properties' ),
				'view_item'                  => __( 'View Area', 'properties' ),
				'separate_items_with_commas' => __( 'Separate areas with commas', 'properties' ),
				'add_or_remove_items'        => __( 'Add or remove areas', 'properties' ),
				'choose_from_most_used'      => __( 'Choose from the most used areas', 'properties' ),
				'popular_items'              => __( 'Popular Areas', 'properties' ),
				'search_items'               => __( 'Search Areas', 'properties' ),
				'not_found'                  => __( 'No areas found', 'properties' ),
				'no_terms'                   => __( 'No areas', 'properties' ),
				'items_list'                 => __( 'areas list', 'properties' ),
				'items_list_navigation'      => __( 'areas list navigation', 'properties' ),
			);
			$rewrite = array(
				'slug'                       => self::get_slug_option( 'pp_property_area_slug', _x( 'property-areas', 'URL friendly slug', 'properties' ) ),
				'with_front'                 => false,
				'hierarchical'               => true,
			);
			$args = array(
				'labels'                     => $labels,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
				'rewrite'                    => $rewrite,
			);
			register_taxonomy( 'property_area', array( 'property' ), $args );
		}

		public static function register_property_taxonomy_type() {
			$labels = array(
				'name'                       => _x( 'Types', 'Taxonomy General Name', 'properties' ),
				'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'properties' ),
				'menu_name'                  => __( 'Types', 'properties' ),
				'all_items'                  => __( 'All Types', 'properties' ),
				'parent_item'                => __( 'Parent Type', 'properties' ),
				'parent_item_colon'          => __( 'Parent Type:', 'properties' ),
				'new_item_name'              => __( 'New Type Name', 'properties' ),
				'add_new_item'               => __( 'Add New Type', 'properties' ),
				'edit_item'                  => __( 'Edit Type', 'properties' ),
				'update_item'                => __( 'Update Type', 'properties' ),
				'view_item'                  => __( 'View Type', 'properties' ),
				'separate_items_with_commas' => __( 'Separate types with commas', 'properties' ),
				'add_or_remove_items'        => __( 'Add or remove types', 'properties' ),
				'choose_from_most_used'      => __( 'Choose from the most used types', 'properties' ),
				'popular_items'              => __( 'Popular Types', 'properties' ),
				'search_items'               => __( 'Search Types', 'properties' ),
				'not_found'                  => __( 'No types found', 'properties' ),
				'no_terms'                   => __( 'No types', 'properties' ),
				'items_list'                 => __( 'types list', 'properties' ),
				'items_list_navigation'      => __( 'types list navigation', 'properties' ),
			);
			$rewrite = array(
				'slug'                       => self::get_slug_option( 'pp_property_type_slug', _x( 'property-types', 'URL friendly slug', 'properties' ) ),
				'with_front'                 => false,
				'hierarchical'               => true,
			);
			$args = array(
				'labels'                     => $labels,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
				'rewrite'                    => $rewrite,
			);
			register_taxonomy( 'property_type', array( 'property' ), $args );
		}

		public static function register_property_content_type() {
			$labels = array(
				'name'                  => _x( 'Properties', 'Post Type General Name', 'properties' ),
				'singular_name'         => _x( 'Property', 'Post Type Singular Name', 'properties' ),
				'menu_name'             => __( 'Properties', 'properties' ),
				'name_admin_bar'        => _x( 'Property', 'New in Admin menu bar', 'properties' ),
				'archives'              => __( 'Property Archives', 'properties' ),
				'parent_item_colon'     => __( 'Parent Property:', 'properties' ),
				'all_items'             => __( 'All Properties', 'properties' ),
				'add_new_item'          => __( 'Add New Property', 'properties' ),
				'add_new'               => _x( 'Add New', 'property', 'properties' ),
				'new_item'              => __( 'New Property', 'properties' ),
				'edit_item'             => __( 'Edit Property', 'properties' ),
				'update_item'           => __( 'Update Property', 'properties' ),
				'view_item'             => __( 'View Property', 'properties' ),
				'search_items'          => __( 'Search Properties', 'properties' ),
				'not_found'             => __( 'Not found', 'properties' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'properties' ),
				'featured_image'        => __( 'Featured Image', 'properties' ),
				'set_featured_image'    => __( 'Set featured image', 'properties' ),
				'remove_featured_image' => __( 'Remove featured image', 'properties' ),
				'use_featured_image'    => __( 'Use as featured image', 'properties' ),
				'insert_into_item'      => __( 'Insert into property', 'properties' ),
				'uploaded_to_this_item' => __( 'Uploaded to this property', 'properties' ),
				'items_list'            => __( 'Properties list', 'properties' ),
				'items_list_navigation' => __( 'Properties list navigation', 'properties' ),
				'filter_items_list'     => __( 'Filter properties list', 'properties' ),
			);
			$rewrite = array(
				'slug'                  => self::get_slug_option( 'pp_property_slug', _x( 'properties', 'URL friendly slug', 'properties' ) ),
				'with_front'            => false,
				'pages'                 => true,
				'feeds'                 => true,
			);
			$args = array(
				'label'                 => __( 'Property', 'properties' ),
				'description'           => __( 'Real estate properties.', 'properties' ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
				'taxonomies'            => self::get_property_taxonomy_names(),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-admin-home',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'rewrite'               => $rewrite,
				'capability_type'       => 'post',
			);
			register_post_type( 'property', $args );
		}

		public static function get_slug_option( $option, $default ) {
			return self::is_empty( $slug = get_option( $option ) ) ? $default : $slug;
		}

		public static function is_empty( $data = null ) {
			return empty( $data );
		}

	}
}
