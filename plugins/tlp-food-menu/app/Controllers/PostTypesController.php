<?php
/**
 * Custom Post Type Register Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Custom Post Type Register Class.
 */
class PostTypesController {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Post Type Slug.
	 *
	 * @var string
	 */
	private $post_type_slug;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		$settings = get_option( TLPFoodMenu()->options['settings'] );

		$this->post_type_slug = isset( $settings['slug'] ) ? ( $settings['slug'] ? sanitize_title_with_dashes( $settings['slug'] ) : 'food-menu' ) : 'food-menu';

		$this
			->post_types()
			->taxonomies()
			->flush();
	}

	/**
	 * Post Type Definition.
	 *
	 * @return PostTypesController
	 */
	protected function post_types() {
		if ( empty( $this->post_type_slug ) ) {
			return $this;
		}

		$post_types = $this->post_type_args();

		if ( empty( $post_types ) ) {
			return $this;
		}

		foreach ( $post_types as $post_type => $args ) {
			\register_post_type( $post_type, $args );
		}

		\add_filter( 'post_updated_messages', [ $this, 'post_updated_messages' ] );

		return $this;
	}

	/**
	 * Taxonomy Definition.
	 *
	 * @return PostTypesController
	 */
	protected function taxonomies() {
		$taxonomies = $this->taxonomy_args();

		if ( empty( $taxonomies ) ) {
			return;
		}

		foreach ( $taxonomies as $taxonomy => $args ) {
			\register_taxonomy( TLPFoodMenu()->taxonomies[ $taxonomy ], [ TLPFoodMenu()->post_type ], $args );
		}

		return $this;
	}

	/**
	 * Post Type Arguments.
	 *
	 * @return array
	 */
	private function post_type_args() {
		$args = [];

		/**
		 * Post Type: Food Menu.
		 */
		$args[ TLPFoodMenu()->post_type ] = [
			'label'           => esc_html__( 'Food Menu', 'tlp-food-menu' ),
			'description'     => esc_html__( 'Food Menu', 'tlp-food-menu' ),
			'supports'        => [
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'page-attributes',
			],
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => [
				'slug'       => $this->post_type_slug,
				'with_front' => false,
				'feeds'      => true,
			],
			'menu_position'   => 20,
			'menu_icon'       => TLPFoodMenu()->assets_url() . 'images/icon-16x16.png',
		];

		$args[ TLPFoodMenu()->post_type ]['labels'] = [
			'menu_name'          => esc_html__( 'Food Menu', 'tlp-food-menu' ),
			'name'               => esc_html__( 'Food Menu', 'tlp-food-menu' ),
			'singular_name'      => esc_html__( 'Food Menu', 'tlp-food-menu' ),
			'all_items'          => esc_html__( 'All Foods', 'tlp-food-menu' ),
			'add_new'            => esc_html__( 'Add Food', 'tlp-food-menu' ),
			'add_new_item'       => esc_html__( 'Add Food', 'tlp-food-menu' ),
			'edit_item'          => esc_html__( 'Edit Food', 'tlp-food-menu' ),
			'new_item'           => esc_html__( 'New Food', 'tlp-food-menu' ),
			'view_item'          => esc_html__( 'View Food', 'tlp-food-menu' ),
			'search_items'       => esc_html__( 'Search Food', 'tlp-food-menu' ),
			'not_found'          => esc_html__( 'No Food found', 'tlp-food-menu' ),
			'not_found_in_trash' => esc_html__( 'No Food in the trash', 'tlp-food-menu' ),
		];

		/**
		 * Post Type: Shortcodes.
		 */
		$args[ TLPFoodMenu()->shortCodePT ] = [
			'label'               => esc_html__( 'Shortcode', 'tlp-food-menu' ),
			'description'         => esc_html__( 'Food menu pro shortcode generator', 'tlp-food-menu' ),
			'supports'            => [ 'title' ],
			'public'              => false,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=' . TLPFoodMenu()->post_type,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
		];

		$args[ TLPFoodMenu()->shortCodePT ]['labels'] = [
			'all_items'          => esc_html__( 'Shortcode Generator', 'tlp-food-menu' ),
			'menu_name'          => esc_html__( 'Shortcode', 'tlp-food-menu' ),
			'singular_name'      => esc_html__( 'Shortcode', 'tlp-food-menu' ),
			'edit_item'          => esc_html__( 'Edit Shortcode', 'tlp-food-menu' ),
			'new_item'           => esc_html__( 'New Shortcode', 'tlp-food-menu' ),
			'view_item'          => esc_html__( 'View Shortcode', 'tlp-food-menu' ),
			'search_items'       => esc_html__( 'Shortcode Locations', 'tlp-food-menu' ),
			'not_found'          => esc_html__( 'No Shortcode found.', 'tlp-food-menu' ),
			'not_found_in_trash' => esc_html__( 'No Shortcode found in trash.', 'tlp-food-menu' ),
		];

		return $args;
	}

	/**
	 * Taxonomy Arguments.
	 *
	 * @return array
	 */
	private function taxonomy_args() {
		$args = [];

		/**
		 * Taxonomy: Category.
		 */
		$args['category'] = [
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => [
				'slug'         => $this->post_type_slug . '-category',
				'with_front'   => false,
				'hierarchical' => true,
			],
			'show_admin_column' => true,
			'query_var'         => true,
		];

		$args['category']['labels'] = [
			'name'                       => esc_html__( 'Categories', 'tlp-food-menu' ),
			'singular_name'              => esc_html__( 'Category', 'tlp-food-menu' ),
			'menu_name'                  => esc_html__( 'Categories', 'tlp-food-menu' ),
			'edit_item'                  => esc_html__( 'Edit Category', 'tlp-food-menu' ),
			'update_item'                => esc_html__( 'Update Category', 'tlp-food-menu' ),
			'add_new_item'               => esc_html__( 'Add New Category', 'tlp-food-menu' ),
			'new_item_name'              => esc_html__( 'New Category', 'tlp-food-menu' ),
			'parent_item'                => esc_html__( 'Parent Category', 'tlp-food-menu' ),
			'parent_item_colon'          => esc_html__( 'Parent Category:', 'tlp-food-menu' ),
			'all_items'                  => esc_html__( 'All Categories', 'tlp-food-menu' ),
			'search_items'               => esc_html__( 'Search Categories', 'tlp-food-menu' ),
			'popular_items'              => esc_html__( 'Popular Categories', 'tlp-food-menu' ),
			'separate_items_with_commas' => esc_html__( 'Separate categories with commas', 'tlp-food-menu' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove categories', 'tlp-food-menu' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used  categories', 'tlp-food-menu' ),
			'not_found'                  => esc_html__( 'No categories found.', 'tlp-food-menu' ),
		];

		return $args;
	}

	/**
	 * Post Updated Messeges
	 *
	 * @param array $messages Message.
	 * @return array
	 */
	public function post_updated_messages( $messages ) {
		$messages[ TLPFoodMenu()->shortCodePT ] = [
			0  => '', // Unused. Messages start at index 1.
			1  => esc_html__( 'ShortCode options updated.', 'tlp-food-menu' ),
			2  => esc_html__( 'ShortCode options updated.', 'tlp-food-menu' ),
			3  => esc_html__( 'Custom field deleted.', 'tlp-food-menu' ),
			4  => esc_html__( 'ShortCode updated.', 'tlp-food-menu' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf(
				esc_html__(
					'ShortCode restored to revision from %s',
					'tlp-food-menu'
				),
				wp_post_revision_title( (int) $_GET['revision'], false )
			) : false,
			6  => esc_html__( 'ShortCode published.', 'tlp-food-menu' ),
			7  => esc_html__( 'ShortCode saved.', 'tlp-food-menu' ),
			8  => esc_html__( 'ShortCode submitted.', 'tlp-food-menu' ),
			9  => esc_html__( 'ShortCode scheduled for.', 'tlp-food-menu' ),
			10 => esc_html__( 'ShortCode draft updated.', 'tlp-food-menu' ),
		];

		return $messages;
	}

	/**
	 * Remove rewrite rules and then recreate rewrite rules.
	 *
	 * @return void
	 */
	private function flush() {
		$flush = get_option( TLPFoodMenu()->options['flash'] );

		if ( $flush ) {
			\flush_rewrite_rules();
			update_option( TLPFoodMenu()->options['flash'], false );
		}
	}
}
