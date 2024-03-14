<?php
/**
 * Register custom patterns
 *
 * @package    BoldBlocks
 * @author     Phi Phan <mrphipv@gmail.com>
 * @copyright  Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( Patterns::class ) ) :
	/**
	 * Manage custom patterns.
	 */
	class Patterns extends CoreComponent {
		/**
		 * Post type
		 *
		 * @var string
		 */
		protected $post_type = 'boldblocks_pattern';

		/**
		 * Post type helper instance
		 *
		 * @var PostType
		 */
		protected $post_type_helper;

		/**
		 * Store all custom patterns
		 *
		 * @var array
		 */
		protected $all_custom_patterns = [];

		/**
		 * Max items that can query
		 *
		 * @var integer
		 */
		protected $max_items = 200;

		/**
		 * Constructor
		 */
		public function __construct( $the_plugin_instance ) {
			parent::__construct( $the_plugin_instance );

			$this->post_type_helper = PostType::get_instance();
		}

		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Register custom post type.
			add_action( 'init', [ $this, 'register_custom_post_type' ], 5 );

			// Load data for js.
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );

			// Register patterns.
			add_action( 'init', [ $this, 'register_patterns' ] );

			// Custom pattern columns.
			add_filter( 'manage_edit-' . $this->post_type . '_columns', [ $this, 'add_pattern_custom_columns' ] );
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', [ $this, 'manage_pattern_columns' ], 10, 2 );

			// Add rest api endpoint to query all pattern categories.
			add_action( 'rest_api_init', [ $this, 'register_get_pattern_categories_endpoint' ] );

			// Register rest fields.
			add_action( 'rest_api_init', [ $this, 'register_rest_fields' ] );

			// Add meta fields to meta revisioning.
			add_filter( 'boldblocks_post_revision_meta_keys', [ $this, 'add_fields_to_revision_meta_keys' ] );

			// Change the placeholder text.
			add_filter( 'enter_title_here', [ $this, 'enter_title_here' ] );

			// Clear transient cache.
			add_action( 'save_post_' . $this->post_type, [ $this, 'clear_transient_cache' ] );

			// Clear the transient cache on upgraded.
			add_action( 'cbb_version_upgraded', [ $this, 'clear_transient_cache' ] );
		}

		/**
		 * Register custom post type
		 *
		 * @return void
		 */
		public function register_custom_post_type() {
			// Register pattern type.
			$this->post_type_helper->register_post_type(
				$this->post_type,
				[
					'rest_base'    => 'boldblocks-patterns',
					'show_in_menu' => 'edit.php?post_type=boldblocks_block',
				],
				[
					'name'           => _x( 'Patterns', 'post type general name', 'content-blocks-builder' ),
					'singular_name'  => _x( 'Pattern', 'post type singular name', 'content-blocks-builder' ),
					'name_admin_bar' => _x( 'Pattern', 'add new on admin bar', 'content-blocks-builder' ),
					'all_items'      => __( 'All Patterns', 'content-blocks-builder' ),
					'add_new'        => __( 'Add New Pattern', 'content-blocks-builder' ),
				]
			);

			// Pattern keywords.
			$this->post_type_helper->register_taxonomy(
				'boldblocks_pattern_keywords',
				$this->post_type,
				[],
				[
					'name'          => _x( 'Keywords', 'Taxonomy General Name', 'content-blocks-builder' ),
					'singular_name' => _x( 'Keyword', 'Taxonomy Singular Name', 'content-blocks-builder' ),
				]
			);

			// Meta fields.
			$this->register_meta(
				'boldblocks_pattern_categories',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'name'  => array(
										'type' => 'string',
									),
									'label' => array(
										'type' => 'string',
									),
								),
							),
						),
					),
					'default'      => [],
				]
			);

			$this->register_meta( 'boldblocks_pattern_description' );

			$this->register_meta(
				'boldblocks_pattern_dependencies',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'slug' => array(
										'type' => 'string',
									),
									'name' => array(
										'type' => 'string',
									),
								),
							),
						),
					),
					'default'      => [],
				]
			);

			$this->register_meta(
				'boldblocks_pattern_dependent_blocks',
				[
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type' => 'string',
							),
						),
					),
					'type'         => 'array',
					'default'      => [],
				]
			);

			$this->register_meta(
				'boldblocks_pattern_block_types',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
					),
					'default'      => [],
				]
			);

			$this->register_meta(
				'boldblocks_pattern_disabled_inserter',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			// Setting fields.
			register_setting(
				'boldblocks',
				'boldblocks_pattern_categories',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'name'  => array(
										'type' => 'string',
									),
									'label' => array(
										'type' => 'string',
									),
								),
							),
						),
					),
					'default'      => [],
				]
			);

			register_setting(
				'boldblocks',
				'boldblocks_pattern_categories_all_label',
				[
					'type'         => 'string',
					'show_in_rest' => true,
					'default'      => __( 'All patterns by CBB', 'content-blocks-builder' ),
				]
			);
		}

		/**
		 * Register meta field
		 *
		 * @param string $meta_key
		 * @param array  $args
		 * @return void
		 */
		private function register_meta( $meta_key, $args = [] ) {
			$this->post_type_helper->register_meta( $this->post_type, $meta_key, $args );
		}

		/**
		 * Enqueue editor assets
		 *
		 * @return void
		 */
		public function enqueue_block_editor_assets() {
			// Custom blocks access file.
			$patterns_asset = $this->the_plugin_instance->include_file( 'build/patterns.asset.php' );

			// Scripts.
			wp_enqueue_script(
				'boldblocks-patterns',
				$this->the_plugin_instance->get_file_uri( '/build/patterns.js' ),
				$patterns_asset['dependencies'] ?? [],
				$this->the_plugin_instance->get_script_version( $patterns_asset ),
				[ 'in_footer' => true ]
			);

			// Add translation.
			wp_set_script_translations( 'boldblocks-patterns', 'content-blocks-builder' );
		}

		/**
		 * Register all custom patterns
		 *
		 * @return void
		 */
		public function register_patterns() {
			// Register custom categories.
			$pattern_categories = get_option( 'boldblocks_pattern_categories', [] );
			if ( is_array( $pattern_categories ) ) {
				foreach ( $pattern_categories as $category ) {
					if ( ! empty( $category['name'] ) && ! empty( $category['label'] ) ) {
						if ( ! \WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $category['name'] ) ) {
							register_block_pattern_category( $category['name'], [ 'label' => $category['label'] ] );
						}
					}
				}
			}

			// Register a custom category for patterns made by BoldBlocks.
			register_block_pattern_category( 'boldblocks', [ 'label' => get_option( 'boldblocks_pattern_categories_all_label', _x( 'All patterns by CBB', 'Block pattern category', 'content-blocks-builder' ) ) ] );

			if ( apply_filters( 'boldblocks_load_patterns', true ) ) {
				// Query all patterns.
				$pattern_posts = $this->get_all_custom_patterns();

				foreach ( $pattern_posts as $pattern_name => $pattern_settings ) {
					register_block_pattern(
						sprintf( 'boldblocks/%s', $pattern_name ),
						$pattern_settings
					);
				}
			}
		}

		/**
		 * Get all custom patterns.
		 *
		 * @return array
		 */
		public function get_all_custom_patterns() {
			if ( ! $this->all_custom_patterns ) {
				$this->all_custom_patterns = $this->get_posts();
			}

			return $this->all_custom_patterns;
		}

		/**
		 * Query posts and cache the results.
		 *
		 * @param bool $force_refresh Optional. Whether to force the cache to be refreshed. Default false.
		 * @return array Array of WP_Post objects.
		 */
		public function get_posts( $force_refresh = false ) {
			$cache_key = 'bb_pattern_posts';

			$posts = get_transient( $cache_key );

			// If nothing is found, build the object.
			if ( true === $force_refresh || false === $posts ) {
				// Query posts.
				$posts = $this->query_posts();

				if ( ! is_wp_error( $posts ) && count( $posts ) > 0 ) {
					set_transient( $cache_key, $posts, HOUR_IN_SECONDS );
				}
			}

			return $posts;
		}

		/**
		 * Query and parse patterns
		 *
		 * @return array
		 */
		public function query_posts() {
			// Query all patterns.
			$raw_posts = get_posts(
				[
					'post_type'      => $this->post_type,
					'posts_per_page' => $this->max_items,
					'orderby'        => [
						'menu_order' => 'DESC',
						'date'       => 'DESC',
					],
				]
			);

			$pattern_posts = [];

			foreach ( $raw_posts as $pattern_post ) {
				$blocks = parse_blocks( $pattern_post->post_content );
				if ( count( $blocks ) === 0 ) {
					continue;
				}

				$categories = get_post_meta( $pattern_post->ID, 'boldblocks_pattern_categories', true );
				if ( ! is_array( $categories ) ) {
					$categories = [];
				} else {
					$categories = array_map(
						function( $item ) {
							return $item['name'] ?? '';
						},
						$categories
					);
				}
				$categories[] = 'boldblocks';

				$pattern_settings = [
					'title'       => wp_strip_all_tags( $pattern_post->post_title ),
					'content'     => $pattern_post->post_content,
					'categories'  => $categories,
					'description' => get_post_meta( $pattern_post->ID, 'boldblocks_pattern_description', true ) ?? '',
					'inserter'    => ! ( get_post_meta( $pattern_post->ID, 'boldblocks_pattern_disabled_inserter', true ) ?? false ),
				];

				$keywords = get_the_terms( $pattern_post, 'boldblocks_pattern_keywords' );
				if ( $keywords && ! is_wp_error( $keywords ) ) {
					$keywords = wp_list_pluck( $keywords, 'name' );
				} else {
					$keywords = false;
				}

				if ( $keywords ) {
					$pattern_settings['keywords'] = $keywords;
				}

				// Add blockTypes.
				$pattern_settings['blockTypes'] = [];
				if ( in_array( 'header', $categories, true ) ) {
					$pattern_settings['blockTypes'][] = 'core/template-part/header';
				}

				if ( in_array( 'footer', $categories, true ) ) {
					$pattern_settings['blockTypes'][] = 'core/template-part/footer';
				}

				if ( in_array( 'query', $categories, true ) ) {
					$pattern_settings['blockTypes'][] = 'core/query';
				}

				$pattern_posts[ sanitize_key( $pattern_post->post_name ) ] = $pattern_settings;

			}

			return $pattern_posts;
		}

		/**
		 * Clear transient cache
		 *
		 * @return void
		 */
		public function clear_transient_cache() {
			delete_transient( 'bb_pattern_posts' );
		}

		/**
		 * Add custom columns to custom post type
		 *
		 * @param  array $columns The array of columns.
		 * @return array
		 */
		public function add_pattern_custom_columns( $columns ) {
			$custom_columns = array(
				'pattern_name' => esc_html__( 'Pattern name', 'content-blocks-builder' ),
			);

			// Add after title column.
			$return = array_slice( $columns, 0, 2, true ) + $custom_columns + array_slice( $columns, 2, count( $columns ) - 2, true );

			return $return;
		}

		/**
		 * Manage custom columns.
		 *
		 * @param string $column  the column name.
		 * @param int    $post_id the post id.
		 */
		public function manage_pattern_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'pattern_name':
					echo esc_html( sprintf( _x( 'boldblocks/%s', 'Pattern name' ), sanitize_key( get_post_field( 'post_name', $post_id ) ) ) );
					break;

				// Just break out of the switch statement for everything else.
				default:
					break;
			}
		}

		/**
		 * Register an endpoint to query pattern categories.
		 *
		 * @return array
		 */
		public function register_get_pattern_categories_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getPatternCategories/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_pattern_categories' ],
					'permission_callback' => '__return_true',
				)
			);
		}

		/**
		 * Build pattern categories.
		 *
		 * @param WP_Rest_Request $request
		 * @return WP_Rest_Response
		 */
		public function get_pattern_categories( $request ) {
			$categories = \WP_Block_Pattern_Categories_Registry::get_instance()->get_all_registered();

			return $categories;
		}

		/**
		 * Add custom block's meta fields to meta revisioning.
		 *
		 * @param array $fields
		 * @return array
		 */
		public function add_fields_to_revision_meta_keys( $fields ) {
			return array_merge( $fields, [ 'boldblocks_pattern_categories' ] );
		}

		/**
		 * Register custom rest fields for easier getting, updating data.
		 *
		 * @return void
		 */
		public function register_rest_fields() {
			register_rest_field(
				$this->post_type,
				'keywords',
				array(
					'get_callback'    => function( $params ) {
						$keywords = wp_list_pluck( wp_get_object_terms( $params['id'], 'boldblocks_pattern_keywords' ), 'name' );

						return \implode( ',', $keywords );
					},
					'update_callback' => function( $value, $post ) {
						wp_set_post_terms( $post->ID, $value, 'boldblocks_pattern_keywords' );
					},
					'schema'          => array(
						'type' => 'string',
					),
				)
			);
		}

		/**
		 * Placeholder text. Default 'Add title'.
		 *
		 * @param string $text
		 * @return string
		 */
		public function enter_title_here( $text ) {
			$post_type = get_post_type();
			if ( $this->post_type === $post_type ) {
				$text = __( 'Add pattern title', 'content-blocks-builder' );
			}

			return $text;
		}
	}
endif;
