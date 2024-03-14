<?php
/**
 * Library
 *
 * @package    BoldBlocks
 * @author     Phi Phan <mrphipv@gmail.com>
 * @copyright  Copyright (c) 2023, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( Library::class ) ) :
	/**
	 * Create/edit custom content blocks.
	 */
	class Library extends CoreComponent {
		/**
		 * The library url
		 *
		 * @var string
		 */
		private $library_url = 'https://boldpatterns.net';

		/**
		 * Get the library url
		 *
		 * @return string
		 */
		public function get_library_url() {
			if ( defined( 'CBB_LIBRARY_URL' ) ) {
				return CBB_LIBRARY_URL;
			}

			return $this->library_url;
		}

		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Create the block library page.
			add_action( 'admin_menu', [ $this, 'admin_menu_add_block_library_page' ] );

			// Add the header of the block library page.
			add_action( 'in_admin_header', [ $this, 'in_admin_header_block_library' ] );

			// Create the variation library page.
			add_action( 'admin_menu', [ $this, 'admin_menu_add_variation_library_page' ] );

			// Add the header of the variation library page.
			add_action( 'in_admin_header', [ $this, 'in_admin_header_variation_library' ] );

			// Clear the blocks cache on upgraded.
			add_action( 'cbb_version_upgraded', [ $this, 'clear_library_cache' ] );

			// Enqueue block library scripts.
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_block_library_scripts' ] );

			// Add rest api endpoint to query all blocks.
			add_action( 'rest_api_init', [ $this, 'register_block_library_get_blocks_endpoint' ] );

			// Add rest api endpoint to query all block keywords.
			add_action( 'rest_api_init', [ $this, 'register_get_block_keywords_from_library_endpoint' ] );

			// Enqueue variation library scripts.
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_variation_library_scripts' ] );

			// Add rest api endpoint to query all variations.
			add_action( 'rest_api_init', [ $this, 'register_variation_library_get_variations_endpoint' ] );

			// Add rest api endpoint to query all variation keywords.
			add_action( 'rest_api_init', [ $this, 'register_get_variation_keywords_from_library_endpoint' ] );

			// Enqueue pattern library scripts.
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_pattern_library_scripts' ] );

			// Add rest api endpoint to query all patterns.
			add_action( 'rest_api_init', [ $this, 'register_pattern_library_get_patterns_endpoint' ] );

			// Add rest api endpoint to query all pattern keywords.
			add_action( 'rest_api_init', [ $this, 'register_get_pattern_keywords_from_library_endpoint' ] );
		}

		/**
		 * Create the admin page for the block library
		 *
		 * @return void
		 */
		public function admin_menu_add_block_library_page() {
			// Make "Custom Blocks" as parent slug.
			$parent_slug = 'edit.php?post_type=boldblocks_block';

			add_submenu_page(
				$parent_slug,
				__( 'Block Library', 'content-blocks-builder' ),
				__( 'Block Library', 'content-blocks-builder' ),
				'manage_options',
				'cbb-block-library',
				function () {
					?>
					<div class="wrap">
						<h2 class="screen-reader-text">Block Library</h2>
						<div class="boldblocks-settings js-boldblocks-settings-root"></div>
					</div>
					<?php
				},
				2
			);
		}

		/**
		 * Print the header of the block library page.
		 *
		 * @return void
		 */
		public function in_admin_header_block_library() {
			$screen = get_current_screen();
			if ( 'boldblocks_block_page_cbb-block-library' === $screen->base ) {
				?>
				<div class="cbb-settings-header">
					<h1><strong><?php printf( __( 'Block Library', 'content-blocks-builder' ) ); ?></strong></h1>
				</div>
				<?php
			}
		}

		/**
		 * Create the admin page for the variation libary
		 *
		 * @return void
		 */
		public function admin_menu_add_variation_library_page() {
			// Make "Custom Blocks" as parent slug.
			$parent_slug = 'edit.php?post_type=boldblocks_block';

			add_submenu_page(
				$parent_slug,
				__( 'Variation Library', 'content-blocks-builder' ),
				__( 'Variation Library', 'content-blocks-builder' ),
				'manage_options',
				'cbb-variation-library',
				function () {
					?>
					<div class="wrap">
						<h2 class="screen-reader-text">Variation Library</h2>
						<div class="boldblocks-settings js-boldblocks-settings-root"></div>
					</div>
					<?php
				},
				4
			);
		}

		/**
		 * Print the header of the variation library page.
		 *
		 * @return void
		 */
		public function in_admin_header_variation_library() {
			$screen = get_current_screen();
			if ( 'boldblocks_block_page_cbb-variation-library' === $screen->base ) {
				?>
				<div class="cbb-settings-header">
					<h1><strong><?php printf( __( 'Variation Library', 'content-blocks-builder' ) ); ?></strong></h1>
				</div>
				<?php
			}
		}

		/**
		 * Update library cache.
		 */
		private function update_library_cache( $data, $cache_key, $expired_time = DAY_IN_SECONDS ) {
			set_transient( $cache_key, $data, $expired_time );
		}

		/**
		 * Clear library cache
		 *
		 * @return void
		 */
		public function clear_library_cache() {
			delete_transient( $this->get_library_block_cache_key() );
			delete_transient( $this->get_library_block_keywords_cache_key() );

			delete_transient( $this->get_library_variation_cache_key() );
			delete_transient( $this->get_library_variation_keywords_cache_key() );

			delete_transient( $this->get_library_pattern_cache_key() );
			delete_transient( $this->get_library_pattern_keywords_cache_key() );
		}

		/**
		 * Define blocks cache key
		 *
		 * @return string
		 */
		private function get_library_block_cache_key() {
			return 'bb_blocks_store';
		}

		/**
		 * Define block keywords cache key
		 *
		 * @return string
		 */
		private function get_library_block_keywords_cache_key() {
			return 'bb_block_keywords_store';
		}

		/**
		 * Enqueue scripts for the block library page.
		 *
		 * @param string $hook_suffix
		 * @return void
		 */
		public function enqueue_block_library_scripts( $hook_suffix ) {
			// Only load scripts for the block library page.
			if ( 'boldblocks_block_page_cbb-block-library' === $hook_suffix ) {
				$custom_blocks_handle = $this->the_plugin_instance->get_component( CustomBlocks::class )->custom_blocks_handle;
				$block_registry       = \WP_Block_Type_Registry::get_instance();
				foreach ( $block_registry->get_all_registered() as $block_name => $block_type ) {
					// Front-end and editor scripts.
					foreach ( $block_type->script_handles as $script_handle ) {
						wp_enqueue_script( $script_handle );
					}

					// Editor scripts.
					foreach ( $block_type->editor_script_handles as $editor_script_handle ) {
						wp_enqueue_script( $editor_script_handle );
					}
				}

				// Asset files.
				$block_library_asset = $this->the_plugin_instance->include_file( 'build/block-library.asset.php' );
				$settings_asset      = $this->the_plugin_instance->include_file( 'build/settings.asset.php' );

				// Register custom blocks.
				$this->the_plugin_instance->get_component( CustomBlocks::class )->register_scripts();
				wp_enqueue_script( $custom_blocks_handle );

				$block_library_handle = 'boldblocks-block-library';

				// Enqueue scripts.
				wp_enqueue_script(
					$block_library_handle,
					$this->the_plugin_instance->get_file_uri( 'build/block-library.js' ),
					array_merge( $block_library_asset['dependencies'] ?? [], [ $custom_blocks_handle ] ),
					$this->the_plugin_instance->get_script_version( $block_library_asset ),
					[ 'in_footer' => true ]
				);

				// For debuging.
				$this->the_plugin_instance->enqueue_debug_information( $block_library_handle );

				// Load block library url.
				wp_add_inline_script( $block_library_handle, 'var BoldBlocksBlockLibrary=' . wp_json_encode( [ 'URL' => $this->get_library_url() ] ), 'before' );

				// Enqueue style.
				wp_enqueue_style(
					'boldblocks-settings',
					$this->the_plugin_instance->get_file_uri( 'build/settings.css' ),
					[],
					$this->the_plugin_instance->get_script_version( $settings_asset )
				);

				// Load components style.
				wp_enqueue_style( 'wp-components' );
			}
		}

		/**
		 * Register an endpoint to query blocks.
		 *
		 * @return array
		 */
		public function register_block_library_get_blocks_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getBlocks/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_blocks_from_library' ],
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'boldblocks/v1',
				'/getFullBlockData/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_full_block_library_data' ],
					'permission_callback' => '__return_true',
				)
			);
		}

		/**
		 * Query blocks.
		 *
		 * @param WP_Rest_Request $request
		 * @return WP_Rest_Response
		 */
		public function get_blocks_from_library( $request ) {
			// Get all blocks.
			$data = $this->get_all_blocks_from_library();

			if ( is_array( $data ) ) {
				return wp_send_json( array_values( $data ) );
			}

			return wp_send_json( $data );
		}

		/**
		 * Build blocks data
		 *
		 * @param boolean $force_refresh
		 * @return array
		 */
		private function get_all_blocks_from_library( $force_refresh = false ) {
			$cache_key = $this->get_library_block_cache_key();
			$data      = get_transient( $cache_key );

			if ( $force_refresh || false === $data ) {
				$response = wp_remote_get(
					$this->get_library_url() . '/wp-json/wp/v2/boldblocks-blocks',
					[
						'timeout'   => 120,
						'sslverify' => false,
						'body'      => [
							'_load_all' => 1,
							'_fields'   =>
							'id,slug,meta,menu_order,order,title,description,keywords,thumbnail,boldblocks_block_keywords,keywordIds,is_pro,has_pro_features',
						],
					]
				);

				$response_code = wp_remote_retrieve_response_code( $response );

				if ( 200 === absint( $response_code ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( $data ) {
						// Re-index the data.
						$data = array_column( $data, null, 'id' );

						// Update cache.
						$this->update_library_cache( $data, $cache_key );
					}
				}
			}

			return $data;
		}

		/**
		 * Get full data for blocks
		 *
		 * @param WP_Rest_Request $request
		 * @return array
		 */
		public function get_full_block_library_data( $request ) {
			// Return array.
			$data = [];

			// Get all blocks.
			$all_blocks = $this->get_all_blocks_from_library();

			$block_ids = $request->get_param( 'blockIds' );

			$response = wp_remote_get(
				$this->get_library_url() . '/wp-json/wp/v2/boldblocks-blocks',
				[
					'timeout'   => 120,
					'sslverify' => false,
					'body'      => [
						'_fields'      =>
						'id,slug,meta,menu_order,order,title,content,description,keywords,thumbnail,variations,parentVariations,boldblocks_block_keywords,keywordIds,is_pro,has_pro_features',
						'include_data' => true,
						'include'      => $block_ids,
					],
				]
			);

			$response_code = wp_remote_retrieve_response_code( $response );

			if ( 200 === absint( $response_code ) ) {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( $data ) {
					// Update cache.
					foreach ( $data as $post ) {
						$all_blocks[ $post['id'] ] = $post;
					}

					// Update cache.
					$this->update_library_cache( $all_blocks, $this->get_library_block_cache_key() );
				}
			}

			return $data;
		}

		/**
		 * Register an endpoint to query block keywords.
		 *
		 * @return array
		 */
		public function register_get_block_keywords_from_library_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getBlockKeywords/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_block_keywords_from_library' ],
					'permission_callback' => '__return_true',
				)
			);
		}

		/**
		 * Query block keywords.
		 *
		 * @param WP_Rest_Request $request
		 * @return WP_Rest_Response
		 */
		public function get_block_keywords_from_library( $request ) {
			$cache_key = $this->get_library_block_keywords_cache_key();
			$data      = get_transient( $cache_key );

			if ( false === $data ) {
				$response = wp_remote_get(
					$this->get_library_url() . '/wp-json/wp/v2/boldblocks_block_keywords',
					[
						'timeout'   => 120,
						'sslverify' => false,
						'body'      => [
							'per_page'   => 100,
							'orderby'    => 'order',
							'order'      => 'desc',
							'hide_empty' => 1,
							'_fields'    => 'id,count,name,slug',
						],
					]
				);

				$response_code = wp_remote_retrieve_response_code( $response );

				if ( 200 === absint( $response_code ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ), true );

					if ( ! empty( $data ) ) {
						set_transient( $cache_key, $data, 7 * DAY_IN_SECONDS );
					}
				}
			}

			return wp_send_json( $data );
		}

		/**
		 * Define blocks cache key
		 *
		 * @return string
		 */
		private function get_library_variation_cache_key() {
			return 'bb_variation_store';
		}

		/**
		 * Define blocks cache key
		 *
		 * @return string
		 */
		private function get_library_variation_keywords_cache_key() {
			return 'bb_variation_keywords_store';
		}

		/**
		 * Enqueue scripts for the variation library page.
		 *
		 * @param string $hook_suffix
		 * @return void
		 */
		public function enqueue_variation_library_scripts( $hook_suffix ) {
			// Only load scripts for the variation library page.
			if ( 'boldblocks_block_page_cbb-variation-library' === $hook_suffix ) {
				$custom_blocks_handle = $this->the_plugin_instance->get_component( CustomBlocks::class )->custom_blocks_handle;
				$block_registry       = \WP_Block_Type_Registry::get_instance();
				foreach ( $block_registry->get_all_registered() as $block_name => $block_type ) {
					// Front-end and editor scripts.
					foreach ( $block_type->script_handles as $script_handle ) {
						wp_enqueue_script( $script_handle );
					}

					// Editor scripts.
					foreach ( $block_type->editor_script_handles as $editor_script_handle ) {
						wp_enqueue_script( $editor_script_handle );
					}
				}

				// Asset files.
				$variation_library_asset = $this->the_plugin_instance->include_file( 'build/variation-library.asset.php' );
				$settings_asset          = $this->the_plugin_instance->include_file( 'build/settings.asset.php' );

				// Register custom blocks.
				$blocks_component = $this->the_plugin_instance->get_component( CustomBlocks::class );
				$blocks_component->register_scripts();
				wp_enqueue_script( $custom_blocks_handle );

				$variation_library_handle = 'boldblocks-variation-library';

				// Enqueue scripts.
				wp_enqueue_script(
					$variation_library_handle,
					$this->the_plugin_instance->get_file_uri( 'build/variation-library.js' ),
					array_merge( $variation_library_asset['dependencies'] ?? [], [ $custom_blocks_handle ] ),
					$this->the_plugin_instance->get_script_version( $variation_library_asset ),
					[ 'in_footer' => true ]
				);

				// For debuging.
				$this->the_plugin_instance->enqueue_debug_information( $variation_library_handle );

				// Load block library url.
				wp_add_inline_script( $variation_library_handle, 'var BoldBlocksVariationLibrary=' . wp_json_encode( [ 'URL' => $this->get_library_url() ] ), 'before' );

				// Enqueue style.
				wp_enqueue_style(
					'boldblocks-settings',
					$this->the_plugin_instance->get_file_uri( 'build/settings.css' ),
					[],
					$this->the_plugin_instance->get_script_version( $settings_asset )
				);

				// Load components style.
				wp_enqueue_style( 'wp-components' );
			}
		}

		/**
		 * Register an endpoint to query variations.
		 *
		 * @return array
		 */
		public function register_variation_library_get_variations_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getVariations/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_variations_from_library' ],
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'boldblocks/v1',
				'/getFullVariationData/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_full_variation_library_data' ],
					'permission_callback' => '__return_true',
				)
			);
		}

		/**
		 * Query variations.
		 *
		 * @param WP_Rest_Request $request
		 * @return WP_Rest_Response
		 */
		public function get_variations_from_library( $request ) {
			// Get all variations.
			$data = $this->get_all_variations_from_library();

			if ( is_array( $data ) ) {
				return wp_send_json( array_values( $data ) );
			}

			return wp_send_json( $data );
		}

		/**
		 * Build variations data
		 *
		 * @param boolean $force_refresh
		 * @return array
		 */
		private function get_all_variations_from_library( $force_refresh = false ) {
			$cache_key = $this->get_library_variation_cache_key();
			$data      = get_transient( $cache_key );

			if ( $force_refresh || false === $data ) {
				$response = wp_remote_get(
					$this->get_library_url() . '/wp-json/wp/v2/boldblocks-variations',
					[
						'timeout'   => 120,
						'sslverify' => false,
						'body'      => [
							'_load_all' => 1,
							'_fields'   =>
							'id,slug,meta,menu_order,order,title,description,keywords,thumbnail,boldblocks_variation_keywords,keywordIds,is_pro,has_pro_features',
						],
					]
				);

				$response_code = wp_remote_retrieve_response_code( $response );

				if ( 200 === absint( $response_code ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( $data ) {
						// Re-index the data.
						$data = array_column( $data, null, 'id' );

						// Update cache.
						$this->update_library_cache( $data, $cache_key );
					}
				}
			}

			return $data;
		}

		/**
		 * Get full data for variations
		 *
		 * @param WP_Rest_Request $request
		 * @return array
		 */
		public function get_full_variation_library_data( $request ) {
			// Return array.
			$data = [];

			// Get all variations.
			$all_variations = $this->get_all_variations_from_library();

			$variation_ids = $request->get_param( 'variationIds' );

			$response = wp_remote_get(
				$this->get_library_url() . '/wp-json/wp/v2/boldblocks-variations',
				[
					'timeout'   => 120,
					'sslverify' => false,
					'body'      => [
						'_fields'      =>
						'id,slug,meta,menu_order,order,title,content,description,keywords,thumbnail,boldblocks_variation_keywords,keywordIds,is_pro,has_pro_features',
						'include_data' => true,
						'include'      => $variation_ids,
					],
				]
			);

			$response_code = wp_remote_retrieve_response_code( $response );

			if ( 200 === absint( $response_code ) ) {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( $data ) {
					// Update cache.
					foreach ( $data as $post ) {
						$all_variations[ $post['id'] ] = $post;
					}

					// Update cache.
					$this->update_library_cache( $all_variations, $this->get_library_variation_cache_key() );
				}
			}

			return $data;
		}

		/**
		 * Register an endpoint to query variation keywords.
		 *
		 * @return array
		 */
		public function register_get_variation_keywords_from_library_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getVariationKeywords/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_variation_keywords_from_library' ],
					'permission_callback' => '__return_true',
				)
			);
		}

		/**
		 * Query variation keywords.
		 *
		 * @param WP_Rest_Request $request
		 * @return WP_Rest_Response
		 */
		public function get_variation_keywords_from_library( $request ) {
			$cache_key = $this->get_library_variation_keywords_cache_key();
			$data      = get_transient( $cache_key );

			if ( false === $data ) {
				$response = wp_remote_get(
					$this->get_library_url() . '/wp-json/wp/v2/boldblocks_variation_keywords',
					[
						'timeout'   => 120,
						'sslverify' => false,
						'body'      => [
							'per_page'   => 100,
							'orderby'    => 'order',
							'order'      => 'desc',
							'hide_empty' => 1,
							'_fields'    => 'id,count,name,slug',
						],
					]
				);

				$response_code = wp_remote_retrieve_response_code( $response );

				if ( 200 === absint( $response_code ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ), true );

					if ( ! empty( $data ) ) {
						set_transient( $cache_key, $data, 7 * DAY_IN_SECONDS );
					}
				}
			}

			return wp_send_json( $data );
		}

		/**
		 * Define patterns cache key
		 *
		 * @return string
		 */
		private function get_library_pattern_cache_key() {
			return 'bb_patterns_store';
		}

		/**
		 * Define pattern keywords cache key
		 *
		 * @return string
		 */
		private function get_library_pattern_keywords_cache_key() {
			return 'bb_pattern_keywords_store';
		}

		/**
		 * Enqueue scripts for patterns from the library.
		 *
		 * @return void
		 */
		public function enqueue_pattern_library_scripts() {
			// Load pattern library url.
			wp_add_inline_script( $this->the_plugin_instance->get_component( CustomBlocks::class )->custom_blocks_handle, 'var BoldBlocksPatternLibrary=' . wp_json_encode( [ 'URL' => $this->get_library_url() ] ), 'before' );
		}

		/**
		 * Register an endpoint to query patterns.
		 *
		 * @return array
		 */
		public function register_pattern_library_get_patterns_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getPatterns/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_patterns_from_library' ],
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'boldblocks/v1',
				'/getFullPatternData/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_full_pattern_library_data' ],
					'permission_callback' => '__return_true',
				)
			);
		}

		/**
		 * Query patterns.
		 *
		 * @param WP_Rest_Request $request
		 * @return WP_Rest_Response
		 */
		public function get_patterns_from_library( $request ) {
			// Get all patterns.
			$data = $this->get_all_patterns_from_library();

			if ( is_array( $data ) ) {
				return wp_send_json( array_values( $data ) );
			}

			return wp_send_json( $data );
		}

		/**
		 * Build patterns data
		 *
		 * @param boolean $force_refresh
		 * @return array
		 */
		private function get_all_patterns_from_library( $force_refresh = false ) {
			$cache_key = $this->get_library_pattern_cache_key();
			$data      = get_transient( $cache_key );

			if ( $force_refresh || false === $data ) {
				$response = wp_remote_get(
					$this->get_library_url() . '/wp-json/wp/v2/boldblocks-patterns',
					[
						'timeout'   => 120,
						'sslverify' => false,
						'body'      => [
							'_load_all'   => 1,
							'_fields'     =>
							'id,slug,meta,menu_order,order,title,description,keywords,thumbnail,boldblocks_pattern_keywords,keywordIds,is_pro,has_pro_features',
							'api_version' => 2,
						],
					]
				);

				$response_code = wp_remote_retrieve_response_code( $response );

				if ( 200 === absint( $response_code ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ), true );

					// Re-index the data.
					$data = array_column( $data, null, 'id' );

					// Update cache.
					$this->update_library_cache( $data, $cache_key );
				}
			}

			return $data;
		}


		/**
		 * Get full data for patterns
		 *
		 * @param WP_Rest_Request $request
		 * @return array
		 */
		public function get_full_pattern_library_data( $request ) {
			// Return array.
			$data = [];

			// Get all patterns.
			$all_patterns = $this->get_all_patterns_from_library();

			$pattern_ids = $request->get_param( 'patternIds' );

			$response = wp_remote_get(
				$this->get_library_url() . '/wp-json/wp/v2/boldblocks-patterns',
				[
					'timeout'   => 120,
					'sslverify' => false,
					'body'      => [
						'_fields'      =>
						'id,slug,meta,menu_order,order,title,description,keywords,thumbnail,boldblocks_pattern_keywords,keywordIds,is_pro,has_pro_features,variations,libraryBlocks',
						'api_version'  => 2,
						'include_data' => true,
						'include'      => $pattern_ids,
					],
				]
			);

			$response_code = wp_remote_retrieve_response_code( $response );

			if ( 200 === absint( $response_code ) ) {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );

				// Update cache.
				foreach ( $data as $post ) {
					$all_patterns[ $post['id'] ] = $post;
				}

				// Update cache.
				$this->update_library_cache( $all_patterns, $this->get_library_pattern_cache_key() );
			}

			return $data;
		}

		/**
		 * Register an endpoint to query pattern keywords.
		 *
		 * @return array
		 */
		public function register_get_pattern_keywords_from_library_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getPatternKeywords/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_pattern_keywords_from_library' ],
					'permission_callback' => '__return_true',
				)
			);
		}

		/**
		 * Query pattern keywords.
		 *
		 * @param WP_Rest_Request $request
		 * @return WP_Rest_Response
		 */
		public function get_pattern_keywords_from_library( $request ) {
			$cache_key = $this->get_library_pattern_keywords_cache_key();
			$data      = get_transient( $cache_key );
			if ( false === $data ) {
				$response = wp_remote_get(
					$this->get_library_url() . '/wp-json/wp/v2/boldblocks_pattern_keywords',
					[
						'timeout'   => 120,
						'sslverify' => false,
						'body'      => [
							'per_page'   => 100,
							'orderby'    => 'order',
							'order'      => 'desc',
							'hide_empty' => 1,
							'_fields'    => 'id,count,name,slug,is_design_field',
						],
					]
				);

				$response_code = wp_remote_retrieve_response_code( $response );

				if ( 200 === absint( $response_code ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ), true );

					set_transient( $cache_key, $data, 7 * DAY_IN_SECONDS );
				}
			}

			return wp_send_json( $data );
		}
	}
endif;
