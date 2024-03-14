<?php
/**
 * Google CLassroom Integration class.
 *
 * @since 1.8.3
 *
 * @package Masteriyo\PostType;
 */

namespace Masteriyo\Addons\GoogleClassroomIntegration\PostType;

use Masteriyo\PostType\PostType;

/**
 * GoogleClassroomIntegration class.
 */
class GoogleClassroomIntegration extends PostType {

	/**
	 * Constructor.
	 *
	 * @since 1.8.3
	 */
	public function __construct() {
		$debug = masteriyo_is_post_type_debug_enabled();

		$this->labels = array(
			'name'                  => _x( 'Google Classroom', 'Google Classroom General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Google Classroom', 'Google Classroom Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Google Classrooms', 'masteriyo' ),
			'name_admin_bar'        => __( 'Google Classroom', 'masteriyo' ),
			'archives'              => __( 'Google Classroom Archives', 'masteriyo' ),
			'attributes'            => __( 'Google Classroom Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Google Classroom:', 'masteriyo' ),
			'all_items'             => __( 'All Google Classrooms', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Google Classroom', 'masteriyo' ),
			'edit_item'             => __( 'Edit Google Classroom', 'masteriyo' ),
			'update_item'           => __( 'Update Google Classroom', 'masteriyo' ),
			'view_item'             => __( 'View Google Classroom', 'masteriyo' ),
			'view_items'            => __( 'View Google Classrooms', 'masteriyo' ),
			'search_items'          => __( 'Search Google Classroom', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash.', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into Google Classroom', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Google Classroom', 'masteriyo' ),
			'items_list'            => __( 'Google Classrooms list', 'masteriyo' ),
			'items_list_navigation' => __( 'Google Classrooms list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter Google Classrooms list', 'masteriyo' ),
		);

		$this->args = array(
			'label'               => __( 'Google Classroom', 'masteriyo' ),
			'description'         => __( 'Google Classroom Description', 'masteriyo' ),
			'labels'              => $this->labels,
			'supports'            => array( 'title', 'custom-fields' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'menu_position'       => 5,
			'public'              => $debug,
			'show_ui'             => $debug,
			'show_in_menu'        => $debug,
			'show_in_admin_bar'   => $debug,
			'show_in_nav_menus'   => $debug,
			'show_in_rest'        => false,
			'has_archive'         => false,
			'map_meta_cap'        => true,
			'capability_type'     => array( 'google_classroom', 'google_classrooms' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'can_export'          => true,
			'delete_with_user'    => false,
		);
	}
}
