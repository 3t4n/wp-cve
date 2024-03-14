<?php
/**
 * Custom Post Type Staff
 *
 * @package WordPress
 * @subpackage Charitas Lite Plugin
 * @since 1.0.0
 */

class Charitas_Lite_Staff_CPT {
	/**
	 * Construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/* Register custom post type */
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
	}

	/**
	 * Get CPT parameters from Customizer
	 *
	 * @since 1.0.0
	 */
	public function charitas_lite_get_rewrite_url() {
		return get_theme_mod( 'charitas_lite_staff_url_rewrite', 'staff' );
	}

	public function charitas_lite_get_name_singular() {
		return get_theme_mod( 'charitas_lite_staff_singular_name', 'Staff' );
	}

	public function charitas_lite_get_name_plural() {
		return get_theme_mod( 'charitas_lite_staff_plural_name', 'Staff' );
	}


	/**
	 * Register custom post type
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$labels = array(
			'name'                => _x( $this->charitas_lite_get_name_plural(), 'Post Type General Name', 'charitas-lite' ),
			'singular_name'       => _x( $this->charitas_lite_get_name_singular(), 'Post Type Singular Name', 'charitas-lite' ),
			'menu_name'           => __( $this->charitas_lite_get_name_plural(), 'charitas-lite' ),
			'name_admin_bar'      => __( $this->charitas_lite_get_name_singular(), 'charitas-lite' ),
			'parent_item_colon'   => __( 'Parent Item:', 'charitas-lite' ),
			'all_items'           => sprintf( _x( 'All %1$s', 'Plural name', 'charitas-lite' ), $this->charitas_lite_get_name_plural() ),
			'add_new_item'        => sprintf( _x( 'Add New %1$s', 'Singular name', 'charitas-lite' ), $this->charitas_lite_get_name_singular() ),
			'add_new'             => sprintf( _x( 'Add %1$s', 'Singular name', 'charitas-lite' ), $this->charitas_lite_get_name_singular() ),
			'new_item'            => sprintf( _x( 'New %1$s', 'Singular name', 'charitas-lite' ), $this->charitas_lite_get_name_singular() ),
			'edit_item'           => sprintf( _x( 'Edit %1$s', 'Singular name', 'charitas-lite' ), $this->charitas_lite_get_name_singular() ),
			'update_item'         => sprintf( _x( 'Update %1$s', 'Singular name', 'charitas-lite' ), $this->charitas_lite_get_name_singular() ),
			'view_item'           => sprintf( _x( 'View %1$s', 'Singular name', 'charitas-lite' ), $this->charitas_lite_get_name_singular() ),
			'search_items'        => sprintf( _x( 'Search %1$s', 'Singular name', 'charitas-lite' ), $this->charitas_lite_get_name_singular() ),
			'not_found'           => __( 'Not found', 'charitas-lite' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'charitas-lite' ),
		);
		$rewrite = array(
			'slug'                => $this->charitas_lite_get_rewrite_url(),
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => false,
		);
		$args = array(
			'label'               => __( $this->charitas_lite_get_name_singular(), 'charitas-lite' ),
			'description'         => __( 'Add charitable causes to your site.', 'charitas-lite' ),
			'labels'              => $labels,
			'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-id-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'show_in_rest'        => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'post_staff', $args );
	}
}
?>
