<?php
/**
 * The PostType helper class
 *
 * @package   BoldBlocks
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2023, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( PostType::class ) ) :
	/**
	 * A helper class to register post type, taxonomy, and meta fields.
	 */
	class PostType {
		/**
		 * Class instance
		 *
		 * @var PostType
		 */
		private static $instance;

		/**
		 * A dummy constructor
		 */
		private function __construct() {}

		/**
		 * Initialize the instance.
		 *
		 * @return PostType
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new PostType();
			}

			return self::$instance;
		}

		/**
		 * Generate thumbnail column for custom post type.
		 *
		 * @param string $post_type
		 * @return void
		 */
		public function generate_thumbnail_column( $post_type ) {
			add_filter( 'manage_edit-' . $post_type . '_columns', [ $this, 'add_thumbnail_custom_columns' ] );
			add_action( 'manage_' . $post_type . '_posts_custom_column', [ $this, 'manage_thunbnail_custom_columns' ], 10, 2 );
			add_action( 'admin_head', [ $this, 'add_thumbnail_custom_columns_style' ] );
		}

		/**
		 * Register a custom post type
		 *
		 * @param string $post_type
		 * @param array  $args
		 * @param array  $labels
		 * @return void
		 */
		public function register_post_type( $post_type, $args = [], $labels = [] ) {
			if ( empty( $labels['name'] ) || empty( $labels['singular_name'] ) ) {
				_doing_it_wrong( __CLASS__ . '->' . __FUNCTION__, __( 'name, singular_name labels are required', 'content-blocks-builder' ) );
				return;
			}

			$name          = $labels['name'];
			$singular_name = $labels['singular_name'];

			// Post type args.
			$labels = wp_parse_args(
				$labels,
				[
					'name'               => $name,
					'singular_name'      => $singular_name,
					'menu_name'          => $name,
					'name_admin_bar'     => $singular_name,
					'add_new'            => sprintf( __( 'Add New %s', 'content-blocks-builder' ), $singular_name ),
					'add_new_item'       => sprintf( __( 'Add New %s', 'content-blocks-builder' ), $singular_name ),
					'new_item'           => sprintf( __( 'New %s', 'content-blocks-builder' ), $singular_name ),
					'edit_item'          => sprintf( __( 'Edit %s', 'content-blocks-builder' ), $singular_name ),
					'view_item'          => sprintf( __( 'View %s', 'content-blocks-builder' ), $singular_name ),
					'all_items'          => sprintf( __( 'All %s', 'content-blocks-builder' ), $name ),
					'search_items'       => sprintf( __( 'Search %s', 'content-blocks-builder' ), $name ),
					'parent_item_colon'  => __( 'Parent item:', 'content-blocks-builder' ),
					'not_found'          => __( 'No items found.', 'content-blocks-builder' ),
					'not_found_in_trash' => __( 'No items found in trash.', 'content-blocks-builder' ),
				]
			);

			$args = wp_parse_args(
				$args,
				[
					'labels'              => $labels,
					'description'         => sprintf( __( 'All %s', 'content-blocks-builder' ), $name ),
					'public'              => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => true,
					'show_in_rest'        => true,
					'query_var'           => false,
					'rewrite'             => false,
					'capability_type'     => 'post',
					'has_archive'         => false,
					'hierarchical'        => false,
					'menu_position'       => 5,
					'supports'            => [ 'title', 'editor', 'author', 'custom-fields', 'revisions', 'page-attributes' ],
					'can_export'          => true,
					'template_lock'       => 'insert',
				]
			);

			// Register post type.
			register_post_type( $post_type, $args );
		}

		/**
		 * Register a custom taxonomy
		 *
		 * @param string       $taxonomy_name
		 * @param string|array $post_type
		 * @param array        $args
		 * @param array        $labels
		 * @return void
		 */
		public function register_taxonomy( $taxonomy_name, $post_type, $args = [], $labels = [] ) {
			if ( empty( $labels['name'] ) || empty( $labels['singular_name'] ) ) {
				_doing_it_wrong( __CLASS__ . '->' . __FUNCTION__, __( 'name, singular_name labels are required', 'content-blocks-builder' ) );
				return;
			}

			$name          = $labels['name'];
			$singular_name = $labels['singular_name'];

			// Taxonomy args.
			$labels = wp_parse_args(
				$labels,
				[
					'name'                       => $name,
					'singular_name'              => $singular_name,
					'menu_name'                  => $name,
					'all_items'                  => __( 'All Items', 'content-blocks-builder' ),
					'new_item_name'              => __( 'New Item Name', 'content-blocks-builder' ),
					'add_new_item'               => __( 'Add New Item', 'content-blocks-builder' ),
					'edit_item'                  => __( 'Edit Item', 'content-blocks-builder' ),
					'update_item'                => __( 'Update Item', 'content-blocks-builder' ),
					'view_item'                  => __( 'View Item', 'content-blocks-builder' ),
					'separate_items_with_commas' => __( 'Separate items with commas', 'content-blocks-builder' ),
					'add_or_remove_items'        => __( 'Add or remove items', 'content-blocks-builder' ),
					'choose_from_most_used'      => __( 'Choose from the most used', 'content-blocks-builder' ),
					'popular_items'              => __( 'Popular Items', 'content-blocks-builder' ),
					'search_items'               => __( 'Search Items', 'content-blocks-builder' ),
					'not_found'                  => __( 'Not Found', 'content-blocks-builder' ),
				]
			);

			$args = wp_parse_args(
				$args,
				[
					'labels'            => $labels,
					'hierarchical'      => false,
					'public'            => false,
					'show_ui'           => true,
					'show_in_menu'      => false,
					'show_admin_column' => true,
					'show_in_nav_menus' => false,
					'show_tagcloud'     => false,
					'query_var'         => false,
					'show_in_rest'      => true,
				]
			);

			register_taxonomy( $taxonomy_name, $post_type, $args );
		}

		/**
		 * Register custom meta field
		 *
		 * @param string $post_type
		 * @param string $meta_key
		 * @param array  $args
		 * @return void
		 */
		public function register_meta( $post_type, $meta_key, $args = [] ) {
			register_meta(
				'post',
				$meta_key,
				wp_parse_args(
					$args,
					[
						'object_subtype' => $post_type,
						'show_in_rest'   => true,
						'single'         => true,
						'type'           => 'string',
						'default'        => '',
					]
				)
			);
		}

		/**
		 * Add custom thumbnail column to custom post type
		 *
		 * @param  array $columns The array of columns.
		 * @return array
		 */
		public function add_thumbnail_custom_columns( $columns ) {
			// Thumbnail column.
			$thumbnail_column = [
				'thumbnail' => esc_html__( 'Thumbnail', 'content-blocks-builder' ),
			];

			// Put the thumbnail column right after the first column.
			$return = array_slice( $columns, 0, 1, true ) + $thumbnail_column + array_slice( $columns, 1, count( $columns ) - 1, true );

			return $return;
		}

		/**
		 * Manage custom columns.
		 *
		 * @param string $column  the column name.
		 * @param int    $post_id the post id.
		 */
		public function manage_thunbnail_custom_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'thumbnail':
					echo get_the_post_thumbnail( $post_id, [ 60, 60 ] );
					break;

				// Just break out of the switch statement for everything else.
				default:
					break;
			}
		}

		/**
		 * Add style to custom columns
		 *
		 * @return void
		 */
		public function add_thumbnail_custom_columns_style() {
			echo '<style>.column-thumbnail {width: 70px}</style>';
		}
	}
endif;
