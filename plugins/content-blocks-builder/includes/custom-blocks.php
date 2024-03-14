<?php
/**
 * Register custom blocks
 *
 * @package    BoldBlocks
 * @author     Phi Phan <mrphipv@gmail.com>
 * @copyright  Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( CustomBlocks::class ) ) :
	/**
	 * Create/edit custom content blocks.
	 */
	class CustomBlocks extends CoreComponent {
		/**
		 * Post type
		 *
		 * @var string
		 */
		protected $post_type = 'boldblocks_block';

		/**
		 * Post type helper instance
		 *
		 * @var PostType
		 */
		protected $post_type_helper;

		/**
		 * Store all custom blocks
		 *
		 * @var array
		 */
		protected $all_custom_blocks = [];

		/**
		 * Store all names of custom blocks
		 *
		 * @var array
		 */
		protected $custom_blocks = [];

		/**
		 * Store all names of repeater blocks
		 *
		 * @var array
		 */
		protected $repeater_blocks = [];

		/**
		 * Max items that can query
		 *
		 * @var integer
		 */
		protected $max_items = 200;

		/**
		 * The handle for custom blocks scripts
		 *
		 * @var string
		 */
		public $custom_blocks_handle = 'boldblocks-custom-blocks';

		/**
		 * The handle for custom blocks frontend scripts
		 *
		 * @var string
		 */
		public $custom_blocks_frontend_handle = 'boldblocks-custom-blocks-frontend';

		/**
		 * The handle for custom blocks editor scripts
		 *
		 * @var string
		 */
		public $custom_blocks_editor_handle = 'boldblocks-custom-blocks-editor';

		/**
		 * The handle for carousel blocks frontend scripts
		 *
		 * @var string
		 */
		public $carousel_blocks_frontend_handle = 'boldblocks-carousel-frontend';

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

			// Custom admin columns.
			add_filter( 'manage_edit-' . $this->post_type . '_columns', [ $this, 'add_block_custom_columns' ] );
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', [ $this, 'manage_block_columns' ], 10, 2 );
			add_action( 'admin_head', [ $this, 'add_block_custom_columns_style' ] );

			// Register scripts, make sure it is placed before load_custom_blocks.
			add_action( 'init', [ $this, 'register_scripts' ] );

			// Load custom blocks.
			add_action( 'init', [ $this, 'load_custom_blocks' ] );

			// Enqueue block editor style.
			add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_editor_style' ] );

			// Allow creating patterns from custom blocks.
			add_filter( 'boldblocks_get_pattern_allowed_blocks', [ $this, 'allow_creating_patterns' ] );

			// Add meta fields to meta revisioning.
			add_filter( 'boldblocks_post_revision_meta_keys', [ $this, 'add_fields_to_revision_meta_keys' ] );

			// Add theme name to body class.
			add_filter( 'body_class', [ $this, 'add_body_class' ] );

			// Add block version to custom blocks.
			add_action( 'save_post_' . $this->post_type, [ $this, 'save_version_to_block' ], 10, 3 );

			// Prevent users from deleting the core blocks.
			add_filter( 'user_has_cap', [ $this, 'prevent_core_blocks_from_deletion' ], 10, 3 );

			// Change the placeholder text.
			add_filter( 'enter_title_here', [ $this, 'enter_title_here' ] );

			// Register codemirror.
			add_action( 'admin_enqueue_scripts', [ $this, 'register_code_editor_scripts' ] );

			// Clear transient cache.
			add_action( 'save_post_' . $this->post_type, [ $this, 'clear_transient_cache' ] );

			// Clear the transient cache on upgraded.
			add_action( 'cbb_version_upgraded', [ $this, 'clear_transient_cache' ] );

			// Enqueue frontend scripts for carousel layout.
			add_filter( 'render_block', [ $this, 'enqueue_frontend_carousel_scripts' ], 10, 3 );

			// Register rest fields.
			add_action( 'rest_api_init', [ $this, 'register_rest_fields' ] );

			// Hide on the frontend.
			add_filter( 'render_block', [ $this, 'hide_on_frontend' ], 10, 3 );

			// Enqueue custom scripts & styles for custom blocks.
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_custom_block_scripts' ] );

			// Customize the Query Loop block.
			add_action( 'init', [ $this, 'query_loop_extended_filters_and_sorting' ], PHP_INT_MAX );
		}

		/**
		 * Register custom post type
		 *
		 * @return void
		 */
		public function register_custom_post_type() {
			// Register block type.
			$this->post_type_helper->register_post_type(
				$this->post_type,
				[
					'rest_base'     => 'boldblocks-blocks',
					'menu_icon'     => 'dashicons-block-default',
					'menu_position' => 80, // Right after Settings.
				],
				[
					'name'           => _x( 'Custom Blocks', 'post type general name', 'content-blocks-builder' ),
					'singular_name'  => _x( 'Custom Block', 'post type singular name', 'content-blocks-builder' ),
					'name_admin_bar' => _x( 'Block', 'add new on admin bar', 'content-blocks-builder' ),
					'all_items'      => __( 'All Blocks', 'content-blocks-builder' ),
					'add_new'        => __( 'Add New Block', 'content-blocks-builder' ),
				]
			);

			// Block keywords.
			$this->post_type_helper->register_taxonomy(
				'boldblocks_block_keywords',
				$this->post_type,
				[],
				[
					'name'          => _x( 'Keywords', 'Taxonomy General Name', 'content-blocks-builder' ),
					'singular_name' => _x( 'Keyword', 'Taxonomy Singular Name', 'content-blocks-builder' ),
				]
			);

			// Meta fields.
			$this->register_meta(
				'boldblocks_block_blocks',
				[
					'default' => '[]',
				]
			);

			$this->register_meta(
				'boldblocks_block_dependent_blocks',
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

			$this->register_meta( 'boldblocks_block_class' );

			$this->register_meta( 'boldblocks_block_description' );

			$this->register_meta( 'boldblocks_block_icon' );

			$this->register_meta(
				'boldblocks_block_supports',
				[
					'show_in_rest' => [
						'schema' => [
							'type'                 => 'object',
							'additionalProperties' => array(
								'type' => [ 'boolean' ], // can be ['boolean', 'object'...].
							),
						],
					],
					'type'         => 'object',
				]
			);

			$this->register_meta(
				'boldblocks_template_lock',
				[
					'default' => 'false',
				]
			);

			$this->register_meta(
				'boldblocks_enable_variation_picker',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_enable_repeater',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_parent_layout_type',
				[
					'default' => 'vstack',
				]
			);

			$this->register_meta( 'boldblocks_parent_block_icon' );

			$this->register_meta(
				'boldblocks_parent_block_supports',
				[
					'show_in_rest' => [
						'schema' => [
							'type'                 => 'object',
							'additionalProperties' => array(
								'type' => [ 'boolean' ], // can be ['boolean', 'object'...].
							),
						],
					],
					'type'         => 'object',
				]
			);

			$this->register_meta(
				'boldblocks_parent_enable_variation_picker',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_disable_standalone',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_is_fixed_nested_item_count',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_nested_item_count',
				[
					'type'    => 'integer',
					'default' => 1,
				]
			);

			$this->register_meta( 'boldblocks_parent_block_name' );

			$this->register_meta( 'boldblocks_parent_block_title' );

			$this->register_meta( 'boldblocks_parent_block_description' );

			$this->register_meta( 'boldblocks_parent_block_class' );

			$this->register_meta( 'boldblocks_block_version' );

			$this->register_meta(
				'boldblocks_block_dependencies',
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
				'boldblocks_block_external_scripts',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'handle'           => array(
										'type'    => 'string',
										'default' => '',
									),
									'src'              => array(
										'type'    => 'string',
										'default' => '',
									),
									'deps'             => array(
										'type'    => 'string',
										'default' => '',
									),
									'version'          => array(
										'type'    => 'string',
										'default' => '',
									),
									'in_footer'        => array(
										'type'    => 'boolean',
										'default' => false,
									),
									'loading_strategy' => array(
										'type'    => 'string',
										'default' => 'none',
									),
									'in_backend'       => array(
										'type'    => 'boolean',
										'default' => false,
									),
								),
							),
						),
					),
					'default'      => [],
				]
			);

			$this->register_meta(
				'boldblocks_block_custom_scripts',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'handle' => array(
										'type' => 'string',
									),
									'value'  => array(
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
				'boldblocks_block_external_styles',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'handle'     => array(
										'type'    => 'string',
										'default' => '',
									),
									'src'        => array(
										'type'    => 'string',
										'default' => '',
									),
									'deps'       => array(
										'type'    => 'string',
										'default' => '',
									),
									'version'    => array(
										'type'    => 'string',
										'default' => '',
									),
									'media'      => array(
										'type'    => 'string',
										'default' => 'all',
									),
									'in_backend' => array(
										'type'    => 'boolean',
										'default' => false,
									),
								),
							),
						),
					),
					'default'      => [],
				]
			);

			$this->register_meta(
				'boldblocks_block_custom_styles',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'handle' => array(
										'type' => 'string',
									),
									'value'  => array(
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
				'boldblocks_block_attributes',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'                 => 'object',
								'properties'           => array(
									'name' => array(
										'type' => 'string',
									),
									'type' => array(
										'type' => 'string',
									),
								),
								'additionalProperties' => array(
									'type' => [ 'string', 'boolean', 'object', 'array' ],
								),
							),
						),
					),
					'default'      => [],
				]
			);

			$this->register_meta(
				'boldblocks_parent_block_attributes',
				[
					'type'         => 'array',
					'show_in_rest' => array(
						'schema' => array(
							'items' => array(
								'type'                 => 'object',
								'properties'           => array(
									'name' => array(
										'type' => 'string',
									),
									'type' => array(
										'type' => 'string',
									),
								),
								'additionalProperties' => array(
									'type' => [ 'string', 'boolean', 'object', 'array' ],
								),
							),
						),
					),
					'default'      => [],
				]
			);

			$this->register_meta(
				'boldblocks_enable_custom_attributes',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_parent_enable_custom_attributes',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_hide_on_frontend',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_is_readonly',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_is_transformable',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta(
				'boldblocks_is_hidden',
				[
					'type'    => 'boolean',
					'default' => false,
				]
			);

			$this->register_meta( 'boldblocks_block_parent' );

			$this->register_meta( 'boldblocks_block_ancestor' );
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
		 * Load all custom blocks
		 *
		 * @return void
		 */
		public function load_custom_blocks() {
			// Load all custom blocks.
			$all_custom_blocks = $this->get_all_custom_blocks();

			// Build an array of custom block name.
			$this->custom_blocks = array_column( $all_custom_blocks, null, 'name' );

			// Build an array of repeater blocks.
			$this->repeater_blocks = array_filter(
				array_map(
					function ( $block_args ) {
						return isset( $block_args['parent'] ) ? $block_args['parent'] : false;
					},
					$all_custom_blocks
				)
			);

			// Make name as the key.
			$this->repeater_blocks = array_column( $this->repeater_blocks, null, 'name' );

			// Get current post.
			$post_id             = $_GET['post'] ?? 0;
			$post                = $post_id ? get_post( $post_id ) : false;
			$block_name          = '';
			$repeater_block_name = '';
			if ( $post && $this->post_type === $post->post_type ) {
				$block_name = sprintf( 'boldblocks/%s', sanitize_key( $post->post_name ) );

				$enable_repeater = get_post_meta( $post->ID, 'boldblocks_enable_repeater', true );
				if ( $enable_repeater ) {
					$parent_name = get_post_meta( $post->ID, 'boldblocks_parent_block_name', true );
					if ( ! $parent_name ) {
						$parent_name = $post->post_name;
					}
					$repeater_block_name = sprintf( 'boldblocks/%s-repeater', sanitize_key( $parent_name ) );
				}
			}

			// Register all blocks.
			foreach ( array_merge( $this->custom_blocks, $this->repeater_blocks ) as $name => $args ) {
				// Build block attributes.
				$block_atts = [
					'api_version'  => 3,
					'supports'     => [
						'cbb'    => true,
						'layout' => true,
					],
					'uses_context' => [ 'postId', 'postType' ],
				];

				if ( $args['blockSupports']['background'] ?? false ) {
					// New duotone in WP 6.3.
					if ( ! ( $block_atts['supports']['filter'] ?? false ) ) {
						$block_atts['supports']['filter'] = [];
					}
					$block_atts['supports']['filter']['duotone'] = true;

					if ( ! ( $block_atts['selectors'] ?? false ) ) {
						$block_atts['selectors'] = [];
					}

					if ( ! ( $block_atts['selectors']['filter'] ?? false ) ) {
						$block_atts['selectors']['filter'] = [];
					}

					$block_atts['selectors']['filter']['duotone'] = '> .bb\\:block-background';
				}

				if ( ! isset( $args['parent'] ) ) {
					if ( $args['blockParent'] ?? false ) {
						$block_parent = $args['blockParent'];
						if ( $block_name ) {
							$index = array_search( $block_name, $block_parent, true );
							if ( $index !== false ) {
								unset( $block_parent[ $index ] );
								$block_parent = array_values( $block_parent );
							}
						}

						if ( $repeater_block_name ) {
							$index = array_search( $repeater_block_name, $block_parent, true );
							if ( $index !== false ) {
								unset( $block_parent[ $index ] );
								$block_parent = array_values( $block_parent );
							}
						}

						if ( $block_parent ) {
							$block_atts['parent'] = $block_parent;
						}
					}

					if ( $args['blockAncestor'] ?? false ) {
						$block_ancestor = $args['blockAncestor'];
						if ( $block_name ) {
							$index = array_search( $block_name, $block_ancestor, true );
							if ( $index !== false ) {
								unset( $block_ancestor[ $index ] );
								$block_ancestor = array_values( $block_ancestor );
							}
						}

						if ( $repeater_block_name ) {
							$index = array_search( $repeater_block_name, $block_ancestor, true );
							if ( $index !== false ) {
								unset( $block_ancestor[ $index ] );
								$block_ancestor = array_values( $block_ancestor );
							}
						}

						if ( $block_ancestor ) {
							$block_atts['ancestor'] = $block_ancestor;
						}
					}
				}

				// Styles and scripts.
				$handles = [
					'style_handles'         => [ $this->custom_blocks_handle ],
					'editor_style_handles'  => [],
					'script_handles'        => [],
					'editor_script_handles' => [ $this->custom_blocks_handle ],
					'view_script_handles'   => [ $this->custom_blocks_frontend_handle ],
				];

				$parent_layout_type = $args['layoutType'] ?? '';

				if ( empty( $parent_layout_type ) && ! isset( $args['parent'] ) ) {
					$block_atts['supports']['layoutType'] = 'standalone';
				} else {
					$block_layout_type = '';
					if ( $parent_layout_type ) {
						// Is parent block.
						$block_layout_type = $parent_layout_type;
					} else {
						// Is child block.
						$parent_layout     = $args['parent']['layoutType'] ?? '';
						$block_layout_type = $parent_layout . 'Item';
					}
					$block_atts['supports']['layoutType'] = $block_layout_type;
				}

				// Don't allow layout on repeater block except vstack layout.
				if ( $parent_layout_type && 'vstack' !== $parent_layout_type ) {
					$block_atts['supports']['layout'] = false;
				}

				// Don't allow layout on accordion item.
				if ( 'accordion' === ( $args['parent']['layoutType'] ?? '' ) ) {
					$block_atts['supports']['layout'] = false;
				}

				// Enqueue scripts for specific block types.
				if ( 'carousel' === $parent_layout_type ) {
					// Mark it as carousel item block.
					$block_atts['supports']['parentLayoutType'] = 'carousel';

					$handles['style_handles'][]  = $this->carousel_blocks_frontend_handle;
					$handles['script_handles'][] = $this->carousel_blocks_frontend_handle;
				}

				// Scripts & styles.
				$is_backend           = is_admin();
				$block_class          = '.wp-block-' . str_replace( '/', '-', $name );
				$block_inline_handle  = 'cbb-' . str_replace( '/', '-', $name );
				$custom_style_handler = $this->the_plugin_instance->get_component( CustomStyle::class );

				if ( ! empty( $args['external_scripts'] ) ) {
					$external_scripts = array_filter(
						$args['external_scripts'],
						function ( $script ) {
							return $script['handle'] ?? false;
						}
					);

					foreach ( $external_scripts as $script ) {
						if ( $script['deps'] ?? false ) {
							$deps = $this->refine_script_handle( $script['deps'], $is_backend );
							$deps = \explode( ',', $deps );
						} else {
							$deps = [];
						}
						$version = $style['version'] ?? null;
						if ( $version ) {
							if ( 'WP' === $version ) {
								$version = '';
							} elseif ( 'CBB' === $version ) {
								$version = BOLDBLOCKS_CBB_VERSION;
							}
						}

						// Don't load external resources on admin side for some cases.
						if ( ! $is_backend || ! ( $script['src'] ?? '' ) || ( $script['in_backend'] ?? false ) ) {
							$loading_strategy_args = [
								'in_footer' => isset( $script['in_footer'] ) ? $script['in_footer'] : true,
							];

							$loading_strategy = $script['loading_strategy'] ?? '';
							if ( in_array( $loading_strategy, [ 'defer', 'async' ], true ) ) {
								$loading_strategy_args['strategy'] = $loading_strategy;
							}

							wp_register_script( $script['handle'], $script['src'] ?? '', $deps, $version, $loading_strategy_args );

							// Add the handle to the block.
							$handles['view_script_handles'][] = $script['handle'];

							if ( $is_backend ) {
								$handles['editor_script_handles'][] = $script['handle'];
							}
						}
					}
				}

				if ( ! empty( $args['custom_scripts'] ) ) {
					$custom_scripts = array_filter(
						$args['custom_scripts'],
						function ( $script ) {
							return ! empty( $script['value'] );
						}
					);

					foreach ( $custom_scripts as $script ) {
						if ( $script['value'] ) {
							$value = $custom_style_handler->refine_custom_value( $script['value'], [ 'BLOCKSELECTOR' => $block_class ], 'JS' );
							if ( empty( $script['handle'] ) ) {
								$handle = $block_inline_handle;
								wp_register_script( $handle, '', [ $this->refine_script_handle( 'CBB_BLOCK_API', $is_backend ) ], false, [ 'in_footer' => true ] );

								// Add the default handle to the block.
								$handles['view_script_handles'][] = $handle;

								if ( $is_backend ) {
									$handles['editor_script_handles'][] = $handle;
								}
							} else {
								$handle = $this->refine_script_handle( $script['handle'], $is_backend );
							}

							wp_add_inline_script( $handle, $value );
						}
					}
				}

				if ( ! empty( $args['external_styles'] ) ) {
					$external_styles = array_filter(
						$args['external_styles'],
						function ( $style ) {
							return $style['handle'] ?? false;
						}
					);

					foreach ( $external_styles as $style ) {
						if ( $style['deps'] ?? false ) {
							$deps = $this->refine_style_handle( $style['deps'], $is_backend );
							$deps = \explode( ',', $deps );
						} else {
							$deps = [];
						}
						$version = $style['version'] ?? null;
						if ( $version ) {
							if ( 'WP' === $version ) {
								$version = '';
							} elseif ( 'CBB' === $version ) {
								$version = BOLDBLOCKS_CBB_VERSION;
							}
						}
						// Don't load external resources on admin side for some cases.
						if ( ! $is_backend || ! ( $style['src'] ?? '' ) || ( $style['in_backend'] ?? false ) ) {
							wp_register_style( $style['handle'], $style['src'] ?? '', $deps, $version, $style['media'] ?? 'all' );

							// Add the handle to the block.
							$handles['style_handles'][] = $style['handle'];
						}
					}
				}

				if ( ! empty( $args['custom_styles'] ) ) {
					$custom_styles = array_filter(
						$args['custom_styles'],
						function ( $style ) {
							return ! empty( $style['value'] );
						}
					);

					foreach ( $custom_styles as $style ) {
						if ( $style['value'] ) {
							$value = $custom_style_handler->refine_custom_value( $style['value'], [ 'selector' => $block_class ], 'CSS' );
							if ( empty( $style['handle'] ) ) {
								$handle = $block_inline_handle;
								wp_register_style( $handle, '' );

								// Add the default handle to the block.
								$handles['style_handles'][] = $handle;
							} else {
								$handle = $this->refine_style_handle( $style['handle'], $is_backend );
							}
							wp_add_inline_style( $handle, $value );
						}
					}
				}

				// Hide on frontend.
				$block_atts['supports']['hideOnFrontend'] = $args['hideOnFrontend'] ?? false;

				// Block keywords.
				if ( $args['keywords'] ?? false ) {
					$block_atts['keywords'] = $args['keywords'];
				}

				if ( $args['isReadonly'] ?? false ) {
					$block_atts['supports']['block_id']      = $args['id'];
					$block_atts['supports']['block_content'] = $args['postContent'];
					$block_atts['render_callback']           = [ $this, 'render_readonly_block' ];
				}

				register_block_type( $name, array_merge( $block_atts, $handles ) );
			}
		}

		/**
		 * Handle script handle
		 *
		 * @param string  $handle
		 * @param boolean $is_backend
		 * @return string
		 */
		private function refine_script_handle( $handle, $is_backend = false ) {
			if ( $handle ) {
				$handle = str_replace( 'CBB_BLOCK_API', $is_backend ? $this->custom_blocks_handle : $this->custom_blocks_frontend_handle, $handle );
				$handle = str_replace( 'CBB_CAROUSEL_API', $is_backend ? $this->custom_blocks_handle : $this->carousel_blocks_frontend_handle, $handle );
			}

			return $handle;
		}

		/**
		 * Handle style handle
		 *
		 * @param string  $handle
		 * @param boolean $is_backend
		 * @return string
		 */
		private function refine_style_handle( $handle, $is_backend = false ) {
			if ( $handle ) {
				$handle = str_replace( 'CBB_BLOCK_STYLE', $this->custom_blocks_handle, $handle );
				$handle = str_replace( 'CBB_CAROUSEL_STYLE', $is_backend ? $this->custom_blocks_handle : $this->carousel_blocks_frontend_handle, $handle );
			}

			return $handle;
		}

		/**
		 * Register code editor scripts
		 *
		 * @return void
		 */
		public function register_code_editor_scripts() {
			$screen = get_current_screen();
			if ( $screen && $screen->is_block_editor ) {
				wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
				wp_enqueue_code_editor( array( 'type' => 'javascript' ) );
			}
		}

		/**
		 * Register scripts
		 *
		 * @return void
		 */
		public function register_scripts() {
			// Custom blocks asset file.
			$custom_blocks_asset = $this->the_plugin_instance->include_file( "build/custom-blocks{$this->the_plugin_instance->get_script_suffix()}.asset.php" );

			// Scripts.
			wp_register_script(
				$this->custom_blocks_handle,
				$this->the_plugin_instance->get_file_uri( "build/custom-blocks{$this->the_plugin_instance->get_script_suffix()}.js" ),
				array_merge( $custom_blocks_asset['dependencies'] ?? [], array( 'code-editor', 'csslint', 'jshint' ) ),
				$this->the_plugin_instance->get_script_version( $custom_blocks_asset ),
				[ 'in_footer' => true ]
			);

			// Add translation.
			wp_set_script_translations( $this->custom_blocks_handle, 'content-blocks-builder' );

			// Define localize sripts.
			$blocks_scripts = [
				'blocks' => $this->get_all_custom_blocks(),
			];

			// Load all custom blocks for registration.
			wp_add_inline_script( $this->custom_blocks_handle, 'var BoldBlocksBlocks=' . wp_json_encode( $blocks_scripts ), 'before' );

			// Load all variations for registration.
			wp_add_inline_script( $this->custom_blocks_handle, 'var BoldBlocksVariations=' . wp_json_encode( $this->the_plugin_instance->get_component( Variations::class )->get_all_variations() ), 'before' );

			// Styles.
			wp_register_style(
				$this->custom_blocks_handle,
				$this->the_plugin_instance->get_file_uri( "build/custom-blocks{$this->the_plugin_instance->get_script_suffix()}.css" ),
				[],
				$this->the_plugin_instance->get_script_version( $custom_blocks_asset )
			);

			// Custom blocks asset file.
			$custom_blocks_frontend_asset = $this->the_plugin_instance->include_file( "build/custom-blocks-frontend{$this->the_plugin_instance->get_script_suffix()}.asset.php" );

			// Frontend script.
			wp_register_script(
				$this->custom_blocks_frontend_handle,
				$this->the_plugin_instance->get_file_uri( "build/custom-blocks-frontend{$this->the_plugin_instance->get_script_suffix()}.js" ),
				$custom_blocks_frontend_asset['dependencies'] ?? [],
				$this->the_plugin_instance->get_script_version( $custom_blocks_frontend_asset ),
				[ 'in_footer' => true ]
			);

			// Add translation.
			wp_set_script_translations( $this->custom_blocks_frontend_handle, 'content-blocks-builder' );

			// Carousel asset file.
			$caousel_frontend_asset = $this->the_plugin_instance->include_file( 'build/carousel-frontend.asset.php' );

			// Carousel styles.
			wp_register_style(
				$this->carousel_blocks_frontend_handle,
				$this->the_plugin_instance->get_file_uri( 'build/carousel-frontend.css' ),
				[],
				$this->the_plugin_instance->get_script_version( $caousel_frontend_asset )
			);

			// Carousel frontend script.
			wp_register_script(
				$this->carousel_blocks_frontend_handle,
				$this->the_plugin_instance->get_file_uri( 'build/carousel-frontend.js' ),
				$caousel_frontend_asset['dependencies'] ?? [],
				$this->the_plugin_instance->get_script_version( $caousel_frontend_asset ),
				[ 'in_footer' => true ]
			);

			// Add translation.
			wp_set_script_translations( $this->carousel_blocks_frontend_handle, 'content-blocks-builder' );
		}

		/**
		 * Enqueue block editor style
		 *
		 * @return void
		 */
		public function enqueue_block_editor_style() {
			if ( ! is_admin() ) {
				return;
			}

			// Custom blocks editor styles.
			wp_enqueue_style(
				$this->custom_blocks_editor_handle,
				$this->the_plugin_instance->get_file_uri( 'build/custom-blocks-editor.css' ),
				[],
				$this->the_plugin_instance->get_plugin_version()
			);
		}

		/**
		 * Get all custom blocks.
		 *
		 * @return array
		 */
		public function get_all_custom_blocks() {
			if ( ! $this->all_custom_blocks ) {
				$this->all_custom_blocks = $this->get_posts();
			}

			return $this->all_custom_blocks;
		}

		/**
		 * Query posts and cache the results.
		 *
		 * @param bool $force_refresh Optional. Whether to force the cache to be refreshed. Default false.
		 * @return array Array of WP_Post objects.
		 */
		public function get_posts( $force_refresh = false ) {
			$cache_key = 'bb_block_posts';

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
		 * Query and parse blocks
		 *
		 * @return array
		 */
		public function query_posts() {
			$post_args = [
				'post_type'      => $this->post_type,
				'posts_per_page' => $this->max_items,
				'orderby'        => [
					'menu_order' => 'DESC',
					'date'       => 'DESC',
				],
			];

			$raw_posts = get_posts( $post_args );

			$block_posts = array_map(
				function( $item ) {
					// Template lock.
					$template_lock = get_post_meta( $item->ID, 'boldblocks_template_lock', true );

					// Convert boolean string to boolean.
					$template_lock = in_array( $template_lock, [ 'false', 'inherit' ], true ) ? false : $template_lock;

					// Scripts & styles.
					$block_external_scripts = get_post_meta( $item->ID, 'boldblocks_block_external_scripts', true );
					$block_custom_scripts   = get_post_meta( $item->ID, 'boldblocks_block_custom_scripts', true );
					$block_external_styles  = get_post_meta( $item->ID, 'boldblocks_block_external_styles', true );
					$block_custom_styles    = get_post_meta( $item->ID, 'boldblocks_block_custom_styles', true );

					// Hide on frontend.
					$hide_on_frontend = ! ! get_post_meta( $item->ID, 'boldblocks_hide_on_frontend', true );

					// Is a readonly block.
					$is_readonly = ! ! get_post_meta( $item->ID, 'boldblocks_is_readonly', true );

					// Is block transformable.
					$is_transformable = ! ! get_post_meta( $item->ID, 'boldblocks_is_transformable', true );

					// Is block hidden.
					$is_hidden = ! ! get_post_meta( $item->ID, 'boldblocks_is_hidden', true );

					// Parent & ancestor.
					$block_parent   = get_post_meta( $item->ID, 'boldblocks_block_parent', true );
					$block_parent   = $block_parent ? array_filter( explode( ',', $block_parent ) ) : [];
					$block_ancestor = get_post_meta( $item->ID, 'boldblocks_block_ancestor', true );
					$block_ancestor = $block_ancestor ? array_filter( explode( ',', $block_ancestor ) ) : [];

					$keywords = get_the_terms( $item->ID, 'boldblocks_block_keywords' );
					if ( $keywords && ! is_wp_error( $keywords ) ) {
						$keywords = wp_list_pluck( $keywords, 'name' );
					} else {
						$keywords = false;
					}

					$block_args = [
						'id'                    => $item->ID,
						'name'                  => sprintf( 'boldblocks/%s', sanitize_key( $item->post_name ) ),
						'title'                 => wp_strip_all_tags( $item->post_title ),
						'postContent'           => $item->post_content,
						'blocks'                => get_post_meta( $item->ID, 'boldblocks_block_blocks', true ),
						'description'           => get_post_meta( $item->ID, 'boldblocks_block_description', true ),
						'templateLock'          => $template_lock,
						'className'             => get_post_meta( $item->ID, 'boldblocks_block_class', true ),
						'blockIcon'             => get_post_meta( $item->ID, 'boldblocks_block_icon', true ),
						'blockSupports'         => get_post_meta( $item->ID, 'boldblocks_block_supports', true ),
						'blockAttributes'       => [
							'attributes'             => get_post_meta( $item->ID, 'boldblocks_block_attributes', true ),
							'enableCustomAttributes' => ! ! get_post_meta( $item->ID, 'boldblocks_enable_custom_attributes', true ),
						],
						'enableVariationPicker' => ! ! get_post_meta( $item->ID, 'boldblocks_enable_variation_picker', true ),
						'blockVersion'          => get_post_meta( $item->ID, 'boldblocks_block_version', true ),
						'dependentBlocks'       => get_post_meta( $item->ID, 'boldblocks_block_dependent_blocks', true ),
						'hideOnFrontend'        => $hide_on_frontend,
						'isTransformable'       => $is_transformable,
						'isHidden'              => $is_hidden,
						'isReadonly'            => $is_readonly, // Only for standalone blocks.
					];

					// Get parent block parameters.
					$enable_repeater = get_post_meta( $item->ID, 'boldblocks_enable_repeater', true );
					if ( $enable_repeater ) {
						$parent_name = get_post_meta( $item->ID, 'boldblocks_parent_block_name', true );
						if ( ! $parent_name ) {
							$parent_name = $item->post_name;
						}

						$parent_block_name = sprintf( 'boldblocks/%s-repeater', sanitize_key( $parent_name ) );

						$parent_layout_type = get_post_meta( $item->ID, 'boldblocks_parent_layout_type', true );

						$parent_title = get_post_meta( $item->ID, 'boldblocks_parent_block_title', true );
						if ( ! $parent_title ) {
							// translators: Repeater block title.
							$parent_title = sprintf( __( 'Repeater: %s', 'content-blocks-builder' ), $item->post_title );
						}

						$parent_args = [
							'name'                  => $parent_block_name,
							'title'                 => wp_strip_all_tags( $parent_title ),
							'description'           => get_post_meta( $item->ID, 'boldblocks_parent_block_description', true ),
							'className'             => get_post_meta( $item->ID, 'boldblocks_parent_block_class', true ) ?? '',
							'blockIcon'             => get_post_meta( $item->ID, 'boldblocks_parent_block_icon', true ),
							'blockSupports'         => get_post_meta( $item->ID, 'boldblocks_parent_block_supports', true ),
							'blockAttributes'       => [
								'attributes'             => get_post_meta( $item->ID, 'boldblocks_parent_block_attributes', true ),
								'enableCustomAttributes' => ! ! get_post_meta( $item->ID, 'boldblocks_parent_enable_custom_attributes', true ),
							],
							'enableVariationPicker' => ! ! get_post_meta( $item->ID, 'boldblocks_parent_enable_variation_picker', true ),
							'external_scripts'      => $block_external_scripts,
							'custom_scripts'        => $block_custom_scripts,
							'external_styles'       => $block_external_styles,
							'custom_styles'         => $block_custom_styles,
							'hideOnFrontend'        => $hide_on_frontend,
							'blockParent'           => $block_parent,
							'blockAncestor'         => $block_ancestor,
						];

						if ( $keywords ) {
							$parent_args['keywords'] = $keywords;
						}

						$is_fixed_nested_item_count = get_post_meta( $item->ID, 'boldblocks_is_fixed_nested_item_count', true );
						if ( $is_fixed_nested_item_count ) {
							$nested_item_count              = get_post_meta( $item->ID, 'boldblocks_nested_item_count', true );
							$nested_item_count              = $nested_item_count ? absint( $nested_item_count ) : 1;
							$parent_args['nestedItemCount'] = $nested_item_count ? $nested_item_count : 1;
						}

						$parent_args['layoutType'] = $parent_layout_type;
						$block_args['parent']      = $parent_args;
						$block_args['parentName']  = $parent_block_name;

						$block_args['standalone'] = ! get_post_meta( $item->ID, 'boldblocks_disable_standalone', true );
					} else {
						if ( $keywords ) {
							$block_args['keywords'] = $keywords;
						}

						$block_args = array_merge(
							$block_args,
							[
								'external_scripts' => $block_external_scripts,
								'custom_scripts'   => $block_custom_scripts,
								'external_styles'  => $block_external_styles,
								'custom_styles'    => $block_custom_styles,
								'blockParent'      => $block_parent,
								'blockAncestor'    => $block_ancestor,
							]
						);
					}

					return $block_args;
				},
				$raw_posts
			);

			return $block_posts;
		}

		/**
		 * Clear transient cache
		 *
		 * @return void
		 */
		public function clear_transient_cache() {
			delete_transient( 'bb_block_posts' );
		}

		/**
		 * Add custom blocks to the list of blocks that can create patterns from.
		 *
		 * @param array $allowed_blocks
		 * @return array
		 */
		public function allow_creating_patterns( $allowed_blocks ) {
			return array_merge( $allowed_blocks, array_keys( $this->custom_blocks ), array_keys( $this->repeater_blocks ) );
		}

		/**
		 * Add custom columns to custom post type
		 *
		 * @param  array $columns The array of columns.
		 * @return array
		 */
		public function add_block_custom_columns( $columns ) {
			$custom_columns = array(
				'block_icon'        => esc_html__( 'Block icon', 'content-blocks-builder' ),
				'block_name'        => esc_html__( 'Block name', 'content-blocks-builder' ),
				'parent_block_icon' => esc_html__( 'Parent icon', 'content-blocks-builder' ),
				'parent_block_name' => esc_html__( 'Parent block name', 'content-blocks-builder' ),
			);

			if ( $this->the_plugin_instance->is_debug_mode() ) {
				$custom_columns['block_version'] = esc_html__( 'Block version', 'content-blocks-builder' );
			}

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
		public function manage_block_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'block_icon':
					$block_icon = get_post_meta( $post_id, 'boldblocks_block_icon', true );
					if ( empty( $block_icon ) ) {
						$block_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="bb-icon bb-icon--block-default"><path d="M19 8h-1V6h-5v2h-2V6H6v2H5c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2zm.5 10c0 .3-.2.5-.5.5H5c-.3 0-.5-.2-.5-.5v-8c0-.3.2-.5.5-.5h14c.3 0 .5.2.5.5v8z"/></svg>';
					}
					echo $block_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					break;

				case 'block_name':
					echo esc_html( 'boldblocks/' . sanitize_key( get_post_field( 'post_name', $post_id ) ) );
					break;

				case 'parent_block_icon':
					if ( get_post_meta( $post_id, 'boldblocks_enable_repeater', true ) ) {
						$block_icon = get_post_meta( $post_id, 'boldblocks_parent_block_icon', true );
						if ( empty( $block_icon ) ) {
							$block_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="bb-icon bb-icon--block-default"><path d="M19 8h-1V6h-5v2h-2V6H6v2H5c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2zm.5 10c0 .3-.2.5-.5.5H5c-.3 0-.5-.2-.5-.5v-8c0-.3.2-.5.5-.5h14c.3 0 .5.2.5.5v8z"/></svg>';
						}
						echo $block_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						esc_html_e( 'NA', 'content-blocks-builder' );
					}
					break;

				case 'parent_block_name':
					$enable_repeater = get_post_meta( $post_id, 'boldblocks_enable_repeater', true );
					if ( $enable_repeater ) {
						echo esc_html( 'boldblocks/' . sanitize_key( get_post_field( 'post_name', $post_id ) ) . '-repeater' );
					} else {
						esc_html_e( 'NA', 'content-blocks-builder' );
					}
					break;

				case 'block_version':
					$block_version = get_post_meta( $post_id, 'boldblocks_block_version', true );
					if ( $block_version ) {
						echo esc_html( $block_version );
					} else {
						esc_html_e( '1.x', 'content-blocks-builder' );
					}
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
		public function add_block_custom_columns_style() {
			echo '<style>.column-block_icon, .column-parent_block_icon {width: 70px;}.column-block_icon svg,.column-parent_block_icon svg {max-width: 32px;max-height: 32px;}</style>';
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
					'boldblocks_block_blocks',
					'boldblocks_block_class',
					'boldblocks_block_description',
					'boldblocks_block_icon',
					'boldblocks_block_supports',
					'boldblocks_template_lock',
					'boldblocks_enable_variation_picker',
					'boldblocks_enable_repeater',
					'boldblocks_parent_layout_type',
					'boldblocks_parent_block_icon',
					'boldblocks_parent_block_supports',
					'boldblocks_parent_enable_variation_picker',
					'boldblocks_disable_standalone',
					'boldblocks_is_fixed_nested_item_count',
					'boldblocks_nested_item_count',
					'boldblocks_parent_block_name',
					'boldblocks_parent_block_title',
					'boldblocks_parent_block_description',
					'boldblocks_parent_block_class',
					'boldblocks_block_custom_scripts',
					'boldblocks_block_custom_styles',
					'boldblocks_block_attributes',
					'boldblocks_parent_block_attributes',
				]
			);
		}

		/**
		 * Add theme name to body class
		 *
		 * @param array $classes
		 * @return array
		 */
		public function add_body_class( $classes ) {
			// Add a frontend CSS class.
			$classes[] = 'cbb-frontend';

			// Add theme slug.
			$classes[] = get_stylesheet();

			return $classes;
		}

		/**
		 * Save the plugin version to custom blocks
		 *
		 * @param int     $post_id
		 * @param WP_Post $post
		 * @param boolean $update
		 * @return void
		 */
		public function save_version_to_block( $post_id, $post, $update ) {
			// Bail if it is a update action.
			if ( $update ) {
				return;
			}

			// Save the plugin version to blocks.
			update_post_meta( $post_id, 'boldblocks_block_version', $this->the_plugin_instance->get_plugin_version() );
		}

		/**
		 * Prevent users from deleting core blocks.
		 *
		 * @param array $allcaps
		 * @param array $caps
		 * @param array $args
		 * @return array
		 */
		public function prevent_core_blocks_from_deletion( $allcaps, $caps, $args ) {
			// Bail out if we're not asking about deleting a post.
			if ( 'delete_post' !== $args[0] ) {
				return $allcaps;
			}

			// Bail out for users who can't delete posts.
			if ( empty( $allcaps['delete_posts'] ) ) {
				return $allcaps;
			}

			// Load the post data.
			$post = get_post( $args[2] );

			// Bail out if the post type is not boldblocks_block.
			if ( $this->post_type !== $post->post_type ) {
				return $allcaps;
			}

			// Don't allow deleting core blocks.
			if ( \in_array( $post->post_name, [ 'group', 'carousel-item', 'grid-item', 'stack-item', 'accordion-item' ], true ) ) {
				$allcaps[ $caps[0] ] = false;
			}

			return $allcaps;
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
				$text = __( 'Add block title', 'content-blocks-builder' );
			}

			return $text;
		}

		/**
		 * Enqueue frontend scripts for carousel layout in the query loop block
		 *
		 * @param string   $block_content
		 * @param array    $block
		 * @param WP_Block $block_instance
		 * @return string
		 */
		public function enqueue_frontend_carousel_scripts( $block_content, $block, $block_instance ) {
			// Ignore admin side.
			if ( is_admin() ) {
				return $block_content;
			}

			if ( isset( $GLOBALS['boldblocks_carousel_loaded'] ) ) {
				return $block_content;
			}

			// Make sure we have the blockName.
			if ( empty( $block['blockName'] ) ) {
				return $block_content;
			}

			if ( 'core/query' === $block['blockName'] ) {
				// If this is a core/query block, enqueue the carousel script.
				if ( $this->is_enqueue_carousel_script_for_query_loop( $block, $block_instance ) ) {
					wp_enqueue_script( $this->carousel_blocks_frontend_handle );
					wp_enqueue_style( $this->carousel_blocks_frontend_handle );

					$GLOBALS['boldblocks_carousel_loaded'] = true;
				}
			} elseif ( strpos( $block['blockName'], 'boldblocks/' ) === 0 ) {
				if ( isset( $block_instance->block_type->supports['parentLayoutType'] ) && 'carousel' === $block_instance->block_type->supports['parentLayoutType'] ) {
					$GLOBALS['boldblocks_carousel_loaded'] = true;
				}
			}

			return $block_content;
		}

		/**
		 * Whether to load the carousel scripts for core query block.
		 *
		 * @param array    $block
		 * @param WP_Block $block_instance
		 * @return boolean
		 */
		private function is_enqueue_carousel_script_for_query_loop( $block, $block_instance ) {
			if ( 'carousel' === ( $block['attrs']['displayLayout']['type'] ?? '' ) ) {
				return true;
			}

			if ( $block_instance->block_type->api_version >= 3 ) {
				$post_template = $this->the_plugin_instance->get_component( CustomStyle::class )->find_nested_block( $block_instance, 'core/post-template' );

				if ( $post_template ) {
					if ( 'carousel' === ( $post_template->attributes['boldblocks']['layout']['type'] ?? '' ) ) {
						return true;
					}
				}
			}

			return false;
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
						$keywords = wp_list_pluck( wp_get_object_terms( $params['id'], 'boldblocks_block_keywords' ), 'name' );

						return \implode( ',', $keywords );
					},
					'update_callback' => function( $value, $post ) {
						wp_set_post_terms( $post->ID, $value, 'boldblocks_block_keywords' );
					},
					'schema'          => array(
						'type' => 'string',
					),
				)
			);
		}

		/**
		 * Hide the block on the frontend.
		 *
		 * @param string   $block_content
		 * @param array    $block
		 * @param WP_Block $block_instance
		 * @return string
		 */
		public function hide_on_frontend( $block_content, $block, $block_instance ) {
			// Ignore admin side.
			if ( is_admin() ) {
				return $block_content;
			}

			// Make sure we have the blockName.
			if ( empty( $block['blockName'] ) ) {
				return $block_content;
			}

			if ( strpos( $block['blockName'], 'boldblocks/' ) === 0 ) {
				if ( $block_instance->block_type->supports['hideOnFrontend'] ?? false ) {
					return null;
				}
			}

			return $block_content;
		}

		/**
		 * Enqueue scripts for custom blocks when editing them
		 *
		 * @param string $hook_suffix
		 * @return void
		 */
		public function enqueue_custom_block_scripts( $hook_suffix ) {
			global $post;
			$screen = get_current_screen();
			if ( 'post.php' === $hook_suffix && $this->post_type === $screen->post_type ) {
				$custom_style_handler = $this->the_plugin_instance->get_component( CustomStyle::class );
				// Styles.
				$external_styles = get_post_meta( $post->ID, 'boldblocks_block_external_styles', true );
				$custom_styles   = get_post_meta( $post->ID, 'boldblocks_block_custom_styles', true );

				$block_class = '.wp-block-post-content';

				if ( ! empty( $external_styles ) ) {
					$external_styles = array_filter(
						$external_styles,
						function ( $style ) {
							return $style['handle'] ?? false;
						}
					);

					foreach ( $external_styles as $style ) {
						if ( $style['deps'] ?? false ) {
							$deps = $this->refine_style_handle( $style['deps'], $is_backend );
							$deps = \explode( ',', $deps );
						} else {
							$deps = [];
						}
						$version = $style['version'] ?? null;
						if ( $version ) {
							if ( 'WP' === $version ) {
								$version = '';
							} elseif ( 'CBB' === $version ) {
								$version = BOLDBLOCKS_CBB_VERSION;
							}
						}
						// Don't load external resources on admin side.
						if ( ! ( $style['src'] ?? '' ) ) {
							// Add 'edit-' prefix to separate it from the handle registered by other hook.
							wp_register_style( 'edit-' . $style['handle'], $style['src'] ?? '', $deps, $version, $style['media'] ?? 'all' );
						}
					}
				}

				if ( ! empty( $custom_styles ) ) {
					$custom_styles = array_filter(
						$custom_styles,
						function ( $style ) {
							return ! empty( $style['value'] );
						}
					);

					$dynamic_style = '';
					foreach ( $custom_styles as $style ) {
						$dynamic_style .= $custom_style_handler->refine_custom_value( $style['value'], [ 'selector' => $block_class ], 'CSS' );
					}

					if ( $dynamic_style ) {
						$dynamic_style .= '.wp-block-post-content{position:relative!important;top:unset!important;right:unset!important;bottom:unset!important;left:unset!important;display:block!important;width:auto!important;max-width:none!important;height:auto!important;max-height:none!important;}.is-selected,.has-child-selected,.has-child-selected * {animation:none!important;}';
						$handle         = 'edit-' . $this->custom_blocks_handle;
						if ( ! wp_style_is( $handle, 'registered' ) ) {
							wp_register_style( $handle, '' );
						}

						wp_add_inline_style( $handle, $dynamic_style );
						wp_enqueue_style( $handle );
					}
				}
			}
		}

		/**
		 * Renders the 'readonly' block on the server.
		 *
		 * @param  array    $attributes Block attributes.
		 * @param  string   $content    Block default content.
		 * @param  WP_Block $block      Block instance.
		 * @return string Returns the block content on the frontend.
		 */
		public function render_readonly_block( $attributes, $content, $block ) {
			$raw_block_content = $block->block_type->supports['block_content'] ?? '';

			// Ignore empty block.
			if ( empty( $raw_block_content ) ) {
				return $content;
			}

			$block_content = apply_filters( 'the_content', $raw_block_content );

			if ( $block_content ) {
				$wrapper_content = trim( $block->parsed_block['innerHTML'] ?? '' );
				if ( $wrapper_content ) {
					$closing_div = substr( $wrapper_content, -6 );

					if ( $closing_div === '</div>' ) {
						$opening_div   = substr( $wrapper_content, 0, strlen( $wrapper_content ) - 6 );
						$block_content = $opening_div . $block_content . $closing_div;

						return $block_content;
					}
				}
			}

			return $content;
		}

		/**
		 * Customize Query Loop
		 *
		 * @return void
		 */
		public function query_loop_extended_filters_and_sorting() {
			// Get public post types.
			$post_types = get_post_types(
				[
					'public'       => true,
					'show_in_rest' => true,
				]
			);

			// For backend.
			foreach ( $post_types as $post_type ) {
				add_filter( 'rest_' . $post_type . '_collection_params', [ $this, 'query_loop_support_advanced_sorting' ], 10 );
				add_filter( 'rest_' . $post_type . '_query', [ $this, 'query_loop_add_custom_query_params' ], 10, 2 );
			}

			// For frontend.
			add_filter( 'query_loop_block_query_vars', [ $this, 'query_loop_customize_frontend' ], 10, 2 );

			// Add custom query vars.
			add_filter( 'query_vars', [ $this, 'query_loop_query_vars' ], 1 );

			// Alter the result of query loop.
			add_filter( 'the_posts', [ $this, 'query_loop_the_posts' ], 10, 2 );
		}

		/**
		 * Support advanced sorting for the Query Loop block.
		 *
		 * @param array $query_params
		 * @return array
		 */
		public function query_loop_support_advanced_sorting( $query_params ) {
			$query_params['orderby']['enum'][] = 'meta_value';
			$query_params['orderby']['enum'][] = 'meta_value_num';
			$query_params['orderby']['enum'][] = 'menu_order';
			$query_params['orderby']['enum'][] = 'rand';

			return $query_params;
		}

		/**
		 * Customize query args for frontend.
		 *
		 * @param array    $query_args
		 * @param WP_Block $block
		 * @return array
		 */
		public function query_loop_customize_frontend( $query_args, $block ) {
			$query_context  = $block->context['query'] ?? [];
			$queried_object = get_queried_object();

			return $this->query_loop_alter_query_params( $query_args, $query_context['cbb'] ?? [], $query_context, $queried_object );
		}

		/**
		 * Add custom query parameters to the Query Loop.
		 *
		 * @param array           $query_args
		 * @param WP_REST_Request $request
		 * @return array
		 */
		public function query_loop_add_custom_query_params( $query_args, $request ) {
			return $this->query_loop_alter_query_params( $query_args, $request->get_param( 'cbb' ) ?? [] );
		}

		/**
		 * Change query args by CBB.
		 *
		 * @param array $query_args
		 * @param array $cbb_params
		 * @param mixed $query_context
		 * @param mixed $queried_object
		 * @return array
		 */
		private function query_loop_alter_query_params( $query_args, $cbb_params, $query_context = null, $queried_object = null ) {
			$cbb_args = [];
			if ( $cbb_params && is_array( $cbb_params ) ) {
				// It's a cbb query.
				$cbb_args = [ 'cbb' => $cbb_params ];

				// Query by custom post ids.
				$post_in_args = $this->query_loop_filter_by_post_ids( $cbb_params['include'] ?? '' );
				if ( $post_in_args ) {
					$cbb_args = array_merge( $cbb_args, $post_in_args );
				}

				// Query by meta fields.
				$meta_query_args = $this->query_loop_filter_by_meta_queries( $cbb_params );
				if ( $meta_query_args ) {
					$cbb_args = array_merge( $cbb_args, $meta_query_args );
				}

				// Query by parent.
				$parent_args = $this->query_loop_filter_by_parent( $cbb_params['parent'] ?? '' );
				if ( $parent_args ) {
					$cbb_args = array_merge( $cbb_args, $parent_args );
				}

				// Query by context.
				if ( $queried_object && $queried_object instanceof \WP_Post ) {
					$query_post_type = $query_args['post_type'] ?? '';
					if ( $queried_object->post_type === $query_post_type || ( is_array( $query_post_type ) && in_array( $queried_object->post_type, $query_post_type, true ) ) ) {
						$context_args = $this->query_loop_filter_by_context( $cbb_params, $queried_object );

						if ( $context_args['post__not_in'] ?? false ) {
							$cbb_args['post__not_in'] = $context_args['post__not_in'];
						}

						if ( $context_args['tax_query'] ?? false ) {
							if ( ( $query_args['tax_query'] ?? false ) && is_array( $query_args['tax_query'] ) ) {
								$cbb_args['tax_query'] = array_merge( $query_args['tax_query'], [ $context_args['tax_query'] ] );
							} else {
								$cbb_args['tax_query'] = $context_args['tax_query'];
							}
						}
					}

					// Ignore sticky posts not exlude them.
					if ( ( $cbb_params['ignoreStickyPosts'] ?? false ) && ( 'exclude' === $query_context['sticky'] ?? '' ) ) {
						$cbb_args['ignore_sticky_posts'] = true;
						$sticky                          = get_option( 'sticky_posts' );
						if ( ! empty( $sticky ) && ! empty( $query_args['post__not_in'] ) ) {
							// Remove sticky from the post__not_in list.
							$query_args['post__not_in'] = array_diff( $query_args['post__not_in'], $sticky );
						}
					}
				}

				// Custom sorting.
				$orderby = [];
				if ( ( $cbb_params['orderbyFromQueryString'] ?? false ) && isset( $_GET['orderby'] ) ) {
					$orderby = wp_unslash( $_GET['orderby'] );
					if ( $orderby ) {
						if ( ! is_array( $orderby ) ) {
							$orderby = [ $orderby ];
						}

						$orderby = array_filter(
							array_map(
								function( $item ) {
									return $item ? sanitize_text_field( $item ) : false;
								},
								$orderby
							)
						);
					}
				}

				$sort_args = $this->query_loop_custom_sort( array_merge( $orderby, $cbb_params['sorting'] ?? [] ) );
				if ( $sort_args ) {
					$cbb_args = array_merge( $cbb_args, $sort_args );

					if ( isset( $query_args['order'] ) ) {
						unset( $query_args['order'] );
					}
				}

				// Combine post__not_in value.
				$cbb_args['post__not_in'] = array_merge( $query_args['post__not_in'], $cbb_args['post__not_in'] ?? [] );
			}

			// Allow third-party to alter the final result.
			$query_args = apply_filters( 'cbb_query_loop_block_query_vars', array_merge( $query_args, $cbb_args ), $query_args, $cbb_params, $query_context, $queried_object );

			return $query_args;
		}

		/**
		 * Filter by post ids.
		 *
		 * @param string $include
		 * @return array
		 */
		private function query_loop_filter_by_post_ids( $include ) {
			$post_in_args = [];
			if ( $include ) {
				$post_in = explode( ',', $include );
				if ( $post_in ) {
					$post_in_args['post__in']            = $post_in;
					$post_in_args['orderby']             = 'post__in';
					$post_in_args['ignore_sticky_posts'] = true;
				}
			}

			return $post_in_args;
		}

		/**
		 * Filter by parent.
		 *
		 * @param string $parent
		 * @return array
		 */
		private function query_loop_filter_by_parent( $parent ) {
			$parent_args = [];
			if ( $parent ) {
				$parent_ids = explode( ',', $parent );
				if ( count( $parent_ids ) > 1 ) {
					$parent_args['post_parent__in'] = $parent_ids;
				} else {
					$parent_args['post_parent'] = $parent;
				}
			}

			return $parent_args;
		}

		/**
		 * Filter by meta queries
		 *
		 * @param array $params
		 * @return array
		 */
		private function query_loop_filter_by_meta_queries( $params ) {
			$meta_query_args = [];
			$queries         = $params['metaQuery']['queries'] ?? [];
			if ( $queries && count( $queries ) > 0 ) {
				$queries = array_map(
					function ( $query ) {
						$refined_query = [
							'type'    => strtoupper( $query['type'] ?? 'char' ),
							'compare' => $query['compare'] ?? '=',
							'key'     => $query['key'] ?? '',
							'value'   => $query['value'] ?? '',
						];

						if ( 'NUMERIC' === $refined_query['type'] ) {
							$refined_query['type'] = 'DECIMAL(10,3)';
						}

						// No key.
						if ( ! $refined_query['key'] ) {
							return false;
						}

						// Don't need a value.
						if ( in_array( $refined_query['compare'], [ 'EXISTS', 'NOT EXISTS' ], true ) ) {
							unset( $refined_query['value'] );

							return $refined_query;
						}

						$compare       = $refined_query['compare'];
						$refined_value = $refined_query['value'];

						// No value.
						if ( $refined_value === '' ) {
							return false;
						}

						if ( in_array( $compare, [ 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN' ], true ) ) {
							$refined_value = preg_split( '/,\s*(?=(?:[^"]*"[^"]*")*[^"]*$)/', $refined_value, -1, PREG_SPLIT_NO_EMPTY );
							$refined_value = array_filter(
								array_map(
									function ( $value ) {
										return trim( $value, '"' );
									},
									$refined_value
								)
							);
						}

						if ( 'DATE' === $refined_query['type'] ) {
							$format = ! empty( $query['date_format'] ) ? $query['date_format'] : 'Y-m-d';

							if ( is_array( $refined_value ) ) {
								$refined_value = array_filter(
									array_map(
										function ( $value ) {
											$formated_value = strtotime( $value );

											// No valid date string.
											if ( ! $formated_value ) {
												return false;
											}

											if ( 'timestamp' !== $format ) {
												$formated_value = date( $format, $formated_value );
											}

											return $formated_value;
										},
										$refined_value
									)
								);
							} else {
								$refined_value = strtotime( $refined_value );

								if ( 'timestamp' !== $format ) {
									$refined_value = date( $format, $refined_value );
								}
							}

							// No valid value.
							if ( ! $refined_value ) {
								return false;
							}

							if ( 'timestamp' === $format ) {
								$refined_query['type'] = 'NUMERIC';
							}

							$refined_query['value'] = $refined_value;
						}

						return $refined_query;
					},
					$queries
				);

				$queries = array_filter( $queries );

				if ( count( $queries ) > 0 ) {
					// Maximize 5 items.
					$queries = array_slice( $queries, 0, 5 );

					$refined_queries = [];
					$instances       = [];
					foreach ( $queries as $query ) {
						$key = $query['key'];
						if ( ! isset( $refined_queries[ $key ] ) ) {
							$refined_queries[ $key ] = $query;
							$instances[ $key ]       = 1;
						} else {
							$refined_queries[ $key . '_' . $instances[ $key ] ] = $query;
							$instances[ $key ]                                  = absint( $instances[ $key ] ) + 1;
						}
					}

					$meta_query_args['meta_query'] = $refined_queries;
					if ( count( $queries ) > 1 ) {
						$meta_query_args['meta_query']['relation'] = $params['metaQuery']['relation'] ?? 'AND';
					}
				}
			}

			return $meta_query_args;
		}

		/**
		 * Filter by context.
		 *
		 * @param array $params
		 * @return array
		 */
		private function query_loop_filter_by_context( $params, $post ) {
			$context_args = [];

			if ( $params['ignoreCurrentPost'] ?? false ) {
				$context_args['post__not_in'] = [ $post->ID ];
			}

			if ( $params['queryRelatedPosts'] ?? false ) {
				$taxonomies = \get_object_taxonomies( $post, 'names' );

				$tax_queries = [];
				foreach ( $taxonomies as $taxonomy ) {
					$terms = \get_the_terms( $post->ID, $taxonomy );
					if ( empty( $terms ) ) {
						continue;
					}
					$term_ids      = \wp_list_pluck( $terms, 'term_id' );
					$tax_queries[] = array(
						'taxonomy' => $taxonomy,
						'terms'    => $term_ids,
					);
				}

				if ( count( $tax_queries ) > 1 ) {
					$tax_queries['relation'] = 'OR';
				}

				if ( $tax_queries ) {
					$context_args['tax_query'] = $tax_queries;
				}
			}

			return $context_args;
		}

		/**
		 * Custom sorting.
		 *
		 * @param array $sorting
		 * @return array
		 */
		private function query_loop_custom_sort( $sorting ) {
			$sort_args = [];

			if ( $sorting && is_array( $sorting ) ) {
				if ( 'rand' === $sorting[0] ) {
					$sort_args['orderby'] = 'rand';
				} else {
					$order_by = [];
					foreach ( $sorting as $item ) {
						if ( ! $item || 'rand' === $item ) {
							continue;
						}

						$item_array = explode( '/', $item );

						if ( count( $item_array ) !== 2 ) {
							continue;
						}

						if ( isset( $order_by[ $item_array[0] ] ) || ! in_array( strtoupper( $item_array[1] ), [ 'ASC', 'DESC' ], true ) ) {
							continue;
						}

						$order_by[ $item_array[0] ] = strtoupper( $item_array[1] );
					}

					if ( $order_by ) {
						$sort_args['orderby'] = $order_by;
					}
				}
			}

			return $sort_args;
		}

		/**
		 * Add custom query vars.
		 *
		 * @param array $vars
		 * @return array
		 */
		public function query_loop_query_vars( $vars ) {
			$vars[] = 'cbb';

			return $vars;
		}

		/**
		 * Alter the result of the query loop
		 *
		 * @param array    $posts
		 * @param WP_Query $query
		 * @return array
		 */
		public function query_loop_the_posts( $posts, $query ) {
			if ( $query->query_vars['cbb'] ?? false ) {
				$cbb_args       = $query->query_vars['cbb'] ?? [];
				$posts_per_page = $query->query_vars['posts_per_page'] ?? 1;
				$found_posts    = $query->found_posts;

				if ( ( $cbb_args['queryFallbackPosts'] ?? false ) && $found_posts < $posts_per_page ) {
					$ignore_posts = [];
					if ( $found_posts > 0 ) {
						$ignore_posts = wp_list_pluck( $query->posts, 'ID' );
					}

					$fallback_args = [
						'post_type'           => $query->query_vars['post_type'] ?? 'any',
						'post_status'         => 'publish',
						'posts_per_page'      => $posts_per_page - $found_posts,
						'ignore_sticky_posts' => 1,
						'post__not_in'        => array_merge( $query->query_vars['post__not_in'] ?? [], $ignore_posts ),
						'no_found_rows'       => true,
					];

					$fallback_posts = get_posts( $fallback_args );

					if ( $fallback_posts ) {
						$posts = array_merge( $posts, $fallback_posts );
					}
				}
			}

			return $posts;
		}
	}
endif;
