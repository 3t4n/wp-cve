<?php

namespace Whodunit\MywpCustomPatterns\Init;

class Core extends Plugin {
	public         $name_cat = 'mywp-category-custom-patterns';
	public         $name_cpt = 'mywp-custom-patterns';
	protected      $main_file;
	private static $_instance;

	public function __construct( $main_file = null ) {
		$this->main_file = $main_file;
		parent::__construct();
		add_action( 'init', array( $this, 'init_cpt' ) );

		$this->init();
	}


	public function get_templates() {
		$mywp_templates = new \WP_Query(
			array(
				'post_type'      => $this->name_cpt,
				'posts_per_page' => - 1,
			)
		);

		if ( $mywp_templates->post_count > 0 ) {
			return $mywp_templates->posts;
		}

		return array();
	}


	public function get_categories() {
		$terms = \get_terms(
			array(
				'taxonomy'   => $this->name_cat,
				'hide_empty' => false,
			)
		);

		if ( ! is_wp_error( $terms ) && isset( $terms ) && count( $terms ) > 0 ) {
			return $terms;
		}

		return array();
	}

	public function init_cpt() {

		$labels = array(
			'name'               => _x( 'Custom Patterns', 'Post Type General Name', 'mywp-custom-patterns' ),
			'singular_name'      => _x( 'Custom Pattern', 'Post Type Singular Name', 'mywp-custom-patterns' ),
			'menu_name'          => __( 'Custom Patterns', 'mywp-custom-patterns' ),
			'all_items'          => __( 'All Custom Patterns', 'mywp-custom-patterns' ),
			'view_item'          => __( 'View Custom Pattern', 'mywp-custom-patterns' ),
			'add_new_item'       => __( 'Add new Custom Pattern', 'mywp-custom-patterns' ),
			'add_new'            => __( 'Add', 'mywp-custom-patterns' ),
			'edit_item'          => __( 'Edit Custom Pattern', 'mywp-custom-patterns' ),
			'update_item'        => __( 'Update Custom Pattern', 'mywp-custom-patterns' ),
			'search_items'       => __( 'Find a Custom Pattern', 'mywp-custom-patterns' ),
			'not_found'          => __( 'Not found', 'mywp-custom-patterns' ),
			'not_found_in_trash' => __( 'Not found in the trash', 'mywp-custom-patterns' ),
		);

		$args = array(
			'label'             => __( 'Custom Patterns', 'mywp-custom-patterns' ),
			'description'       => __( 'All Custom Patterns', 'mywp-custom-patterns' ),
			'labels'            => $labels,
			'supports'          => array( 'title', 'editor', 'custom-fields' ),
			'show_in_rest'      => true,
			'show_ui'           => true,
			'show_in_admin_bar' => false,
			'hierarchical'      => false,
			'public'            => false,
			'has_archive'       => false,
			'menu_icon'         => 'data:image/svg+xml;base64,' . base64_encode( '<svg width="17" height="17" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg"><g fill="#FFF" fill-rule="nonzero"><path d="M9 6h8v5H9zM16.667 0H9v5h8V.312C17 .141 16.85 0 16.667 0zM9 17h7.667c.183 0 .333-.14.333-.313V12H9v5zM.333 17H8V9H0v7.667c0 .183.15.333.333.333zM8 0H.333A.334.334 0 0 0 0 .333V8h8V0z" /></g></svg>' ),
			'rewrite'           => array( 'slug' => $this->name_cpt ),

		);

		register_post_type( $this->name_cpt, $args );


		register_taxonomy(
			$this->name_cat,
			$this->name_cpt,
			array(
				'label'             => __( 'Categories', 'mywp-custom-patterns' ),
				'labels'            => array(
					'name'          => __( 'Categories', 'mywp-custom-patterns' ),
					'singular_name' => __( 'Category', 'mywp-custom-patterns' ),
					'all_items'     => __( 'All categories', 'mywp-custom-patterns' ),
					'edit_item'     => __( 'Edit category', 'mywp-custom-patterns' ),
					'view_item'     => __( 'Show category', 'mywp-custom-patterns' ),
					'update_item'   => __( 'Edit category', 'mywp-custom-patterns' ),
					'add_new_item'  => __( 'Add category', 'mywp-custom-patterns' ),
					'new_item_name' => __( 'New category', 'mywp-custom-patterns' ),
					'search_items'  => __( 'Search categories', 'mywp-custom-patterns' ),
					'popular_items' => __( 'Popular categories', 'mywp-custom-patterns' ),
				),
				'public'            => false,
				'show_ui'           => true,
				'show_tagcloud'     => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'has_archive'       => false,
				'hierarchical'      => false,
				'query_var'         => false,
			)
		);


	}

	/**
	 * Plugin init setup
	 *
	 * @return void
	 */
	public function init() {
		$this->init_class( 'Enqueue', 'Admin' );
		$this->init_class( 'Patterns', 'Admin' );
		$this->init_class( 'Ajax', 'Admin' );
	}

	/**
	 * Init class easily to avoid lot of namespace use
	 *
	 * @param string $name      Class name to init
	 * @param string $namespace Namespace to use
	 *
	 * @return mixed Class instance
	 */
	public function init_class( $name, $namespace ) {
		$class_name = '\\Whodunit\\MywpCustomPatterns\\' . $namespace . '\\' . ( $name );

		return new $class_name( $this );
	}

	/**
	 * Get instance of this class
	 *
	 * @return Core Instance of this class
	 */
	public static function get_instance( $main_file ): Core {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new Core( $main_file );
		}

		return self::$_instance;
	}
}
