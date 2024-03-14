<?php
/**
 * The settings: import/export blocks, variants, patterns.
 *
 * @package   BoldBlocks
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( Settings::class ) ) :
	/**
	 * The controller class for the settings.
	 */
	class Settings extends CoreComponent {
		/**
		 * Store the version of core blocks
		 *
		 * @var string
		 */
		protected $core_blocks_version = '2.4.6';

		/**
		 * The script handle
		 *
		 * @var string
		 */
		protected $script_handle = 'boldblocks-settings';

		/**
		 * Purged cache status
		 *
		 * @var boolean
		 */
		protected $is_purged_cache = false;

		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Add admin toolbar.
			add_action( 'in_admin_header', [ $this, 'in_admin_header' ] );

			// Create the settings page.
			add_action( 'admin_menu', [ $this, 'add_admin_page' ] );

			// Enqueue settings script.
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_settings_scripts' ] );

			// Do setting up stuff when the plugin is activated.
			add_action( 'content_block_builder_activate', [ $this, 'run_the_plugin_setup' ] );

			// Maybe update core blocks.
			add_action( 'init', [ $this, 'maybe_update_core_blocks' ] );

			// Add rest api endpoint to query docs.
			add_action( 'rest_api_init', [ $this, 'register_docs_endpoint' ] );

			// Clear the transient cache on upgraded.
			add_action( 'cbb_version_upgraded', [ $this, 'clear_transient_cache' ] );

			// Change the footer text for the settings pages.
			add_action( 'admin_footer_text', [ $this, 'admin_footer_text' ] );

			// Purge the cache.
			add_action( 'admin_init', [ $this, 'boldblocks_purge_cache' ] );
		}

		/**
		 * Print the admin toolbar.
		 *
		 * @return void
		 */
		public function in_admin_header() {
			$screen = get_current_screen();
			if ( 'boldblocks_block_page_cbb-settings' === $screen->base ) {
				// Left links.
				$left_links = [
					[
						'url'    => 'https://wordpress.org/support/plugin/content-blocks-builder',
						'title'  => __( 'Help & Support ↗', 'display-a-meta-field-as-block' ),
						'target' => '_blank',
						'icon'   => '<span class="dashicons dashicons-editor-help"></span> ',
						'class'  => '',
					],
					[
						'url'    => 'https://wordpress.org/support/plugin/content-blocks-builder/reviews/#new-post',
						'title'  => __( 'Review ↗', 'display-a-meta-field-as-block' ),
						'target' => '_blank',
						'icon'   => '<span class="dashicons dashicons-star-filled"></span> ',
						'class'  => '',
					],
				];

				$left_links = apply_filters( 'content_blocks_builder_get_header_left_links', $left_links );

				?>
				<div class="cbb-settings-header">
					<h1><strong><?php printf( __( 'Content Blocks Builder', 'content-blocks-builder' ) ); ?></strong> <code><?php echo esc_html( $this->the_plugin_instance->get_plugin_version() ); ?></code></h1>
					<ul class="lelf-links">
						<?php foreach ( $left_links as $link ) : ?>
							<?php printf( '<li %5$s><a href="%1$s" target="%3$s"%6$s>%4$s%2$s</a></li>', $link['url'], $link['title'], $link['target'], $link['icon'], ( $link['class'] ?? '' ) ? 'class="' . $link['class'] . '"' : '', $link['style'] ?? '' ? ' style="' . $link['style'] . '"' : '' ); ?>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php
			}
		}

		/**
		 * Create the admin page
		 *
		 * @return void
		 */
		public function add_admin_page() {
			// Make "Custom Blocks" as parent slug.
			$parent_slug = 'edit.php?post_type=boldblocks_block';

			add_submenu_page(
				$parent_slug,
				__( 'Content Blocks Builder' ),
				__( 'Settings', 'content-blocks-builder' ),
				'manage_options',
				'cbb-settings',
				function () {
					?>
					<div class="wrap">
						<h2 class="screen-reader-text">Content Blocks Builder</h2>
						<div class="boldblocks-settings js-boldblocks-settings-root"></div>
					</div>
					<?php
				}
			);
		}

		/**
		 * Enqueue scripts for the settings page.
		 *
		 * @param string $hook_suffix
		 * @return void
		 */
		public function enqueue_settings_scripts( $hook_suffix ) {
			// Only load scripts for the settings page.
			if ( 'boldblocks_block_page_cbb-settings' === $hook_suffix ) {
				// Settings asset file.
				$settings_asset = $this->the_plugin_instance->include_file( 'build/settings.asset.php' );

				// Enqueue scripts.
				wp_enqueue_script(
					$this->script_handle,
					$this->the_plugin_instance->get_file_uri( 'build/settings.js' ),
					$settings_asset['dependencies'] ?? [],
					$this->the_plugin_instance->get_script_version( $settings_asset ),
					[ 'in_footer' => true ]
				);

				// For debuging.
				$this->the_plugin_instance->enqueue_debug_information( $this->script_handle );

				// Add data to the script.
				wp_add_inline_script(
					$this->script_handle,
					'const CBBSettings=' . wp_json_encode(
						[
							'nonce'         => wp_create_nonce( 'cbb_purce_nonce' ),
							'isPurgedCache' => $this->is_purged_cache ? 1 : 0,
						]
					),
					'before'
				);

				// Enqueue style.
				wp_enqueue_style(
					$this->script_handle,
					$this->the_plugin_instance->get_file_uri( 'build/settings.css' ),
					[],
					$this->the_plugin_instance->get_script_version( $settings_asset )
				);

				// Load components style.
				wp_enqueue_style( 'wp-components' );
			}
		}

		/**
		 * Run activated stuff
		 * https://wearnhardt.com/2020/03/redirecting-after-plugin-activation/
		 *
		 * @return void
		 */
		public function run_the_plugin_setup() {
			// Deploy core blocks.
			if ( is_admin() && ! get_option( 'boldblocks_deployed_core_blocks' ) ) {
				// Update core blocks.
				$this->update_core_blocks();

				// Add the marker to the settings to make sure this logic only runs one time.
				add_option( 'boldblocks_deployed_core_blocks', 1 );
			}

			// Redirect to the getting started page, ignore bulk activation.
			if (
			! ( ( isset( $_REQUEST['action'] ) && 'activate-selected' === $_REQUEST['action'] ) &&
			( isset( $_POST['checked'] ) && count( $_POST['checked'] ) > 1 ) ) ) {
				add_option( 'boldblocks_activation_redirect', wp_get_current_user()->ID );
			}
		}

		/**
		 * Maybe update core blocks
		 *
		 * @return void
		 */
		public function maybe_update_core_blocks() {
			if ( is_admin() && current_user_can( 'manage_options' ) ) {
				$core_blocks_version = get_option( 'boldblocks_core_blocks_version' );
				if ( ! $core_blocks_version || $core_blocks_version !== $this->core_blocks_version ) {
					$this->update_core_blocks( $core_blocks_version, $this->core_blocks_version );
				}
			}
		}

		/**
		 * Parse core blocks
		 *
		 * @return array
		 */
		private function get_core_blocks() {
			$core_blocks           = false;
			$core_blocks_file_path = $this->the_plugin_instance->get_file_path( 'data/core-blocks/blocks.json' );

			if ( \file_exists( $core_blocks_file_path ) ) {
				$core_blocks = \file_get_contents( $core_blocks_file_path );
				$core_blocks = \json_decode( $core_blocks, true );
			}

			return $core_blocks;
		}

		/**
		 * Update core blocks
		 *
		 * @param string $prev_blocks_version
		 * @param string $next_blocks_version
		 * @return void
		 */
		private function update_core_blocks( $prev_blocks_version = '', $next_blocks_version = '' ) {
			if ( is_admin() ) {
				$core_blocks_data = $this->get_core_blocks();

				if ( $core_blocks_data ) {
					$core_blocks = $core_blocks_data['blocks'] ?? [];

					foreach ( $core_blocks as $block ) {
						$name        = $block['slug'] ?? '';
						$found_posts = get_posts(
							[
								'post_type'      => 'boldblocks_block',
								'name'           => $name,
								'posts_per_page' => 1,
							]
						);

						// There is no block with that slug.
						if ( ! ( is_array( $found_posts ) && count( $found_posts ) > 0 ) ) {
							$title   = $block['title'] ?? '';
							$content = $block['content'] ?? '';
							$meta    = $block['meta'] ?? [];

							// Insert new block.
							$block_id = wp_insert_post(
								[
									'post_type'    => 'boldblocks_block',
									'post_title'   => $title,
									'post_content' => $content,
									'post_name'    => $name,
									'post_status'  => 'publish',
									'meta_input'   => $meta,
								]
							);
						} else {
							$found_post = $found_posts[0];
							$meta       = $block['meta'] ?? [];

							$updating_meta = [];
							$title         = $found_post->post_title ?? '';
							$description   = $meta['boldblocks_block_description'] ?? '';
							if ( is_array( $description ) && count( $description ) > 0 ) {
								$description = $description[0] ?? '';
							}
							$updating_meta['boldblocks_block_description'] = $description;
							$icon = $meta['boldblocks_block_icon'] ?? '';
							if ( is_array( $icon ) && count( $icon ) > 0 ) {
								$icon = $icon[0] ?? '';
							}
							$updating_meta['boldblocks_block_icon'] = $icon;

							$supports = $meta['boldblocks_block_supports'] ?? [];
							if ( ! empty( $supports ) ) {
								$updating_meta['boldblocks_block_supports'] = $supports;
							}

							if ( version_compare( $prev_blocks_version, '2.3.4', '<' ) ) {
								$updating_meta['boldblocks_enable_custom_attributes'] = $meta['boldblocks_enable_custom_attributes'] ?? false;
							}

							// New transformable in 2.4.1.
							if ( $meta['boldblocks_is_transformable'] ?? false ) {
								$updating_meta['boldblocks_is_transformable'] = $meta['boldblocks_is_transformable'];
							}

							$enable_repeater = $meta['boldblocks_enable_repeater'] ?? '';
							if ( is_array( $enable_repeater ) && count( $enable_repeater ) > 0 ) {
								$enable_repeater = $enable_repeater[0] ?? '';
							}

							if ( $enable_repeater ) {
								$parent_title = $meta['boldblocks_parent_block_title'] ?? '';
								if ( is_array( $parent_title ) && count( $parent_title ) > 0 ) {
									$parent_title = $parent_title[0] ?? '';
								}
								$updating_meta['boldblocks_parent_block_title'] = $parent_title;

								$parent_description = $meta['boldblocks_parent_block_description'] ?? '';
								if ( is_array( $parent_description ) && count( $parent_description ) > 0 ) {
									$parent_description = $parent_description[0] ?? '';
								}
								$updating_meta['boldblocks_parent_block_description'] = $parent_description;

								$parent_icon = $meta['boldblocks_parent_block_icon'] ?? '';
								if ( is_array( $parent_icon ) && count( $parent_icon ) > 0 ) {
									$parent_icon = $parent_icon[0] ?? '';
								}
								$updating_meta['boldblocks_parent_block_icon'] = $parent_icon;

								$parent_supports = $meta['boldblocks_parent_block_supports'] ?? [];
								if ( ! empty( $parent_supports ) ) {
									$updating_meta['boldblocks_parent_block_supports'] = $parent_supports;
								}

								if ( version_compare( $prev_blocks_version, '2.3.4', '<' ) ) {
									$updating_meta['boldblocks_parent_enable_custom_attributes'] = $meta['boldblocks_parent_enable_custom_attributes'] ?? false;
								}
							}

							$updating_data = [
								'ID'         => $found_post->ID,
								'post_title' => $block['title'] ?? '',
								'meta_input' => $updating_meta,
							];

							$post_id = wp_update_post( $updating_data );
						}
					}

					update_option( 'boldblocks_core_blocks_version', $this->core_blocks_version );
				}
			}
		}

		/**
		 * Build a custom endpoint to query docs.
		 *
		 * @return void
		 */
		public function register_docs_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getDocs/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_docs' ],
					'permission_callback' => function () {
						return current_user_can( 'publish_posts' );
					},
				)
			);
		}

		/**
		 * Get docs.
		 *
		 * @param WP_REST_Request $request The request object.
		 * @return void
		 */
		public function get_docs( $request ) {
			$cache_key         = 'bb_docs';
			$data              = get_transient( $cache_key );
			$library_component = $this->the_plugin_instance->get_component( Library::class );
			$library_url       = $library_component ? $library_component->get_library_url() : 'https://boldpatterns.net';

			if ( false === $data ) {
				$response = wp_remote_get(
					$library_url . '/wp-json/api.boldblocks/v1/getDocs',
					[
						'timeout'   => 120,
						'sslverify' => false,
					]
				);

				if ( ! is_wp_error( $response ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ), true );

					if ( $data ) {
						set_transient( $cache_key, $data, DAY_IN_SECONDS );
					}
				}
			}

			wp_send_json(
				[
					'data'    => $data,
					'success' => true,
				]
			);
		}

		/**
		 * Clear transient cache
		 *
		 * @return void
		 */
		public function clear_transient_cache() {
			delete_transient( 'bb_docs' );
		}

		/**
		 * Change the footer text for the settings pages
		 *
		 * @param string $footer_text
		 * @return string
		 */
		public function admin_footer_text( $footer_text ) {
			// Get current screen.
			$current_screen = get_current_screen();
			if ( 'edit.php?post_type=boldblocks_block' === $current_screen->parent_file ) {
				$footer_text = '<i><a href="https://contentblocksbuilder.com/?utm_source=CBB&utm_campaign=CBB+visit+site&utm_medium=link&utm_content=footer-text" target="_blank" title="' . __( 'Visit the Plugin website', 'content-blocks-builder' ) . '" style="text-decoration:none"><strong>CBB</strong></a> <code>' . esc_html__( $this->the_plugin_instance->get_plugin_version() ) . '</code>. Please <a target="_blank" href="https://wordpress.org/support/plugin/content-blocks-builder/reviews/#new-post" title="Rate the plugin" style="text-decoration:none">rate the plugin <span style="color:#ffb900">★★★★★</span></a> to help us spread the word. Thank you from the CBB team!</i>';
			}

			return $footer_text;
		}

		/**
		 * Delete the entire cache contents.
		 *
		 * @return void
		 */
		public function boldblocks_purge_cache() {
			$nonce = wp_unslash( $_REQUEST['_cbb_purge'] ?? '' );
			if ( wp_verify_nonce( $nonce, 'cbb_purce_nonce' ) ) {
				$cbb = $this->the_plugin_instance;

				// Library cache.
				$cbb->get_component( Library::class )->clear_library_cache();

				// Blocks.
				$cbb->get_component( CustomBlocks::class )->clear_transient_cache();

				// Variations.
				$cbb->get_component( Variations::class )->clear_transient_cache();

				// Patterns.
				$cbb->get_component( Patterns::class )->clear_transient_cache();

				// Docs.
				$this->clear_transient_cache();

				$this->is_purged_cache = 1;
			}
		}
	}
endif;
