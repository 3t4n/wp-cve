<?php
/**
 * Register custom variations
 *
 * @package    BoldBlocks
 * @author     Phi Phan <mrphipv@gmail.com>
 * @copyright  Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( Variations::class ) ) :
	/**
	 * Manage custom block variations
	 */
	class Variations extends CoreComponent {
		/**
		 * Post type
		 *
		 * @var string
		 */
		protected $post_type = 'boldblocks_variation';

		/**
		 * Post type helper instance
		 *
		 * @var PostType
		 */
		protected $post_type_helper;

		/**
		 * Store all variations
		 *
		 * @var array
		 */
		protected $all_variations = [];

		/**
		 * Max items that can query
		 *
		 * @var integer
		 */
		protected $max_items = 200;

		/**
		 * List of custom style for variations
		 *
		 * @var array
		 */
		private $variation_style_array = [];

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

			// Custom variation columns.
			add_filter( 'manage_edit-' . $this->post_type . '_columns', [ $this, 'add_variation_custom_columns' ] );
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', [ $this, 'manage_variation_columns' ], 10, 2 );
			add_filter( 'manage_edit-' . $this->post_type . '_sortable_columns', [ $this, 'variation_sortable_columns' ] );
			add_action( 'pre_get_posts', [ $this, 'pre_get_posts_order_variation_custom_columns' ], 1 );

			// Load custom block variation styles.
			add_action( 'init', [ $this, 'load_custom_block_variation_styles' ] );

			// Only do customization on 'edit.php' page for variation.
			add_action( 'load-edit.php', [ $this, 'admin_listing_variation_page' ] );

			// Load data for js.
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );

			// Update template on the fly for variation post type.
			add_action( 'admin_init', [ $this, 'update_template_for_variation' ] );

			// Add rest api endpoint to create variations.
			add_action( 'rest_api_init', [ $this, 'build_create_variation_endpoint' ] );

			// Add meta fields to meta revisioning.
			add_filter( 'boldblocks_post_revision_meta_keys', [ $this, 'add_fields_to_revision_meta_keys' ] );

			// Clear transient cache.
			add_action( 'save_post_' . $this->post_type, [ $this, 'clear_transient_cache' ] );

			// Clear the transient cache on upgraded.
			add_action( 'cbb_version_upgraded', [ $this, 'clear_transient_cache' ] );

			// Enqueue styles for the iframe editor.
			add_filter( 'block_editor_settings_all', [ $this, 'enqueue_style_for_the_editor' ] );

			// Enqueue custom scripts & styles for custom variations.
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_custom_variation_scripts' ] );
		}

		/**
		 * Register custom post type
		 *
		 * @return void
		 */
		public function register_custom_post_type() {
			// Register variation type.
			$this->post_type_helper->register_post_type(
				$this->post_type,
				[
					'rest_base'         => 'boldblocks-variations',
					'show_in_menu'      => 'edit.php?post_type=boldblocks_block',
					'show_in_admin_bar' => false,
					'capabilities'      => array(
						'create_posts' => 'do_not_allow',
					),
					'map_meta_cap'      => true,
				],
				[
					'name'          => _x( 'Block Variations', 'post type general name', 'content-blocks-builder' ),
					'singular_name' => _x( 'Block Variation', 'post type singular name', 'content-blocks-builder' ),
					'all_items'     => __( 'All Variations', 'content-blocks-builder' ),
				]
			);

			// Meta fields.
			$this->register_meta( 'boldblocks_variation_block_name' );

			$this->register_meta( 'boldblocks_variation_name' );

			$this->register_meta( 'boldblocks_variation_description' );

			$this->register_meta( 'boldblocks_variation_icon' );

			$this->register_meta(
				'boldblocks_variation_is_default',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_variation_is_transformable',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_variation_hide_from_inserter',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_variation_data',
				[
					'default' => '{}',
				]
			);

			$this->register_meta(
				'boldblocks_variation_dependent_blocks',
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

			$this->register_meta( 'boldblocks_variation_custom_style' );

			// Register a block style for a custom variation.
			$this->register_meta(
				'boldblocks_variation_enable_style',
				[
					'type'    => 'boolean',
					'default' => false,
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
		 * Load all custom variation styles
		 *
		 * @return void
		 */
		public function load_custom_block_variation_styles() {
			// Load all custom variations.
			$all_custom_variations = $this->get_all_variations();

			foreach ( $all_custom_variations as $block_variation ) {
				$variation_handle = false;
				$custom_style     = $block_variation['custom_style'] ?? false;
				if ( $custom_style ) {
					$variation_handle = $block_variation['variationClass'];
					wp_register_style( $variation_handle, '' );
					wp_add_inline_style( $variation_handle, $custom_style );

					wp_enqueue_block_style( $block_variation['blockName'], [ 'handle' => $variation_handle ] );

					// Save custom style.
					$this->save_variation_style( $variation_handle, $custom_style, $block_variation );
				}

				if ( $block_variation['enable_block_style'] ?? false ) {
					$style_args = [
						'name'  => str_replace( '/', '-', $block_variation['variationName'] ?? '' ),
						'label' => $block_variation['title'] ?? '',
					];

					if ( $variation_handle ) {
						$style_args['style_handle'] = $variation_handle;
					}

					register_block_style(
						$block_variation['blockName'] ?? '',
						$style_args
					);
				}
			}
		}

		/**
		 * Enqueue editor assets
		 *
		 * @return void
		 */
		public function enqueue_block_editor_assets() {
			// Custom blocks access file.
			$variations_asset = $this->the_plugin_instance->include_file( 'build/variations.asset.php' );

			// Scripts.
			wp_enqueue_script(
				'boldblocks-variations',
				$this->the_plugin_instance->get_file_uri( '/build/variations.js' ),
				$variations_asset['dependencies'] ?? [],
				$this->the_plugin_instance->get_script_version( $variations_asset ),
				[ 'in_footer' => true ]
			);

			// Add translation.
			wp_set_script_translations( 'boldblocks-variations', 'content-blocks-builder' );
		}

		/**
		 * Update template for variation
		 *
		 * @return void
		 */
		public function update_template_for_variation() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			global $pagenow;

			if ( 'post.php' === $pagenow && isset( $_GET['post'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id      = absint( $_GET['post'] );
				$current_post = get_post( $post_id );

				if ( ! $current_post || ! $current_post instanceof \WP_Post ) {
					return;
				}

				if ( $this->post_type !== $current_post->post_type ) {
					return;
				}

				$block_name = get_post_meta( $post_id, 'boldblocks_variation_block_name', true );
				if ( $block_name ) {
					$post_type_object           = get_post_type_object( $this->post_type );
					$post_type_object->template = array(
						array( $block_name ),
					);
				}
			}
		}

		/**
		 * Get all variations
		 *
		 * @return array
		 */
		public function get_all_variations() {
			if ( ! $this->all_variations ) {
				$this->all_variations = $this->get_posts();
			}

			return $this->all_variations;
		}

		/**
		 * Query posts and cache the results.
		 *
		 * @param bool $force_refresh Optional. Whether to force the cache to be refreshed. Default false.
		 * @return array Array of WP_Post objects.
		 */
		public function get_posts( $force_refresh = false ) {
			$cache_key = 'bb_variation_posts';

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
		 * Query and parse variations
		 *
		 * @return array
		 */
		public function query_posts() {
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

			$custom_style_handler = $this->the_plugin_instance->get_component( CustomStyle::class );

			$variation_posts = array_map(
				function( $item ) use ( $custom_style_handler ) {
					$variation_name  = get_post_meta( $item->ID, 'boldblocks_variation_name', true );
					$variation_class = 'is-style-' . str_replace( '/', '-', $variation_name );

					$custom_style = get_post_meta( $item->ID, 'boldblocks_variation_custom_style', true );
					if ( $custom_style ) {
						$custom_style = $custom_style_handler->refine_custom_value( $custom_style, [ 'selector' => '.' . $variation_class ], 'CSS' );
					}

					return [
						'id'                 => $item->ID,
						'blockName'          => get_post_meta( $item->ID, 'boldblocks_variation_block_name', true ),
						'variationName'      => $variation_name,
						'title'              => $item->post_title,
						'postContent'        => $item->post_content,
						'description'        => get_post_meta( $item->ID, 'boldblocks_variation_description', true ),
						'blockIcon'          => get_post_meta( $item->ID, 'boldblocks_variation_icon', true ),
						'isDefault'          => ! ! get_post_meta( $item->ID, 'boldblocks_variation_is_default', true ),
						'isTransformable'    => ! ! get_post_meta( $item->ID, 'boldblocks_variation_is_transformable', true ),
						'hideFromInserter'   => ! ! get_post_meta( $item->ID, 'boldblocks_variation_hide_from_inserter', true ),
						'variationData'      => get_post_meta( $item->ID, 'boldblocks_variation_data', true ),
						'dependentBlocks'    => get_post_meta( $item->ID, 'boldblocks_variation_dependent_blocks', true ),
						'variationClass'     => $variation_class,
						'custom_style'       => $custom_style,
						'enable_block_style' => ! ! get_post_meta( $item->ID, 'boldblocks_variation_enable_style', true ),
					];
				},
				$raw_posts
			);

			return $variation_posts;
		}

		/**
		 * Clear transient cache
		 *
		 * @return void
		 */
		public function clear_transient_cache() {
			delete_transient( 'bb_variation_posts' );
		}

		/**
		 * Build a custom endpoint to create variation
		 *
		 * @return void
		 */
		public function build_create_variation_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/createVariation/',
				array(
					'methods'             => 'POST',
					'callback'            => [ $this, 'create_variation' ],
					'permission_callback' => function () {
						return current_user_can( 'publish_posts' );
					},
				)
			);
		}

		/**
		 * Create variation
		 *
		 * @param WP_REST_Request $request The request object.
		 * @return void
		 */
		public function create_variation( $request ) {
			$args = $request->get_params();

			if ( empty( $args['title'] ) || empty( $args['content'] ) || empty( $args['meta'] ) ) {
				wp_send_json_error( __( 'Invalid request!', 'content-blocks-builder' ), 400 );
			}

			// Using Posts controller to create a custom variation.
			$controller = new \WP_REST_Posts_Controller( $this->post_type );
			$response   = $controller->create_item( $request );

			// Create a hook for new variation created.
			do_action( 'boldblocks_variation_created', $response->data );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( $response );
			}

			if ( $response->status === 201 ) {
				wp_send_json(
					[
						'data'    => __( 'The new variation has been created!', 'content-blocks-builder' ),
						'success' => true,
						'post'    => $response->data,
					]
				);
			}
		}

		/**
		 * Add custom columns to custom post type
		 *
		 * @param  array $columns The array of columns.
		 * @return array
		 */
		public function add_variation_custom_columns( $columns ) {
			$custom_columns = array(
				'block_name'     => esc_html__( 'Block name', 'content-blocks-builder' ),
				'variation_name' => esc_html__( 'Variation name', 'content-blocks-builder' ),
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
		public function manage_variation_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'block_name':
					echo esc_html( get_post_meta( $post_id, 'boldblocks_variation_block_name', true ) );
					break;

				case 'variation_name':
					echo esc_html( get_post_meta( $post_id, 'boldblocks_variation_name', true ) );
					break;

				// Just break out of the switch statement for everything else.
				default:
					break;
			}
		}

		/**
		 * Make custom columns sortable.
		 *
		 * @param  array $columns The array of columns.
		 * @return array
		 */
		public function variation_sortable_columns( $columns ) {
			$columns['block_name']     = 'block_name';
			$columns['variation_name'] = 'variation_name';

			return $columns;
		}

		/**
		 * Alter the main query for sorting.
		 *
		 * @param  WP_Query $query The main query.
		 * @return void
		 */
		public function pre_get_posts_order_variation_custom_columns( $query ) {
			// Do nothing on front end side.
			if ( ! is_admin() ) {
				return;
			}

			// Only for main query with orderby parameter.
			if ( $query->is_main_query() && $this->post_type === $query->get( 'post_type' ) && $query->get( 'orderby' ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_GET['boldblocks_variation_filter_block'] ) ) {
					// Ignore if we're filtering.
					return;
				}

				$orderby = $query->get( 'orderby' );

				switch ( $orderby ) {
					case 'block_name':
						// set our query's meta_key, which is used for custom fields.
						$query->set( 'meta_key', 'boldblocks_variation_block_name' );
						$query->set( 'orderby', 'meta_value' );
						break;

					case 'variation_name':
						// set our query's meta_key, which is used for custom fields.
						$query->set( 'meta_key', 'boldblocks_variation_name' );
						$query->set( 'orderby', 'meta_value' );
						break;

					default:
						break;
				}
			}

		}

		/**
		 * Add filters for custom variation columns
		 *
		 * @return void
		 */
		public function admin_listing_variation_page() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['post_type'] ) && $this->post_type === $_GET['post_type'] ) {
				add_filter( 'request', [ $this, 'add_custom_columns_to_query_vars' ] );
				add_filter( 'restrict_manage_posts', [ $this, 'add_filter_select_control' ] );
			}
		}

		/**
		 * Add custom vars to query vars
		 *
		 * @param array $query_vars The array of query vars.
		 * @return array
		 */
		public function add_custom_columns_to_query_vars( $query_vars ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['boldblocks_variation_filter_block'] ) ) {
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$query_vars['meta_key'] = 'boldblocks_variation_block_name';
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value, WordPress.Security.NonceVerification.Recommended
				$query_vars['meta_value'] = sanitize_text_field( wp_unslash( $_GET['boldblocks_variation_filter_block'] ) );
			}

			return $query_vars;
		}

		/**
		 * Add a filter select control to filter variation by block name
		 *
		 * @return void
		 */
		public function add_filter_select_control() {
			global $wpdb;
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$block_names = $wpdb->get_col( 'SELECT DISTINCT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "boldblocks_variation_block_name" ORDER BY meta_value' );

			$boldblocks_variation_filter_block = false;
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['boldblocks_variation_filter_block'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$boldblocks_variation_filter_block = sanitize_text_field( wp_unslash( $_GET['boldblocks_variation_filter_block'] ) );
			}
			?>
			<select name="boldblocks_variation_filter_block" id="boldblocks_variation_filter_block">
				<option value=""><?php esc_html_e( 'All Blocks', 'content-blocks-builder' ); ?></option>
				<?php foreach ( $block_names as $block_name ) { ?>
					<option value="<?php echo esc_attr( $block_name ); ?>"<?php selected( $boldblocks_variation_filter_block, $block_name ); ?>><?php echo esc_attr( $block_name ); ?></option>
				<?php } ?>
			</select>
			<?php
		}

		/**
		 * Add custom block's meta fields to meta revisioning.
		 *
		 * @param array $fields
		 * @return array
		 */
		public function add_fields_to_revision_meta_keys( $fields ) {
			return array_merge(
				$fields,
				[
					'boldblocks_variation_description',
					'boldblocks_variation_icon',
					'boldblocks_variation_data',
					'boldblocks_variation_custom_style',
				]
			);
		}

		/**
		 * Get style by variation name
		 *
		 * @param string $variation_name
		 * @param string $style
		 * @param array  $block_variation
		 * @return string
		 */
		private function save_variation_style( $variation_name, $style, $block_variation ) {
			if ( ! isset( $this->variation_style_array[ $variation_name ] ) ) {
				if ( 'core/html' === ( $block_variation['blockName'] ?? '' ) ) {
					$style = str_replace( $block_variation['variationClass'], 'cbb-html-preview', $style );
				}

				$this->variation_style_array[ $variation_name ] = $style;
			}
		}

		/**
		 * Enqueue scripts/styles for the editor
		 *
		 * @param array $editor_settings
		 * @return void
		 */
		public function enqueue_style_for_the_editor( $editor_settings ) {
			$style = '';
			if ( $this->variation_style_array ) {
				foreach ( $this->variation_style_array as $variation_style ) {
					if ( $variation_style ) {
						$style .= $variation_style;
					}
				}
			}

			if ( $style ) {
				$editor_settings['styles'][] = [ 'css' => $style ];
			}

			return $editor_settings;
		}

		/**
		 * Enqueue scripts for custom variations when editing them
		 *
		 * @param string $hook_suffix
		 * @return void
		 */
		public function enqueue_custom_variation_scripts( $hook_suffix ) {
			global $post;
			$screen = get_current_screen();
			if ( 'post.php' === $hook_suffix && $this->post_type === $screen->post_type ) {
				$custom_style_handler = $this->the_plugin_instance->get_component( CustomStyle::class );

				// Styles.
				$custom_style = get_post_meta( $post->ID, 'boldblocks_variation_custom_style', true );

				if ( $custom_style ) {
					$block_name = get_post_meta( $post->ID, 'boldblocks_variation_block_name', true );
					if ( 0 === strpos( $block_name, 'core/' ) ) {
						$block_name = str_replace( 'core/', '', $block_name );
					}
					$block_class   = '.wp-block-post-content > .wp-block-' . str_replace( '/', '-', $block_name );
					$custom_style  = $custom_style_handler->refine_custom_value( $custom_style, [ 'selector' => $block_class ], 'CSS' );
					$custom_style .= '.is-selected,.has-child-selected,.has-child-selected * {animation:none!important;}';

					$variation_handle = 'edit-variation-style';
					wp_register_style( $variation_handle, '' );
					wp_add_inline_style( $variation_handle, $custom_style );
					wp_enqueue_style( $variation_handle );
				}
			}
		}
	}
endif;
