<?php
namespace Thim_EL_Kit\Modules\MegaMenu;

use Thim_EL_Kit\SingletonTrait;

class Custom_Post_Type {
	use SingletonTrait;

	const CPT = 'thim_ekits_menu';

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	public function register_post_type() {
		$labels = array(
			'name'          => esc_html__( 'Mega Menu Items', 'thim-elementor-kit' ),
			'singular_name' => esc_html__( 'Mega Menu Item', 'thim-elementor-kit' ),
			'add_new'       => esc_html__( 'Add New Mega Menu Item', 'thim-elementor-kit' ),
			'add_new_item'  => esc_html__( 'Add New Mega Menu Item', 'thim-elementor-kit' ),
			'edit_item'     => esc_html__( 'Edit Mega Menu Item', 'thim-elementor-kit' ),
			'menu_name'     => esc_html__( 'Mega Menu Items', 'thim-elementor-kit' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-admin-page',
			'show_ui'             => false,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'publicly_queryable'  => true,
			'can_export'          => true,
			'query_var'           => true,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'elementor' ),
		);

		register_post_type( self::CPT, $args );
	}
}

Custom_Post_Type::instance();
