<?php
/**
 * The typography settings
 *
 * @package   BoldBlocks
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( Typography::class ) ) :
	/**
	 * The controller class for typography settings.
	 */
	class Typography extends CoreComponent {
		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Register setting fields.
			add_action( 'init', [ $this, 'register_setting_fields' ] );

			// Add rest api endpoint to query google fonts.
			add_action( 'rest_api_init', [ $this, 'register_google_fonts_endpoint' ] );

			// Load typography style.
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_typography_scripts' ], PHP_INT_MAX );
			add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_editor_typography_scripts' ] );

			add_filter( 'wp_resource_hints', [ $this, 'preconnect_fonts' ], 10, 2 );
			add_action( 'wp_head', [ $this, 'preload_fonts' ], 5 );

			// Add body class.
			add_filter( 'body_class', [ $this, 'add_typography_to_body_class' ] );
		}

		/**
		 * Register custom setting fields
		 *
		 * @return void
		 */
		public function register_setting_fields() {
			// Setting fields.
			register_setting(
				'boldblocks',
				'boldblocks_enable_typography',
				[
					'type'         => 'boolean',
					'show_in_rest' => [
						'name' => 'EnableTypography',
					],
					'default'      => false,
				]
			);

			register_setting(
				'boldblocks',
				'boldblocks_use_bunny_fonts',
				[
					'type'         => 'boolean',
					'show_in_rest' => [
						'name' => 'UseBunnyFonts',
					],
					'default'      => false,
				]
			);

			register_setting(
				'boldblocks',
				'boldblocks_typography',
				[
					'type'         => 'object',
					'show_in_rest' => array(
						'name'   => 'BoldBlocksTypography',
						'schema' => array(
							'type'                 => 'object',
							'properties'           => array(
								'fonts' => array(
									'type' => 'string',
								),
							),
							'additionalProperties' => array(
								'type' => 'string',
							),
						),
					),
				]
			);

			register_meta(
				'post',
				'boldblocks_typography',
				[
					'single'        => true,
					'type'          => 'object',
					'show_in_rest'  => array(
						'name'   => 'BoldBlocksTypography',
						'schema' => array(
							'type'                 => 'object',
							'additionalProperties' => array(
								'type' => 'string',
							),
						),
					),
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				]
			);
		}

		/**
		 * Build a custom endpoint to query google fonts.
		 *
		 * @return void
		 */
		public function register_google_fonts_endpoint() {
			register_rest_route(
				'boldblocks/v1',
				'/getGoogleFonts/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_google_fonts' ],
					'permission_callback' => function () {
						return current_user_can( 'publish_posts' );
					},
				)
			);
		}

		/**
		 * Get google fonts.
		 *
		 * @param WP_REST_Request $request The request object.
		 * @return void
		 */
		public function get_google_fonts( $request ) {
			// fonts file path.
			$fonts_file = $this->the_plugin_instance->get_file_path( 'data/fonts/google-fonts.json' );

			// Send the error if the fonts file is not exists.
			if ( ! \file_exists( $fonts_file ) ) {
				wp_send_json_error( __( 'The google-fonts.json file is not exists.', 'content-blocks-builder' ), 500 );
			}

			// Parse json.
			$fonts = wp_json_file_decode( $fonts_file, [ 'associative' => true ] );

			wp_send_json(
				[
					'data'    => $fonts,
					'success' => true,
				]
			);
		}

		/**
		 * Enqueue scripts for typography
		 *
		 * @return void
		 */
		public function enqueue_frontend_typography_scripts() {
			$post_id = 0;

			if ( is_singular() ) {
				$post_id = get_queried_object_id();
			}

			// Enqueue custom fonts.
			$this->enqueue_custom_fonts( $post_id );
		}

		/**
		 * Enqueue typography for editor
		 *
		 * @return void
		 */
		public function enqueue_block_editor_typography_scripts() {
			if ( ! is_admin() ) {
				return;
			}

			global $post;

			$screen    = get_current_screen();
			$is_editor = $screen->is_block_editor;

			// Bail if it's not a page that has block editor.
			if ( ! $is_editor ) {
				return;
			}

			$post_id = $screen->base === 'post' && $post ? $post->ID : 0;

			// Enqueue custom fonts.
			$this->enqueue_custom_fonts( $post_id, $is_editor );
		}

		/**
		 * Enqueue custom fonts
		 *
		 * @param int     $post_id
		 * @param boolean $is_editor
		 * @return void
		 */
		private function enqueue_custom_fonts( $post_id, $is_editor = false ) {
			$use_bunny_fonts = get_option( 'boldblocks_use_bunny_fonts' );

			$fonts_url = $this->get_fonts_url( $post_id, $use_bunny_fonts );
			if ( $fonts_url ) {
				$prefix      = $is_editor ? 'https:' : '';
				$service_url = $use_bunny_fonts ? '//fonts.bunny.net/css?family=' : '//fonts.googleapis.com/css2?';
				wp_enqueue_style( 'boldblocks-custom-fonts', $prefix . $service_url . $fonts_url, [], null );

				$typography_style = $this->get_typography_style( $post_id, $is_editor );
				wp_add_inline_style( 'boldblocks-custom-fonts', $typography_style );

				if ( $is_editor ) {
					$wp_styles     = wp_styles();
					$custom_blocks = $wp_styles->query( $this->the_plugin_instance->get_component( CustomBlocks::class )->custom_blocks_handle, 'registered' );

					if ( $custom_blocks ) {
						$custom_blocks->deps[] = 'boldblocks-custom-fonts';
					}
				}
			}
		}

		/**
		 * Add preconnect for font service.
		 *
		 * @param array  $urls           URLs to print for resource hints.
		 * @param string $relation_type  The relation type the URLs are printed.
		 * @return array $urls           URLs to print for resource hints.
		 */
		public function preconnect_fonts( $urls, $relation_type ) {
			if ( wp_style_is( 'boldblocks-custom-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
				$use_bunny_fonts = get_option( 'boldblocks_use_bunny_fonts' );

				$urls[] = array(
					'href' => $use_bunny_fonts ? '//fonts.bunny.net' : '//fonts.gstatic.com',
					'crossorigin',
				);
			}

			return $urls;
		}

		/**
		 * Add preload for fonts.
		 *
		 * @return void
		 */
		public function preload_fonts() {
			$wp_styles = wp_styles();
			$style     = $wp_styles->query( 'boldblocks-custom-fonts', 'registered' );

			if ( $style ) {
				echo "<link rel='preload' as='style' href='{$style->src}'/>";
			}
		}

		/**
		 * Load google fonts
		 *
		 * @param int     $post_id
		 * @param boolean $use_bunny_fonts
		 * @return void
		 */
		private function get_fonts_url( $post_id, $use_bunny_fonts ) {
			$typography = $this->get_custom_typography( $post_id );

			if ( ! $typography ) {
				return;
			}

			$family_urls = [];

			$body               = $typography['body'] ?? [];
			$body_font_family   = $body['fontFamily'] ?? '';
			$body_font_variants = $body['fontVariants'] ?? [];

			$body_font_url = $this->get_font_url( $body_font_family, $body_font_variants, $use_bunny_fonts );
			if ( $body_font_url ) {
				$family_urls[] = $body_font_url;
			}

			$headings               = $typography['headings'] ?? [];
			$headings_font_family   = $headings['fontFamily'] ?? '';
			$headings_font_variants = $headings['fontVariants'] ?? [];

			$headings_font_url = $this->get_font_url( $headings_font_family, $headings_font_variants, $use_bunny_fonts );
			if ( $headings_font_url ) {
				$family_urls[] = $headings_font_url;
			}

			if ( count( $family_urls ) > 0 ) {
				$glue = $use_bunny_fonts ? '|' : '&';
				return implode( $glue, $family_urls ) . '&display=swap';
			}

			return;
		}

		/**
		 * Get font url
		 *
		 * @param string  $font_family
		 * @param array   $font_variants
		 * @param boolean $use_bunny_fonts
		 * @return string
		 */
		private function get_font_url( $font_family, $font_variants, $use_bunny_fonts ) {
			$family_url = '';

			if ( $font_family ) {
				$family_url = $use_bunny_fonts ? \str_replace( ' ', '+', $font_family ) : 'family=' . \str_replace( ' ', '+', $font_family );
				if ( $font_variants ) {
					if ( ! $use_bunny_fonts ) {
						usort(
							$font_variants,
							function( $val1, $val2 ) {
								$val1_has_italic = strpos( $val1, 'italic' ) !== false;
								$val2_has_italic = strpos( $val2, 'italic' ) !== false;

								if ( $val1_has_italic && ! $val2_has_italic ) {
									return 1;
								} elseif ( ! $val1_has_italic && $val2_has_italic ) {
									return -1;
								}

								return strcmp( $val1, $val2 );
							}
						);
						$family_url .= ':ital,wght@';
						foreach ( $font_variants as $variant ) {
							if ( strpos( $variant, 'italic' ) === false ) {
								$family_url .= '0,' . $variant . ';';
							} else {
								$family_url .= '1,' . str_replace( 'italic', '', $variant ) . ';';
							}
						}
					} else {
						$family_url .= array_reduce(
							$font_variants,
							function ( $accumulator, $item ) {
								$variant = \str_replace( 'italic', 'i', $item );
								if ( empty( $accumulator ) ) {
									$accumulator = ':' . $variant;
								} else {
									$accumulator .= ',' . $variant;
								}

								return $accumulator;
							},
							''
						);
					}
				}
			}

			return trim( $family_url, ';' );
		}

		/**
		 * Build typography style
		 *
		 * @param int     $post_id
		 * @param boolean $is_editor
		 * @return string
		 */
		private function get_typography_style( $post_id = 0, $is_editor = false ) {
			$typography = $this->get_custom_typography( $post_id );

			if ( ! $typography ) {
				return;
			}

			$body     = $this->get_typography_style_by_type( 'body', $typography['body'] ?? [], $is_editor );
			$headings = $this->get_typography_style_by_type( 'headings', $typography['headings'] ?? [], $is_editor );

			if ( $is_editor ) {
				$style = '.editor-styles-wrapper {' . $body['vars'] . $headings['vars'] . '}';
			} else {
				$style = ':root {' . $body['vars'] . $headings['vars'] . '}';
			}
			$style .= $body['style'] . $headings['style'];

			return $style;
		}

		/**
		 * Build CSS variables and style by type
		 *
		 * @param string  $type
		 * @param array   $type_values
		 * @param boolean $is_editor
		 * @return array
		 */
		private function get_typography_style_by_type( $type, $type_values, $is_editor = false ) {
			$type_styles = [];
			$type_vars   = [];

			if ( empty( $type_values ) ) {
				return [
					'vars'  => '',
					'style' => '',
				];
			}

			$editor_selector   = '.editor-styles-wrapper .is-root-container';
			$frontend_selector = '.has-boldblocks-typography';

			$font_family   = '--cbb--' . $type . '--font-family';
			$type_styles[] = '.' . $type . '-font-family { font-family: var(' . $font_family . ') !important; }';

			if ( 'headings' !== $type ) {
				$selector = $type === 'body' ? 'body' : '.' . $type;
				if ( $is_editor ) {
					$selector = 'body' === $type ? "{$editor_selector}, {$editor_selector} p" : "{$editor_selector} ." . $type;
				} else {
					$selector = 'body' === $type ? "{$selector}, {$frontend_selector} p" : "{$frontend_selector} {$selector}";
				}
				$type_styles[] = $selector . ' {';
				if ( $type_values['fontFamily'] ?? '' ) {
					$type_vars[]   = $font_family . ': "' . $type_values['fontFamily'] . '", ' . ( $type_values['genericFamily'] ?? 'sans-serif' ) . ';';
					$type_styles[] = 'font-family: var(' . $font_family . ');';
				}

				$type_styles[] = '}';
			} else {
				if ( $type_values['fontFamily'] ?? '' ) {
					if ( $is_editor ) {
						$type_styles[] = ".editor-styles-wrapper .wp-block.wp-block-post-title, {$editor_selector} h1, {$editor_selector} .h1, {$editor_selector} h2, {$editor_selector} .h2, {$editor_selector} h3, {$editor_selector} .h3, {$editor_selector} h4, {$editor_selector} .h4, {$editor_selector} h5, {$editor_selector} .h5, {$editor_selector} h6, {$editor_selector} .h6 {";
					} else {
						$type_styles[] = "{$frontend_selector} h1,{$frontend_selector} .h1,{$frontend_selector} h2,{$frontend_selector} .h2,{$frontend_selector} h3,{$frontend_selector} .h3,{$frontend_selector} h4,{$frontend_selector} .h4,{$frontend_selector} h5,{$frontend_selector} .h5,{$frontend_selector} h6,{$frontend_selector} .h6 {";
					}
					$type_vars[]   = $font_family . ':"' . $type_values['fontFamily'] . '", ' . ( $type_values['genericFamily'] ?? 'sans-serif' ) . ';';
					$type_styles[] = 'font-family: var(' . $font_family . ');';
					$type_styles[] = '} ';
				}
			}

			return [
				'vars'  => \implode( '', $type_vars ),
				'style' => \implode( '', $type_styles ),
			];
		}

		/**
		 * Check whether current site support custom typography
		 *
		 * @param int $post_id
		 * @return mixed
		 */
		private function get_custom_typography( $post_id = 0 ) {
			$enable_typography = get_option( 'boldblocks_enable_typography' );

			if ( ! $enable_typography ) {
				return false;
			}

			$typography_raw = $post_id > 0 ? get_post_meta( $post_id, 'boldblocks_typography', true ) : false;
			if ( empty( $typography_raw ) || ! is_array( $typography_raw ) || empty( $typography_raw['fonts'] ) ) {
				$typography_raw = \get_option( 'boldblocks_typography' );
			}

			if ( ! is_array( $typography_raw ) || empty( $typography_raw['fonts'] ) ) {
				return;
			}

			$fonts = $typography_raw ? \json_decode( $typography_raw['fonts'], true ) : '';

			if ( empty( $fonts ) ) {
				return;
			}

			return $fonts;
		}

		/**
		 * Add a custom css class to body for typography
		 *
		 * @param array $classes
		 * @return string
		 */
		public function add_typography_to_body_class( $classes ) {
			$enable_typography = get_option( 'boldblocks_enable_typography' );

			if ( $enable_typography ) {
				$classes[] = 'has-boldblocks-typography';
			}

			return $classes;
		}
	}
endif;
